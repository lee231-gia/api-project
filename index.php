<?php
    header("Content-Type: application/json");

    $request = $_SERVER['REQUEST_URI'];

    $method  = $_SERVER['REQUEST_METHOD'];

    if (strpos($request, '/api/products') !== false) {
        require 'products.php';
        
    } else {
        echo json_encode(["message" => "Endpoint not found"]);
    }

?>