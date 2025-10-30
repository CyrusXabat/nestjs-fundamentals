<?php
session_start();
require 'includes/header.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $variationId = $_POST['variation'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$productId][$variationId])) {
        $_SESSION['cart'][$productId][$variationId]['quantity'] += $quantity;
    } else {
        $productQuery = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $productQuery->execute([$productId]);
        $product = $productQuery->fetch();

        $variationQuery = $pdo->prepare("SELECT * FROM product_variations WHERE id = ?");
        $variationQuery->execute([$variationId]);
        $variation = $variationQuery->fetch();

        $_SESSION['cart'][$productId][$variationId] = [
            'product_name' => $product['name'],
            'variation' => $variation['attribute'] . ': ' . $variation['value'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }
}

$cart = $_SESSION['cart'] ?? [];
?>

<main>
    <section class="cart">
        <h2>Shopping Cart</h2>
        <?php if (empty($cart)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Variation</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($cart as $productId => $variations): ?>
                    <?php foreach ($variations as $variationId => $item): ?>
                        <tr>
                            <td><?php echo $item['product_name']; ?></td>
                            <td><?php echo $item['variation']; ?></td>
                            <td>$<?php echo $item['price']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
                            <td>
                                <form action="cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <input type="hidden" name="variation_id" value="<?php echo $variationId; ?>">
                                    <button type="submit" name="action" value="remove">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </table>
            <a href="checkout.php">Proceed to Checkout</a>
        <?php endif; ?>
    </section>
</main>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {
    $productId = $_POST['product_id'];
    $variationId = $_POST['variation_id'];
    unset($_SESSION['cart'][$productId][$variationId]);

    if (empty($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }

    header('Location: cart.php');
    exit;
}
?>

<?php require 'includes/footer.php'; ?>