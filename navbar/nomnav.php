<nav>
    <div class="brand">Libra<span>Byte</span></div>

    <div class="hamburger" onclick="toggleMenu()">☰</div>

    <div class="nav-links" id="navLinks">
        <a href="index.php" class="<?= ($activePage == 'index.php') ? 'active' : '' ?>">Home</a>
        <a href="blog.php" class="<?= ($activePage == 'blog.php') ? 'active' : '' ?>">Blog</a>
        <a href="about.php" class="<?= ($activePage == 'about.php') ? 'active' : '' ?>">About us</a>
    </div>
</nav>
