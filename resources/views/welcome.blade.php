<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autilearn</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        /* Global Styles */
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f7f9fc; /* Soft background color */
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Header Section */
        .header {
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo {
            height: 60px;
            margin-right: 20px;
        }

        .system-name {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        }

        /* Hero Section */
        .hero {
            padding: 80px 20px;
            background: linear-gradient(to right, #00aaff, #007bff); /* Gradient background */
            color: white;
            text-align: center;
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-bottom-left-radius: 50% 25%;
            border-bottom-right-radius: 50% 25%;
        }

        .hero h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .hero p {
            font-size: 20px;
            max-width: 750px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .cta-buttons a {
            display: inline-block;
            padding: 15px 30px;
            font-size: 18px;
            color: white;
            background-color: #007bff;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            margin: 10px;
            transition: background-color 0.3s ease;
        }

        .cta-buttons a:hover {
            background-color: #0056b3;
        }

        /* Features Section */
        .features {
            padding: 60px 20px;
            background: #ffffff;
            text-align: center;
        }

        .features h2 {
            font-size: 36px;
            color: #007bff;
            margin-bottom: 40px;
        }

        .feature-box {
            max-width: 850px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 15px;
            background: #f8f9fa;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .feature-box h3 {
            color: #0056b3;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .feature-box p {
            font-size: 18px;
            color: #555;
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            background: #ffffff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <img src="{{ asset('images/logo.png') }}" alt="Autilearn Logo" class="logo">
    <span class="system-name">Autilearn</span>
</div>

<!-- Hero Section -->
<div class="hero">
    <h1>Empowering Every Autistic Child Through Learning</h1>
    <p>Autilearn is a dedicated learning platform designed for educators and parents to help children with autism learn and grow. Start making a difference today!</p>

    <div class="cta-buttons">
        @if (Route::has('login'))
            <a href="{{ route('login') }}">Login</a>
        @endif
    </div>
</div>

<!-- Features Section -->
<div class="features">
    <h2>Why Choose Autilearn?</h2>

    <div class="feature-box">
        <h3>👩‍🏫 For Educators</h3>
        <p>✓ Track and manage student progress.<br>✓ Provide a supportive environment with specialized resources.</p>
    </div>

    <div class="feature-box">
        <h3>👨‍👩‍👧 For Parents</h3>
        <p>✓ View real-time progress of your child.<br>✓ Stay connected with educators to ensure the best support for your child.</p>
    </div>

    <div class="feature-box">
        <h3>🔑 How to Get Started?</h3>
        <p>1. Educators log in using the provided credentials.<br>2. Parents receive login details after educator register their children.<br>3. Begin tracking progress and engaging in learning activities!</p>
    </div>
</div>

{{--<!-- Contact Us Section -->
<div class="contact-us" style="padding: 60px 20px; background-color: #f0f4f8; text-align: center;">
    <h2 style="font-size: 36px; color: #007bff; margin-bottom: 40px;">Contact Us</h2>
    <form action="#" method="post" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <input type="text" name="name" placeholder="Your Name" style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #ccc;">
        <input type="email" name="email" placeholder="Your Email" style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #ccc;">
        <textarea name="message" placeholder="Your Message" style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #ccc; height: 150px;"></textarea>
        <button type="submit" style="padding: 10px 20px; font-size: 18px; background-color: #007bff; color: white; border: none; border-radius: 30px; cursor: pointer;">Send Message</button>
    </form>
</div>--}}


<!-- Footer -->
<div class="footer">
    &copy; {{ date('Y') }} Autilearn. All Rights Reserved.
</div>

</body>
</html>
