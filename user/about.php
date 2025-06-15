<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Timeless EO</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <!-- <img src="img/Logo_timeless.png" alt="Timeless Logo" class="logo-img"> -->
            <h1 class="logo-text">TimeLess EO</h1>
            <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
            </div>
            <nav>
                <a href="../index.php">Home</a>
                <a href="list.php">List</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
                <span class="icons">
                    <a href="profile.php"><i class="fa-solid fa-user"></i></a>
                </span>
            </nav>
        </div>
    </header>

    <main class="about-main">
        <section class="about-hero">
            <div class="container">
                <h2>Our Story</h2>
                <p class="subtitle">Creating timeless moments since 2025</p>
            </div>
        </section>

        <section class="about-content">
            <div class="container">
                <div class="about-grid">
                    <div class="about-text">
                        <h3>Who We Are</h3>
                        <p>Timeless EO is a premier event organizing company dedicated to crafting unforgettable experiences. Founded in Bandung, we've grown to become one of the most trusted names in the industry.</p>
                        
                        <h3>Our Mission</h3>
                        <p>To transform ordinary events into extraordinary memories through innovative design, meticulous planning, and flawless execution.</p>
                        
                        <h3>Core Values</h3>
                        <ul class="values-list">
                            <li>Creativity & Innovation</li>
                            <li>Attention to Detail</li>
                            <li>Client-Centric Approach</li>
                            <li>Sustainability</li>
                        </ul>
                    </div>
                    
                    <div class="about-image">
                        <img src="../img/ourteam2.jpg" alt="Our Team">
                    </div>
                </div>
            </div>
        </section>

        <section class="team-section">
            <div class="container">
                <h3>Meet The Team</h3>
                <div class="team-grid">
                    <div class="team-member">
                        <img src="../img/septiawan.png" alt="septiawan - Founder">
                        <h4>Septiawan Hadi Prasetyo</h4>
                        <p>Founder & CEO</p>
                    </div>
                    <div class="team-member">
                        <img src="../img/restu.png" alt="restu - Creative Director">
                        <h4>Restu Utami</h4>
                        <p>Creative Director</p>
                    </div>
                    <div class="team-member">
                        <img src="../img/nabila.png" alt="nabila - Operations">
                        <h4>Dyna Nabilah Wiaam</h4>
                        <p>Operations Manager</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-logo">Timeless EO</div>
            <div class="footer-links">
                <a href="../index.php">Home</a>
                <a href="list.php">Packages</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
                 <a href="../privacy.php">Privacy Policy</a>
            </div>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-pinterest"></i></a>
            </div>
            <div class="copyright">© 2025 Timeless EO. All rights reserved.</div>
        </div>
    </footer>
<script>
    const hamburger = document.getElementById('hamburger');
    const menu = document.getElementById('menu');
    hamburger.addEventListener('click', function() {
    menu.classList.toggle('active');
   });
</script>
</body>
</html>