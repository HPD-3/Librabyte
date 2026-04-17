<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['book_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing book ID']);
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = $data['book_id'];

$stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND book_id = ?");
$stmt->bind_param("is", $user_id, $book_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
