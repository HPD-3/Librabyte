<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->prepare("SELECT * FROM favorites WHERE user_id = ?");
$result->bind_param("i", $user_id);
$result->execute();
$favorites = $result->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Favorites</title>
    <link rel="stylesheet" href="assets/dashstyle.css">
</head>

<body>
<script src="scripts/dashboard.js"></script>
<?php include('navbar/dashnav.php'); ?>
<button class="button"><a href="dashboard.php">⬅ Back to Dashboard</a></button>

<div id="favorite">
    <h3 id="fav">📚 My Favorite Books</h3>
</div>

<div id="results" class="results">
    <?php while ($book = $favorites->fetch_assoc()): 
        $book_id = htmlspecialchars($book['book_id']);
        $title = htmlspecialchars($book['title']);
        $authors = htmlspecialchars($book['authors']);
        $rating = $book['rating'] ? $book['rating'] . ' / 5' : 'No rating';
        $thumbnail = htmlspecialchars($book['thumbnail']);
    ?>
        <div class="book" data-book-id="<?= $book_id ?>">
            <div class="book-card">
                <a href="books.php?id=<?= $book_id ?>">
                    <img src="<?= $thumbnail ?>" alt="Book Cover">
                </a>
                <div class="book-details">
                    <h4><a href="books.php?id=<?= $book_id ?>"><?= $title ?></a></h4>
                    <p><strong>Rating:</strong> <?= $rating ?></p>
                    <p><strong>Author(s):</strong> <?= $authors ?></p>
                    <button onclick="removeFavorite('<?= $book_id ?>')">Remove</button>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script>
    function removeFavorite(bookId) {
        fetch('api/remove_favorite.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ book_id: bookId })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`[data-book-id="${bookId}"]`).remove();
                    alert('Removed from favorites.');
                } else {
                    alert('Failed to remove.');
                }
            });
    }
</script>

</body>
</html>
