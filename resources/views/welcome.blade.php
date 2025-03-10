<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autilearn</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        /* Global Styles */
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f0f8ff; /* Light Blue */
            margin: 0;
            padding: 0;
            text-align: center;
            color: #333;
        }

        /* Header Section */
        .header {
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo {
            height: 50px;
            margin-right: 15px;
        }

        .system-name {
            font-size: 24px;
            font-weight: bold;
        }

        /* Hero Section */
        .hero {
            padding: 60px 20px;
            background: #e3f2fd; /* Soft Blue */
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .hero h1 {
            font-size: 42px;
            font-weight: bold;
            color: #0056b3;
        }

        .hero p {
            font-size: 18px;
            color: #444;
            max-width: 700px;
            margin: 15px auto;
        }

        .cta-buttons {
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            font-size: 18px;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
            margin: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Features Section */
        .features {
            padding: 50px 20px;
            background: #ffffff;
        }

        .features h2 {
            font-size: 32px;
            color: #007bff;
        }

        .feature-box {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            background: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .feature-box h3 {
            color: #0056b3;
        }

        /* Footer */
        .footer {
            background: #ffffff;
            padding: 15px;
            text-align: center;
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
            font-size: 14px;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <img src="{{ asset('images/logo.png') }}" alt="Autilearn Logo" class="logo"> <!-- Ensure logo is placed in public/images -->
    <span class="system-name">Autilearn</span>
</div>

<!-- Hero Section -->
<div class="hero">
    <h1>Empowering Every Autistic Child Through Learning</h1>
    <p>Autilearn is a dedicated learning platform designed for kindergarten educators and parents to help children with autism learn and grow.</p>

    <div class="cta-buttons">
        @if (Route::has('login'))
            <a href="{{ route('login') }}" class="btn">Login</a>
        @endif
      {{--  @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn">Register as Educator</a>
        @endif--}}
    </div>
</div>

<!-- Features Section -->
<div class="features">
    <h2>Why Use Autilearn?</h2>

    <div class="feature-box">
        <h3>👩‍🏫 For Educators</h3>
        <p>✓ Create structured learning plans for children with autism.<br> ✓ Track and manage student progress.<br> ✓ Provide a supportive environment with specialized resources.</p>
    </div>

    <div class="feature-box">
        <h3>👨‍👩‍👧 For Parents</h3>
        <p>✓ View real-time progress of your child.<br> ✓ Access learning materials.<br> ✓ Stay connected with educators to ensure the best support for your child.</p>
    </div>

    <div class="feature-box">
        <h3>🔑 How to Get Started?</h3>
        <p>1. Educators register and add children to the system.<br> 2. Parents receive login credentials once their child is registered.<br> 3. Begin tracking progress and engaging in learning activities!</p>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    &copy; {{ date('Y') }} Autilearn. All Rights Reserved.
</div>

</body>
</html>
