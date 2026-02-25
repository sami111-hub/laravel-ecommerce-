import 'package:dio/dio.dart';
import '../constants/api_constants.dart';
import '../storage/storage_service.dart';
import 'api_exception.dart';

class ApiClient {
  static final ApiClient _instance = ApiClient._internal();
  factory ApiClient() => _instance;

  late final Dio _dio;

  ApiClient._internal() {
    _dio = Dio(
      BaseOptions(
        baseUrl: ApiConstants.baseUrl,
        connectTimeout: const Duration(seconds: 30),
        receiveTimeout: const Duration(seconds: 30),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      ),
    );

    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          final token = await StorageService.getToken();
          if (token != null && token.isNotEmpty) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          return handler.next(options);
        },
        onError: (error, handler) async {
          if (error.response?.statusCode == 401) {
            await StorageService.removeToken();
          }
          return handler.next(error);
        },
      ),
    );
  }

  Future<Response> get(String path, {Map<String, dynamic>? queryParameters}) async {
    try {
      return await _dio.get(path, queryParameters: queryParameters);
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> post(String path, {dynamic data}) async {
    try {
      return await _dio.post(path, data: data);
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> put(String path, {dynamic data}) async {
    try {
      return await _dio.put(path, data: data);
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  Future<Response> delete(String path) async {
    try {
      return await _dio.delete(path);
    } on DioException catch (e) {
      throw _handleError(e);
    }
  }

  ApiException _handleError(DioException e) {
    if (e.type == DioExceptionType.connectionTimeout ||
        e.type == DioExceptionType.receiveTimeout) {
      return ApiException('انتهت مهلة الاتصال، حاول مرة أخرى', statusCode: 408);
    }

    if (e.type == DioExceptionType.connectionError) {
      return ApiException('لا يوجد اتصال بالإنترنت', statusCode: 0);
    }

    final statusCode = e.response?.statusCode;
    final data = e.response?.data;

    switch (statusCode) {
      case 401:
        return ApiException('يجب تسجيل الدخول أولاً', statusCode: 401);
      case 403:
        return ApiException('غير مصرح لك بهذه العملية', statusCode: 403);
      case 404:
        return ApiException('لم يتم العثور على البيانات', statusCode: 404);
      case 422:
        String msg = 'بيانات غير صحيحة';
        Map<String, dynamic>? errors;
        if (data is Map<String, dynamic>) {
          msg = data['message'] ?? msg;
          errors = data['errors'] as Map<String, dynamic>?;
          if (errors != null && errors.isNotEmpty) {
            final firstKey = errors.keys.first;
            final firstError = errors[firstKey];
            if (firstError is List && firstError.isNotEmpty) {
              msg = firstError.first.toString();
            }
          }
        }
        return ApiException(msg, statusCode: 422, errors: errors);
      case 500:
        return ApiException('خطأ في الخادم، حاول لاحقاً', statusCode: 500);
      default:
        return ApiException(
          data is Map ? (data['message'] ?? 'حدث خطأ غير متوقع') : 'حدث خطأ غير متوقع',
          statusCode: statusCode,
        );
    }
  }
}
