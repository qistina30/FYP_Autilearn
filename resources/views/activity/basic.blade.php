@extends('layouts.app')


@section('content')

    <style>
        body {
            text-align: center;
            font-family: 'Lexend Deca', Arial, sans-serif;
            overflow: hidden; /* Prevent scrolling */
            transition: background-color 0.5s ease-in-out;
        }

        .game-container {
            display: flex;
            align-items: center;
            height: calc(120vh - 80px);
            gap: 20px;
            padding: 20px;
        }

        .sidebar {

            width: 300px;
            height: 110vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar button {
            width: 100%;
            margin-top: 10px;
            font-size: 18px;
            padding: 12px;
        }

        .game-content {
            margin-left: 320px;
            width: 80%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            height: 90%;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        }

        .image-container {
            width: 400px;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #FFFFFF;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        }

        .image-container img {
            max-width: 100%;
            height: 300px;  /* Set a fixed height */
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
                <option value="ms">Malay (Bahasa Melayu)</option>
            </select>

        </div>

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

    <script>
        // Change background color based on selection
        $("#themeSelector").on("change", function () {
            let selectedColor = $(this).val();
            $("body").css("background-color", selectedColor);
        });


        $(document).ready(function () {
            let timer = 0, score = 0, isRunning = false;
            let interval, questionCount = 0;
            const totalRounds = 5;
            let correctAnswer, currentSound;
            let availableAnimals = [];

            let animalData = [
                { url: "{{ asset('images/dog.jpg') }}", name: "Dog", sound: "{{ asset('sounds/dog.wav') }}" },
                { url: "{{ asset('images/cat.jpg') }}", name: "Cat", sound: "{{ asset('sounds/cat.wav') }}" },
                { url: "{{ asset('images/lion.jpg') }}", name: "Lion", sound: "{{ asset('sounds/lion.wav') }}" },
                { url: "{{ asset('images/elephant.jpg') }}", name: "Elephant", sound: "{{ asset('sounds/elephant.flac') }}" }
            ];

            let audioVolume = 1; // Default volume

// Update volume when the slider changes
            $("#volumeControl").on("input", function () {
                let volumeLevel = $(this).val();
                $("#volumeLabel").text(`🔊 Volume: ${Math.round(volumeLevel * 100)}%`);
                audioVolume = volumeLevel;
            });


            function playSound(soundUrl) {
                if (soundUrl) {
                    let audio = new Audio(soundUrl);
                    audio.volume = audioVolume;
                    audio.play();
                }
            }

            window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            const recognition = new SpeechRecognition();
            recognition.lang = 'en-US';
            recognition.continuous = false;
            recognition.interimResults = false;

            function startListening() {
                recognition.start();
            }

            recognition.onresult = function (event) {
                let speechResult = event.results[0][0].transcript.toLowerCase().trim();
                let confidence = event.results[0][0].confidence;

                console.log("Recognized:", speechResult, "| Confidence:", confidence);

                if (confidence > 0.75) {  // Adjust threshold if needed
                    if (speechResult.includes("start")) $("#startBtn").click();
                    else if (speechResult.includes("restart")) $("#restartBtn").click();
                    else if (speechResult.includes("submit")) $("#submitBtn").click();
                    else {
                        $(".answer-btn").each(function () {
                            if ($(this).text().toLowerCase() === speechResult) {
                                $(this).click();
                            }
                        });
                    }
                }
            };


            recognition.onerror = function (event) {
                console.error("Speech recognition error:", event.error);
            };

// Add a voice control button
            $("<button id='voiceControlBtn' class='sound-btn'>🎙️ Voice Command</button>")
                .insertAfter("#playSoundBtn")
                .on("click", startListening);

            function startGame() {
                isRunning = true;
                timer = 0;
                score = 0;
                questionCount = 0;
                availableAnimals = [...animalData];

                $("#score").text(score);
                $("#timer").text(timer);
                $("#restartBtn").prop("disabled", false);
                $("#submitBtn").prop("disabled", true);
                $("#startBtn").prop("disabled", true);

                interval = setInterval(() => {
                    timer++;
                    $("#timer").text(timer);
                }, 1000);

                generateAnimalQuestion();
            }

            function stopGame() {
                isRunning = false;
                clearInterval(interval);
                $("#startBtn").prop("disabled", false);
            }

            function submitGame() {
                alert("Game progress saved! Final Score: " + score);
            }

            const translations = {
                en: {
                    title: "🐾 Image Recognition",
                    time: "⏳ Time:",
                    score: "⭐ Score:",
                    start: "Start 🎮",
                    restart: "Restart 🔄",
                    submit: "Submit ✅",
                    playSound: "🔊 Play Sound",
                    voiceCommand: "🎙️ Voice Command",
                    volume: "🔊 Volume:",
                    background: "🎨 Background:",
                    wellDone: "🎉 Well done! Click Submit to save your progress.",
                },
                ms: {
                    title: "🐾 Pengecaman Imej",
                    time: "⏳ Masa:",
                    score: "⭐ Markah:",
                    start: "Mula 🎮",
                    restart: "Mulakan Semula 🔄",
                    submit: "Hantar ✅",
                    playSound: "🔊 Mainkan Bunyi",
                    voiceCommand: "🎙️ Perintah Suara",
                    volume: "🔊 Kelantangan:",
                    background: "🎨 Latar Belakang:",
                    wellDone: "🎉 Tahniah! Klik Hantar untuk menyimpan kemajuan anda.",
                }
            };


            function generateAnimalQuestion() {
                if (questionCount >= totalRounds || availableAnimals.length === 0) {
                    stopGame();
                    $("#submitBtn").prop("disabled", false);
                    $("#answerOptions").html(`<p class='info-box'>${translations[$("#languageSelector").val()].wellDone}</p>`);

                    return;
                }

                let randomIndex = Math.floor(Math.random() * availableAnimals.length);
                let selectedAnimal = availableAnimals.splice(randomIndex, 1)[0];
                correctAnswer = selectedAnimal.name;
                currentSound = selectedAnimal.sound;

                $("#animalImage").attr("src", selectedAnimal.url).removeAttr("hidden").show();
                $("#playSoundBtn").show().off("click").on("click", function () {
                    playSound(currentSound);
                });

                // Select the correct answer and one random wrong answer
                let incorrectAnswers = animalData.filter(a => a.name !== correctAnswer);
                let randomWrongAnswer = incorrectAnswers[Math.floor(Math.random() * incorrectAnswers.length)].name;

                let allAnswers = [correctAnswer, randomWrongAnswer].sort(() => Math.random() - 0.5);

                $("#answerOptions").html(allAnswers.map(answer =>
                    `<button class="answer-btn" data-answer="${answer}">${answer}</button>`
                ).join(''));

                $(".answer-btn").off("click").on("click", function () {
                    let selectedAnswer = $(this).data("answer");
                    if (selectedAnswer === correctAnswer) {
                        $(this).addClass("correct");
                        score += 10;
                        playSound("{{ asset('sounds/correct.mp3') }}");
                    } else {
                        $(this).addClass("wrong");
                        playSound("{{ asset('sounds/wrong.mp3') }}");
                    }
                    $("#score").text(score);
                    questionCount++;
                    setTimeout(generateAnimalQuestion, 1000);
                });

                function updateLanguage() {
                    let selectedLang = $("#languageSelector").val();
                    let langData = translations[selectedLang];

                    $(".title").text(langData.title);
                    $("#timer").prev().text(langData.time);
                    $("#score").prev().text(langData.score);
                    $("#startBtn").text(langData.start);
                    $("#restartBtn").text(langData.restart);
                    $("#submitBtn").text(langData.submit);
                    $("#playSoundBtn").text(langData.playSound);
                    $("#voiceControlBtn").text(langData.voiceCommand);
                    $("#volumeLabel").text(`${langData.volume} ${Math.round($("#volumeControl").val() * 100)}%`);
                    $("label[for='themeSelector']").text(langData.background);
                }

// Listen for language change and update the UI
                $("#languageSelector").on("change", updateLanguage);

// Initialize language on page load
                updateLanguage();

            }


            $("#startBtn").click(startGame);
            $("#restartBtn").click(() => { stopGame(); startGame(); });
            $("#submitBtn").click(submitGame);
        });
    </script>

@endsection
