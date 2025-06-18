/* Konfigurasi ScrollReveal */
const scrollRevealOption = {
    distance: "50px",
    origin: "bottom",
    duration: 1000,
};

/* Animasi ScrollReveal untuk Header */
ScrollReveal().reveal(".header-content h4", { ...scrollRevealOption });
ScrollReveal().reveal(".header-content h1", { ...scrollRevealOption, delay: 200 });
ScrollReveal().reveal(".header-content h2", { ...scrollRevealOption, delay: 400 });
ScrollReveal().reveal(".header-content p", { ...scrollRevealOption, delay: 600 });
ScrollReveal().reveal(".header-btn", { ...scrollRevealOption, delay: 800 });

/* Animasi ScrollReveal untuk Intro */
ScrollReveal().reveal(".intro-card", { ...scrollRevealOption, interval: 200 });

/* Animasi ScrollReveal untuk About */
ScrollReveal().reveal(
    ".about-row:nth-child(3) .about-image img, .about-row:nth-child(5) .about-image img",
    { ...scrollRevealOption, origin: "left" }
);
ScrollReveal().reveal(".about-row:nth-child(4) .about-image img", { ...scrollRevealOption, origin: "right" });
ScrollReveal().reveal(".about-content span", { ...scrollRevealOption, delay: 200 });
ScrollReveal().reveal(".about-content h4", { ...scrollRevealOption, delay: 400 });
ScrollReveal().reveal(".about-content p", { ...scrollRevealOption, delay: 600 });

/* Animasi ScrollReveal untuk Fact */
ScrollReveal().reveal(".fact-card", { ...scrollRevealOption, interval: 200 });

/* Animasi ScrollReveal untuk Service */
ScrollReveal().reveal(".service-card", { duration: 1000, interval: 200 });

/* Konfigurasi Swiper untuk Client Slider */
if (typeof Swiper !== 'undefined' && document.querySelector('.swiper')) {
    const swiper = new Swiper(".swiper", {
        slidesPerView: 3, // Default untuk desktop
        spaceBetween: 20,
        loop: true,
        breakpoints: {
            400: {
                slidesPerView: 1, // 1 slide untuk mobile
                spaceBetween: 10,
            },
            600: {
                slidesPerView: 2, // 2 slide untuk tablet
                spaceBetween: 15,
            },
            1000: {
                slidesPerView: 3, // 3 slide untuk desktop
                spaceBetween: 20,
            },
        },
    });
}

// Seleksi elemen nav-profile, tombol Login, ikon dropdown, dan dropdown
const navProfile = document.querySelector('.nav-profile');
const loginBtn = document.getElementById('login-btn');
const profileName = navProfile.querySelector('span');
const profileIcon = navProfile.querySelector('i');
const profileDropdown = document.getElementById('profile-dropdown');
const logoutBtn = document.getElementById('logout-btn');
const chatbotToggler = document.querySelector("#chatbot-toggler");

// Elemen Mobile
const hamburger = document.querySelector('.hamburger');
const mobileMenu = document.querySelector('.mobile-menu');
const mobileProfileInfo = document.getElementById('mobile-profile-info');
let mobileProfileName = null;
if (mobileProfileInfo) {
    mobileProfileName = mobileProfileInfo.querySelector('span');
}
const loginBtnMobile = document.getElementById('login-btn-mobile');
const logoutBtnMobile = document.getElementById('logout-btn-mobile');

// Event listener untuk tombol hamburger
hamburger.addEventListener('click', () => {
    mobileMenu.classList.toggle('active');
    
    // Animate hamburger to X
    const hamburgerLines = document.querySelectorAll('.hamburger span');
    if (mobileMenu.classList.contains('active')) {
        hamburgerLines[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
        hamburgerLines[1].style.opacity = '0';
        hamburgerLines[2].style.transform = 'rotate(-45deg) translate(7.5px, -7.5px)';
    } else {
        hamburgerLines[0].style.transform = 'none';
        hamburgerLines[1].style.opacity = '1';
        hamburgerLines[2].style.transform = 'none';
    }
});

// Event listener untuk tombol Login
if (loginBtn) {
    loginBtn.addEventListener('click', () => {
        console.log("Tombol login diklik");
        window.location.href = '_Login/login.php';
    });
}

// Event listener untuk tombol Login Mobile
if (loginBtnMobile) {
    loginBtnMobile.addEventListener('click', () => {
        console.log("Tombol login mobile diklik");
        window.location.href = '_Login/login.html';
    });
}

// Event listener untuk ikon penampilkan profile
if (profileIcon) {
    profileIcon.addEventListener('click', () => {
        if (navProfile.classList.contains('logged-in')) {
            profileDropdown.classList.toggle('active');
            
            // Toggle visibility untuk gambar profil dan nama pengguna
            const profileImg = navProfile.querySelector('img');
            const profileSpan = navProfile.querySelector('span');
            
            if (profileDropdown.classList.contains('active')) {
                // Sembunyikan gambar dan nama saat dropdown aktif
                profileImg.style.opacity = '0';
                profileImg.style.visibility = 'hidden';
                profileSpan.style.opacity = '0';
                profileSpan.style.visibility = 'hidden';
            } else {
                // Tampilkan kembali gambar dan nama saat dropdown tidak aktif
                profileImg.style.opacity = '1';
                profileImg.style.visibility = 'visible';
                profileSpan.style.opacity = '1';
                profileSpan.style.visibility = 'visible';
            }
        }
    });
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', (e) => {
    if (!navProfile.contains(e.target)) {
        profileDropdown.classList.remove('active');
        
        // Pastikan elemen-elemen profil kembali terlihat
        const profileImg = navProfile.querySelector('img');
        const profileSpan = navProfile.querySelector('span');
        
        profileImg.style.opacity = '1';
        profileImg.style.visibility = 'visible';
        profileSpan.style.opacity = '1';
        profileSpan.style.visibility = 'visible';
    }
});

