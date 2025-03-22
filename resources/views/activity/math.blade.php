@extends('layouts.app')

@section('content')
    <style>
        /* Page Background */
        body {
            text-align: center;
            font-family: 'Poppins', Arial, sans-serif;

        }

        .content-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Ensure full height */
            flex-direction: column;
        }

        /* Title */
        .title {
            font-size: 32px;
            color: #007BFF;
            margin-bottom: 15px;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 123, 255, 0.2);
        }

        /* Info Text */
        .info {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        /* Game Board */
        .game-board {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            padding: 15px;
        }

        /* Question */
        .question {
            font-size: 26px;
            font-weight: bold;
            color: #333;
            background: #ffeb99;
            padding: 10px;
            border-radius: 10px;
        }

        /* Answer Buttons */
        .answer-btn {
            width: 220px;
            padding: 14px;
            font-size: 24px;
            font-weight: bold;
            background: linear-gradient(to bottom, #ffcc00, #ff9900);
            border: 3px solid #ff8000;
            border-radius: 12px;
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s;
            color: white;
        }

        .answer-btn:hover {
            transform: scale(1.1);
            background: linear-gradient(to bottom, #ff9900, #ff6600);
        }

        /* Correct & Wrong Answers */
        .correct {
            background: linear-gradient(to bottom, #32cd32, #228b22) !important;
            border: 3px solid #2e8b57 !important;
            animation: correctShake 0.3s ease-in-out;
        }

        .wrong {
            background: linear-gradient(to bottom, #dc143c, #8b0000) !important;
            border: 3px solid #b22222 !important;
            animation: wrongShake 0.3s ease-in-out;
        }

        @keyframes correctShake {
            0% { transform: scale(1.1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1.1); }
        }

        @keyframes wrongShake {
            0%, 100% { transform: translateX(0); }
            25%, 75% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
        }

    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="content-wrapper">
        <div class="container">
            <h1 class="title">Math Activity</h1>

        <!-- Select Student Dropdown -->
        <div class="mb-3">
            <label for="studentSelect" class="info">👩‍🎓 Select Student:</label>
            <select id="studentSelect" class="form-control select2">
                <option value="">-- Select Student --</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                @endforeach
            </select>
        </div>

        <p class="info">⏳ Time: <span id="timer">0</span> seconds</p>
        <p class="info">⭐ Score: <span id="score">0</span></p>

        <!-- Hidden Input for Educator ID -->
        <input type="hidden" id="educator_id" value="{{ Auth::id() }}">

        <!-- Game Controls -->
        <div class="button-group">
            <button id="startBtn" class="btn btn-success btn-lg">Start 🎮</button>
            <button id="restartBtn" class="btn btn-warning btn-lg">Restart 🔄</button>
            <button id="submitBtn" class="btn btn-primary btn-lg" disabled>Submit ✅</button>
        </div>

        <!-- Game Board -->
        <div id="mathGameBoard" class="game-board"></div>
    </div>



        <script>
            $(document).ready(function () {
                let timer = 0, score = 0, isRunning = false;
                let interval, questionCount = 0;
                const totalRounds = 5;
                let correctAnswer;

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

                    generateMathQuestion();
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
                    let activityId = 3;

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

                function generateMathQuestion() {
                    if (questionCount >= totalRounds) {
                        stopGame();
                        $("#submitBtn").prop("disabled", false);
                        $("#mathGameBoard").html("<p class='question'>🎉 Well Done! Click Submit to save progress.</p>");
                        return;
                    }

                    let num1 = Math.floor(Math.random() * 10) + 1;
                    let num2 = Math.floor(Math.random() * 10) + 1;
                    correctAnswer = num1 + num2;
                    let currentQuestion = `${num1} + ${num2} = ?`;

                    let allAnswers = [correctAnswer, correctAnswer + 1, correctAnswer - 1, correctAnswer + 2]
                        .sort(() => Math.random() - 0.5);

                    $("#mathGameBoard").html(`
            <p class="question">Question ${questionCount + 1}: ${currentQuestion}</p>
            ${allAnswers.map(answer => `<button class="answer-btn" data-answer="${answer}">${answer}</button>`).join('')}
        `);

                    $(".answer-btn").off("click").on("click", function () {
                        let selectedAnswer = $(this).data("answer");
                        $(this).addClass(selectedAnswer === correctAnswer ? "correct" : "wrong");
                        score += selectedAnswer === correctAnswer ? 10 : 0;
                        $("#score").text(score);
                        questionCount++;
                        setTimeout(generateMathQuestion, 1000);
                    });
                }

                $("#startBtn").click(startGame);
                $("#restartBtn").click(() => {
                    stopGame();
                    startGame();
                });
                $("#submitBtn").click(submitGame);
            });

        </script>
@endsection
