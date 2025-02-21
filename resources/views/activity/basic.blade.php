@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Alphabet Matching Activity</title>

    <!-- Bootstrap (for better button styles) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        #gameBoard {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        .letterBtn {
            width: 60px;
            height: 60px;
            font-size: 24px;
            text-align: center;
        }
    </style>
</head>
<body>

<h1>Alphabet Matching Activity</h1>
<p><strong>Time:</strong> <span id="timer">0</span> seconds</p>
<p><strong>Score:</strong> <span id="score">0</span></p>

<!-- Game Controls -->
<button id="startBtn" class="btn btn-success">Start</button>
<button id="stopBtn" class="btn btn-danger" disabled>Stop</button>
<button id="restartBtn" class="btn btn-warning">Restart</button>
<button id="submitBtn" class="btn btn-primary" disabled>Submit</button>

<h3>Module Status:</h3>
<p id="moduleStatus">Pending</p>

<!-- Game Board -->
<div id="gameBoard"></div>

@push('scripts')
    <script>
        let timer = 0, score = 0, isRunning = false, matchedPairs = 0;
        let totalPairs = 5, interval;
        let letters = ["A", "B", "C", "D", "E"];
        let selected = [];

        function startGame() {
            isRunning = true;
            timer = 0;
            score = 0;
            matchedPairs = 0;
            selected = [];
            generateAlphabetGame();
            interval = setInterval(() => {
                timer++;
                $("#timer").text(timer);
            }, 1000);
            $("#startBtn").prop("disabled", true);
            $("#stopBtn").prop("disabled", false);
            $("#submitBtn").prop("disabled", true);
            $("#moduleStatus").text("In Progress");
        }


        function stopGame() {
            isRunning = false;
            clearInterval(interval);
            $("#stopBtn").prop("disabled", true);
            $("#startBtn").prop("disabled", false);
        }

        function restartGame() {
            stopGame();
            startGame();
        }

        function submitGame() {
            stopGame();
            $("#submitBtn").prop("disabled", true);
            $("#moduleStatus").text("Completed");
            alert(`Module Completed! Score: ${score}`);
        }

        function generateAlphabetGame() {
            let shuffled = [...letters, ...letters].sort(() => Math.random() - 0.5);

            $("#gameBoard").html(""); // Clear previous board

            shuffled.forEach((letter, index) => {
                $("#gameBoard").append(`
                    <button class="btn btn-info letterBtn" data-index="${index}" data-letter="${letter}">?</button>
                `);
            });

            $(".letterBtn").prop("disabled", false);
        }

        $(document).on("click", ".letterBtn", function () {
            let index = $(this).data("index");
            let letter = $(this).data("letter");

            if (selected.length < 2) {
                $(this).text(letter).addClass("selected");
                selected.push({ index, letter });

                if (selected.length === 2) {
                    if (selected[0].letter === selected[1].letter) {
                        score += 10;
                        matchedPairs++;
                        $(".selected").removeClass("selected").prop("disabled", true);
                        if (matchedPairs === totalPairs) {
                            $("#submitBtn").prop("disabled", false);
                        }
                    } else {
                        setTimeout(() => {
                            $(".selected").text("?").removeClass("selected");
                        }, 500);
                    }
                    selected = [];
                    $("#score").text(score);
                }
            }
        });

        $(document).ready(() => {
            $("#startBtn").click(startGame);
            $("#stopBtn").click(stopGame);
            $("#restartBtn").click(restartGame);
            $("#submitBtn").click(submitGame);
        });
    </script>
@endpush

@stack('scripts')

</body>
</html>
@endsection
