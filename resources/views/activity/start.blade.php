@extends('layouts.app')
@section('content')

    <style>
        body {
            font-family: 'Lexend Deca', Arial, sans-serif;
            transition: background-color 0.5s ease-in-out;
        }

        .game-container {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: center;
            min-height: 100vh;
            gap: 20px;
            padding: 20px;
        }

        .sidebar {
            text-align: center;
            width: 300px;
            min-height: 80vh;
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

        .game-content {
            flex-grow: 1;
            min-height: 90vh;
            max-width: 700px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        }

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
            width: 100%;
            max-width: 350px;
            max-height: 250px;
            height: auto;
            object-fit: contain;
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

        .audio-controls {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .sound-btn {
            width: 250px;
            background: #3498db;
            border: none;
            color: black;
            padding: 10px 16px;
            font-size: 16px;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .answer-btn {
            width: 250px; /* Ensure both have the same width */
            padding: 16px; /* Ensure consistent padding */
            font-size: 22px; /* Ensure consistent font size */
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            color: black;
            margin: 10px;
        }
        .sound-btn:hover {
            background: #2e86c1;
            transform: scale(1.1);
        }

        /* Specific Button Colors */
        .start-btn {
            background-color: #28a745; /* Green for Start */
        }

        .start-btn:hover {
            background-color: #218838; /* Darker Green for hover */
        }

        .restart-btn {
            background-color: #ffc107; /* Yellow for Restart */
        }

        .restart-btn:hover {
            background-color: #e0a800; /* Darker Yellow for hover */
        }

        .submit-btn {
            background-color: #007bff; /* Blue for Submit */
        }

        .submit-btn:hover {
            background-color: #0069d9; /* Darker Blue for hover */
        }

        /* Settings Button Styles */
        .settings-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #3498db;
            border: none;
            color: white;
            padding: 15px 25px;
            font-size: 18px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .settings-btn:hover {
            background: #2980b9;
        }

        .settings-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .settings-modal .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .settings-modal .modal-content select,
        .settings-modal .modal-content input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
        }

    </style>

    <div class="game-container">
        <div class="sidebar">
            <h2 class="title">🐾 Animal Image Recognition 🐾</h2>

            <!-- Game Info -->
            <div style="margin-bottom: 7px;">
                <p><strong>⏳ Time:</strong> <span id="timer">0</span> sec</p>
                <p><strong>⭐ Score:</strong> <span id="score">0</span></p>
            </div>

            <!-- Game Controls -->
            <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                <button id="startBtn" class="sound-btn start-btn">🎮 Start</button>
                <button id="restartBtn" class="sound-btn restart-btn" disabled>🔄 Restart</button>
                <button id="submitBtn" class="sound-btn submit-btn" disabled>✅ Submit</button>
            </div>
            <!-- Audio Section -->
            <div style="border-top: 1px solid #ccc; padding-top: 20px; width: 100%;">
                <h5 style="margin-bottom: 10px;">🔊 Audio</h5>
                <button id="playSoundBtn" class="sound-btn" style="width: 100%;">Play Animal Sound</button>
            </div>
        </div>

        <div class="game-content">
            <div class="image-container">
                <img id="animalImage" src="" class="animal-image" alt="Animal Image" hidden>
            </div>

            <div id="answerOptions"></div>
        </div>
    </div>

    <!-- Settings Button -->
    <button class="settings-btn" onclick="toggleSettings()">⚙️</button>

    <!-- Settings Modal -->
    <div id="settingsModal" class="settings-modal">
        <div class="modal-content">
            <h3>Settings</h3>
            <label id="volumeLabel" for="volumeControl">🔊 Volume:</label>
            <input type="range" id="volumeControl" min="0" max="1" step="0.01">

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
            <button onclick="closeSettings()" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 8px;">Close</button>
        </div>
    </div>


@endsection

@section('scripts')
    {{-- Step 1: Declare animalData --}}
    <script>
        const animalData = [
            {
                name: "dog",
                url: 'https://media2.giphy.com/media/v1.Y2lkPTc5MGI3NjExeTZmNW45YnRuams4eThub2p5dG01dnJ6a3RvdDF6bXIydXZ1amR6NSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/ekeUFvDhX4kXH7apSy/giphy.gif',
                sound: "{{ asset('sounds/dog.mp3') }}"
            },
            {
                name: "cat",
                url: 'https://media0.giphy.com/media/v1.Y2lkPTc5MGI3NjExdWIyYnBxaXFpZWE4bmp3cnZhZDNkYm56bnA0cnNzMzQwbmg1MWR5NyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/8Ag4AORS8xPYHdeU6f/giphy.gif',
                sound: "{{ asset('sounds/cat.mp3') }}"
            },
            {
                name: "lion",
                url: 'https://media1.giphy.com/media/v1.Y2lkPTc5MGI3NjExZmV2Nmp5N2dzMWcxZnF4cGh6bjg5bmdmMWp6OTg5Nnd6ZXJ5aWVwNiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/l46C8pSaCzoiZVXAk/giphy.gif',
                sound: "{{ asset('sounds/lion.wav') }}"
            },
            {
                name: "elephant",
                url: 'https://media3.giphy.com/media/v1.Y2lkPTc5MGI3NjExeDJjN3M5dWR1cXloNTZ6ODlpcHp2eTBsaGludm1nbjhqZjQ2M2huaSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/H3Fv074RPfgK4/giphy.gif',
                sound: "{{ asset('sounds/elephant.flac') }}"
            }
        ];
        console.log("animalData from Blade:", animalData);
    </script>
            <script>
                var correctSound = "{{ asset('sounds/correct.mp3') }}";
                var wrongSound = "{{ asset('sounds/wrong.mp3') }}";
            </script>

    {{-- Step 2: Now load the game.js file --}}
    <script src="{{ asset('js/game.js') }}"></script>

    <script>
        var audioVolume = 1; // Declare globally

        $(document).ready(function () {
            $("#volumeControl").val(audioVolume);
            $("#volumeLabel").text(`🔊 Volume: ${Math.round(audioVolume * 100)}%`);

            $("#volumeControl").on("input", function () {
                audioVolume = parseFloat($(this).val());
                $("#volumeLabel").text(`🔊 Volume: ${Math.round(audioVolume * 100)}%`);
            });
        });
    </script>


    <script>// Toggle settings modal visibility
        function toggleSettings() {
            const modal = document.getElementById('settingsModal');
            modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
        }

        // Close settings modal
        function closeSettings() {
            document.getElementById('settingsModal').style.display = 'none';
        }</script>

@endsection
