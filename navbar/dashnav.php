<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('./config/db.php');
include_once('./auth/user_functions.php');

$usernames = "Guest";
$image_src = "https://cdn.pixabay.com/photo/2025/02/03/21/01/forest-9380294_960_720.jpg";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $result = $conn->prepare("SELECT username, image FROM users WHERE id = ?");
    $result->bind_param("i", $user_id);
    $result->execute();
    $query = $result->get_result();

    if ($query && $row = $query->fetch_assoc()) {
        $usernames = $row['username'];
        if ($row['image']) {
            $image_src = 'data:image/jpeg;base64,' . base64_encode($row['image']);
        }
    }
}
?>

<script>
function profile() {
    window.location.href = 'profile.php';
}
function favorites() {
    window.location.href = 'favorites.php';
}

document.addEventListener("DOMContentLoaded", () => {
    const path = window.location.pathname;
    const page = path.substring(path.lastIndexOf("/") + 1);

    if (page === "profile.php" || page === "favorites.php") {
        const searchHTML = `
            <button onclick="favorites()" id="button">Your favorite Books</button>
            <input type="text" class="search-bar" placeholder="Search books...">
            <button class="search-button" onclick="searchBooks(this.previousElementSibling.value, true)">Search</button>
        `;

        document.querySelectorAll('.desktop-search, .mobile-search').forEach(container => {
            container.innerHTML = searchHTML;
        });
    }
});
</script>

<style>
#button {
    color: white;
    padding: 10px;
    background-color: #53786d;
    border: solid #53786d 1px;
    border-radius: 20px;
}
#button:hover {
    background-color: #5cd1ae;
}
img.profile-img {
    width: 50px;
    height: 50px;
    border: 1px white solid;
    border-radius: 25px;
    cursor: pointer;
}
</style>

<script src="scripts/dashboard.js"></script>

<nav>
    <div class="brand">Libra<span>Byte</span></div>

    <div class="search-container desktop-search">
        <input type="text" id="desktop-search-bar" class="search-bar" placeholder="Search books...">
        <button class="search-button" onclick="searchBooks()">Search</button>
    </div>

    <div class="user-greeting-desktop">
        Hello!&nbsp;&nbsp;&nbsp;<strong><?php echo htmlspecialchars($usernames); ?></strong>
        <img src="<?php echo htmlspecialchars($image_src); ?>" alt="Profile Image" class="profile-img" onclick="profile()">
    </div>

    <div class="hamburger" onclick="toggleMenu()">☰</div>

    <div class="nav-links" id="navLinks">

        <div class="search-container mobile-search">
            <input type="text" id="mobile-search-bar" class="search-bar" placeholder="Search books...">
            <button class="search-button" onclick="searchBooks()">Search</button>
        </div>

        <div class="user-greeting-mobile">
            Hello!&nbsp;&nbsp;&nbsp;<strong><?php echo htmlspecialchars($usernames); ?></strong>
            <img src="<?php echo htmlspecialchars($image_src); ?>" alt="Profile Image" class="profile-img" onclick="profile()">
        </div>
    </div>
</nav>
