import 'package:flutter/material.dart';
import 'package:goal_tracker_app/models/goal.dart';

class GoalCard extends StatelessWidget {
  final Goal goal;
  final Function(Goal) onEdit;
  final Function(Goal) onDelete;

  const GoalCard({
    super.key,
    required this.goal,
    required this.onEdit,
    required this.onDelete,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      color: const Color(0xFF242526),
      child: ListTile(
        leading: Icon(
          goal.status == 'Completed' ? Icons.check_circle : Icons.flag,
          color: goal.status == 'Completed'
              ? Colors.green
              : const Color(0xFF1877F2),
        ),
        title: Text(goal.title, style: const TextStyle(color: Colors.white)),
        subtitle: Text(
          '${goal.category} | ${goal.term} | ${goal.status}\n${goal.notes}',
          style: const TextStyle(color: Color(0xFFB0B3B8)),
        ),
        isThreeLine: true,
        trailing: PopupMenuButton(
          itemBuilder: (context) => const [
            PopupMenuItem(value: 'edit', child: Text('Edit')),
            PopupMenuItem(value: 'delete', child: Text('Delete')),
          ],
          onSelected: (value) {
            if (value == 'edit') onEdit(goal);
            if (value == 'delete') onDelete(goal);
          },
        ),
      ),
    );
  }
}
