@extends('layouts.app')

@section('content')

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Letter Catch Game</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <style>
        body {
            text-align: center;
            font-family: 'Comic Sans MS', sans-serif; /* Friendly font */
            background-color: #E3F2FD; /* Soothing light blue */
        }

        h1 {
            color: #1565C0; /* Deep blue */
            font-size: 28px;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Game Container */
        #gameContainer {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Game Area */
        #gameArea {
            position: relative;
            width: 400px;
            height: 500px;
            border: 4px solid #1565C0; /* Blue border */
            margin: 20px auto;
            background-color: #FFF3E0; /* Soft orange */
            border-radius: 8px;
            overflow: hidden;
        }

        .letter {
            position: absolute;
            font-size: 50px;
            font-weight: bold;
            color: red;
            transition: transform 0.3s ease; /* Smooth scaling effect */
        }

        /* Score & Timer */
        .info-box {
            font-size: 20px;
            font-weight: bold;
            padding: 10px;
            background: #FFD54F; /* Warm yellow */
            border-radius: 6px;
            margin: 10px;
            display: inline-block;
            width: 180px;
        }

        /* Buttons */
        .btn-custom {
            font-size: 20px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            width: 180px;
        }

        .btn-success { background-color: #66BB6A; } /* Soft green */
        .btn-primary { background-color: #42A5F5; } /* Bright blue */

        /* Select Student */
        .select2-container .select2-selection {
            font-size: 18px;
            padding: 8px;
            border-radius: 8px;
        }

        /* Keypress Feedback */
        .key-pressed {
            transform: scale(1.2);
        }
    </style>
</head>
<body>

<div id="gameContainer">
    <h1>🎮 Letter Catch Game</h1>

    <!-- Select Student Dropdown -->
    <div>
        <label for="studentSelect"><strong>Select Student:</strong></label>
        <select id="studentSelect" class="form-control select2">
            <option value="">-- Select Student --</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}">{{ $student->full_name }}</option>
            @endforeach
        </select>
    </div>
    <input type="hidden" id="educator_id" value="{{ Auth::id() }}">
    <input type="hidden" id="activity_id" value="3">

    <!-- Score & Timer -->
    <div>
        <span class="info-box">⭐ Score: <span id="score">0</span></span>
        <span class="info-box">⏳ Time Left: <span id="timeLeft">30</span> sec</span>
    </div>

    <!-- Game Controls -->
    <button id="startBtn" class="btn btn-success btn-custom">Start Game</button>
    <button id="submitBtn" class="btn btn-primary btn-custom" disabled>Submit</button>

    <!-- Game Area -->
    <div id="gameArea"></div>
</div>

@push('scripts')
    <script>
        let score = 0;
        let gameRunning = false;
        let timeLeft = 30;
        let startTime, endTime;
        let interval, countdown;

        function startGame() {
            let studentId = $("#studentSelect").val();
            if (!studentId) {
                alert("Please select a student before starting.");
                return;
            }

            $("#score").text(0);
            $("#timeLeft").text(30);
            $("#submitBtn").prop("disabled", true);
            score = 0;
            timeLeft = 30;
            gameRunning = true;

            startTime = Date.now();

            interval = setInterval(spawnLetter, 1000);
            countdown = setInterval(updateTimer, 1000);
        }

        function updateTimer() {
            timeLeft--;
            $("#timeLeft").text(timeLeft);

            if (timeLeft <= 0) {
                endGame();
            }
        }

        function spawnLetter() {
            if (!gameRunning) return;

            let colors = ["red", "blue", "green", "purple"]; // Different colors for better contrast
            let letter = String.fromCharCode(65 + Math.floor(Math.random() * 26)); // Random A-Z
            let leftPosition = Math.floor(Math.random() * 350);

            let letterElement = $("<div class='letter'>" + letter + "</div>");
            letterElement.css({
                top: "0px",
                left: leftPosition + "px",
                color: colors[Math.floor(Math.random() * colors.length)]
            });

            $("#gameArea").append(letterElement);

            let fallInterval = setInterval(() => {
                let currentTop = parseInt(letterElement.css("top"));
                if (currentTop > 480) {
                    letterElement.remove();
                    clearInterval(fallInterval);
                } else {
                    letterElement.css("top", (currentTop + 5) + "px");
                }
            }, 50);
        }

        $(document).keydown(function (event) {
            if (!gameRunning) return;

            let pressedKey = String.fromCharCode(event.which);
            $(".letter").each(function () {
                if ($(this).text() === pressedKey) {
                    $(this).addClass("key-pressed");
                    setTimeout(() => $(this).remove(), 200);
                    score += 10;
                    $("#score").text(score);
                }
            });
        });

        function endGame() {
            clearInterval(interval);
            clearInterval(countdown);
            gameRunning = false;
            $("#submitBtn").prop("disabled", false);
            endTime = Date.now();
        }

        function submitGame() {
            let studentId = $("#studentSelect").val();
            let educatorId = $("#educator_id").val();
            let activityId = 3;

            if (!studentId) {
                alert("Please select a student before submitting.");
                return;
            }

            let timeTaken = Math.floor((endTime - startTime) / 1000);

            $.ajax({
                url: "{{ route('activity.store-progress') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    student_id: studentId,
                    educator_id: educatorId,
                    activity_id: activityId,
                    score: score,
                    time_taken: timeTaken,
                },
                success: function(response) {
                    console.log("Response:", response);
                    alert("Game progress updated! Final Score: " + score);
                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText);
                    alert("Error updating progress.");
                }
            });
        }

        $(document).ready(() => {
            $("#startBtn").click(startGame);
            $("#submitBtn").click(submitGame);
            $('.select2').select2({ placeholder: "-- Select Student --", allowClear: true });
        });
    </script>
@endpush

@stack('scripts')

</body>
</html>

@endsection
