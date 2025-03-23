@extends('layouts.app')

@section('content')
    <style>
        body {
            text-align: center;
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f4f6f9;
        }

        .content-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        h1 {
            color: #1565C0;
            font-size: 28px;
            font-weight: bold;
            margin-top: 10px;
        }

        #gameContainer {
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.1);
        }

        #gameArea {
            width: 450px;
            height: 300px;
            border: 4px solid #1565C0;
            background-color: #FFF3E0;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
            padding: 15px;
        }

        .letter-box {
            font-size: 40px;
            font-weight: bold;
            padding: 10px;
            margin: 5px;
            background-color: #FFD54F;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
            display: inline-block;
            width: 60px;
            text-align: center;
        }

        .letter-box:hover {
            background-color: #FFA000;
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


        .btn-custom {
            font-size: 20px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            width: 180px;
            margin: 5px;
        }

        .btn-success { background-color: #66BB6A; }
        .btn-warning { background-color: #FFCA28; }
        .btn-primary { background-color: #42A5F5; }
        .btn-info { background-color: #29B6F6; }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }
    </style>

    <div class="content-wrapper">
        <div class="container">
            <h1>🔠 Unscramble Words Game</h1>

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
            <input type="hidden" id="activity_id" value="5">

            <!-- Score & Timer -->
            <p class="info">⏳ Time: <span id="timer">0</span> seconds</p>
            <p class="info">⭐ Score: <span id="score">0</span></p>

            <!-- Game Controls -->
            <div class="button-group">
                <button id="startBtn" class="btn btn-success btn-lg">Start 🎮</button>
                <button id="restartBtn" class="btn btn-warning btn-lg" disabled>Restart 🔄</button>
                <button id="hintBtn" class="btn btn-info btn-lg" disabled>Hint 💡</button>
                <button id="submitBtn" class="btn btn-primary btn-lg" disabled>Submit ✅</button>
            </div>

            <div id="gameArea">
                <div id="scrambledWord" style="font-size: 30px; font-weight: bold; margin-bottom: 10px;"></div>
                <div id="letterChoices"></div>
                <div id="userInput" style="font-size: 28px; font-weight: bold; margin-top: 15px;"></div>
                <p id="hintText" style="font-size: 18px; color: #1565C0; font-weight: bold; margin-top: 10px;"></p> <!-- Hint Display -->
            </div>

        </div>
    </div>

    <script>
        let score = 0;
        let gameRunning = false;
        let timeElapsed = 0;
        let round = 0;
        let timerInterval;
        let correctWord = "";
        let userAnswer = "";
        let usedWords = [];

        const words = ["CAT", "DOG", "FISH", "BIRD", "TREE", "LION", "TIGER"];

        function startGame() {
            let studentId = $("#studentSelect").val();
            if (!studentId) {
                alert("Please select a student before starting.");
                return;
            }

            // Reset values
            $("#score").text(0);
            $("#timer").text(0);
            $("#submitBtn, #restartBtn, #hintBtn").prop("disabled", false);
            $("#startBtn").prop("disabled", true);

            score = 0;
            timeElapsed = 0;
            round = 0;
            usedWords = [];
            gameRunning = true;

            startTime = Date.now();
            timerInterval = setInterval(updateTimer, 1000);

            nextQuestion();
        }

        function updateTimer() {
            timeElapsed++;
            $("#timer").text(timeElapsed);
        }

        function nextQuestion() {
            if (!gameRunning) return;

            // Stop game after 5 rounds
            if (round >= 5) {
                gameRunning = false;
                clearInterval(timerInterval);
                $("#submitBtn").prop("disabled", false);
                alert("Game over! Submit your score.");
                return;
            }

            // Select a unique word
            let availableWords = words.filter(word => !usedWords.includes(word));
            if (availableWords.length === 0) {
                alert("No more words available!");
                return;
            }

            let randomIndex = Math.floor(Math.random() * availableWords.length);
            correctWord = availableWords[randomIndex];
            usedWords.push(correctWord);

            let scrambled = shuffleWord(correctWord);

            // Display scrambled word and reset UI
            $("#scrambledWord").text(scrambled);
            $("#userInput").text("");
            $("#hintText").text("");
            userAnswer = "";

            // Display letter choices
            $("#letterChoices").empty();
            scrambled.split("").forEach(letter => {
                let letterBtn = $("<div class='letter-box'>" + letter + "</div>");
                letterBtn.on("click", function() {
                    userAnswer += letter;
                    $("#userInput").text(userAnswer);

                    if (userAnswer.length === correctWord.length) {
                        if (userAnswer === correctWord) {
                            score += 10;
                            $("#score").text(score);
                        }
                        setTimeout(nextQuestion, 500);
                    }
                });
                $("#letterChoices").append(letterBtn);
            });

            round++;
        }

        function giveHint() {
            $("#hintText").text("Hint: The word starts with '" + correctWord.charAt(0) + "' and ends with '" + correctWord.charAt(correctWord.length - 1) + "'");
        }

        function shuffleWord(word) {
            return word.split("").sort(() => Math.random() - 0.5).join("");
        }

        function restartGame() {
            clearInterval(timerInterval);
            $("#timer").text(0);
            gameRunning = false;
            $("#startBtn").prop("disabled", false);
            $("#submitBtn, #restartBtn, #hintBtn").prop("disabled", true);
        }

        function submitGame() {
            clearInterval(timerInterval);
            let studentId = $("#studentSelect").val();
            let educatorId = $("#educator_id").val();
            let activityId = 2;
            let timeTaken = timeElapsed;

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
                    alert("Game progress updated! Final Score: " + score);
                }
            });
        }

        $(document).ready(() => {
            $("#startBtn").click(startGame);
            $("#hintBtn").click(giveHint);
            $("#restartBtn").click(restartGame);
            $("#submitBtn").click(submitGame);
            $('.select2').select2({ placeholder: "-- Select Student --", allowClear: true });
        });

    </script>
@endsection
