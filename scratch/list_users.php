<?php
require 'c:/xampp/htdocs/biharvihaan/app/Config/config.php';
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$res = $conn->query('SELECT id, name, email, role_id, password FROM users');
$users = [];
while($row = $res->fetch_assoc()) {
    $users[] = $row;
}
print_r($users);
