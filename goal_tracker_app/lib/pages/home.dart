import 'package:flutter/material.dart';
import 'package:goal_tracker_app/api_services/api_services.dart';
import 'package:goal_tracker_app/models/goal.dart';
import 'package:goal_tracker_app/pages/widgets/goal_card.dart';
import 'package:goal_tracker_app/pages/widgets/tag_filter_bar.dart';
import 'package:goal_tracker_app/services/local_database.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  final api = ApiService();
  final localDb = LocalDatabase();
  final searchController = TextEditingController();

  List<Goal> goals = [];
  bool isLoaded = false;
  String message = '';
  String? category;
  String? term;
  String? status;

  @override
  void initState() {
    super.initState();
    getGoals();
  }

  @override
  void dispose() {
    searchController.dispose();
    super.dispose();
  }

  Future<void> getGoals() async {
    setState(() => isLoaded = false);
    try {
      goals = await api.getAllGoals();
      await localDb.saveAllGoals(goals);
      message = 'Connected to PHP API and MySQL';
    } catch (_) {
      goals = await localDb.getAllGoals();
      message = 'Offline: showing sqflite saved goals';
    }
    setState(() => isLoaded = true);
  }

  List<Goal> get filteredGoals {
    final search = searchController.text.toLowerCase();
    return goals.where((goal) {
      final byCategory = category == null || goal.category == category;
      final byTerm = term == null || goal.term == term;
      final byStatus = status == null || goal.status == status;
      final bySearch = goal.title.toLowerCase().contains(search);
      return byCategory && byTerm && byStatus && bySearch;
    }).toList();
  }

  Future<void> openForm([Goal? oldGoal]) async {
    final title = TextEditingController(text: oldGoal?.title ?? '');
    final notes = TextEditingController(text: oldGoal?.notes ?? '');
    var selectedCategory = oldGoal?.category ?? Goal.categories.first;
    var selectedTerm = oldGoal?.term ?? Goal.terms.first;
    var selectedStatus = oldGoal?.status ?? Goal.statuses.first;

    final saved = await showDialog<Goal>(
      context: context,
      builder: (context) => StatefulBuilder(
        builder: (context, setDialogState) => AlertDialog(
          backgroundColor: const Color(0xFF242526),
          title: Text(oldGoal == null ? 'Add Goal' : 'Edit Goal'),
          content: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                TextField(
                  controller: title,
                  decoration: const InputDecoration(labelText: 'Title'),
                ),
                TextField(
                  controller: notes,
                  decoration: const InputDecoration(labelText: 'Notes'),
                ),
                dropdown('Category', selectedCategory, Goal.categories, (
                  value,
                ) {
                  setDialogState(() => selectedCategory = value!);
                }),
                dropdown('Term', selectedTerm, Goal.terms, (value) {
                  setDialogState(() => selectedTerm = value!);
                }),
                dropdown('Status', selectedStatus, Goal.statuses, (value) {
                  setDialogState(() => selectedStatus = value!);
                }),
              ],
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Cancel'),
            ),
            ElevatedButton(
              onPressed: () {
                if (title.text.trim().isEmpty) return;
                Navigator.pop(
                  context,
                  Goal(
                    id: oldGoal?.id,
                    title: title.text.trim(),
                    category: selectedCategory,
                    term: selectedTerm,
                    status: selectedStatus,
                    notes: notes.text.trim(),
                  ),
                );
              },
              child: const Text('Save'),
            ),
          ],
        ),
      ),
    );

    if (saved == null) return;
    await api.saveGoal(saved);
    await getGoals();
  }

  Future<void> removeGoal(Goal goal) async {
    if (goal.id == null) return;
    await api.deleteGoal(goal.id!);
    await getGoals();
  }

  DropdownButtonFormField<String> dropdown(
    String label,
    String value,
    List<String> items,
    ValueChanged<String?> onChanged,
  ) {
    return DropdownButtonFormField(
      initialValue: value,
      decoration: InputDecoration(labelText: label),
      items: items.map((item) {
        return DropdownMenuItem(value: item, child: Text(item));
      }).toList(),
      onChanged: onChanged,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Goal Tracker',
          style: TextStyle(color: Colors.white),
        ),
        actions: [
          IconButton(onPressed: getGoals, icon: const Icon(Icons.refresh)),
        ],
      ),
      body: Visibility(
        visible: isLoaded,
        replacement: const Center(child: CircularProgressIndicator()),
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.all(10),
              child: TextField(
                controller: searchController,
                decoration: const InputDecoration(
                  labelText: 'Search goal',
                  prefixIcon: Icon(Icons.search),
                  filled: true,
                ),
                onChanged: (_) => setState(() {}),
              ),
            ),
            TagFilterBar(
              title: 'Category',
              tags: Goal.categories,
              selectedTag: category,
              onSelect: (value) => setState(() => category = value),
            ),
            TagFilterBar(
              title: 'Term',
              tags: Goal.terms,
              selectedTag: term,
              onSelect: (value) => setState(() => term = value),
            ),
            TagFilterBar(
              title: 'Status',
              tags: Goal.statuses,
              selectedTag: status,
              onSelect: (value) => setState(() => status = value),
            ),
            Padding(padding: const EdgeInsets.all(8), child: Text(message)),
            Expanded(
              child: ListView.builder(
                padding: const EdgeInsets.all(10),
                itemCount: filteredGoals.length,
                itemBuilder: (context, index) {
                  return GoalCard(
                    goal: filteredGoals[index],
                    onEdit: openForm,
                    onDelete: removeGoal,
                  );
                },
              ),
            ),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => openForm(),
        child: const Icon(Icons.add, color: Colors.white),
      ),
    );
  }
}
