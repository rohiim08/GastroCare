// Array untuk menyimpan data pengguna
let users = [
    {
        name: "User",
        email: "user@gmail.com",
        password: "user1234"
    },
];

// Elemen DOM
const container = document.getElementById('container');
const overlayCon = document.getElementById('overlayCon');
const overlayBtn = document.getElementById('overlayBtn');
const popup = document.getElementById('customPopup');
const popupTitle = document.getElementById('popupTitle');
const popupMessage = document.getElementById('popupMessage');
const popupClose = document.getElementById('popupClose');

// Fungsi untuk menampilkan popup
function showPopup(title, message) {
    popupTitle.textContent = title;
    popupMessage.textContent = message;
    popup.style.display = 'flex';
}

// Fungsi untuk menyembunyikan popup
function hidePopup() {
    popup.style.display = 'none';
}

// Tutup popup saat tombol close diklik
popupClose.addEventListener('click', hidePopup);

// Tutup popup saat klik di luar konten
popup.addEventListener('click', (e) => {
    if (e.target === popup) {
        hidePopup();
    }
});

// Toggle panel saat overlay button diklik
overlayBtn.addEventListener('click', () => {
    container.classList.toggle('right-panel-active');
});

// Fungsi untuk menangani login
document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const formData = new FormData();
    formData.append("action", "login");
    formData.append("email", email);
    formData.append("password", password);

    fetch("login.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server Response:", data); // Log respons server
        if (data.status === "success") {
            console.log("Redirecting to:", data.redirect); // Log URL pengalihan
            window.location.href = data.redirect;
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error("Error:", error));
});

// Fungsi untuk menangani registrasi
document.getElementById("registerForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const username = document.getElementById("name").value;
    const email = document.getElementById("regEmail").value;
    const password = document.getElementById("regPassword").value;

    const formData = new FormData();
    formData.append("action", "register");
    formData.append("username", username);
    formData.append("email", email);
    formData.append("password", password);

    fetch("login.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            document.getElementById("registerForm").reset();
        }
    })
    .catch(error => console.error("Error:", error));
});

// Toggle panel saat tombol overlay-panel diklik
document.querySelectorAll('.overlay-panel button').forEach(button => {
    button.addEventListener('click', () => {
        container.classList.toggle('right-panel-active');
    });
});