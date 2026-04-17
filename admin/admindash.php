<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../index.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$admin_username = "Admin";

$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($admin_username);
$stmt->fetch();
$stmt->close();

if (isset($_GET['delete_user'])) {
    $delete_id = intval($_GET['delete_user']);
    if ($delete_id !== $admin_id) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        header("Location: admindash.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = intval($_POST['user_id']);
    $new_username = trim($_POST['new_username']);
    $new_password = $_POST['new_password'];

    if (!empty($new_username)) {
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $new_username, $id);
        $stmt->execute();
        $stmt->close();
    }

    if (!empty($new_password)) {
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed, $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admindash.php");
    exit();
}

if (isset($_GET['delete_review'])) {
    $review_id = intval($_GET['delete_review']);
    $stmt = $conn->prepare("DELETE FROM website_reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admindash.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_review'])) {
    $id = intval($_POST['review_id']);
    $name = $_POST['reviewer_name'];
    $rating = intval($_POST['rating']);
    $text = $_POST['review_text'];

    $stmt = $conn->prepare("UPDATE website_reviews SET reviewer_name = ?, rating = ?, review = ? WHERE id = ?");
    $stmt->bind_param("sisi", $name, $rating, $text, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admindash.php");
    exit();
}

$users = [];
$result = $conn->query("SELECT id, username FROM users");
$user_count = $result->num_rows;
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$reviews = [];
$result = $conn->query("SELECT * FROM website_reviews ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles.css">
    <title>Admin Dashboard</title>
</head>

<body>
    <header>
        <h1>Welcome, To Libra<span>Byte</span> Admin Dashboard (<?php echo htmlspecialchars($admin_username); ?>)</h1>
    </header>
    <div class="header">
        <a class="logout" href="../logout.php">Logout</a>
        <h2>User Management (<?php echo $user_count; ?> Users)</h2>
    </div>
    <div class="main">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="text" name="new_username" placeholder="New username">
                                <input type="password" name="new_password" placeholder="New password">
                                <button type="submit" name="update_user" class="btn">Update</button>
                            </form>
                        </td>
                        <td>
                            <?php if ($user['id'] !== $admin_id): ?>
                                <a class="btn btn-delete" href="?delete_user=<?= $user['id'] ?>"
                                    onclick="return confirm('Delete this user?');">Delete</a>
                            <?php else: ?>
                                (You)
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Review Management (<?= count($reviews); ?> Reviews)</h2>
        <div class="reviews-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $rev): ?>
                        <tr>
                            <form method="POST">
                                <input type="hidden" name="review_id" value="<?= $rev['id'] ?>">
                                <td><?= $rev['id'] ?></td>
                                <td><input type="text" name="reviewer_name"
                                        value="<?= htmlspecialchars($rev['reviewer_name']) ?>"></td>
                                <td>
                                    <select name="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?= $i ?>" <?= $rev['rating'] == $i ? 'selected' : '' ?>><?= $i ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td><textarea name="review_text" rows="3"><?= htmlspecialchars($rev['review']) ?></textarea>
                                </td>
                                <td><?= $rev['created_at'] ?></td>
                                <td>
                                    <button type="submit" name="update_review" class="btn">Update</button>
                                    <a class="btn btn-delete" href="?delete_review=<?= $rev['id'] ?>"
                                        onclick="return confirm('Delete this review?');">Delete</a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>