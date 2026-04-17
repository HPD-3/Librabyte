<nav>
    <div class="brand">Libra<span>Byte</span></div>

    <div class="hamburger" onclick="toggleMenu()">☰</div>

    <div class="nav-links" id="navLinks">
        <a href="index.php" class="<?= ($activePage == 'index.php') ? 'active' : '' ?>">Home</a>
        <a href="about.php" class="<?= ($activePage == 'about.php') ? 'active' : '' ?>">About us</a>
        <a href="review.php" class="<?= ($activePage == 'review.php') ? 'active' : '' ?>">Users Reviews</a>
    </div>

    <div class="nav-auth" id="navAuth">
        <a href="register.php" class="signup">Sign Up</a>
        <a href="login.php" class="signin">Sign In</a>
    </div>
</nav>
