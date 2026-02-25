class ReviewModel {
  final int id;
  final int userId;
  final String userName;
  final int rating;
  final String? comment;
  final String? createdAt;

  ReviewModel({
    required this.id,
    required this.userId,
    required this.userName,
    required this.rating,
    this.comment,
    this.createdAt,
  });

  factory ReviewModel.fromJson(Map<String, dynamic> json) {
    final user = json['user'] as Map<String, dynamic>?;
    return ReviewModel(
      id: json['id'] ?? 0,
      userId: json['user_id'] ?? user?['id'] ?? 0,
      userName: user?['name'] ?? json['user_name'] ?? '',
      rating: json['rating'] ?? 0,
      comment: json['comment'],
      createdAt: json['created_at'],
    );
  }
}
