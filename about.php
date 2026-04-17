<?php
$activePage = basename($_SERVER['PHP_SELF']);
include('./config/db.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) as user_count FROM users";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userCount = $row['user_count'];
} else {
    $userCount = 0;
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
    <?php include('navbar/indexnav.php'); ?>
    <div class="aboutus">
            <h2>📖 About Us</h2>
            <p>Welcome to <strong>LibraByte</strong> – your digital gateway to knowledge and discovery.</p>
            <p>At LibraByte, we believe that the power of reading can transform lives. Our online library offers a wide
                range of resources—from bestselling novels and timeless classics to academic journals and niche research
                material—accessible anytime, anywhere.</p>

            <h3>🌟 Our Mission</h3>
            <p>To make reading and learning accessible for everyone by providing a diverse, user-friendly, and
                affordable digital library that grows with the needs of our community.</p>

            <h3>📚 Our Story</h3>
            <p>LibraByte was born out of a simple idea: to bring the world’s knowledge closer to people who need it.
                Started by a group of book lovers and tech enthusiasts in 2024, we saw the gap between traditional
                libraries and the fast-paced, digital-first lifestyle many live today. So we built a space where
                anyone—from students and researchers to casual readers—can find and enjoy great books in just a few
                clicks.</p>

            <h3>💡 What Makes Us Different</h3>
            <ul>
                <li><strong>Free & Affordable Access:</strong> We believe education and stories should never be behind a
                    paywall.</li>
                <li><strong>Curated Collections:</strong> Our team handpicks and regularly updates collections to keep
                    things fresh and relevant.</li>
                <li><strong>Smart Recommendations:</strong> Personalized book suggestions based on your reading history.
                </li>
            </ul>

            <h3>🔍 Our Vision for the Future</h3>
            <p>At LibraByte, we are always evolving. We aim to expand our library continuously, offering the latest
                publications and diverse content across various genres. We plan to integrate more advanced technologies,
                such as AI-powered search features and voice-based browsing, making the library experience even more
                accessible for all.</p>

            <h3>🌱 Our Commitment to Sustainability</h3>
            <p>In addition to our passion for reading, we are committed to sustainability. We believe in reducing our
                environmental footprint and are exploring ways to minimize the energy consumption of our digital
                platform. By promoting e-books and online resources, we aim to contribute to a paperless future.</p>

            <h3>📬 Let’s Stay Connected</h3>
            <p>Have questions, feedback, or just want to say hi? email us at <a
                    href="mailto:support@libraByte.io">support@libraByte.io</a></p>
            <p>Join our newsletter and never miss an update or book drop!</p>
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