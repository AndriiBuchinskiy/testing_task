<?php
require_once 'config/Db.php';
require_once 'classes/Product.php';

$id = isset($_GET['id']) ? $_GET['id'] : die();

$database = new Db();
$db = $database->getConnection();

$product = new Product($db);
$query = "SELECT id, name, price, date FROM products WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bindParam(1, $id);
$stmt->execute();

$product_data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($product_data);

