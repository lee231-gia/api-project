<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form action="/products.php" method="post">
    <input type="text" name="id" placeholder="Manual Id">
    <input type="text" name="product_name" placeholder="Product Name">
    <input type="number" name="price" placeholder="Price">
    <input type="hidden" name="_method" value="PUT">
    <button type="submit">Update Product</button>
</form>
    
</body>
</html>