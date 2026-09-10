<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../vendor/Validator.php";

use Vendor\Validator;

$validator = new Validator($conn);

header("Contect-Type: application/json");

try {

    $data = json_decode(file_get_contents("php://input"), true);

    $validator->required($data,
        [
            "username",
            "first_name",
            "last_name"
        ],
        "Username, first name, and last name are required"
    );

    if (!empty($data["username"])) {
        $validator->exists("users", "username", $data["username"], "Username already exists");
    }
    
    $sql = "
        INSERT INTO users 
            (username, first_name, last_name) 
        VALUES 
            (:username, :first_name, :last_name)";

    $create = $conn->prepare($sql);

    $create->execute([
        ":username" => $data["username"],
        ":first_name" => $data["first_name"],
        ":last_name" => $data["last_name"]
    ]);

    echo json_encode([
        "success" => true,
        "message" => "User insert successfully!",
        "data" => [
            "id" => $conn->lastInsertId(),
            "username" => $data["username"],
            "first_name" => $data["first_name"],
            "last_name" => $data["last_name"]
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}