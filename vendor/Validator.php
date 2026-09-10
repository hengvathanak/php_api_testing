<?php

namespace Vendor;

class Validator
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function required($data, $fields, $msg_req)
    {
        foreach ($fields as $field) {

            if (empty($data[$field])) {

                http_response_code(422);

                echo json_encode([
                    "success" => false,
                    "message" => $msg_req
                ]);

                exit;
            }
        }

        return true;
    }

    public function exists($table, $column, $value, $msg_req)
    {
        $is_exists = false;

        $sql = "SELECT COUNT(*) FROM $table WHERE $column = :value";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":value" => $value
        ]);

        if ($stmt->fetchColumn() > 0) {
            $is_exists = true;
        }

        if ($is_exists === true) {
            http_response_code(422);

            echo json_encode([
                "success" => false,
                "message" => $msg_req
            ]);

            exit;
        }

        return true;
    }
}