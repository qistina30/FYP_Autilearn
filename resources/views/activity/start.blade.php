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
            min-height: 85vh;
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
            min-height: 85vh;
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

        .settings-toggle {
            position: fixed;
            bottom: 75px;
            right: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            gap: 15px;
            z-index: 1000;
            width: 250px;
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

        .select-box {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9f9f9;
            transition: border-color 0.3s;
        }

        .select-box:focus {
            border-color: #007bff;
            outline: none;
        }

        .audio-controls {
            display: flex;
            flex-direction: row;
            gap: 15px; /* Space between buttons */
            margin-top: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .audio-controls .sound-btn {
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
        .game-guide {
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }

    </style>
    <!-- 🎯 How to Play Guide -->
    <div class="game-guide card p-3 mb-3 shadow-sm" style="background-color: #e8f4ff; border-left: 6px solid #007bff;">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-2 fw-bold text-primary">
                🎯 How to Play
            </h5>
            <button class="btn btn-sm btn-outline-primary" onclick="toggleGuide()">
                <i class="bi bi-eye-fill me-1"></i> Toggle Guide
            </button>
        </div>

        <ol id="guideSteps" class="mt-3" style="padding-left: 20px; display: none; transition: all 0.3s ease;">
            <li><strong>Select a student</strong> from the dropdown menu 🎓</li>
            <li>Click <strong>“Start”</strong> to begin the game 🎮</li>
            <li>Click <strong>“Play Animal Sound”</strong> to hear the sound 🔊</li>
            <li><strong>Look at the images</strong> and select the correct animal 🐾</li>
            <li>Or <strong>say the animal name</strong> using <strong>voice command</strong> 🎙️</li>
            <li>Click <strong>“Submit”</strong> to finalize your answer ✅</li>
            <li>Click <strong>“Restart”</strong> to try again 🔄</li>
            <li>Track your <strong>time and score</strong> on the left panel ⏱️⭐</li>
            <li>Use <strong>⚙️</strong> to adjust background, volume, or language</li>
        </ol>
    </div>


    <div class="game-container">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Hidden Input for Educator ID -->
        <input type="hidden" id="educator_id" value="{{ Auth::id() }}">
        <div class="sidebar">
            <div style="width: 100%; margin-bottom: 20px;">
                <label for="studentSelect"><strong>🎓 Select Student</strong></label>
                <select id="studentSelect" class="form-control select2" style="width: 100%;">
                    <option value="">-- Select Student --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <h2 class="title"> Animal Image Recognition </h2>

            <!-- Game Info -->
            <div style="margin-bottom: 7px;">
                <p><strong>⏳ Time:</strong> <span id="timer">0</span> sec</p>
                <p>
                <strong>⭐ Score:</strong>
                <span id="score">0</span>
                <i class="fas fa-info-circle text-info ms-2"
                   data-bs-toggle="tooltip"
                   data-bs-placement="top"
                   title="Correct answers give +10 points, while incorrect answers deduct -5 points.">
                </i>
            </p>

            </div>

            <!-- Game Controls -->
            <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                <button id="startBtn" class="sound-btn start-btn">🎮 Start</button>
                <button id="restartBtn" class="sound-btn restart-btn" disabled>🔄 Restart</button>
                <button id="submitBtn" class="sound-btn submit-btn" disabled>✅ Submit</button>
            </div>

        </div>

        <div class="game-content">
            <!-- Audio Section -->
            <div id="audioSection" style="display: none; padding-top: 10px; padding-bottom: 20px;">
                <div class="audio-controls">
                    <button id="playSoundBtn" class="sound-btn">Play Animal Sound</button>
                    <!-- voiceControlBtn is inserted dynamically here -->
                </div>
            </div>


            <div class="image-container">
                <img id="animalImage" src="" class="animal-image" alt="Animal Image" hidden>
            </div>

            <div id="answerOptions"></div>
        </div>
    </div>

    <!-- Settings Toggle Button -->
    <button id="settingsToggleBtn" class="toggle-icon-btn">
        <i class="fas fa-cog"></i>
    </button>

    <!-- Redesigned Settings Panel -->
    <div class="settings-toggle" id="settingsBox">
        <div class="toggle-group">
            <label for="volumeControl">🔊 Volume</label>
            <input type="range" id="volumeControl" min="0" max="1" step="0.01">
        </div>

        <div class="toggle-group">
            <label for="themeSelector">🎨 Background</label>
            <select id="themeSelector" class="select-box">
                <option value="#D6EAF8">Light Blue</option>
                <option value="#FDEBD0">Soft Peach</option>
                <option value="#D5F5E3">Pale Green</option>
                <option value="#FADBD8">Pastel Pink</option>
                <option value="#FFFACD">Light Yellow</option>
            </select>
        </div>

        <div class="toggle-group">
            <label for="languageSelector">🌎 Language</label>
            <select id="languageSelector" class="select-box">
                <option value="en">English</option>
                <option value="ms">Bahasa Melayu</option>
            </select>
        </div>
    </div>




@endsection

@section('scripts')

    {{-- Step 1: Declare animalData --}}
    <script>
        function toggleGuide() {
            const guide = document.getElementById('guideSteps');
            if (guide.style.display === "none" || guide.style.display === "") {
                guide.style.display = "block";
            } else {
                guide.style.display = "none";
            }
        }

        // Hide guide by default on page load
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('guideSteps').style.display = "none";
        });


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
        $(document).ready(function() {
            $('#studentSelect').select2({
                placeholder: "-- Select Student --"
            });
        });
    </script>

    <script>
        const storeProgressUrl = "{{ route('activity.store-progress') }}";
    </script>
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


    <script>
        const toggleBtn = document.getElementById('settingsToggleBtn');
        const settingsBox = document.getElementById('settingsBox');

        toggleBtn.addEventListener('click', () => {
            settingsBox.style.display = (settingsBox.style.display === 'none' || settingsBox.style.display === '') ? 'flex' : 'none';
        });
    </script>

    <!-- Enable Bootstrap Tooltips -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

@endsection
