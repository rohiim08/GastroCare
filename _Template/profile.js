// Script to update profile UI based on sessionStorage
function updateProfile() {
  const username = sessionStorage.getItem('loggedInUser');
  const navProfile = document.querySelector('.nav-profile');
  
  if (navProfile) {
    const span = navProfile.querySelector('span');
    const loginBtn = navProfile.querySelector('#login-btn');
    const dropdown = navProfile.querySelector('#profile-dropdown');
    const profileImg = navProfile.querySelector('img');
    const caret = navProfile.querySelector('.bx-caret-down');

    // Pastikan elemen profile (img, span, caret) selalu tampil
    if (profileImg) profileImg.style.display = 'inline-block';
    if (span) span.style.display = 'inline-block';
    if (caret) caret.style.display = 'inline-block';
    if (username) {
      if (span) span.textContent = username;
      if (loginBtn) loginBtn.style.display = 'none';
      if (dropdown) dropdown.style.display = 'none';
    } else {
      if (span) span.textContent = 'Nama User';
      if (loginBtn) loginBtn.style.display = 'block';
      if (dropdown) dropdown.style.display = 'none';
    }
  }
  // Mobile
  const mobileProfile = document.getElementById('mobile-profile-info');
  const loginBtnMobile = document.getElementById('login-btn-mobile');
  const logoutBtnMobile = document.getElementById('logout-btn-mobile');
  if (mobileProfile) {
    const span = mobileProfile.querySelector('span');
    if (span) span.style.display = 'inline-block';
    if (username) {
      span.textContent = username;
      if (loginBtnMobile) loginBtnMobile.style.display = 'none';
      if (logoutBtnMobile) logoutBtnMobile.style.display = 'block';
    } else {
      span.textContent = 'Nama User';
      if (loginBtnMobile) loginBtnMobile.style.display = 'block';
      if (logoutBtnMobile) logoutBtnMobile.style.display = 'none';
    }
  }
}

// Event listener untuk segitiga dropdown profile
// dan klik di luar dropdown untuk menutupnya
document.addEventListener('DOMContentLoaded', function() {
  const caret = document.querySelector('.nav-profile .bx-caret-down');
  const dropdown = document.getElementById('profile-dropdown');
  if (caret && dropdown) {
    caret.addEventListener('click', function(e) {
      e.stopPropagation();
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
    // Sembunyikan dropdown jika klik di luar
    document.addEventListener('click', function() {
      dropdown.style.display = 'none';
    });
    dropdown.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  }
});
