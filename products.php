<?php
require 'db.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // GET ALL / GET ONE
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $conn->query("SELECT * FROM products WHERE id=$id");
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

    // CREATE
    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        $product = $input['product'];
        $price = $input['price'];

        $conn->query("INSERT INTO products (product, price)
                      VALUES ('$product', '$price')");

        echo json_encode(["message" => "Product added"]);
        break;

    // UPDATE
    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $id = $input['id'];
        $product = $input['product'];
        $price = $input['price'];

        $conn->query("UPDATE products SET product='$product', price='$price' WHERE id=$id");

        echo json_encode(["message" => "Product updated"]);
        break;

    // DELETE
    case 'DELETE':
        $id = $_GET['id'];

        $conn->query("DELETE FROM products WHERE id=$id");

        echo json_encode(["message" => "Product deleted"]);
        break;
}
?>
