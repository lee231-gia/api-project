<?php

require 'db.php';
header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $conn->prepare(
                "SELECT * FROM products WHERE id=?"
            );
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query(
                "SELECT * FROM products"
            );
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        break;

    case 'POST':
        $input = json_decode(
            file_get_contents("php://input"),
            true
        );
        $product = $input['product'];
        $price   = $input['price'];
        $stmt = $conn->prepare(
            "INSERT INTO products (product, price)
             VALUES (?, ?)"
        );
        $stmt->bind_param("sd", $product, $price);
        $stmt->execute();
        echo json_encode([
            "message" => "Product added"
        ]);
        break;

    case 'PUT':
        $input = json_decode(
            file_get_contents("php://input"),
            true
        );
        $id      = intval($_GET['id']);
        $product = $input['product'];
        $price   = $input['price'];
        $stmt = $conn->prepare(
            "UPDATE products
             SET product=?, price=?
             WHERE id=?"
        );
        $stmt->bind_param("sdi", $product, $price, $id);
        $stmt->execute();
        echo json_encode([
            "message" => "Product updated"
        ]);
        break;

    case 'DELETE':
        $id = intval($_GET['id']);
        $stmt = $conn->prepare(
            "DELETE FROM products WHERE id=?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode([
            "message" => "Product deleted"
        ]);
        break;
    
    default:
        echo json_encode([
            "message" => "Invalid request"
        ]);
}

?>
