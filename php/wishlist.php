<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

include 'config.php';

// Add to Wishlist
if (isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);

    if ($stmt->execute()) {
        echo "Product added to wishlist!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Remove from Wishlist
if (isset($_GET['remove_from_wishlist'])) {
    $wishlist_id = $_GET['remove_from_wishlist'];

    $stmt = $conn->prepare("DELETE FROM wishlist WHERE id = ?");
    $stmt->bind_param("i", $wishlist_id);

    if ($stmt->execute()) {
        echo "Product removed from wishlist!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch Wishlist Items
$user_id = $_SESSION['user_id'];
$wishlist_query = "SELECT w.id, p.name, p.description, p.price, p.image 
                   FROM wishlist w 
                   JOIN products p ON w.product_id = p.id 
                   WHERE w.user_id = ?";
$stmt = $conn->prepare($wishlist_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$wishlist_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
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
            <li><a href="wishlist.php">WISHLIST</a></li>
            <li><a href="cart.php">CART</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        </ul>
    </nav>

    <section class="section__container">
        <h2 class="section__header">Wishlist</h2>
        <div class="wishlist__grid">
            <?php foreach ($wishlist_items as $item): ?>
                <div class="wishlist__card">
                    <img src="assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    <h4><?php echo $item['name']; ?></h4>
                    <p><?php echo $item['description']; ?></p>
                    <p>GHS <?php echo $item['price']; ?></p>
                    <a href="wishlist.php?remove_from_wishlist=<?php echo $item['id']; ?>" class="btn">Remove</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <script src="assets/js/main.js"></script>
</body>
</html>