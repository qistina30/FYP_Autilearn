@extends('layouts.app')

@section('content')
    <style>
        body {
            font-family: 'Lexend Deca', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #D6EAF8;
            transition: background 0.3s ease-in-out;
        }

        .dark-theme {
            background-color: #2c3e50;
            color: white;
        }

        .light-theme {
            background-color: #f5f5f5;
            color: black;
        }

        .welcome-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            min-height: 100vh;
            text-align: center;
        }

        .title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .description {
            font-size: 20px;
            max-width: 700px;
            margin-bottom: 30px;
        }

        .settings-container {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .select-box {
            font-size: 18px;
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
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
    </style>

    <div class="welcome-container">
        <h1 class="title" id="welcomeTitle">Welcome to the Animal Recognition Activity 🐾</h1>
        <p class="description" id="welcomeDescription">
            This activity is designed for children with autism to help them recognize animals through interactive games and sounds.
        </p>

        <div class="settings-container">
            <select id="themeSelector" class="select-box">
                <option value="default">Choose Theme</option>
                <option value="dark">Dark</option>
                <option value="light">Light</option>
            </select>

            <select id="languageSelector" class="select-box">
                <option value="en">English</option>
                <option value="ms">Bahasa Melayu</option>
            </select>
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

        document.getElementById('themeSelector').addEventListener('change', function () {
            const selectedTheme = this.value;
            localStorage.setItem('theme', selectedTheme);
            applyTheme(selectedTheme);
        });

        function applyTheme(theme) {
            document.body.classList.remove('dark-theme', 'light-theme');
            if (theme === 'dark') {
                document.body.classList.add('dark-theme');
            } else if (theme === 'light') {
                document.body.classList.add('light-theme');
            }
        }

        document.getElementById('languageSelector').addEventListener('change', function () {
            const selectedLanguage = this.value;
            localStorage.setItem('language', selectedLanguage);
            applyLanguage(selectedLanguage);
        });

        function applyLanguage(language) {
            if (language === 'ms') {
                document.getElementById('welcomeTitle').innerText = "Selamat Datang ke Aktiviti Pengenalan Haiwan 🐾";
                document.getElementById('welcomeDescription').innerText = "Aktiviti ini direka khas untuk kanak-kanak autisme bagi membantu mereka mengenali haiwan melalui permainan interaktif dan bunyi.";
                document.getElementById('startActivity').innerText = "Mulakan Aktiviti 🎮";
                document.getElementById('watchVideoBtn').innerText = "Tonton Video ▶️";
            } else {
                document.getElementById('welcomeTitle').innerText = "Welcome to the Animal Recognition Activity 🐾";
                document.getElementById('welcomeDescription').innerText = "This activity is designed for children with autism to help them recognize animals through interactive games and sounds.";
                document.getElementById('startActivity').innerText = "Start Activity 🎮";
                document.getElementById('watchVideoBtn').innerText = "Watch Video ▶️";
            }
        }

        function toggleVideo() {
            const videoSection = document.getElementById('videoSection');
            videoSection.style.display = (videoSection.style.display === 'flex') ? 'none' : 'flex';
        }
    </script>
@endsection
