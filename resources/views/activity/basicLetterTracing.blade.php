@extends('layouts.app')

@section('content')

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Match the Letter</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        #gameBoard {
            margin-top: 20px;
            font-size: 80px;
            font-weight: bold;
        }
        .option-btn {
            font-size: 30px;
            padding: 15px;
            margin: 10px;
            width: 100px;
        }
    </style>
</head>
<body>

<h1>Match the Letter</h1>

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
<p><strong>Round:</strong> <span id="round">0</span>/5</p>

<!-- Game Board -->
<div id="gameBoard"></div>

<!-- Game Controls -->
<button id="startBtn" class="btn btn-success">Start</button>
<button id="submitBtn" class="btn btn-primary" disabled>Submit</button>

@push('scripts')
    <script>
        let score = 0;
        let round = 0;
        let currentLetter = "";
        let correctOption = "";
        const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");

        function startGame() {
            let studentId = $("#studentSelect").val();
            if (!studentId) {
                alert("Please select a student before starting.");
                return;
            }

            score = 0;
            round = 0;
            $("#score").text(score);
            $("#round").text(round);
            $("#submitBtn").prop("disabled", true);

            nextRound();
        }

        function nextRound() {
            if (round >= 5) {
                $("#submitBtn").prop("disabled", false);
                return;
            }

            round++;
            $("#round").text(round);

            currentLetter = alphabet[Math.floor(Math.random() * alphabet.length)];
            let wrongOption1 = alphabet[Math.floor(Math.random() * alphabet.length)];
            let wrongOption2 = alphabet[Math.floor(Math.random() * alphabet.length)];

            // Ensure wrong options are not the same as the correct one
            while (wrongOption1 === currentLetter) {
                wrongOption1 = alphabet[Math.floor(Math.random() * alphabet.length)];
            }
            while (wrongOption2 === currentLetter || wrongOption2 === wrongOption1) {
                wrongOption2 = alphabet[Math.floor(Math.random() * alphabet.length)];
            }

            let options = [currentLetter, wrongOption1, wrongOption2];
            options = options.sort(() => Math.random() - 0.5); // Shuffle options

            $("#gameBoard").html(`
                <p>${currentLetter}</p>
                ${options.map(letter => `<button class="option-btn btn btn-warning" data-letter="${letter}">${letter}</button>`).join("")}
            `);
        }

        $(document).on("click", ".option-btn", function () {
            let selectedLetter = $(this).data("letter");
            if (selectedLetter === currentLetter) {
                score += 10;
                alert("Correct! 🎉");
            } else {
                alert("Oops! Try again. ❌");
            }
            $("#score").text(score);
            nextRound();
        });

        function submitGame() {
            let studentId = $("#studentSelect").val();

            $.ajax({
                url: "{{ route('activity.store-progress') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    student_id: studentId,
                    score: score,
                },
                success: function() {
                    alert("Game completed! Final Score: " + score);
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
