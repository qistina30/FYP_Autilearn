@extends('layouts.app')

@section('content')
    <div class="container text-center">
        <h1>Memory Card Game</h1>

        <!-- Select Student Dropdown -->
        <div class="mb-3">
            <label for="studentSelect" class="info">Select Student:</label>
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
        <button id="startBtn" class="btn btn-success btn-lg">Start 🎮</button>
        <button id="restartBtn" class="btn btn-warning btn-lg">Restart 🔄</button>
        <button id="submitBtn" class="btn btn-primary btn-lg" disabled>Submit ✅</button>

        <!-- Game Board -->
        <div id="gameBoard"></div>
    </div>

    @push('styles')
            <style>
                body {
                    text-align: center;
                    font-family: 'Comic Sans MS', Arial, sans-serif;
                    background-color: #F8F9FA;
                }

                h1 {
                    font-size: 32px;
                    color: #007BFF;
                    margin-bottom: 15px;
                    font-weight: bold;
                }

                .container {
                    max-width: 700px;
                    background: linear-gradient(to bottom, #ffffff, #e3f2fd);
                    padding: 25px;
                    border-radius: 15px;
                    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
                    margin: auto;
                }

                #gameBoard {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 15px;
                    margin-top: 20px;
                    justify-content: center;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 10px;
                    box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.1);
                }

                .letterBtn {
                    width: 90px;
                    height: 90px;
                    font-size: 34px;
                    background: linear-gradient(to bottom, #FFD700, #FFA500);
                    border: 3px solid #FF8C00;
                    color: white;
                    font-weight: bold;
                    border-radius: 12px;
                    cursor: pointer;
                    transition: transform 0.3s ease, background-color 0.3s ease;
                    transform-style: preserve-3d;
                }

                .letterBtn:hover {
                    transform: scale(1.1);
                    background: linear-gradient(to bottom, #FFA500, #FF4500);
                }

                .selected {
                    background-color: #FF4500 !important;
                    transform: rotateY(180deg);
                }

                .correct {
                    background-color: #32CD32 !important;
                    transform: scale(1.2);
                    animation: shake 0.4s;
                }

                .wrong {
                    background-color: #DC143C !important;
                    animation: shake 0.4s;
                }

                @keyframes shake {
                    0% { transform: translateX(0); }
                    25% { transform: translateX(-5px); }
                    50% { transform: translateX(5px); }
                    75% { transform: translateX(-5px); }
                    100% { transform: translateX(0); }
                }

                .info {
                    font-size: 20px;
                    font-weight: bold;
                    color: #333;
                }

                .btn-lg {
                    width: 100%;
                    margin-top: 10px;
                    font-size: 22px;
                    padding: 12px;
                    border-radius: 8px;
                    transition: transform 0.2s ease-in-out;
                }

                .btn-lg:hover {
                    transform: scale(1.05);
                }
            </style>
        @endpush


    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

        <script>
            let timer = 0, score = 0, isRunning = false, matchedPairs = 0;
            let totalPairs = 5, interval;
            let letters = ["A", "B", "C", "D", "E"];
            let selected = [];

            function startGame() {
                let studentId = $("#studentSelect").val();

                if (!studentId) {
                    alert("Please select a student before starting the game.");
                    return;
                }

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

                $("#submitBtn").prop("disabled", true);
            }

            function stopGame() {
                isRunning = false;
                clearInterval(interval);

                $("#startBtn").prop("disabled", false);
            }

            function restartGame() {
                stopGame();
                startGame();
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
                        _token: "{{ csrf_token() }}", // Ensure this is passed
                        student_id: studentId,
                        educator_id: educatorId,
                        activity_id: activityId,
                        score: score,
                        time_taken: timeTaken,
                    },
                    success: function(response) {
                        alert("Game progress updated! Final Score: " + score);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // Debugging
                        alert("Error updating progress.");
                    }
                });
            }

            function generateAlphabetGame() {
                let shuffled = [...letters, ...letters].sort(() => Math.random() - 0.5);
                $("#gameBoard").html("");

                shuffled.forEach((letter, index) => {
                    $("#gameBoard").append(`
            <button class="btn letterBtn" data-index="${index}" data-letter="${letter}">?</button>
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
                            $(".selected").removeClass("selected").addClass("correct").prop("disabled", true);
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
                $("#restartBtn").click(restartGame);
                $("#submitBtn").click(submitGame);
                $('.select2').select2({ placeholder: "-- Select Student --", allowClear: true });
            });
        </script>
    @endpush

@endsection
