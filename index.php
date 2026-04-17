<?php
$activePage = basename($_SERVER['PHP_SELF']);
include('./config/db.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userCount = 0;
$setupMessage = '';

try {
    $sql = "SELECT COUNT(*) as user_count FROM users";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userCount = (int) $row['user_count'];
    }
} catch (mysqli_sql_exception $e) {
    if (stripos($e->getMessage(), 'doesn\'t exist') !== false || stripos($e->getMessage(), 'does not exist') !== false) {
        $setupMessage = 'Database tables are not installed yet. Import schema.sql into your database.';
    } else {
        throw $e;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>LibraByte - Online Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/indexstyle.css">
    <script src="scripts/index.js"></script>
</head>

<body>
    <div class="overlay"></div>

    <?php include('navbar/indexnav.php'); ?>

    <div class="hero">
        <h1>Online <span>Library</span></h1>
        <?php if ($setupMessage !== ''): ?>
            <div class="stat-box" style="margin: 0 auto 20px; max-width: 520px; background: #fff3cd; color: #664d03;">
                <?= htmlspecialchars($setupMessage) ?>
            </div>
        <?php endif; ?>
        <div class="search-container">
            <input type="text" class="search-bar" placeholder="Search books..">
            <button class="search-button"
                onclick="searchBooks(document.querySelector('.search-bar').value, true)">Search</button>

        </div>

        <div class="stats">
            <div class="stat-box">
                👥 <br>Member <br> <?= $userCount ?>
            </div>
            <div class="stat-box">
                📚 <br>Books <br> ∞
            </div>
        </div>
    </div>
    <div class="blog">
        <h2>Random Books</h2>
        <div class="results" id="random"></div>
        <div class="results" id="results"></div>

        <h2>About Us</h2>
        <article class="about-us">
            <p>
                Welcome to LibraByte, your digital destination for exploring a vast collection of books from all genres.
                Whether you're a passionate reader or just starting your literary journey, we are dedicated to providing
                a
                seamless experience for discovering, organizing, and enjoying your favorite books.
                Our platform allows you to search through thousands of titles, save books to your personal favorites,
                and
                track your reading progress. With a focus on user-friendly design, we aim to make reading more
                accessible
                and enjoyable for everyone. You can easily add books to your collection, explore recommendations, and
                connect with a community of fellow readers.
                Our mission is to cultivate a love for reading, offer a space for learning and imagination, and empower
                readers everywhere to access the stories that inspire and enlighten.
                Join us in the world of limitless knowledge, one page at a time.

            </p>
            <img src="https://cdn.pixabay.com/photo/2017/07/02/09/03/books-2463779_1280.jpg" alt="Article Image"
                class="side-image">
    </div>
    <div class="qoute">
    <img src="assets/img/albert2.PNG" width="250" height="250"/>


        <h1>"The only thing you absolutely have to know is the location of the library." – Albert Einstein</h1>
        <h3><strong>Stay informed, Stay Inspired!</strong><br>give us feedback if you want!</h3>
    </div>
    <script src="scripts/index.js"></script>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h2>Libra<span>Byte</span></h2>
                <p>
                    123 main Street<br>
                    Bogor, 13213<br>
                    Indonesia
                </p>
                <hr>
            </div>
            <div class="footer-section">
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Account</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <ul>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">Help</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
                <p>@LibraByte 2025</p>
            </div>
        </div>
    </footer>
</body>

</html>