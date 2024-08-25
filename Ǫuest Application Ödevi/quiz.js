let questions = JSON.parse(localStorage.getItem('questions')) || [];

function startQuiz() {
    const quizSection = document.getElementById('quiz-section');
    quizSection.innerHTML = '';

    questions.forEach((item, index) => {
        const questionDiv = document.createElement('div');
        questionDiv.className = 'quiz-question';
        
        questionDiv.innerHTML = `
            <h2>${item.question}</h2>
            ${item.options.map((option, i) => `
                <div>
                    <input type="radio" id="q${index}_o${i}" name="q${index}" value="${i}">
                    <label for="q${index}_o${i}">${option}</label>
                </div>
            `).join('')}
        `;
        quizSection.appendChild(questionDiv);
    });

    const submitButton = document.createElement('button');
    submitButton.innerText = "Quiz'i Bitir";
    submitButton.addEventListener('click', submitQuiz);
    quizSection.appendChild(submitButton);
}

function submitQuiz() {
    let score = 0;

    questions.forEach((item, index) => {
        const selectedOption = document.querySelector(`input[name="q${index}"]:checked`);
        if (selectedOption && parseInt(selectedOption.value) === item.correctAnswer) {
            score += 1;
        }
    });

    alert(`Quiz Sonucu: ${score} / ${questions.length}`);
}

window.onload = function() {
    startQuiz();
}