// Event listener untuk tombol Logout
if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
        sessionStorage.removeItem('loggedInUser');
        sessionStorage.removeItem('userRole');
        window.location.href = 'logout.php';
    });
}

// Event listener untuk tombol Logout Mobile
if (logoutBtnMobile) {
    logoutBtnMobile.addEventListener('click', () => {
        sessionStorage.removeItem('loggedInUser');
        sessionStorage.removeItem('userRole');
        window.location.href = 'logout.php';
    });
}

// Close mobile menu when clicking on a link
const mobileLinks = document.querySelectorAll('.mobile-menu a');
mobileLinks.forEach(link => {
    link.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
        
        // Reset hamburger icon
        const hamburgerLines = document.querySelectorAll('.hamburger span');
        hamburgerLines[0].style.transform = 'none';
        hamburgerLines[1].style.opacity = '1';
        hamburgerLines[2].style.transform = 'none';
    });
});

// Fungsi untuk memperbarui tampilan profil berdasarkan status login
function updateProfile() {
    const loggedInUser = sessionStorage.getItem('loggedInUser');
    if (loggedInUser) {
        // Update desktop/tablet view
        navProfile.classList.add('logged-in');
        profileName.textContent = loggedInUser;
        
        // Pastikan elemen-elemen profil terlihat
        const profileImg = navProfile.querySelector('img');
        const profileSpan = navProfile.querySelector('span');
        profileImg.style.opacity = '1';
        profileImg.style.visibility = 'visible';
        profileSpan.style.opacity = '1';
        profileSpan.style.visibility = 'visible';
        
        // Update mobile view
        if (mobileProfileInfo) {
            mobileProfileInfo.classList.add('logged-in');
        }
        if (mobileProfileName) {
            mobileProfileName.textContent = loggedInUser;
        }
        if (loginBtnMobile) {
            loginBtnMobile.classList.add('logged-in');
        }
        if (logoutBtnMobile) {
            logoutBtnMobile.classList.add('logged-in');
        }
    } else {
        // Update desktop/tablet view
        navProfile.classList.remove('logged-in');
        profileDropdown.classList.remove('active');
        
        // Update mobile view
        if (mobileProfileInfo) {
            mobileProfileInfo.classList.remove('logged-in');
        }
        if (loginBtnMobile) {
            loginBtnMobile.classList.remove('logged-in');
        }
        if (logoutBtnMobile) {
            logoutBtnMobile.classList.remove('logged-in');
        }
    }
}

// Event listener untuk chatbot sudah ditangani di chatbot.js

// Fungsi untuk memeriksa status login
function checkLogin() {
    const loggedInUser = sessionStorage.getItem('loggedInUser');
    return !!loggedInUser; // Mengembalikan true jika ada loggedInUser, false jika tidak
}

// Fungsi untuk menampilkan popup
function showPopup(title, message) {
    const popupTitle = document.getElementById('popupTitle');
    const popupMessage = document.getElementById('popupMessage');
    const popup = document.getElementById('customPopup');
    popupTitle.textContent = title;
    popupMessage.textContent = message;
    popup.style.display = 'flex';
}

// Fungsi untuk menyembunyikan popup
function hidePopup() {
    const popup = document.getElementById('customPopup');
    popup.style.display = 'none';
}

// Event listener untuk tombol tutup popup
const popupClose = document.getElementById('popupClose');
const popup = document.getElementById('customPopup');

if (popupClose) {
    popupClose.addEventListener('click', hidePopup);
}

// Tutup popup jika user mengklik di luar konten popup
if (popup) {
    popup.addEventListener('click', (e) => {
        if (e.target === popup) {
            hidePopup();
        }
    });
}

// Seleksi semua tautan Tools
const toolLinks = document.querySelectorAll('.tool-link');

// Tambahkan event listener untuk memeriksa login saat tautan diklik
toolLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        if (!checkLogin()) {
            e.preventDefault(); // Mencegah aksi default (redirect atau scroll)
            showPopup("Peringatan", "Silakan login terlebih dahulu untuk mengakses fitur ini!");
        }
    });
});

// Panggil updateProfile saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    updateProfile();
});