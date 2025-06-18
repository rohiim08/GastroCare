// Function to calculate BMI
function calculateBMI(weight, height) {
    const heightInMeters = height / 100;
    return weight / (heightInMeters * heightInMeters);
}

// Function to update the health indicator marker
function updateMarker(score) {
    const marker = document.getElementById('marker');
    // Ensure score is between 0 and 40
    const clampedScore = Math.max(0, Math.min(40, score));
    marker.textContent = clampedScore; // Update marker text

    // Calculate marker position (0% to 100% of bar width)
    const percentage = (clampedScore / 40) * 100;
    marker.style.left = `calc(${percentage}% - 15px)`; // Adjust for marker width
}

// Function to display the result
async function displayResult() {
    try {
        const response = await fetch('get_result.php');
        const result = await response.json();

        if (!result.success) {
            alert(result.message || 'Tidak ada data yang ditemukan. Silakan mulai diagnosa baru.');
            window.location.href = 'diagnosa.php';
            return;
        }

        const { totalScore, scores, diagnosis } = result;

        // Update the health indicator
        updateMarker(totalScore);

        // Update the diagnosis conclusion
        document.getElementById('diagnosis-title').textContent = diagnosis.title;
        document.getElementById('conclusion-text').textContent = diagnosis.text;

        // Populate the health details list with questions, answers, and scores
        const healthDetailsList = document.getElementById('health-details-list');
        healthDetailsList.innerHTML = ''; // Clear existing content
        for (const [question, { answer, points }] of Object.entries(scores)) {
            const li = document.createElement('li');
            li.innerHTML = `
                <div class="question">${question}</div>
                <div class="answer-details">
                    <span class="answer">${answer}</span>
                    <span class="score"> ${points} poin</span>
                </div>
            `;
            healthDetailsList.appendChild(li);
        }

        // Clear localStorage after displaying result
        localStorage.removeItem('diagnosisData');
    } catch (error) {
        console.error('Error fetching result:', error);
        alert('Terjadi kesalahan saat mengambil hasil diagnosa.');
        window.location.href = 'diagnosa.php';
    }
}

// Form Handling Functions
function selectOption(button, field, value) {
    const buttons = button.parentElement.querySelectorAll('.option-btn');
    buttons.forEach(btn => btn.classList.remove('selected'));
    button.classList.add('selected');
    document.getElementById(field).value = value;
}

function updateProgressBar(step) {
    const steps = document.querySelectorAll('.step');
    steps.forEach((s, index) => {
        if (index + 1 <= step) {
            s.classList.add('active');
        } else {
            s.classList.remove('active');
        }
    });
}

async function saveFormData(event, nextPage) {
    event.preventDefault();
    const form = document.getElementById('diagnosis-form');
    const formData = new FormData(form);
    let data = {};

    // Convert FormData to JSON object
    for (let [key, value] of formData.entries()) {
        if (key === 'chronic_disease') {
            data[key] = data[key] || [];
            if (!data[key].includes(value)) {
                data[key].push(value);
            }
        } else {
            data[key] = value;
        }
    }

    try {
        const response = await fetch('save_form_data.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ formData: data, nextPage })
        });
        const result = await response.json();

        if (result.success) {
            window.location.href = nextPage;
        } else {
            alert(result.message || 'Gagal menyimpan data.');
        }
    } catch (error) {
        console.error('Error saving form data:', error);
        alert('Terjadi kesalahan saat menyimpan data.');
    }
}

function loadFormData() {
    fetch('load_form_data.php')
        .then(response => response.json())
        .then(result => {
            if (result.success && result.formData) {
                const data = result.formData;

                // Pre-fill option buttons
                document.querySelectorAll('.option-btn').forEach(button => {
                    const field = button.getAttribute('data-field');
                    const value = button.getAttribute('data-value');
                    if (data[field] === value) {
                        button.classList.add('selected');
                        document.getElementById(field).value = value;
                    }
                });

                // Pre-fill select elements
                document.querySelectorAll('select').forEach(select => {
                    if (data[select.name]) {
                        select.value = data[select.name];
                    }
                });

                // Pre-fill number inputs
                document.querySelectorAll('input[type="number"]').forEach(input => {
                    if (data[input.name]) {
                        input.value = data[input.name];
                    }
                });

                // Pre-fill checkboxes
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    if (data['chronic_disease'] && data['chronic_disease'].includes(checkbox.value)) {
                        checkbox.checked = true;
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading form data:', error);
        });
}