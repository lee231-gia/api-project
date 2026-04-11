<?php
    require 'db.php';

    $table = 'users';
    $cols = ['username', 'name', 'password'];
    
    $method = $_SERVER['REQUEST_METHOD'];
    //echo 'method: ' . $_POST['_method'];
    
    if(isset($_POST['_method']) && ($_POST['_method'] === 'PUT' || $_POST['_method'] === 'PATCH')) {
        $id    = $_POST['id'];
        $username  = $_POST['username'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $conn->query("UPDATE $table SET username='$username', name='$name', password='$password' WHERE id=$id");
        echo json_encode(["message" => "User updated"]);
        exit;
    }
    

    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $result = $conn->query("SELECT * FROM $table WHERE id = $id");
                echo json_encode($result->fetch_assoc());
            } else {
                $result = $conn->query("SELECT * FROM $table");
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                echo json_encode($data);
            }
            break;
        case 'POST':
            //$input = json_decode(file_get_contents("php://input"), true);
            $username  = $_POST['username'];
            $name = $_POST['name'];
            $password = $_POST['password'];
            
            $conn->query("INSERT INTO $table (username, name, password) VALUES ('$username', '$name', '$password')");

            echo json_encode(["message" => "User created"]);

            break;
      
        case 'DELETE':
            $id = $_GET['id'];
            $conn->query("DELETE FROM $table WHERE id=$id");
            echo json_encode(["message" => "User deleted"]);
            break;
        default:
            echo json_encode(["message" => "Invalid request"]);
    }
?>