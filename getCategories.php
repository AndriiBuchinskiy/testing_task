<?php

require_once 'config/Db.php';
require_once 'classes/Category.php';

$database = new Db();
$db = $database->getConnection();

$category = new Category($db);
$stmt = $category->read();
$categories = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $query = "SELECT COUNT(*) AS product_count FROM products WHERE category_id = ?";
    $stmt2 = $db->prepare($query);
    $stmt2->bindParam(1, $id);
    $stmt2->execute();
    $product_count = $stmt2->fetch(PDO::FETCH_ASSOC)['product_count'];

    $categories[] = [
        'id' => $id,
        'name' => $name,
        'product_count' => $product_count
    ];
}

echo json_encode($categories);

