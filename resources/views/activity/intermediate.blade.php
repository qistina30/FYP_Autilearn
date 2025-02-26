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

    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        #gameArea {
            position: relative;
            width: 400px;
            height: 500px;
            border: 2px solid #000;
            margin: 20px auto;
            background-color: white;
            overflow: hidden;
        }
        .letter {
            position: absolute;
            font-size: 40px;
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body>

<h1>Letter Catch Game</h1>

<!-- Select Student Dropdown -->
<div>
    <label for="studentSelect"><strong>Select Student:</strong></label>
    <select id="studentSelect" class="form-control" style="display: inline-block; width: auto;">
        <option value="">-- Select Student --</option>
        @foreach($students as $student)
            <option value="{{ $student->id }}">{{ $student->full_name }}</option>
        @endforeach
    </select>
</div>

<p><strong>Score:</strong> <span id="score">0</span></p>
<p><strong>Time Left:</strong> <span id="timeLeft">30</span> seconds</p>

<!-- Hidden Input for Educator ID -->
<input type="hidden" id="educator_id" value="{{ Auth::user()->user_id }}">

<!-- Game Controls -->
<button id="startBtn" class="btn btn-success">Start</button>
<button id="submitBtn" class="btn btn-primary" disabled>Submit</button>

<!-- Game Area -->
<div id="gameArea"></div>

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

            startTime = Date.now(); // Capture start time

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

            let letter = String.fromCharCode(65 + Math.floor(Math.random() * 26)); // Random letter A-Z
            let leftPosition = Math.floor(Math.random() * 350); // Random position in game area

            let letterElement = $("<div class='letter'>" + letter + "</div>");
            letterElement.css({ top: "0px", left: leftPosition + "px" });
            $("#gameArea").append(letterElement);

            let fallInterval = setInterval(() => {
                let currentTop = parseInt(letterElement.css("top"));
                if (currentTop > 480) { // If letter reaches the bottom
                    letterElement.remove();
                    clearInterval(fallInterval);
                } else {
                    letterElement.css("top", (currentTop + 5) + "px");
                }
            }, 50);
        }

        $(document).keydown(function (event) {
            if (!gameRunning) return;

            let pressedKey = String.fromCharCode(event.which); // Get pressed key letter
            $(".letter").each(function () {
                if ($(this).text() === pressedKey) {
                    $(this).remove();
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
            let timeTaken = Math.floor((endTime - startTime) / 1000); // Calculate time taken

            $.ajax({
                url: "{{ route('activity.store-progress') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    student_id: studentId,
                    educator_id: educatorId,
                    score: score,
                    time_taken: timeTaken
                },
                success: function() {
                    alert("Progress saved! Final Score: " + score);
                },
                error: function() {
                    alert("Error saving progress.");
                }
            });
        }

        $(document).ready(() => {
            $("#startBtn").click(startGame);
            $("#submitBtn").click(submitGame);
        });
    </script>
@endpush

@stack('scripts')

</body>
</html>

@endsection
