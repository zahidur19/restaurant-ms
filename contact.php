<?php include "includes/header.php"; ?>


<!-- Hero Section -->
<div class="hero" style="background: url('assets/images/restaurant.jpg') no-repeat center center/cover; height: 50vh; display:flex; align-items:center; justify-content:center; color:#fff; text-align:center;">
    <div>
        <h1 style="font-size:45px; font-weight:bold;">Contact Us</h1>
        <p style="font-size:18px;">We’d love to hear from you! Get in touch with us.</p>
    </div>
</div>

<!-- Contact Info -->
<div class="container my-5">
    <div class="row text-center">
        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 p-3">
                <i class="fas fa-map-marker-alt fa-2x text-danger mb-3"></i>
                <h5>Our Location</h5>
                <p>123 Main Street, Dhaka, Bangladesh</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 p-3">
                <i class="fas fa-phone fa-2x text-success mb-3"></i>
                <h5>Call Us</h5>
                <p>+880 1234 567 890</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 p-3">
                <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                <h5>Email Us</h5>
                <p>info@foodie-restaurant.com</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 p-3">
                <i class="fas fa-clock fa-2x text-warning mb-3"></i>
                <h5>Opening Hours</h5>
                <p>Sat - Thu: 10:00 AM - 11:00 PM<br>Friday: Closed</p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Form -->
<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <h2>Send Us a Message</h2>
            <form action="contact.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Your Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" name="send" class="btn btn-danger">Send Message</button>
            </form>

            <?php
            include "includes/config.php";
            if (isset($_POST['send'])) {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $message = $_POST['message'];

                $sql = "INSERT INTO messages (name, email, message) VALUES ('$name','$email','$message')";
                if (mysqli_query($conn, $sql)) {
                    echo "<div class='alert alert-success mt-3'>Message sent successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Something went wrong!</div>";
                }
            }
            ?>
        </div>

        <!-- Google Map -->
        <div class="col-md-6">
            <h2>Find Us Here</h2>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.903453269287!2d90.3910!3d23.7509!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8947d6c0bfb%3A0xa0e3f8f4569a5c8!2sDhaka%2C%20Bangladesh!5e0!3m2!1sen!2sbd!4v1670000000000"
                width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
