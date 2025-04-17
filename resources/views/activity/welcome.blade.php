@extends('layouts.app')

@section('content')
    <style>
        body {
            font-family: 'Lexend Deca', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('/images/animalback.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
            overflow: hidden; /* ✅ lock scrolling */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }


        /* Optional overlay */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.8); /* Soft white overlay for readability */
            z-index: 0;
        }

        /* Content container */
        .welcome-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            min-height: 100vh;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .description {
            font-size: 20px;
            max-width: 700px;
            margin-bottom: 30px;
            color: #34495e;
        }

        /* Buttons & Inputs */
        .select-box {
            font-size: 18px;
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        .btn-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .btn {
            font-size: 18px;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            text-decoration: none;
            color: white;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn-start {
            background: #28a745;
        }

        .btn-video {
            background: #007bff;
        }

        /* Videos */
        .video-section {
            display: none;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .video-frame {
            border-radius: 10px;
            overflow: hidden;
            width: 300px;
            height: 180px;
        }

        .video-frame iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .title {
                font-size: 28px;
            }

            .description {
                font-size: 16px;
            }

            .video-frame {
                width: 100%;
                height: auto;
                aspect-ratio: 16 / 9;
            }
        }

        /* Toggle Button */
        .toggle-icon-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 1100;
            transition: background-color 0.3s ease;
        }

        .toggle-icon-btn:hover {
            background-color: #0056b3;
        }

        /* Settings Box */
        .settings-toggle {
            position: fixed;
            bottom: 75px;
            right: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 15px 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            gap: 12px;
            z-index: 1000;
        }

        .toggle-group {
            display: flex;
            flex-direction: column;
        }

        .toggle-group label {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }

        .select-box:focus {
            border-color: #007bff;
            outline: none;
        }



    </style>

    <div class="welcome-container">
        <h1 class="title" id="welcomeTitle">Welcome to the Animal Recognition Activity 🐾</h1>
        <p class="description" id="welcomeDescription">
            This activity is designed for children with autism to help them recognize animals through interactive pictures and sounds.
        </p>

        <!-- Toggle Button -->
        <button id="settingsToggleBtn" class="toggle-icon-btn">
            <i class="fas fa-cog"></i>
        </button>

        <!-- Settings Box -->
        <div class="settings-toggle" id="settingsBox">

            <div class="toggle-group">
                <label for="languageSelector">🌐 Language</label>
                <select id="languageSelector" class="select-box">
                    <option value="en">English</option>
                    <option value="ms">Bahasa Melayu</option>
                </select>
            </div>
        </div>

        <div class="btn-container">
            <a href="{{ route('activity.start') }}" class="btn btn-start" id="startActivity">Start Activity 🎮</a>
            <button class="btn btn-video" id="watchVideoBtn" onclick="toggleVideo()">Watch Video ▶️</button>
        </div>

        <div class="video-section" id="videoSection">
            <div class="video-frame">
                <iframe src="https://www.youtube.com/embed/efiWeJbdbxk" allowfullscreen></iframe>
            </div>
            <div class="video-frame">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/gYn-dkLFQVU" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="video-frame">
                <iframe width="656" height="369" src="https://www.youtube.com/embed/viX1rRZfXts" title="Animals for KIDS! | Learn Animal Sounds | Kids Learning Videos" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <script>

        document.addEventListener("DOMContentLoaded", function () {
            const savedTheme = localStorage.getItem('theme') || 'default';
            const savedLanguage = localStorage.getItem('language') || 'en';

            document.getElementById('themeSelector').value = savedTheme;
            document.getElementById('languageSelector').value = savedLanguage;

            applyTheme(savedTheme);
            applyLanguage(savedLanguage);
        });



        document.getElementById('languageSelector').addEventListener('change', function () {
            const selectedLanguage = this.value;
            localStorage.setItem('language', selectedLanguage);
            applyLanguage(selectedLanguage);
        });

        function applyLanguage(language) {
            if (language === 'ms') {
                document.getElementById('welcomeTitle').innerText = "Selamat Datang ke Aktiviti Pengenalan Haiwan 🐾";
                document.getElementById('welcomeDescription').innerText = "Aktiviti ini direka khas untuk kanak-kanak autisme bagi membantu mereka mengenali haiwan melalui gambar interaktif dan bunyi.";
                document.getElementById('startActivity').innerText = "Mulakan Aktiviti 🎮";
                document.getElementById('watchVideoBtn').innerText = "Tonton Video ▶️";
            } else {
                document.getElementById('welcomeTitle').innerText = "Welcome to the Animal Recognition Activity 🐾";
                document.getElementById('welcomeDescription').innerText = "This activity is designed for children with autism to help them recognize animals through interactive pictures and sounds.";
                document.getElementById('startActivity').innerText = "Start Activity 🎮";
                document.getElementById('watchVideoBtn').innerText = "Watch Video ▶️";
            }
        }

        function toggleVideo() {
            const videoSection = document.getElementById('videoSection');
            videoSection.style.display = (videoSection.style.display === 'flex') ? 'none' : 'flex';
        }


            const toggleBtn = document.getElementById('settingsToggleBtn');
            const settingsBox = document.getElementById('settingsBox');

            toggleBtn.addEventListener('click', () => {
            settingsBox.style.display = (settingsBox.style.display === 'none' || settingsBox.style.display === '') ? 'flex' : 'none';
        });


    </script>
@endsection
