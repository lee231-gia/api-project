import 'dart:convert';
import 'dart:io';
import 'package:goal_tracker_app/models/goal.dart';
import 'package:http/http.dart' as http;

class ApiService {
  final String baseUrl = Platform.isAndroid
      ? 'http://10.0.2.2:8000/api/goals'
      : 'http://localhost:8000/api/goals';

  Future<List<Goal>> getAllGoals() async {
    final response = await http.get(Uri.parse(baseUrl));
    if (response.statusCode != 200) throw Exception('Cannot load goals');

    final data = jsonDecode(response.body) as List;
    return data.map((item) => Goal.fromJson(item)).toList();
  }

  Future<void> saveGoal(Goal goal) async {
    final body = jsonEncode(goal.toJson());
    final headers = {'Content-Type': 'application/json'};
    final response = goal.id == null
        ? await http.post(Uri.parse(baseUrl), headers: headers, body: body)
        : await http.put(
            Uri.parse('$baseUrl?id=${goal.id}'),
            headers: headers,
            body: body,
          );

    if (response.statusCode >= 400) {
      throw Exception('Cannot save goal');
    }
  }

  Future<void> deleteGoal(int id) async {
    final response = await http.delete(Uri.parse('$baseUrl?id=$id'));
    if (response.statusCode >= 400) throw Exception('Cannot delete goal');
  }
}
