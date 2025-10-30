<?php
session_start();
require 'includes/header.php';
require 'db.php';

$bannerQuery = $pdo->query("SELECT * FROM banners");
$featuredProductsQuery = $pdo->query("SELECT * FROM products WHERE featured = 1");
$categoriesQuery = $pdo->query("SELECT * FROM categories");
?>

<main>
    <section class="banners">
        <?php while ($banner = $bannerQuery->fetch()): ?>
            <div class="banner">
                <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>">
                <div class="banner-content">
                    <h2><?php echo $banner['title']; ?></h2>
                    <p><?php echo $banner['description']; ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </section>

    <section class="featured-products">
        <h2>Featured Products</h2>
        <div class="products">
            <?php while ($product = $featuredProductsQuery->fetch()): ?>
                <div class="product">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                    <h3><?php echo $product['name']; ?></h3>
                    <p>$<?php echo $product['price']; ?></p>
                    <a href="product.php?id=<?php echo $product['id']; ?>">View Details</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="categories">
        <h2>Categories</h2>
        <div class="category-list">
            <?php while ($category = $categoriesQuery->fetch()): ?>
                <div class="category">
                    <a href="search.php?category=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<?php require 'includes/footer.php'; ?>