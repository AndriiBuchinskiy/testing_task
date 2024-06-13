<?php

$host = 'localhost';
$dbname = 'test';
$username = 'admin';
$password = 'Tesla234';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Неможливо підключитися до бази даних: " . $e->getMessage());
}

function buildCategoryTree($categories) {
    $tree = [];
    $indexedCategories = [];

    foreach ($categories as $category) {
        $indexedCategories[$category['categories_id']] = $category;
        $indexedCategories[$category['categories_id']]['children'] = [];
    }

    foreach ($indexedCategories as &$category) {
        if ($category['parent_id'] == 0) {
            $tree[$category['categories_id']] = &$category;
        } else {
            $indexedCategories[$category['parent_id']]['children'][$category['categories_id']] = &$category;
        }
    }

    return $tree;
}

function formatTree($tree, $level = 0) {
    $output = '';
    foreach ($tree as $category) {
        $output .= str_repeat("    ", $level) . "⮕ " . $category['categories_id'];
        if (!empty($category['children'])) {
            $output .= " {" . PHP_EOL;
            $output .= formatTree($category['children'], $level + 1);
            $output .= str_repeat("    ", $level) . "}" . PHP_EOL;
        } else {
            $output .= PHP_EOL;
        }
    }
    return $output;
}

$query = "SELECT categories_id, parent_id FROM categories";
$stmt = $pdo->query($query);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categoryTree = buildCategoryTree($categories);

echo '<pre>';
echo formatTree($categoryTree);
echo '</pre>';


