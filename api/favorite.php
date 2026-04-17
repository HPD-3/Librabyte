<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['book_id'], $data['title'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = $data['book_id'];
$title = $data['title'];
$authors = $data['authors'] ?? '';
$thumbnail = $data['thumbnail'] ?? '';
$rating = $data['rating'] ?? null;

$stmt = $conn->prepare("INSERT IGNORE INTO favorites (user_id, book_id, title, authors, thumbnail, rating) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssd", $user_id, $book_id, $title, $authors, $thumbnail, $rating);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>