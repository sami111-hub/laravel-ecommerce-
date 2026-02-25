import '../core/network/api_client.dart';
import '../core/constants/api_constants.dart';
import '../core/storage/storage_service.dart';
import '../models/user_model.dart';

class AuthService {
  final _client = ApiClient();

  Future<Map<String, dynamic>> login(String email, String password) async {
    final res = await _client.post(ApiConstants.login, data: {
      'email': email,
      'password': password,
    });
    final data = res.data;
    final token = data['token'] as String;
    await StorageService.saveToken(token);

    final user = data['user'] is Map<String, dynamic> ? data['user'] : data['user']?.toJson();
    if (user != null) await StorageService.saveUser(user);

    return data;
  }

  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? phone,
  }) async {
    final res = await _client.post(ApiConstants.register, data: {
      'name': name,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
      'phone': phone,
    });
    final data = res.data;
    final token = data['token'] as String;
    await StorageService.saveToken(token);

    final user = data['user'] is Map<String, dynamic> ? data['user'] : data['user']?.toJson();
    if (user != null) await StorageService.saveUser(user);

    return data;
  }

  Future<UserModel> getUser() async {
    final res = await _client.get(ApiConstants.user);
    final userData = res.data['user'] ?? res.data;
    return UserModel.fromJson(userData);
  }

  Future<void> updateProfile({required String name, String? phone}) async {
    await _client.put(ApiConstants.updateProfile, data: {
      'name': name,
      if (phone != null) 'phone': phone,
    });
  }

  Future<void> changePassword({
    required String currentPassword,
    required String newPassword,
    required String confirmPassword,
  }) async {
    await _client.put(ApiConstants.changePassword, data: {
      'current_password': currentPassword,
      'password': newPassword,
      'password_confirmation': confirmPassword,
    });
  }

  Future<void> logout() async {
    try {
      await _client.post(ApiConstants.logout);
    } catch (_) {}
    await StorageService.removeToken();
  }
}
