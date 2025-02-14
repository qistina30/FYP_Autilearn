document.addEventListener('DOMContentLoaded', function () {
    var audio = document.getElementById('animal-sound');
    var audioSource = document.getElementById('audio-source');
    var feedbackSection = document.getElementById('feedback');
    var feedbackMessage = document.getElementById('feedback-message');
    var nextButton = document.getElementById('next-button');

    // Animal sounds mapping
    var animalSounds = {
        'cat': 'sounds/cat.mp3',
        'dog': 'sounds/dog.mp3',
        'elephant': 'sounds/elephant.mp3',
        'bird': 'sounds/bird.mp3'
    };

    // Set a random animal sound
    var correctAnimal = Object.keys(animalSounds)[Math.floor(Math.random() * 4)];
    audioSource.src = animalSounds[correctAnimal];
    audio.load();

    // Add event listeners for buttons
    var animalButtons = document.querySelectorAll('.animal-button');
    animalButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var selectedAnimal = this.getAttribute('data-animal');
            checkAnswer(selectedAnimal);
        });
    });

    function checkAnswer(selectedAnimal) {
        if (selectedAnimal === correctAnimal) {
            feedbackMessage.textContent = 'Correct! That’s the sound of a ' + correctAnimal + '!';
        } else {
            feedbackMessage.textContent = 'Wrong! Try again.';
        }

        feedbackSection.style.display = 'block';
        nextButton.style.display = 'inline-block';
    }

    function nextActivity() {
        feedbackSection.style.display = 'none';

        // Load a new random sound
        correctAnimal = Object.keys(animalSounds)[Math.floor(Math.random() * 4)];
        audioSource.src = animalSounds[correctAnimal];
        audio.load();
    }
});
