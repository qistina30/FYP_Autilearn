<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autilearn</title>
    <meta name="description" content="A supportive learning platform for autistic children, parents, and educators.">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Baloo 2', cursive;
            margin: 0;
            padding: 0;
            background-color: #f2f8ff;
            color: #333;
        }

        .hero {
            padding: 80px 20px;
            background: url("{{ asset('images/children-background.jpg') }}") center center / cover no-repeat;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 70vh;
            border-bottom-left-radius: 60% 10%;
            border-bottom-right-radius: 60% 10%;
            position: relative;
            z-index: 1;
            overflow: hidden;
            animation: fadeInUp 1.2s ease-in-out both;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 102, 204, 0.6);
            z-index: -1;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: 20px;
            max-width: 700px;
            color: #f0f8ff;
        }

        .cta-buttons a {
            margin-top: 30px;
            display: inline-block;
            background-color: #0077cc;
            color: #fff;
            padding: 14px 28px;
            font-size: 18px;
            border-radius: 30px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .cta-buttons a:hover {
            background-color: #005fa3;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive layout */
        @media (max-width: 768px) {
            .hero {
                padding: 60px 20px;
            }

            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 18px;
                max-width: 600px;
            }
        }


        .features {
            padding: 60px 20px;
            background-color: #e9f3ff;
            text-align: center;
        }

        .features h2 {
            color: #0077cc;
            font-size: 32px;
            margin-bottom: 40px;
        }

        .feature-box {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* ✨ Add transition */
        }

        .feature-box:hover {
            transform: scale(1.02); /* ✨ Subtle lift */
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12); /* Optional deeper shadow */
        }

        .feature-icon {
            font-size: 50px;
            color: #0077cc;
        }

        .feature-box div {
            text-align: left;
        }

        .feature-box h3 {
            font-size: 22px;
            color: #0077cc;
            margin-bottom: 10px;
        }

        .feature-box p {
            font-size: 16px;
            color: #555;
        }

        .footer {
            background-color: #dbeeff;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #444;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 32px;
            }

            .feature-box {
                flex-direction: column;
                text-align: center;
            }

            .feature-box div {
                text-align: center;
            }
        }

        .hero-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .hero-logo {
            height: 60px;
            margin-right: 10px;
        }

        .hero-system-name {
            font-size: 30px;
            font-weight: 600;
            color: #fff;
        }
    </style>
</head>
<body>

<!-- Hero -->
<div class="hero">
    <div class="hero-header">
        <img src="{{ asset('images/logo.png') }}" alt="Autilearn Logo" class="hero-logo">
        <span class="hero-system-name">Autilearn</span>
    </div>
    <h1>Inspiring Learning through Love and Support</h1>
    <p>Autilearn is a fun, safe, and helpful space for children with autism, their parents, and educators. Let’s learn and grow together!</p>
    <div class="cta-buttons">
        <a href="{{ route('login') }}">Login</a>
    </div>
</div>


<!-- Features -->
<div class="features">
    <h2>What Makes Autilearn Special?</h2>

    <div class="feature-box">
        <div class="feature-icon">👩‍🏫</div>
        <div>
            <h3>For Educators</h3>
            <p>Track your students’ learning and provide personalized support with simple tools designed for special needs learning.</p>
        </div>
    </div>

    <div class="feature-box">
        <div class="feature-icon">👪</div>
        <div>
            <h3>For Parents</h3>
            <p>Stay connected with your child's progress and communicate with educators. Autilearn helps you be part of their learning journey.</p>
        </div>
    </div>

    <div class="feature-box">
        <div class="feature-icon">🧩</div>
        <div>
            <h3>How it Works</h3>
            <p>Educators will receive their unique ID and default password directly from their school. With these credentials, they can log in to the system and begin registering students.
                Teachers and parents can then collaborate to track progress, support learning, and celebrate milestones together!</p>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    &copy; {{ date('Y') }} Autilearn. All Rights Reserved.
</div>

</body>
</html>
