<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: signin.php");
    exit();
}

include 'config.php';

$result = $conn->query("SELECT * FROM analytics ORDER BY date DESC");
$analytics = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
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

    <?php
include 'config.php';

$date = date("Y-m-d");

// Check if today's date already exists in analytics
$stmt = $conn->prepare("SELECT * FROM analytics WHERE date = ?");
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing record
    $stmt = $conn->prepare("UPDATE analytics SET page_views = page_views + 1 WHERE date = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
} else {
    // Insert new record
    $stmt = $conn->prepare("INSERT INTO analytics (date, page_views, unique_visitors) VALUES (?, 1, 1)");
    $stmt->bind_param("s", $date);
    $stmt->execute();
}

$stmt->close();
?>
    

    <section class="section__container">
        <h2 class="section__header">Analytics</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Page Views</th>
                    <th>Unique Visitors</th>
                    <th>Sales</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($analytics as $data): ?>
                    <tr>
                        <td><?php echo $data['date']; ?></td>
                        <td><?php echo $data['page_views']; ?></td>
                        <td><?php echo $data['unique_visitors']; ?></td>
                        <td><?php echo $data['sales']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <script src="assets/js/main.js"></script>
</body>
</html>