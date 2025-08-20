<?php include "includes/header.php"; ?>


<!-- Hero Section -->
<div class="hero" style="background: url('assets/images/pizza.jpg') no-repeat center center/cover; height: 90vh; color: #fff; display:flex; align-items:center; justify-content:center; text-align:center;">
    <div>
        <h1 style="font-size:50px; font-weight:bold;">Welcome to Foodie Restaurant</h1>
        <p style="font-size:20px; margin:20px 0;">Delicious food made with love & served with passion</p>
        <a href="menu.php" class="btn btn-lg btn-warning">Explore Our Menu</a>
    </div>
</div>

<!-- About Us -->
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h2>About Us</h2>
            <p>
                At <b>Foodie Restaurant</b>, we bring you the best taste in town with fresh ingredients 
                and authentic recipes. From juicy burgers to cheesy pizzas – we serve happiness on your plate!
            </p>
            <a href="about.php" class="btn btn-outline-primary">Learn More</a>
        </div>
        <div class="col-md-6">
            <img src="assets/images/burger.jpg" class="img-fluid rounded shadow" alt="Burger">
        </div>
    </div>
</div>

<!-- Popular Menu -->
<div class="container my-5">
    <h2 class="text-center mb-4">Popular Dishes</h2>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <img src="assets/images/pizza.jpg" class="card-img-top" alt="Pizza">
                <div class="card-body">
                    <h5 class="card-title">Cheesy Pizza</h5>
                    <p class="card-text">Hot, fresh & cheesy pizza with toppings you’ll love.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <img src="assets/images/pasta.jpg" class="card-img-top" alt="Pasta">
                <div class="card-body">
                    <h5 class="card-title">Creamy Pasta</h5>
                    <p class="card-text">Rich & creamy pasta cooked in authentic white sauce.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <img src="assets/images/chicken.jpg" class="card-img-top" alt="Chicken">
                <div class="card-body">
                    <h5 class="card-title">Crispy Chicken</h5>
                    <p class="card-text">Golden fried chicken served hot & crispy.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Why Choose Us -->
<div class="container my-5">
    <h2 class="text-center mb-4">Why Choose Us?</h2>
    <div class="row text-center">
        <div class="col-md-3 mb-3">
            <div class="p-3 border rounded shadow-sm">
                <h4>🍔 Fresh Ingredients</h4>
                <p>We always use the freshest and quality ingredients.</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="p-3 border rounded shadow-sm">
                <h4>⚡ Fast Delivery</h4>
                <p>Get your order delivered hot & fresh in no time.</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="p-3 border rounded shadow-sm">
                <h4>👨‍🍳 Expert Chefs</h4>
                <p>Our skilled chefs craft each dish with perfection.</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="p-3 border rounded shadow-sm">
                <h4>⭐ Customer Satisfaction</h4>
                <p>We believe in serving smiles with our food.</p>
            </div>
        </div>
    </div>
</div>

<!-- Contact CTA -->
<div class="text-center py-5" style="background:#f8f9fa;">
    <h2>Hungry? Let’s Talk!</h2>
    <p>Order your favorite food now or get in touch with us.</p>
    <a href="contact.php" class="btn btn-lg btn-success">Contact Us</a>
</div>

<?php include "includes/footer.php"; ?>
