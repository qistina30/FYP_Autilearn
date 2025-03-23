@extends('layouts.app')

@section('content')
    <style>
        body {
            text-align: center;
            font-family: 'Poppins', Arial, sans-serif;

        }

        .content-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        .title {
            font-size: 40px;
            color: #007BFF;
            margin-bottom: 20px;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 123, 255, 0.3);
        }

        .info-box {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 15px;
            font-size: 20px;
            font-weight: bold;
        }

        .info {
            padding: 12px 20px;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .game-board {
            margin-top: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            width: 400px;
        }

        .spell-input {
            width: 80%;
            padding: 12px;
            font-size: 20px;
            border: 2px solid #007BFF;
            border-radius: 8px;
            text-align: center;
            outline: none;
        }

        .spell-input.correct {
            border-color: green;
            background: #d4edda;
        }

        .spell-input.wrong {
            border-color: red;
            background: #f8d7da;
        }

        .btn {
            width: 180px;
            padding: 14px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn-yellow {
            background: #ffcc00;
            border: 3px solid #ff9900;
            color: white;
        }

        .btn-yellow:hover {
            background: #ff9900;
        }

        .btn-green {
            background: #28a745;
            border: 3px solid #218838;
            color: white;
        }

        .btn-green:hover {
            background: #218838;
        }

        .btn-red {
            background: #dc3545;
            border: 3px solid #c82333;
            color: white;
        }

        .btn-red:hover {
            background: #c82333;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="content-wrapper">
        <div class="container">
            <h1 class="title">🎤 Spelling Bee Game 📝</h1>

            <div>
                <label for="studentSelect"><strong>Select Student:</strong></label>
                <select id="studentSelect" class="form-control select2">
                    <option value="">-- Select Student --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="info-box">
                <p class="info">⏳ Time: <span id="timer">0</span> seconds</p>
                <p class="info">⭐ Score: <span id="score">0</span></p>
            </div>

            <input type="hidden" id="educator_id" value="{{ Auth::id() }}">

            <!-- Game Controls -->
            <div class="button-group mt-3">
                <button id="startBtn" class="btn btn-success btn-lg">Start 🎮</button>
                <button id="restartBtn" class="btn btn-warning btn-lg">Restart 🔄</button>
                <button id="submitBtn" class="btn btn-primary btn-lg" disabled>Submit ✅</button>
            </div>

            <div id="spellingGameBoard" class="game-board"></div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let timer = 0, score = 0, isRunning = false;
            let interval, questionCount = 0;
            const totalRounds = 5;
            let currentWord;
            let words = ["elephant", "banana", "giraffe", "umbrella", "chocolate"];

            function startGame() {
                let studentId = $("#studentSelect").val();
                if (!studentId) {
                    alert("Please select a student before starting the game.");
                    return;
                }

                isRunning = true;
                timer = 0;
                score = 0;
                questionCount = 0;
                $("#score").text(score);
                $("#timer").text(timer);

                interval = setInterval(() => {
                    timer++;
                    $("#timer").text(timer);
                }, 1000);

                $("#startBtn").prop("disabled", true);
                $("#submitBtn").prop("disabled", true);

                nextWord();
            }

            function stopGame() {
                isRunning = false;
                clearInterval(interval);
                $("#startBtn").prop("disabled", false);
            }

            function submitGame() {
                let studentId = $("#studentSelect").val();
                let timeTaken = $("#timer").text();
                let educatorId = $("#educator_id").val();
                let activityId = 5;

                $.ajax({
                    url: "{{ route('activity.store-progress') }}",
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    data: {
                        _token: "{{ csrf_token() }}",
                        student_id: studentId,
                        educator_id: educatorId,
                        activity_id: activityId,
                        score: score,
                        time_taken: timeTaken,
                    },
                    success: function(response) {
                        alert("Game progress saved! Final Score: " + score);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert("Error updating progress.");
                    }
                });

                $("#submitBtn").prop("disabled", true);
            }

            function nextWord() {
                if (questionCount >= totalRounds) {
                    stopGame();
                    $("#submitBtn").prop("disabled", false);
                    $("#spellingGameBoard").html("<p class='question'>🎉 Well Done! Click Submit to save progress.</p>");
                    return;
                }

                currentWord = words[Math.floor(Math.random() * words.length)];
                let msg = new SpeechSynthesisUtterance(currentWord);
                speechSynthesis.speak(msg);

                $("#spellingGameBoard").html(`
        <p class="question">🔊 Listen to the word & spell it:</p>
        <button id="listenAgain" class="btn btn-blue">Listen Again 🔊</button>
        <input type="text" id="spellInput" class="spell-input">
        <button id="checkAnswer" class="btn btn-yellow">Check ✅</button>
    `);

                $("#listenAgain").click(() => {
                    speechSynthesis.speak(msg);
                });

                $("#checkAnswer").click(() => {
                    let userAnswer = $("#spellInput").val().trim().toLowerCase();
                    if (userAnswer === currentWord) {
                        $("#spellInput").addClass("correct");
                        score += 10;
                    } else {
                        $("#spellInput").addClass("wrong");
                    }
                    $("#score").text(score);
                    questionCount++;
                    setTimeout(nextWord, 1000);
                });
            }


            $("#startBtn").click(startGame);
            $("#restartBtn").click(() => { stopGame(); startGame(); });
            $("#submitBtn").click(submitGame);
        });
    </script>
@endsection
