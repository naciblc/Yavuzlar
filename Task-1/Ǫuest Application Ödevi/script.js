let questions = JSON.parse(localStorage.getItem('questions')) || [];

function saveQuestions() {
    localStorage.setItem('questions', JSON.stringify(questions));
}

function addQuestion() {
    const questionText = prompt("Yeni soruyu girin:");
    if (questionText) {
        const newQuestion = {
            question: questionText,
            options: ['A Şıkkı', 'B Şıkkı', 'C Şıkkı', 'D Şıkkı'],
            correctAnswer: 0 
        };
        questions.push(newQuestion);
        saveQuestions();
        displayQuestions();
    }
}

function editQuestion(index) {
    const newQuestionText = prompt("Yeni soruyu girin:", questions[index].question);
    if (newQuestionText) {
        questions[index].question = newQuestionText;
        saveQuestions();
        displayQuestions();
    }
}

function deleteQuestion(index) {
    if (confirm("Bu soruyu silmek istediğinize emin misiniz?")) {
        questions.splice(index, 1);
        saveQuestions();
        displayQuestions();
    }
}

function displayQuestions() {
    const questionList = document.querySelector('.question-list');
    questionList.innerHTML = '';

    questions.forEach((item, index) => {
        const questionItem = document.createElement('div');
        questionItem.className = 'question-item';
        questionItem.innerHTML = `
            <span>${index + 1}. ${item.question}</span>
            <div>
                <button onclick="editQuestion(${index})">Düzenle</button>
                <button onclick="deleteQuestion(${index})">Sil</button>
                <button onclick="addOption(${index})">Şık Ekle</button>
                <button onclick="editOptions(${index})">Şıkları Düzenle</button>
                <button onclick="setCorrectAnswer(${index})">Doğru Şıkkı Belirle</button>
            </div>
        `;
        questionList.appendChild(questionItem);
    });
}

function addOption(index) {
    const newOption = prompt("Yeni şıkkı girin:");
    if (newOption) {
        questions[index].options.push(newOption);
        saveQuestions();
        displayQuestions();
    }
}

function editOptions(index) {
    const newOptions = [];
    questions[index].options.forEach((option, i) => {
        const updatedOption = prompt(`Şık ${i + 1}:`, option);
        if (updatedOption) {
            newOptions[i] = updatedOption;
        }
    });
    questions[index].options = newOptions;
    saveQuestions();
    displayQuestions();
}

function setCorrectAnswer(index) {
    const currentOptions = questions[index].options;
    let correctAnswer = prompt("Doğru şıkkın indeksini girin (0, 1, 2, 3):");
    correctAnswer = parseInt(correctAnswer);

    if (correctAnswer >= 0 && correctAnswer < currentOptions.length) {
        questions[index].correctAnswer = correctAnswer;
        saveQuestions();
        displayQuestions();
    } else {
        alert("Geçersiz indeks.");
    }
}

document.getElementById('add-question').addEventListener('click', addQuestion);

document.getElementById('search-input').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const filteredQuestions = questions.filter(q => q.question.toLowerCase().includes(searchTerm));

    const questionList = document.querySelector('.question-list');
    questionList.innerHTML = '';

    filteredQuestions.forEach((item, index) => {
        const questionItem = document.createElement('div');
        questionItem.className = 'question-item';
        questionItem.innerHTML = `
            <span>${index + 1}. ${item.question}</span>
            <div>
                <button onclick="editQuestion(${index})">Düzenle</button>
                <button onclick="deleteQuestion(${index})">Sil</button>
                <button onclick="addOption(${index})">Şık Ekle</button>
                <button onclick="editOptions(${index})">Şıkları Düzenle</button>
                <button onclick="setCorrectAnswer(${index})">Doğru Şıkkı Belirle</button>
            </div>
        `;
        questionList.appendChild(questionItem);
    });
});

window.onload = function() {
    displayQuestions();
}
