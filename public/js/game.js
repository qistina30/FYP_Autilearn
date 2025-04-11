console.log("Top of game.js - animalData:", typeof animalData, animalData);

$("#themeSelector").on("change", function () {
    let selectedColor = $(this).val();
    $("body").css("background-color", selectedColor);
});

console.log("animalData:", window.animalData);

$(document).ready(function () {
    console.log("animalData inside ready():", animalData);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let timer = 0, score = 0, isRunning = false;
    let interval, questionCount = 0;
    const totalRounds = 5;
    let correctAnswer, currentSound;

    let availableAnimals = [...animalData];

        function generateAnimalQuestion() {
        if (availableAnimals.length === 0) {
            return;
        }

        let randomIndex = Math.floor(Math.random() * availableAnimals.length);
        let selectedAnimal = availableAnimals.splice(randomIndex, 1)[0];

        $("#animalImage").attr("src", selectedAnimal.url).show();
    }

    // Remove the duplicate voice control button creation
    if (!$("#voiceControlBtn").length) {  // Check if the button already exists
        $("<button id='voiceControlBtn' class='sound-btn' '>🎙️ Voice Command</button>")
            .insertAfter("#playSoundBtn")
            .on("click", function () {
                startListening();
            });
    }

    window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognition();
    recognition.lang = 'en-US';
    recognition.continuous = false;
    recognition.interimResults = false;

    function startListening() {
        recognition.start();
    }

    recognition.onresult = function (event) {
        let speechResult = event.results[0][0].transcript.toLowerCase().trim();
        let confidence = event.results[0][0].confidence;

        console.log("Recognized:", speechResult, "| Confidence:", confidence);
        $("#liveTranscript").text(`🗣 ${speechResult}`);

        if (confidence > 0.6) {
            if (speechResult.includes("start")) $("#startBtn").click();
            else if (speechResult.includes("restart")) $("#restartBtn").click();
            else if (speechResult.includes("submit")) $("#submitBtn").click();
            else {
                $(".answer-btn").each(function () {
                    let correctAnswer = $(this).text().toLowerCase();
                    let variations = getPronunciationVariations(correctAnswer);

                    if (variations.includes(speechResult)) {
                        $(this).click();
                    }
                });
            }
        }
    };

    recognition.onerror = function (event) {
        console.error("Speech recognition error:", event.error);
    };

    function getPronunciationVariations(word) {
        let variations = {
            "dog": ["dog", "dawg", "doggy", "anjin"],
            "cat": ["cat", "kitten", "kitty", "kucing"],
            "lion": ["lion", "lyon", "singa"],
            "elephant": ["elephant", "elefants", "ellie", "gajah"]
        };
        return variations[word] || [word];
    }

    // Game Start, Stop, and Submit functions
    function startGame() {
        let studentId = $("#studentSelect").val();

        if (!studentId) {
            alert("Please select a student before starting the game.");
            return; // prevent starting the game
        }

        isRunning = true;
        timer = 0;
        score = 0;
        questionCount = 0;
        availableAnimals = [...animalData];

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
        $("#audioSection").show();
    }


    function stopGame() {
        isRunning = false;
        clearInterval(interval);
        $("#startBtn").prop("disabled", false);
    }


    let lang = localStorage.getItem("language") || "en";

    const translations = {
        en: {
            title: "🐾 Animal Image Recognition 🐾",
            time: "⏳ Time:",
            score: "⭐ Score:",
            start: "🎮 Start",
            restart: "🔄 Restart",
            submit: "✅ Submit",
            playSound: "🔊 Play Animal Sound",
            voiceCommand: "🎙️ Voice Command",
            volume: "🔊 Volume:",
            background: "🎨 Background:",
            wellDone: "🎉 Well done! Click Submit to save your progress.",
            name: {
                cat: "Cat",
                dog: "Dog",
                elephant: "Elephant",
                tiger: "Tiger",
                monkey: "Monkey",
                bird: "Bird",
                lion: "Lion",
                cow: "Cow",
                rabbit: "Rabbit",
                horse: "Horse"
            }
        },
        ms: {
            title: "🐾 Pengecaman Imej Haiwan 🐾",
            time: "⏳ Masa:",
            score: "⭐ Markah:",
            start: "🎮 Mula",
            restart: "🔄 Mulakan Semula ",
            submit: "✅ Hantar",
            playSound: "🔊 Mainkan Bunyi Haiwan",
            voiceCommand: "🎙️ Perintah Suara",
            volume: "🔊 Kelantangan:",
            background: "🎨 Latar Belakang:",
            wellDone: "🎉 Tahniah! Klik Hantar untuk menyimpan kemajuan anda.",
            name: {
                cat: "Kucing",
                dog: "Anjing",
                elephant: "Gajah",
                tiger: "Harimau",
                monkey: "Monyet",
                bird: "Burung",
                lion: "Singa",
                cow: "Lembu",
                rabbit: "Arnab",
                horse: "Kuda"
            }
        }
    };

    function updateLanguage() {
        let selectedLang = $("#languageSelector").val();
        let langData = translations[selectedLang];

        $(".title").text(langData.title);
        $("#timer").prev().text(langData.time);
        $("#score").prev().text(langData.score);
        $("#startBtn").text(langData.start);
        $("#restartBtn").text(langData.restart);
        $("#submitBtn").text(langData.submit);
        $("#playSoundBtn").text(langData.playSound);
        $("#voiceControlBtn").text(langData.voiceCommand);
        $("#volumeLabel").text(`${langData.volume} ${Math.round($("#volumeControl").val() * 100)}%`);
        $("label[for='themeSelector']").text(langData.background);
    }

    // Function to generate animal question and options dynamically
    function generateAnimalQuestion() {
        if (questionCount >= totalRounds || availableAnimals.length === 0) {
            stopGame();
            $("#submitBtn").prop("disabled", false);
            $("#answerOptions").html(`<p class='info-box'>${translations[$("#languageSelector").val()].wellDone}</p>`);
            return;
        }

        let randomIndex = Math.floor(Math.random() * availableAnimals.length);
        let selectedAnimal = availableAnimals.splice(randomIndex, 1)[0];
        correctAnswer = selectedAnimal.name;
        currentSound = selectedAnimal.sound;
        const audio = new Audio(currentSound);
        audio.volume = typeof audioVolume !== 'undefined' ? audioVolume : 1;
        audio.play();
        $("#animalImage").attr("src", selectedAnimal.url).removeAttr("hidden").show();
        $("#playSoundBtn").show().off("click").on("click", function () {
            playSound(currentSound);
        });

        let incorrectAnswers = animalData.filter(a => a.name !== correctAnswer);
        let randomWrongAnswer = incorrectAnswers.length > 0
            ? incorrectAnswers[Math.floor(Math.random() * incorrectAnswers.length)].name
            : "Unknown";

        let allAnswers = [correctAnswer, randomWrongAnswer].sort(() => Math.random() - 0.5);

        $("#answerOptions").html(allAnswers.map(answer =>
            `<button class="answer-btn" data-answer="${answer}">${translations[$("#languageSelector").val()].name[answer] || answer}</button>`
        ).join(''));

        function playSound(soundUrl) {
            if (soundUrl) {
                let audio = new Audio(soundUrl);
                audio.volume = audioVolume;
                audio.play().catch(error => {
                    console.error("Error playing sound:", error);
                });
            } else {
                console.warn("No sound URL provided.");
            }
        }

        $(".answer-btn").off("click").on("click", function () {
            let selectedAnswer = $(this).data("answer");

            // Check if selected answer is correct or not
            if (selectedAnswer === correctAnswer) {
                $(this).addClass("correct");
                score += 10;
                $("#score").text(score); // ← Force update score in the DOM

                const sound = new Audio(correctSound);
                sound.volume = audioVolume;
                sound.play();

                questionCount++;
                setTimeout(generateAnimalQuestion, 1000);
            } else {
                $(this).addClass("wrong"); // Highlight wrong answer
                score -= 5; // Deduct score for wrong answer
                const sound = new Audio(wrongSound);
                sound.volume = audioVolume;
                sound.play(); // Wrong answer sound

                // Update the score
                $("#score").text(score);

                // Don't move to the next question, allow the user to try again
            }
        });

    }

    function submitGame() {
        let studentId = $("#studentSelect").val();
        let timeTaken = $("#timer").text();
        let educatorId = $("#educator_id").val();
        let activityId = 4;

        $.ajax({
            url: storeProgressUrl,
            method: "POST",
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
    $("#languageSelector").on("change", updateLanguage);
    updateLanguage();

    $("#startBtn").click(startGame);
    $("#restartBtn").click(() => { stopGame(); startGame(); });
    $("#submitBtn").click(submitGame);
});
