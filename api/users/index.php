<?php

require_once __DIR__ . "/../../config/database.php";

header("Content-Type: application/json");

try {

    $sql = "SELECT * FROM users";

    $stmt = $conn->query($sql);

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $users
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}