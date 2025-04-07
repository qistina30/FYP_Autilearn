// Change background color based on selection
$("#themeSelector").on("change", function () {
    let selectedColor = $(this).val();
    $("body").css("background-color", selectedColor);
});

$(document).ready(function () {
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

    let audioVolume = 1; // Default volume

    // Update volume when the slider changes
    $("#volumeControl").on("input", function () {
        let volumeLevel = $(this).val();
        $("#volumeLabel").text(`🔊 Volume: ${Math.round(volumeLevel * 100)}%`);
        audioVolume = volumeLevel;
    });

    function playSound(soundUrl) {
        if (soundUrl) {
            let audio = new Audio(soundUrl);
            audio.volume = audioVolume;
            audio.onerror = () => console.warn("Audio file not found:", soundUrl);
            audio.play();
        }
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
        $("#liveTranscript").text(`🗣 ${speechResult}`); // Display live transcript

        if (confidence > 0.6) {  // Adjusted threshold
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

    function getPronunciationVariations(word) {
        let variations = {
            "dog": ["dog", "dawg", "doggy"],
            "cat": ["cat", "kitten", "kitty"],
            "lion": ["lion", "lyon"],
            "elephant": ["elephant", "elefants", "ellie"]
        };
        return variations[word] || [word];
    }

    recognition.onerror = function (event) {
        console.error("Speech recognition error:", event.error);
    };

    // Add a voice control button
    $("<button id='voiceControlBtn' class='sound-btn'>🎙️ Voice Command</button>")
        .insertAfter("#playSoundBtn")
        .on("click", startListening);

    function startGame() {
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
    }

    function stopGame() {
        isRunning = false;
        clearInterval(interval);
        $("#startBtn").prop("disabled", false);
    }

    function submitGame() {
        alert("Game progress saved! Final Score: " + score);
    }

    const translations = {
        en: {
            title: "🐾 Image Recognition",
            time: "⏳ Time:",
            score: "⭐ Score:",
            start: "Start 🎮",
            restart: "Restart 🔄",
            submit: "Submit ✅",
            playSound: "🔊 Play Sound",
            voiceCommand: "🎙️ Voice Command",
            volume: "🔊 Volume:",
            background: "🎨 Background:",
            wellDone: "🎉 Well done! Click Submit to save your progress.",
        },
        ms: {
            title: "🐾 Pengecaman Imej",
            time: "⏳ Masa:",
            score: "⭐ Markah:",
            start: "Mula 🎮",
            restart: "Mulakan Semula 🔄",
            submit: "Hantar ✅",
            playSound: "🔊 Mainkan Bunyi",
            voiceCommand: "🎙️ Perintah Suara",
            volume: "🔊 Kelantangan:",
            background: "🎨 Latar Belakang:",
            wellDone: "🎉 Tahniah! Klik Hantar untuk menyimpan kemajuan anda.",
        }
    };

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
            `<button class="answer-btn" data-answer="${answer}">${answer}</button>`
        ).join(''));

        $(".answer-btn").off("click").on("click", function () {
            let selectedAnswer = $(this).data("answer");
            if (selectedAnswer === correctAnswer) {
                $(this).addClass("correct");
                score += 10;
                playSound("{{ asset('sounds/correct.mp3') }}");
            } else {
                $(this).addClass("wrong");
                playSound("{{ asset('sounds/wrong.mp3') }}");
            }
            $("#score").text(score);
            questionCount++;
            setTimeout(generateAnimalQuestion, 1000);
        });
    }



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

    $("#languageSelector").on("change", updateLanguage);
    updateLanguage();

    $("#startBtn").click(startGame);
    $("#restartBtn").click(() => { stopGame(); startGame(); });
    $("#submitBtn").click(submitGame);
});
