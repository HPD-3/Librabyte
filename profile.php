<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include('./config/db.php');
include_once('./auth/user_functions.php');

$usernames = "Guest";
$email = "No email yet.";
$image_src = "https://cdn.pixabay.com/photo/2025/02/03/21/01/forest-9380294_960_720.jpg";
$bio = "No bio yet.";
$upload_success = false;

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $result = $conn->prepare("SELECT username, email, image, bio FROM users WHERE id = ?");
  $result->bind_param("i", $user_id);
  $result->execute();
  $query = $result->get_result();

  if ($query && $row = $query->fetch_assoc()) {
    $usernames = $row['username'];
    $email = $row['email'];
    $bio = $row['bio'] ?? "No bio yet.";
    if ($row['image']) {
      $image_src = 'data:image/jpeg;base64,' . base64_encode($row['image']);
    }
  }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
  $image = file_get_contents($_FILES['profile_image']['tmp_name']);
  $update_stmt = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
  $null = NULL;
  $update_stmt->bind_param("bi", $null, $user_id);
  $update_stmt->send_long_data(0, $image);
  $update_stmt->execute();

  if ($update_stmt->affected_rows > 0) {
    $upload_success = true;
  } else {
    echo "<script>alert('Error uploading image!');</script>";
  }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_username'], $_POST['email'], $_POST['bio']) && !isset($_FILES['profile_image'])) {
  $new_username = trim($_POST['new_username']);
  $new_email = trim($_POST['email']);
  $new_bio = trim($_POST['bio']);


  if (!empty($new_username) && !empty($new_email)) {
    if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
      $update_info = $conn->prepare("UPDATE users SET username = ?, email = ?, bio = ? WHERE id = ?");
      $update_info->bind_param("sssi", $new_username, $new_email, $new_bio, $user_id);
      $update_info->execute();

      if ($update_info->affected_rows > 0) {
        echo "<script>alert('Profile updated!'); window.location.href = 'profile.php';</script>";
        exit;
      } else {
        echo "<script>alert('No changes made.');</script>";
      }
    } else {
      echo "<script>alert('Please enter a valid email address.');</script>";
    }
  } else {
    echo "<script>alert('Username and email are required!');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile</title>
  <link rel="stylesheet" href="assets/dashstyle.css" />
  <link rel="stylesheet" href="assets/profile.css">
</head>

<body>
<script src="scripts/dashboard.js"></script>
  <?php include('navbar/dashnav.php'); ?>
  <div class="overlay"></div>
  <button id="log-btn" onclick="logout()">logout</button>
  <div id="profile">
    <img src="<?php echo htmlspecialchars($image_src); ?>" alt="Profile Image" width="100" onclick="openModal()">

    <div class="profile-info">
      <div class="username">
        <h4>Hello, <?php echo htmlspecialchars($usernames); ?>!</h4>
      </div>

      <div class="bio">
        <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($bio)); ?></p>
      </div>

      <div class="email">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
      </div>

    </div>

    <button id="edit-btn" onclick="openModal()">Edit Profile</button>
  </div>

  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h3>Edit Profile</h3>


      <form method="POST" action="profile.php">
        <label for="new_username">Username:</label><br>
        <input type="text" id="new_username" name="new_username" value="<?php echo htmlspecialchars($usernames); ?>"
          required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

        <label for="bio">Bio:</label><br>
        <textarea id="bio" name="bio" rows="4" cols="40"
          required><?php echo htmlspecialchars($bio); ?></textarea><br><br>

        <button type="submit">Save Profile Changes</button>
      </form>

      <hr>

      <form method="POST" action="profile.php" enctype="multipart/form-data">
        <label for="profile_image">Change Profile Image (optional):</label><br>
        <input type="file" name="profile_image" id="profile_image" accept="image/*"><br><br>

        <button type="submit">Upload Profile Image</button>
      </form>
    </div>
  </div>

  <?php if ($upload_success): ?>
    <script>
      alert("Image uploaded successfully!");
      window.location.href = "profile.php";
    </script>
  <?php endif; ?>

  <script>
    function openModal() {
      document.getElementById("editModal").style.display = "flex";
    }

    function closeModal() {
      document.getElementById("editModal").style.display = "none";
    }

    window.onclick = function (e) {
      const modal = document.getElementById("editModal");
      if (e.target === modal) closeModal();
    }
    function logout() {
    window.location.href = 'logout.php';
    }
  </script>
</body>

</html>