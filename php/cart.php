<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

include 'config.php';

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);

    if ($stmt->execute()) {
        echo "Product added to cart!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Remove from Cart
if (isset($_GET['remove_from_cart'])) {
    $cart_id = $_GET['remove_from_cart'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);

    if ($stmt->execute()) {
        echo "Product removed from cart!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch Cart Items
$user_id = $_SESSION['user_id'];
$cart_query = "SELECT c.id, p.name, p.description, p.price, p.image, c.quantity 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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
        <h2 class="section__header">Cart</h2>
        <div class="cart__grid">
            <?php foreach ($cart_items as $item): ?>
                <div class="cart__card">
                    <img src="assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    <h4><?php echo $item['name']; ?></h4>
                    <p><?php echo $item['description']; ?></p>
                    <p>GHS <?php echo $item['price']; ?></p>
                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                    <a href="cart.php?remove_from_cart=<?php echo $item['id']; ?>" class="btn">Remove</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <script src="assets/js/main.js"></script>
</body>
</html>