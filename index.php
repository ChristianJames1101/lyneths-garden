<link rel="icon" href="logo.jpg" type="image/x-icon">
<?php
include 'db.php';

$products = $conn->query("SELECT * FROM products");

$slideshowImages = array_slice(
    glob("uploads/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE),
    0, 5
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lyneth's Garden</title>
  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('bg.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.18);
      z-index: -1;
    }

    header {
      position: fixed;
      top: 0;
      width: 100%;
      background-color:rgba(46, 125, 50, 0.7);
      color: white;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
      z-index: 1000;
      flex-wrap: wrap;
      gap: 1rem;
    }

    header h1 {
      margin: 0;
      font-size: 1.5rem;
      flex: 1 1 auto;
    }

    .navbar-controls {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .navbar-controls input {
      padding: 0.5rem;
      font-size: 1rem;
      border-radius: 18px;
      border: none;
      width: 200px;
    }

    .navbar-controls button {
      padding: 0.5rem 1rem;
      background-color:rgba(46, 125, 50, 0);
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
    }

    .navbar-controls button:hover {
      background-color: #256429;
    }

    .spacer { height: 100px; }

    .slideshow-wrapper {
      overflow: hidden;
      width: 100%;
      padding: 1rem 0;
      position: relative;
    }

    .slideshow-track {
      display: flex;
      gap: 10px;
      animation: scroll-left 30s linear infinite;
      width: max-content;
    }

    .slideshow-track img {
      height: 300px;
      width: auto;
      border-radius: 10px;
      object-fit: cover;
      flex-shrink: 0;
    }

    @keyframes scroll-left {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }

    /* Section Labels */
    .section-label {
      text-align: center;
      margin: 3rem 0 2rem 0;
      position: relative;
    }

    .section-label h2 {
      font-size: 2.5rem;
      font-weight: 700;
      color: #2e7d32;
      margin: 0;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      position: relative;
      display: inline-block;
      padding: 0.5rem 2rem;
    }

    .section-label h2::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(46, 125, 50, 0.1), rgba(46, 125, 50, 0.05));
      border-radius: 15px;
      border: 2px solid rgba(46, 125, 50, 0.3);
      z-index: -1;
    }

    .section-label h2::after {
      content: '';
      position: absolute;
      top: -5px;
      left: -5px;
      right: -5px;
      bottom: -5px;
      background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      border-radius: 20px;
      z-index: -2;
    }

    /* New combined section styles */
    .combined-section {
      display: flex;
      gap: 2rem;
      padding: 2rem;
      align-items: flex-start;
    }

    .announcements-section,
    .showcase-section {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .section-title {
      text-align: center;
      margin-bottom: 1rem;
    }

    .section-title h3 {
      font-size: 2rem;
      font-weight: 700;
      color: #2e7d32;
      margin: 0;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      position: relative;
      display: inline-block;
      padding: 0.5rem 1.5rem;
    }

    .section-title h3::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(46, 125, 50, 0.1), rgba(46, 125, 50, 0.05));
      border-radius: 15px;
      border: 2px solid rgba(46, 125, 50, 0.3);
      z-index: -1;
    }

    .announcements-content,
    .showcase-content {
      display: flex;
      flex-direction: column;
      gap: 20px;
      align-items: center;
    }

    .menu-img {
      width: 100%;
      max-width: 450px;
      height: auto;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.4);
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .menu-img:hover {
      transform: scale(1.02);
    }

    .menu-video {
      width: 100%;
      max-width: 450px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.4);
      transition: transform 0.3s ease;
    }

    .menu-video:hover {
      transform: scale(1.02);
    }

    .products-container {
      display: flex;
      flex-direction: column;
      gap: 2rem;
      padding: 2rem;
    }

    .product-card {
      display: flex;
      background-color: rgba(0, 0, 0, 0.4);
      border-radius: 10px;
      padding: 1rem;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
      transition: transform 0.2s;
      gap: 2rem;
      align-items: center;
      width: 100%;
    }

    .product-card:hover {
      transform: scale(1.01);
    }

    .product-image img {
      width: 400px;
      height: 400px;
      object-fit: cover;
      border-radius: 8px;
    }

    .product-info {
      flex: 1;
      color: white;
    }

    .product-info h2 {
      font-size: 1.8rem;
      margin: 0.5rem 0;
      font-weight: 500;
    }

    .product-info p {
      font-size: 1.1rem;
      color: #f0f0f0;
      margin: 0.25rem 0;
      font-weight: 300;
    }

    .price { color: #c8f5c3; font-size: 1.2rem; font-weight: 400; }

    .stock { color: #dddddd; font-size: 1rem; font-weight: 300; }

    @media (max-width: 768px) {
      header { flex-direction: column; align-items: flex-start; }
      .product-card { flex-direction: column !important; text-align: center; }
      .product-image img { width: 100%; height: auto; }
      .navbar-controls input { width: 100%; }
      .slideshow-track img { height: 200px; }
      
      .section-label h2 {
        font-size: 2rem;
        padding: 0.5rem 1rem;
      }
      
      .section-title h3 {
        font-size: 1.5rem;
        padding: 0.5rem 1rem;
      }

      .combined-section {
        flex-direction: column;
        gap: 2rem;
      }

      .menu-img, .menu-video {
        width: 100%;
      }
    }

    .logo-title {
      display: flex;
      align-items: center;
      gap: 1rem;
      flex: 1 1 auto;
    }

    .logo-title img {
      height: 60px;
      width: auto;
      border-radius: 8px;
    }

    /* FAQs Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 2000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow-y: auto;
      background-color: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
      background-color: #fff;
      margin: 5% auto;
      padding: 2rem;
      border-radius: 10px;
      width: 90%;
      max-width: 800px;
      max-height: 90vh;
      overflow-y: auto;
      color: #333;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: black;
    }
    .search-wrapper {
      position: relative;
      width: 200px;
    }

    .search-wrapper input {
      width: 100%;
      padding: 0.5rem 2.5rem 0.5rem 0.5rem;
      font-size: 1rem;
      border-radius: 18px;
      border: none;
    }

    .search-icon,
    .clear-icon {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 1.2rem;
      color: white;
      user-select: none;
    }

    .search-icon {
      right: 2rem;
      color: black;
    }

    .clear-icon {
      right: 0.5rem;
      color: black;
    }
  </style>
</head>
<body>

  <header>
    <div class="logo-title">
      <img src="logo.jpg" alt="Logo">
      <h1>Lyneth's Garden</h1>
    </div>
    <div class="navbar-controls">
      <div class="search-wrapper">
        <input type="text" id="searchInput" placeholder="Search products...">
        <span class="search-icon" onclick="searchProducts()">🔍︎</span>
        <span class="clear-icon" onclick="clearSearch()">✖</span>
      </div>
      <button onclick="faqs()">FAQs</button>
      <a href="login.php" style="text-decoration: none;"><button>Login to Order</button></a>
    </div>
  </header>

  <div class="spacer"></div>

  <div class="slideshow-wrapper">
    <div class="slideshow-track">
      <?php foreach ($slideshowImages as $image): ?>
        <img src="<?= $image ?>" alt="Slideshow Image">
      <?php endforeach; ?>
      <?php foreach ($slideshowImages as $image): ?>
        <img src="<?= $image ?>" alt="Slideshow Image">
      <?php endforeach; ?>
    </div>
  </div>

  <?php
$menuImages = glob("featured/pics/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
$menuVideos = glob("featured/videos/*.{mp4,webm,ogg}", GLOB_BRACE);
?>

<?php if (!empty($menuImages) || !empty($menuVideos)): ?>
<div class="combined-section">
  <?php if (!empty($menuImages)): ?>
  <div class="announcements-section">
    <div class="section-title">
      <h3>📢 Announcements</h3>
    </div>
    <div class="announcements-content">
      <?php foreach ($menuImages as $img): ?>
        <img src="<?= htmlspecialchars($img) ?>" alt="Menu Image" class="menu-img">
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <?php if (!empty($menuVideos)): ?>
  <div class="showcase-section">
    <div class="section-title">
      <h3>🎬 Showcase</h3>
    </div>
    <div class="showcase-content">
      <?php foreach ($menuVideos as $video): ?>
        <video class="menu-video" controls autoplay muted loop>
          <source src="<?= htmlspecialchars($video) ?>" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<div class="section-label">
  <h2>🌱 Our Products</h2>
</div>

  <div class="products-container">
    <?php 
    $i = 0;
    while ($row = $products->fetch_assoc()): 
      $i++;
      $imageOnLeft = $i % 2 == 0;
    ?>
      <div class="product-card" style="flex-direction: <?= $imageOnLeft ? 'row' : 'row-reverse' ?>;">
        <div class="product-image">
          <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        </div>
        <div class="product-info">
          <h2><?= htmlspecialchars($row['name']) ?></h2>
          <p class="price">₱<?= number_format($row['price'], 2) ?></p>
          <p class="stock">Stock: <?= $row['stock'] ?></p>
          <p><?= htmlspecialchars($row['description']) ?></p>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <div id="faqsModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeFAQs()">&times;</span>
      <h2>Frequently Asked Questions (FAQs)</h2>

      <h3>1. What is Lyneth's Garden?</h3>
      <p>Lyneth's Garden is a family-owned business based in Guiguinto, Bulacan, Philippines, specializing in a wide variety of plants, landscaping services, and gardening products.</p>

      <h3>2. Where are you located?</h3>
      <p>We are located in Guiguinto, Bulacan. For full directions and contact information, please visit our Contact page or message us directly on Facebook.</p>

      <h3>3. What types of plants do you sell?</h3>
      <p>We offer a wide selection of indoor and outdoor plants, succulents, flowering plants, trees, herbs, and decorative pots.</p>

      <h3>4. Do you offer delivery services?</h3>
      <p>Yes! We deliver within Guiguinto, Bulacan and nearby areas. Delivery fees vary depending on distance and order size.</p>

      <h3>5. Can I order online?</h3>
      <p>Yes. You can browse our online catalog, add products to your cart, and place an order through our website after logging in.</p>

      <h3>6. Do you accept bulk or wholesale orders?</h3>
      <p>Absolutely. We cater to bulk and wholesale orders for landscaping, events, and corporate clients. Contact us for a custom quote.</p>

      <h3>7. What payment methods do you accept?</h3>
      <p>We accept cash on delivery (COD) only within selected areas.</p>

      <h3>8. How do I know if a product is in stock?</h3>
      <p>The stock availability is displayed on each product listing. If an item is out of stock, it will be marked accordingly.</p>

      <h3>9. Do you offer plant care advice?</h3>
      <p>Yes! We're happy to guide you on how to care for your plants. Feel free to message us for tips and tutorials.</p>

      <h3>10. How do I contact you?</h3>
      <p>You can reach us via our Facebook page, contact form on the website, or email at <a href="mailto:lynethsgarden@example.com">lynethsgarden@example.com</a>.</p>
    </div>
  </div>

  <script>
    function searchProducts() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const cards = document.querySelectorAll('.product-card');

      cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(input) ? 'flex' : 'none';
      });
    }

    function clearSearch() {
      document.getElementById('searchInput').value = '';
      searchProducts();
    }

    function faqs() {
      document.getElementById('faqsModal').style.display = 'block';
    }

    function closeFAQs() {
      document.getElementById('faqsModal').style.display = 'none';
    }

    window.onclick = function(event) {
      const modal = document.getElementById('faqsModal');
      if (event.target === modal) {
        modal.style.display = "none";
      }
    }
  </script>

</body>
</html>