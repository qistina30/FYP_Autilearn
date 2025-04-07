@extends('layouts.app')
@section('content')

    <style>
        body {
            text-align: center;
            font-family: 'Lexend Deca', Arial, sans-serif;
            /*overflow: hidden; !* Prevent scrolling *!*/
            transition: background-color 0.5s ease-in-out;
        }

        .game-container {
            display: flex;
            flex-wrap: wrap; /* Allow content to wrap on small screens */
            align-items: flex-start;
            justify-content: center;
            min-height: 100vh; /* Ensure it takes the full height but not more */
            gap: 20px;
            padding: 20px;
        }

        /* Sidebar styles */
        .sidebar {
            width: 300px;
            min-height: 100vh; /* Adjust to full height */
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
        }

        .sidebar button {
            width: 100%;
            margin-top: 10px;
            font-size: 18px;
            padding: 12px;
        }

        /* Game content */
        .game-content {
            flex-grow: 1; /* Take remaining space */
            max-width: 700px; /* Prevent it from stretching too much */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        }

        /* Image container */
        .image-container {
            width: 100%;
            max-width: 400px;
            height: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #FFFFFF;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        }

        .image-container img {
            width: 100%; /* Ensure it fills the container */
            max-width: 350px; /* Prevents it from becoming too large */
            max-height: 250px; /* Keeps the image proportionate */
            height: auto; /* Maintain aspect ratio */
            object-fit: contain; /* Ensure the whole image is visible */
        }

        .answer-btn {
            width: 250px;
            padding: 16px;
            font-size: 22px;
            font-weight: bold;
            background: #FFD700;
            border: 3px solid #FFA500;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            color: black;
            margin: 10px;
        }

        .answer-btn:hover {
            transform: scale(1.1);
            background-color: #FFA500;
        }

        .correct {
            background: #32CD32 !important;
            border: 3px solid #228B22 !important;
        }

        .wrong {
            background: #DC143C !important;
            border: 3px solid #8B0000 !important;
        }

        /* Sound button */
        .audio-controls {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .sound-btn {
            background: #3498db;
            border: none;
            color: white;
            padding: 15px 20px;
            font-size: 20px;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .sound-btn:hover {
            background: #2e86c1;
            transform: scale(1.1);
        }


    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="game-container">
        <div class="sidebar">
            <h2 class="title">🐾 Image Recognition</h2>

            <p><strong>⏳ Time:</strong> <span id="timer">0</span> sec</p>
            <p><strong>⭐ Score:</strong> <span id="score">0</span></p>

            <button id="startBtn" class="btn btn-success">Start 🎮</button>
            <button id="restartBtn" class="btn btn-warning" disabled>Restart 🔄</button>
            <button id="submitBtn" class="btn btn-primary" disabled>Submit ✅</button>

            <label id="volumeLabel">🔊 Volume: 100%</label>

            <input type="range" id="volumeControl" min="0" max="1" step="0.1" value="1">

            <label for="themeSelector">🎨 Background:</label>
            <select id="themeSelector">
                <option value="#D6EAF8">Light Blue</option>
                <option value="#FDEBD0">Soft Peach</option>
                <option value="#D5F5E3">Pale Green</option>
                <option value="#FADBD8">Pastel Pink</option>
                <option value="#FFFACD">Light Yellow</option>
            </select>
            <label for="languageSelector">🌎 Language:</label>
            <select id="languageSelector">
                <option value="en">English</option>
                <option value="ms">Bahasa Melayu</option>
            </select>

        </div>
        <!-- Instruction Modal -->
        <div id="instructionModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
            <div style="background:white; padding:25px; border-radius:10px; width:80%; max-width:500px; text-align:left;">
                <h3 style="color:#333;">📝 How to Play</h3>
                <ul style="color:#555; font-size:18px;">
                    <li>Click <strong>Start Game</strong>.</li>
                    <li>See the animal picture.</li>
                    <li>Press <strong>Play Sound</strong> to hear the animal.</li>
                    <li>Click the correct name.</li>
                    <li>Earn points for correct answers!</li>
                </ul>
                <p style="color:green;"><strong>Have fun! 🎉</strong></p>
                <button onclick="closeInstructions()" style="padding:10px 20px; background-color:#28a745; color:white; border:none; border-radius:8px;">Got it!</button>
            </div>
        </div>

        <script>
            function showInstructions() {
                document.getElementById('instructionModal').style.display = 'flex';
            }

            function closeInstructions() {
                document.getElementById('instructionModal').style.display = 'none';
            }

            // Automatically show instructions when page loads
            window.onload = showInstructions;
        </script>

        <div class="game-content">
            <div class="image-container">
                <img id="animalImage" src="" class="animal-image" alt="Animal Image" hidden>
            </div>

            <!-- Play Sound and Voice Command Buttons Moved Here -->
            <div class="audio-controls">
                <button id="playSoundBtn" class="sound-btn">🔊 Play Sound</button>
            </div>

            <div id="answerOptions"></div>
        </div>

        <div id="answerOptions"></div>
        </div>

@endsection
@section('scripts')
    <script>
        let animalData = [
            { url: "{{ asset('images/dog.jpg') }}", name: "Dog", sound: "{{ asset('sounds/dog.mp3') }}" },
            { url: "{{ asset('images/cat.jpg') }}", name: "Cat", sound: "{{ asset('sounds/cat.mp3') }}" },
            { url: "{{ asset('images/lion.jpg') }}", name: "Lion", sound: "{{ asset('sounds/lion.wav') }}" },
            { url: "{{ asset('images/elephant.jpg') }}", name: "Elephant", sound: "{{ asset('sounds/elephant.flac') }}" }
        ];
    </script>
    <script src="{{ asset('js/game.js') }}"></script>
@endsection
