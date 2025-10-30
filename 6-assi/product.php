<?php
session_start();
require 'includes/header.php';
require 'db.php';

$productId = $_GET['id'];
$productQuery = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$productQuery->execute([$productId]);
$product = $productQuery->fetch();

$variationsQuery = $pdo->prepare("SELECT * FROM product_variations WHERE product_id = ?");
$variationsQuery->execute([$productId]);
$variations = $variationsQuery->fetchAll();
?>

<main>
    <section class="product-detail">
        <div class="product-image">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        </div>
        <div class="product-info">
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo $product['description']; ?></p>
            <p>Price: $<?php echo $product['price']; ?></p>
            <p>Stock: <?php echo $product['stock']; ?></p>
            <form action="cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <label for="variation">Select Variation:</label>
                <select name="variation" id="variation">
                    <?php foreach ($variations as $variation): ?>
                        <option value="<?php echo $variation['id']; ?>"><?php echo $variation['attribute']; ?>: <?php echo $variation['value']; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="quantity" value="1" min="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    </section>
</main>

<?php require 'includes/footer.php'; ?>