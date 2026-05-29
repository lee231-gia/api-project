<?php
class GoalApi
{
    private mysqli $conn;
    private array $terms = ['Short Term', 'Medium Term', 'Long Term'];
    private array $statuses = ['Not Started', 'In Progress', 'Completed'];

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function handle(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'GET') $this->read();
        elseif ($method === 'POST') $this->create();
        elseif ($method === 'PUT') $this->update();
        elseif ($method === 'DELETE') $this->delete();
        else $this->send(['message' => 'Method not allowed'], 405);
    }

    private function read(): void
    {
        if (isset($_GET['id'])) {
            $goal = $this->find((int) $_GET['id']);
            $goal ? $this->send($goal) : $this->send(['message' => 'Goal not found'], 404);
        }

        $sql = 'SELECT * FROM goals';
        $where = [];
        $types = '';
        $params = [];

        foreach (['category', 'term', 'status'] as $field) {
            if (!empty($_GET[$field])) {
                $where[] = "$field = ?";
                $types .= 's';
                $params[] = $_GET[$field];
            }
        }

        if (!empty($_GET['search'])) {
            $where[] = '(title LIKE ? OR notes LIKE ?)';
            $types .= 'ss';
            $params[] = '%' . $_GET['search'] . '%';
            $params[] = '%' . $_GET['search'] . '%';
        }

        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY id DESC';

        $stmt = $this->conn->prepare($sql);
        if ($params) $this->bind($stmt, $types, $params);
        $stmt->execute();

        $this->send($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    }

    private function create(): void
    {
        $goal = $this->goalFromInput();
        $stmt = $this->conn->prepare(
            'INSERT INTO goals (title, category, term, status, notes, due_date) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('ssssss', $goal['title'], $goal['category'], $goal['term'], $goal['status'], $goal['notes'], $goal['due_date']);
        $stmt->execute();

        $this->send(['message' => 'Goal created', 'goal' => $this->find($this->conn->insert_id)], 201);
    }

    private function update(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) $this->send(['message' => 'Goal id is required'], 400);

        $goal = $this->goalFromInput();
        $stmt = $this->conn->prepare(
            'UPDATE goals SET title=?, category=?, term=?, status=?, notes=?, due_date=? WHERE id=?'
        );
        $stmt->bind_param('ssssssi', $goal['title'], $goal['category'], $goal['term'], $goal['status'], $goal['notes'], $goal['due_date'], $id);
        $stmt->execute();

        $this->send(['message' => 'Goal updated', 'goal' => $this->find($id)]);
    }

    private function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) $this->send(['message' => 'Goal id is required'], 400);

        $stmt = $this->conn->prepare('DELETE FROM goals WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $this->send(['message' => $stmt->affected_rows ? 'Goal deleted' : 'Goal not found']);
    }

    private function goalFromInput(): array
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $goal = [
            'title' => trim($input['title'] ?? ''),
            'category' => trim($input['category'] ?? 'Personal'),
            'term' => $input['term'] ?? 'Short Term',
            'status' => $input['status'] ?? 'Not Started',
            'notes' => trim($input['notes'] ?? ''),
            'due_date' => trim($input['due_date'] ?? ''),
        ];

        if ($goal['title'] === '') $this->send(['message' => 'Title is required'], 422);
        if (!in_array($goal['term'], $this->terms)) $this->send(['message' => 'Invalid term'], 422);
        if (!in_array($goal['status'], $this->statuses)) $this->send(['message' => 'Invalid status'], 422);
        if ($goal['due_date'] === '') $goal['due_date'] = null;
        return $goal;
    }

    private function find(int $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM goals WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    private function bind(mysqli_stmt $stmt, string $types, array &$params): void
    {
        $refs = [];
        foreach ($params as $key => &$value) $refs[$key] = &$value;
        $stmt->bind_param($types, ...$refs);
    }

    private function send(array $data, int $code = 200): void
    {
        http_response_code($code);
        echo json_encode($data);
        exit;
    }
}
