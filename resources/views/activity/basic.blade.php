@extends('layouts.app')

@section('content')

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Find the Missing Letter</title>

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
            font-size: 36px;
            font-weight: bold;
        }
        .choiceBtn {
            width: 80px;
            height: 60px;
            font-size: 24px;
            margin: 10px;
        }
    </style>
</head>
<body>

<h1>Find the Missing Letter</h1>

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
<p><strong>Time Taken:</strong> <span id="timer">0</span> seconds</p>

<!-- Hidden Input for Educator ID -->
<input type="hidden" id="educator_id" value="{{ Auth::user()->user_id }}">
<!-- Game Controls -->
<button id="startBtn" class="btn btn-success">Start</button>
<button id="submitBtn" class="btn btn-primary" disabled>Submit</button>

<!-- Game Board -->
<div id="gameBoard"></div>

@push('scripts')
    <script>
        let score = 0;
        let round = 0;
        let correctLetter = "";
        let startTime, timerInterval;

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
            $("#timer").text(0);
            $("#submitBtn").prop("disabled", true);

            startTime = Date.now(); // Start the timer
            timerInterval = setInterval(updateTimer, 1000);

            nextRound();
        }

        function updateTimer() {
            let elapsedTime = Math.floor((Date.now() - startTime) / 1000);
            $("#timer").text(elapsedTime);
        }

        function nextRound() {
            if (round >= 5) {
                clearInterval(timerInterval); // Stop timer when game ends
                $("#submitBtn").prop("disabled", false);
                return;
            }

            round++;
            $("#round").text(round);

            let alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
            let startIdx = Math.floor(Math.random() * 23);
            let sequence = alphabet.slice(startIdx, startIdx + 4);

            let missingIdx = Math.floor(Math.random() * 4);
            correctLetter = sequence[missingIdx];
            sequence[missingIdx] = "_";

            let choices = [correctLetter];
            while (choices.length < 3) {
                let randomChoice = alphabet[Math.floor(Math.random() * 26)];
                if (!choices.includes(randomChoice)) {
                    choices.push(randomChoice);
                }
            }
            choices.sort(() => Math.random() - 0.5);

            $("#gameBoard").html(`
            <p>${sequence.join(" ")}</p>
            ${choices.map(letter => `<button class="btn btn-info choiceBtn">${letter}</button>`).join(" ")}
        `);
        }

        $(document).on("click", ".choiceBtn", function () {
            let chosenLetter = $(this).text();
            if (chosenLetter === correctLetter) {
                score += 10;
            }
            $("#score").text(score);
            nextRound();
        });

        function submitGame() {
            let studentId = $("#studentSelect").val();
            let timeTaken = $("#timer").text();
            let educatorId = $("#educator_id").val();

            $.ajax({
                url: "{{ route('activity.store-progress') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    student_id: studentId,
                    educator_id: educatorId,
                    score: score,
                    time_taken: timeTaken,
                    // status: "Completed"
                },
                success: function() {
                    alert("Game completed! Final Score: " + score );
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
