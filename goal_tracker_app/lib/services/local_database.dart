import 'dart:io';
import 'package:goal_tracker_app/models/goal.dart';
import 'package:path/path.dart' as p;
import 'package:sqflite_common_ffi/sqflite_ffi.dart';

class LocalDatabase {
  static Future<void> setup() async {
    if (Platform.isWindows || Platform.isLinux || Platform.isMacOS) {
      sqfliteFfiInit();
      databaseFactory = databaseFactoryFfi;
    }
  }

  Future<Database> openDb() async {
    final path = p.join(await getDatabasesPath(), 'goals.db');
    return openDatabase(
      path,
      version: 1,
      onCreate: (db, version) {
        return db.execute(
          'CREATE TABLE goals(id INTEGER PRIMARY KEY, title TEXT, category TEXT, term TEXT, status TEXT, notes TEXT, due_date TEXT)',
        );
      },
    );
  }

  Future<List<Goal>> getAllGoals() async {
    final db = await openDb();
    final rows = await db.query('goals', orderBy: 'id DESC');
    return rows.map((row) {
      return Goal.fromJson(Map<String, dynamic>.from(row));
    }).toList();
  }

  Future<void> saveAllGoals(List<Goal> goals) async {
    final db = await openDb();
    await db.delete('goals');

    for (final goal in goals) {
      await db.insert(
        'goals',
        goal.toJson(),
        conflictAlgorithm: ConflictAlgorithm.replace,
      );
    }
  }
}
