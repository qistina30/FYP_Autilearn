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
            min-height: 100vh;
            flex-direction: column;
        }

        /* Title */
        .title {
            font-size: 40px;
            color: #007BFF;
            margin-bottom: 20px;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 123, 255, 0.3);
        }

        /* Score & Timer */
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

        /* Game Board */
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

        /* Question */
        .question {
            font-size: 26px;
            font-weight: bold;
            color: #333;
            background: #ffeb99;
            padding: 12px;
            border-radius: 12px;
        }

        /* Answer Buttons */
        .answer-btn {
            width: 230px;
            padding: 14px;
            font-size: 24px;
            font-weight: bold;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .answer-btn:hover {
            transform: scale(1.05);
        }

        /* Button Colors */
        .btn-yellow { background: #ffcc00; border: 3px solid #ff9900; }
        .btn-yellow:hover { background: #ff9900; }

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
            25%, 75% { transform: translateX(-6px); }
            50% { transform: translateX(6px); }
        }

    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="content-wrapper">
        <div class="container">
            <h1 class="title">🎯 Math Activity 🎯</h1>

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

            <!-- Hidden Input for Educator ID -->
            <input type="hidden" id="educator_id" value="{{ Auth::id() }}">

            <!-- Game Controls -->
            <div class="button-group mt-3">
                <button id="startBtn" class="btn btn-success btn-lg">Start 🎮</button>
                <button id="restartBtn" class="btn btn-warning btn-lg">Restart 🔄</button>
                <button id="submitBtn" class="btn btn-primary btn-lg" disabled>Submit ✅</button>
            </div>

            <!-- Game Board -->
            <div id="mathGameBoard" class="game-board"></div>
        </div>
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
                let allAnswers = [correctAnswer, correctAnswer + 1, correctAnswer - 1, correctAnswer + 2]
                    .sort(() => Math.random() - 0.5);

                $("#mathGameBoard").html(`
                    <p class="question">${num1} + ${num2} = ?</p>
                    ${allAnswers.map(answer => `<button class="answer-btn btn-yellow" data-answer="${answer}">${answer}</button>`).join('')}
                `);

                $(".answer-btn").click(function () {
                    let selectedAnswer = $(this).data("answer");
                    $(this).addClass(selectedAnswer === correctAnswer ? "correct" : "wrong");
                    score += selectedAnswer === correctAnswer ? 10 : 0;
                    $("#score").text(score);
                    questionCount++;
                    setTimeout(generateMathQuestion, 1000);
                });
            }

            $("#startBtn").click(startGame);
            $("#restartBtn").click(() => { stopGame(); startGame(); });
            $("#submitBtn").click(submitGame);
        });
    </script>
@endsection
