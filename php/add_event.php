<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: signin.php");
    exit();
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $image = $_FILES['image']['name'];
    $target = "assets/images/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $description, $event_date, $image);

        if ($stmt->execute()) {
            echo "Event added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Failed to upload image!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
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
        <h2 class="section__header">Add Event</h2>
        <form action="add_event.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Event Title" required>
            <textarea name="description" placeholder="Event Description" required></textarea>
            <input type="date" name="event_date" required>
            <input type="file" name="image" required>
            <button type="submit" class="btn">Add Event</button>
        </form>
    </section>

    <script src="assets/js/main.js"></script>
</body>
</html>