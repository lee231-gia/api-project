class Goal {
  int? id;
  String title;
  String category;
  String term;
  String status;
  String notes;
  String? dueDate;

  Goal({
    this.id,
    required this.title,
    required this.category,
    required this.term,
    required this.status,
    required this.notes,
    this.dueDate,
  });

  static const categories = ['Personal', 'School', 'Home', 'Health'];
  static const terms = ['Short Term', 'Medium Term', 'Long Term'];
  static const statuses = ['Not Started', 'In Progress', 'Completed'];

  factory Goal.fromJson(Map<String, dynamic> json) {
    return Goal(
      id: int.tryParse('${json['id']}'),
      title: json['title'] ?? '',
      category: json['category'] ?? 'Personal',
      term: json['term'] ?? 'Short Term',
      status: json['status'] ?? 'Not Started',
      notes: json['notes'] ?? '',
      dueDate: json['due_date'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'category': category,
      'term': term,
      'status': status,
      'notes': notes,
      'due_date': dueDate,
    };
  }
}
