<?php
    require 'db.php';
    $method = $_SERVER['REQUEST_METHOD'];
    //echo 'method: ' . $_POST['_method'];
    

    //send the request as POST
    //add _method field with value PUT or PATCH to update
    if(isset($_POST['_method']) && ($_POST['_method'] === 'PUT' || $_POST['_method'] === 'PATCH')) {
        $id    = $_POST['id'];
        $name  = $_POST['product_name'];
        $price = $_POST['price'];
        $conn->query("UPDATE products SET product_name='$name', price='$price' WHERE id=$id");
        echo json_encode(["message" => "Product updated"]);
        exit;
    }
    

    //POST, GET, PUT, DELETE
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
            //$input = json_decode(file_get_contents("php://input"), true);
            $name  = $_POST['product_name'];
            $price = $_POST['price'];
            
            $conn->query("INSERT INTO products (product_name, price) VALUES ('$name', '$price')");

            echo json_encode(["message" => "Product created"]);

            break;
        case 'PUT':
            // $input = json_decode(file_get_contents("php://input"));
            // var_dump($input);
            echo 'Under construction';
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