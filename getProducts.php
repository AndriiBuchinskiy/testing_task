<?php
require_once 'config/Db.php';
require_once 'classes/Product.php';

$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'date';

$database = new Db();
$db = $database->getConnection();

$product = new Product($db);
$stmt = $product->read($category_id, $order_by);
$products = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $products[] = [
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'date' => $date,
        'category_id' => $category_id
    ];
}

echo json_encode($products);
