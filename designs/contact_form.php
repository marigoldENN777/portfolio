<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link
            href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,400..900;1,400..900&family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
            rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="./assets/style_music_classes.css">
    <style>
        @media screen and (max-width: 500px) {
            .hero div.menu-and-main-title {
                height: 40vh;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="hero">
        <div class="background-light-green menu-and-main-title">
            <ul class="main-menu">
                <a href="./music_classes.html">
                    <li>Home</li>
                </a>
            </ul>
            <div class="d-flex flex-direction-column justify-center align-center main-title">
                <!-- <h1 style="width: min-content; position:relative; top: 20px">I LOVE SINGING</h1> -->
                <img src="./assets/images/logo.png" alt="Logo">
            </div>
        </div>


    </div>
</div>
    <section id="Contact" class="contact-section" style="max-width:90%; margin:0 auto; background-color: #034748; color: white; padding: 0 20px 60px 20px; text-align: center;">
    <h2>Contact Me</h2>
    <p>I will reply as soon as possible.</p>

    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <p style="color: #00ffcc; margin-top: 15px;">Your message has been sent successfully!</p>
    <?php endif; ?>

    <form action="send_message.php" method="POST" class="contact-form" style="max-width: 500px; margin: 30px auto; display: flex; flex-direction: column; gap: 15px;">
        <input type="text" name="name" placeholder="Your Name" required style="padding: 10px; border: none; border-radius: 5px;">
        <input type="email" name="email" placeholder="Your Email" required style="padding: 10px; border: none; border-radius: 5px;">
        <textarea name="message" placeholder="Your Message" rows="5" required style="padding: 10px; border: none; border-radius: 5px;"></textarea>
        <button type="submit" style="background-color: #001021; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer;">Send Message</button>
    </form>
</section>

</body>
</html>