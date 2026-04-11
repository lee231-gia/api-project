<?php
    require 'db.php';
    $method = $_SERVER['REQUEST_METHOD'];
    //echo 'method: ' . $_POST['_method'];
    
    if(isset($_POST['_method']) && ($_POST['_method'] === 'PUT' || $_POST['_method'] === 'PATCH')) {
        $id    = $_POST['id'];
        $name  = $_POST['product_name'];
        $price = $_POST['price'];
        $conn->query("UPDATE products SET product_name='$name', price='$price' WHERE id=$id");
        echo json_encode(["message" => "Product updated"]);
        exit;
    }
    

    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $result = $conn->query("SELECT * FROM products WHERE id = $id");
                echo json_encode($result->fetch_assoc());
            } else {
                $result = $conn->query("SELECT * FROM products");
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                echo json_encode($data);
            }
            break;
        case 'POST':
            //$input = json_decode(file_get_contents("php://input"), true);
            $name  = $_POST['product_name'];
            $price = $_POST['price'];
            
            $conn->query("INSERT INTO products (product_name, price) VALUES ('$name', '$price')");

            echo json_encode(["message" => "Product created"]);

            break;
      
        case 'DELETE':
            $id = $_GET['id'];
            $conn->query("DELETE FROM products WHERE id=$id");
            echo json_encode(["message" => "Product deleted"]);
            break;
        default:
            echo json_encode(["message" => "Invalid request"]);
    }
?>