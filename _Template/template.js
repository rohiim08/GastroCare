// Seleksi elemen nav-profile, tombol Login, ikon dropdown, dan dropdown
const navProfile = document.querySelector('.nav-profile');
const profileIcon = navProfile ? navProfile.querySelector('i') : null;
const loginBtn = document.getElementById('login-btn');
const profileDropdown = document.getElementById('profile-dropdown');
const logoutBtn = document.getElementById('logout-btn');
const chatbotToggler = document.querySelector("#chatbot-toggler");

// Elemen Mobile
const hamburger = document.querySelector('.hamburger');
const mobileMenu = document.querySelector('.mobile-menu');
const mobileProfileInfo = document.getElementById('mobile-profile-info');
const mobileProfileName = mobileProfileInfo.querySelector('span');
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
        window.location.href = '_Login/login.html';
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
        mobileProfileInfo.classList.add('logged-in');
        mobileProfileName.textContent = loggedInUser;
        loginBtnMobile.classList.add('logged-in');
        logoutBtnMobile.classList.add('logged-in');
    } else {
        // Update desktop/tablet view
        navProfile.classList.remove('logged-in');
        profileDropdown.classList.remove('active');
        
        // Update mobile view
        mobileProfileInfo.classList.remove('logged-in');
        loginBtnMobile.classList.remove('logged-in');
        logoutBtnMobile.classList.remove('logged-in');
    }
}

// Fungsi untuk memeriksa status login
function checkLogin() {
    const loggedInUser = sessionStorage.getItem('loggedInUser');
    return !!loggedInUser; // Mengembalikan true jika ada loggedInUser, false jika tidak
}

// Event listener untuk toggler chatbot
chatbotToggler.addEventListener("click", () => {
    if (!checkLogin()) {
        showPopup("Peringatan", "Silakan login terlebih dahulu untuk mengakses chatbot!");
    } else {
        document.body.classList.toggle("show-chatbot");
    }
});

// Panggil updateProfile saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    updateProfile();
});