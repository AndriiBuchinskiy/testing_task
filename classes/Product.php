<?php

class Product
{
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $price;
    public $date;
    public $category_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($category_id = null, $order_by = 'date') {
        $query = "SELECT id, name, price, date, category_id FROM " . $this->table_name;

        if ($category_id) {
            $query .= " WHERE category_id = :category_id";
        }

        switch ($order_by) {
            case 'price':
                $query .= " ORDER BY price ASC";
                break;
            case 'name':
                $query .= " ORDER BY name ASC";
                break;
            default:
                $query .= " ORDER BY date DESC";
                break;
        }

        $stmt = $this->conn->prepare($query);

        if ($category_id) {
            $stmt->bindParam(':category_id', $category_id);
        }

        $stmt->execute();
        return $stmt;
    }
}
