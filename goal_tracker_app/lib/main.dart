import 'package:flutter/material.dart';
import 'package:goal_tracker_app/pages/home.dart';
import 'package:goal_tracker_app/services/local_database.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await LocalDatabase.setup();
  runApp(const GoalTrackerApp());
}

class GoalTrackerApp extends StatelessWidget {
  const GoalTrackerApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Goal Tracker',
      theme: ThemeData.dark().copyWith(
        scaffoldBackgroundColor: const Color(0xFF18191A),
        appBarTheme: const AppBarTheme(backgroundColor: Color(0xFF1877F2)),
        floatingActionButtonTheme: const FloatingActionButtonThemeData(
          backgroundColor: Color(0xFF1877F2),
        ),
      ),
      home: const HomePage(),
    );
  }
}
