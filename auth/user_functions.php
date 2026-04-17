<?php
function getUsernameById($userId) {
    global $conn;

    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $username = null;
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
    }

    $stmt->close();
    return $username;
}
?>
