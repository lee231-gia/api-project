import 'package:flutter/material.dart';

class TagFilterBar extends StatelessWidget {
  final String title;
  final List<String> tags;
  final String? selectedTag;
  final Function(String?) onSelect;

  const TagFilterBar({
    super.key,
    required this.title,
    required this.tags,
    required this.selectedTag,
    required this.onSelect,
  });

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 48,
      child: ListView(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 8),
        children: [
          buildItem('All $title', selectedTag == null, () => onSelect(null)),
          for (final tag in tags)
            buildItem(tag, selectedTag == tag, () => onSelect(tag)),
        ],
      ),
    );
  }

  Widget buildItem(String text, bool selected, VoidCallback onTap) {
    return Padding(
      padding: const EdgeInsets.only(right: 8),
      child: ChoiceChip(
        label: Text(text),
        selected: selected,
        onSelected: (_) => onTap(),
      ),
    );
  }
}
