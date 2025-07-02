<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: signin.php");
    exit();
}

include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <nav>
        <div class="nav__header">
            <div class="nav__logo">
                <a href="index.html">ShopAshiorkor</a>
            </div>
            <div class="nav__menu__btn" id="menu-btn">
                <i class="ri-menu-line"></i>
            </div>
        </div>
        <ul class="nav__links" id="nav-links">
            <li><a href="index.html">HOME</a></li>
            <li><a href="catalogue.html">CATALOGUE</a></li>
            <li><a href="events.html">EVENTS</a></li>
            <li><a href="admin_dashboard.php">DASHBOARD</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        </ul>
    </nav>

    <section class="section__container">
        <h2 class="section__header">Admin Dashboard</h2>
        <div class="admin__options">
            <a href="add_product.php" class="btn">Add Product</a>
            <a href="add_event.php" class="btn">Add Event</a>
            <a href="analytics.php" class="btn">View Analytics</a>
        </div>
    </section>

    <script src="assets/js/main.js"></script>
</body>
</html>