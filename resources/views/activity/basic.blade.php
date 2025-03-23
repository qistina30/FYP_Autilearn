@extends('layouts.app')

@section('content')
{{--    <img id="animalImage" src="{{ asset('images/dog.jpg') }}" class="animal-image">--}}

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
            font-size: 32px;
            color: #007BFF;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .info {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .image-container {
            margin-top: 20px;
            padding: 15px;
        }

        .animal-image {
            max-width: 100%;
            height: 300px;
            border-radius: 10px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        }

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
            margin: 5px;
        }

        .answer-btn:hover {
            transform: scale(1.1);
            background: linear-gradient(to bottom, #ff9900, #ff6600);
        }

        .correct {
            background: linear-gradient(to bottom, #32cd32, #228b22) !important;
            border: 3px solid #2e8b57 !important;
        }

        .wrong {
            background: linear-gradient(to bottom, #dc143c, #8b0000) !important;
            border: 3px solid #b22222 !important;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="content-wrapper">
        <div class="container">
            <h1 class="title">🐾 Image Recognition Activity</h1>

            <div>
                <label for="studentSelect"><strong>Select Student:</strong></label>
                <select id="studentSelect" class="form-control select2">
                    <option value="">-- Select Student --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <p class="info-box">⏳ Time: <span id="timer">0</span> seconds</p>
            <p class="info-box">⭐ Score: <span id="score">0</span></p>

            <input type="hidden" id="educator_id" value="{{ Auth::id() }}">

            <div class="button-group">
                <button id="startBtn" class="btn btn-success btn-lg">Start 🎮</button>
                <button id="restartBtn" class="btn btn-warning btn-lg" disabled>Restart 🔄</button>
                <button id="submitBtn" class="btn btn-primary btn-lg" disabled>Submit ✅</button>
            </div>

            <div class="image-container">
                <img id="animalImage" src="" class="animal-image" alt="Animal Image" hidden>
            </div>

            <div id="answerOptions" class="game-board"></div>
        </div>

        <script>
            $(document).ready(function () {
                let timer = 0, score = 0, isRunning = false;
                let interval, questionCount = 0;
                const totalRounds = 5;
                let correctAnswer;

                let animalImages = [
                    { url: "{{ asset('images/dog.jpg') }}", name: "Dog" },
                    { url: "{{ asset('images/cat.jpg') }}", name: "Cat" },
                    { url: "{{ asset('images/lion.jpg') }}", name: "Lion" },
                    { url: "{{ asset('images/elephant.jpg') }}", name: "Elephant" }
                ];

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
                    let studentId = $("#studentSelect").val();
                    let timeTaken = $("#timer").text();
                    let educatorId = $("#educator_id").val();
                    let activityId = 4;

                    $.ajax({
                        url: "{{ route('activity.store-progress') }}",
                        method: "POST",
                        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        data: {
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

                let usedAnimals = [];

                function generateAnimalQuestion() {
                    if (questionCount >= totalRounds) {
                        stopGame();
                        $("#submitBtn").prop("disabled", false);
                        $("#answerOptions").html("<p class='info'>🎉 Well Done! Click Submit to save progress.</p>");
                        return;
                    }

                    // Get a new image that hasn't been used yet
                    let availableAnimals = animalImages.filter(a => !usedAnimals.includes(a.name));

                    if (availableAnimals.length === 0) {
                        // Reset used images if all have been shown
                        usedAnimals = [];
                        availableAnimals = [...animalImages];
                    }

                    let randomAnimal = availableAnimals[Math.floor(Math.random() * availableAnimals.length)];
                    correctAnswer = randomAnimal.name;
                    usedAnimals.push(correctAnswer); // Mark as used

                    $("#animalImage").attr("src", "").hide();

                    let img = new Image();
                    img.src = randomAnimal.url;

                    img.onload = function () {
                        $("#animalImage").attr("src", img.src).removeAttr("hidden").show();
                    };

                    img.onerror = function () {
                        alert("Image failed to load. Please try again.");
                    };

                    let allAnswers = [...animalImages.map(a => a.name)];
                    allAnswers = allAnswers.sort(() => Math.random() - 0.5);

                    $("#answerOptions").html(allAnswers.map(answer =>
                        `<button class="answer-btn" data-answer="${answer}">${answer}</button>`
                    ).join(''));

                    $(".answer-btn").off("click").on("click", function () {
                        let selectedAnswer = $(this).data("answer");
                        $(this).addClass(selectedAnswer === correctAnswer ? "correct" : "wrong");
                        score += selectedAnswer === correctAnswer ? 10 : 0;
                        $("#score").text(score);
                        questionCount++;
                        setTimeout(generateAnimalQuestion, 1000);
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
