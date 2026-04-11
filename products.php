<?php
    require 'db.php';
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) { // /api/products?id=1
        case 'GET':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $result = $conn->query("SELECT * FROM products WHERE id = $id");
                echo json_encode($result->fetch_assoc());
            } else { // /api/products
                $result = $conn->query("SELECT * FROM products");
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                echo json_encode($data);
            }
            break;
        case 'POST':
            $input = json_decode(file_get_contents("php://input"), true);

            $name  = $input['product_name'];
            $price = $input['price'];
            
            $conn->query("INSERT INTO products (product_name, price) VALUES ('$name', '$price')");
            echo json_encode(["message" => "Product created"]);

            break;
        case 'PUT':
            $raw = file_get_contents("php://input");
            $input = json_decode($raw, true);

            $name  = $input['product_name'];
            $price = $input['price'];
            $id    = $input['id'];

            $conn->query("UPDATE products SET product_name='$name', price='$price' WHERE id=$id");
            echo json_encode(["message" => "Product updated"]);

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