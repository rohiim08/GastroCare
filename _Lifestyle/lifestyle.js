(function() {
    // Global variables
    let makananData = {};
    let stressTips = [];
    let currentCategory = "sayur";
    let tampilSemua = false;
    let menus = [];
    let idCounter = 1;
    let lastNotified = {};

    // Jadwal notifikasi
    const jadwalMakan = [
        { waktu: "07:00", pesan: "Saatnya sarapan pagi!", timeOfDay: "pagi" },
        { waktu: "11:28", pesan: "Saatnya makan siang!", timeOfDay: "siang" },
        { waktu: "18:00", pesan: "Saatnya makan malam!", timeOfDay: "malam" }
    ];

    const days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];

    // Function to load makanan data from database
    async function loadMakananData() {
        try {
            const response = await fetch('get_makanan.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();
            console.log("Data makanan berhasil dimuat:", data);
            
            makananData = data;
            
            // Transform data untuk menu system
            menus = [];
            idCounter = 1;
            
            for (const category in data) {
                data[category].forEach(menu => {
                    menus.push({
                        id: menu.id,
                        name: menu.nama,
                        kategori: menu.kategori
                    });
                });
            }
            
            // Initialize the food display
            renderMakanan();
            initializeScheduleDropdowns();
            
        } catch (error) {
            console.error('Error loading makanan data:', error);
            // Fallback message
            document.getElementById("makanan-list").innerHTML = 
                '<p>Error loading data. Please check your database connection.</p>';
        }
    }

    // Function to load stress management data from database
    async function loadStresData() {
        try {
            const response = await fetch('get_manajemen_stres.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();
            console.log("Data manajemen stres berhasil dimuat:", data);
            
            stressTips = data;
            initializeStressButtons();
            
        } catch (error) {
            console.error('Error loading stress management data:', error);
        }
    }

    // Render makanan function
    function renderMakanan() {
        const list = document.getElementById("makanan-list");
        if (!list) return;
        
        list.innerHTML = "";

        if (!makananData[currentCategory]) {
            list.innerHTML = '<p>Data tidak tersedia untuk kategori ini.</p>';
            return;
        }

        const jumlahTampil = tampilSemua ? makananData[currentCategory].length : 6;
        const data = makananData[currentCategory].slice(0, jumlahTampil);

        data.forEach(item => {
            const card = document.createElement("div");
            card.className = "makanan-card";
            card.innerHTML = `
                <img src="${item.img || 'https://via.placeholder.com/150'}" alt="${item.nama}">
                <h3>${item.nama}</h3>
                <p>${item.khasiat}</p>
            `;
            list.appendChild(card);
        });

        const seeAllButton = document.getElementById("see-all");
        if (seeAllButton) {
            seeAllButton.textContent = tampilSemua ? "Sembunyikan" : "Tampilkan Lebih Banyak";
        }
    }

    // Filter makanan function
    function filterMakanan(kategori) {
        currentCategory = kategori;
        tampilSemua = false;
        renderMakanan();

        const kategoriButtons = document.querySelectorAll('.kategori span');
        kategoriButtons.forEach(button => button.classList.remove('active'));
        const activeButton = document.querySelector(`.kategori span[onclick="filterMakanan('${kategori}')"]`);
        if (activeButton) {
            activeButton.classList.add('active');
        }
    }
    window.filterMakanan = filterMakanan;

    // Toggle makanan function
    function toggleMakanan() {
        tampilSemua = !tampilSemua;
        renderMakanan();
        if (!tampilSemua) {
            const makananSehatSection = document.querySelector('.makanan-sehat');
            if (makananSehatSection) {
                makananSehatSection.scrollIntoView({ behavior: "smooth" });
            }
        }
    }
    window.toggleMakanan = toggleMakanan;

    // Initialize schedule dropdowns
    function initializeScheduleDropdowns() {
        // Function to determine weight based on id (you may need to adjust this based on your actual data)
        function getBobot(id) {
            // Simple logic - you can customize this based on your needs
            if (id <= 4) return 1;
            if (id <= 8) return 2;
            return 3;
        }

        // Add weight to menus
        const menusWithBobot = menus.map(menu => ({
            ...menu,
            bobot: getBobot(menu.id)
        }));

        // Initialize dropdowns
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach((row, rowIndex) => {
            const day = days[rowIndex];
            const dropdowns = row.querySelectorAll('select');
            dropdowns.forEach((dropdown, dropdownIndex) => {
                let timeOfDay;
                if (dropdownIndex === 0) timeOfDay = "pagi";
                else if (dropdownIndex === 1) timeOfDay = "siang";
                else timeOfDay = "malam";

                dropdown.id = `${day.toLowerCase()}-${timeOfDay}`;
                dropdown.setAttribute("data-day", day);
                dropdown.setAttribute("data-time", timeOfDay);

                dropdown.innerHTML = '<option value="">-- Pilih Makanan --</option>';
                menusWithBobot.forEach(menu => {
                    const option = document.createElement('option');
                    option.value = menu.id;
                    option.textContent = `${menu.name} (${menu.kategori})`;
                    dropdown.appendChild(option);
                });
            });
        });

        // Add event listeners
        document.querySelectorAll('select').forEach(dropdown => {
            dropdown.addEventListener('change', (event) => {
                const id = event.target.id;
                const value = event.target.value;
                saveToSessionStorage(id, value);
                // Additional logic for dropdown dependencies can be added here
            });
        });

        loadStoredData();
        mulaiPemeriksaanNotifikasi();
    }

    // Initialize stress management buttons
    function initializeStressButtons() {
        const stressButtons = document.querySelectorAll('.stress-button');
        const popup = document.querySelector('.popup');
        const overlay = document.querySelector('.overlay');
        const closePopup = document.querySelector('.close-popup');

        if (!popup || !overlay || !closePopup) return;

        stressButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                if (stressTips[index]) {
                    const tip = stressTips[index];
                    const videoId = getYouTubeId(tip.video);

                    if (videoId) {
                        const thumbnailUrl = `https://img.youtube.com/vi/${videoId}/0.jpg`;
                        popup.querySelector('.popup-content').innerHTML = `
                            <h3>${tip.title}</h3>
                            <div class="video-container">
                                <img class="video-thumbnail" src="${thumbnailUrl}" alt="${tip.title}" data-video-id="${videoId}">
                            </div>
                            <a href="${tip.video}" target="_blank">Buka di YouTube</a>
                        `;
                        popup.classList.add('active');
                        overlay.classList.add('active');

                        const thumbnail = popup.querySelector('.video-thumbnail');
                        thumbnail.addEventListener('click', () => {
                            const videoContainer = popup.querySelector('.video-container');
                            videoContainer.innerHTML = `
                                <iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1" 
                                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen></iframe>
                            `;
                        });
                    } else {
                        popup.querySelector('.popup-content').innerHTML = `
                            <h3>${tip.title}</h3>
                            <p>Video tidak tersedia. Silakan cek URL video.</p>
                            <a href="${tip.video}" target="_blank">Buka di YouTube</a>
                        `;
                        popup.classList.add('active');
                        overlay.classList.add('active');
                    }
                }
            });
        });

        closePopup.addEventListener('click', () => {
            popup.classList.remove('active');
            overlay.classList.remove('active');
        });

        overlay.addEventListener('click', () => {
            popup.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    // Utility functions
    function getYouTubeId(url) {
        const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
        const match = url.match(regex);
        return match ? match[1] : null;
    }

    function saveToSessionStorage(id, value) {
        let storedData = JSON.parse(sessionStorage.getItem('mealSchedule')) || {};
        storedData[id] = value;
        sessionStorage.setItem('mealSchedule', JSON.stringify(storedData));
        console.log(`Data disimpan: ${id} = ${value}`);
    }

    function loadStoredData() {
        let storedData = JSON.parse(sessionStorage.getItem('mealSchedule')) || {};
        for (let id in storedData) {
            const dropdown = document.getElementById(id);
            if (dropdown) {
                dropdown.value = storedData[id];
                console.log(`Data dimuat: ${id} = ${storedData[id]}`);
            }
        }
    }

    function getSelectedMenu(day, timeOfDay) {
        const dropdown = document.querySelector(`select[data-day="${day}"][data-time="${timeOfDay}"]`);
        if (!dropdown) {
            console.warn(`Dropdown tidak ditemukan untuk ${day} ${timeOfDay}`);
            return "Dropdown tidak ditemukan";
        }
        if (dropdown.value) {
            const selectedId = parseInt(dropdown.value);
            const selectedMenu = menus.find(menu => menu.id === selectedId);
            if (selectedMenu) {
                console.log(`Menu terpilih untuk ${day} ${timeOfDay}: ${selectedMenu.name}`);
                return selectedMenu.name;
            } else {
                console.warn(`Menu dengan ID ${selectedId} tidak ditemukan di menus`);
                return "Menu tidak valid";
            }
        }
        console.log(`Tidak ada menu terpilih untuk ${day} ${timeOfDay}`);
        return "Belum memilih menu";
    }

    function tampilkanNotifikasi(pesan, timeOfDay) {
        const today = new Date();
        const dayIndex = today.getDay();
        const currentDay = days[dayIndex === 0 ? 6 : dayIndex - 1];

        const selectedMenu = getSelectedMenu(currentDay, timeOfDay);
        const fullMessage = `${pesan} Menu: ${selectedMenu}`;

        if (!("Notification" in window)) {
            console.warn("Browser tidak mendukung notifikasi. Menggunakan alert sebagai fallback.");
            alert(fullMessage);
            return;
        }

        if (Notification.permission === "granted") {
            new Notification("Pengingat Makan", { body: fullMessage });
            console.log(`Notifikasi ditampilkan: ${fullMessage}`);
        } else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    new Notification("Pengingat Makan", { body: fullMessage });
                    console.log(`Notifikasi ditampilkan setelah izin diberikan: ${fullMessage}`);
                } else {
                    console.warn("Izin notifikasi ditolak. Menggunakan alert sebagai fallback.");
                    alert(fullMessage);
                }
            });
        } else {
            console.warn("Notifikasi diblokir oleh pengguna. Menggunakan alert sebagai fallback.");
            alert(fullMessage);
        }
    }

    function cekWaktuMakan() {
        const sekarang = new Date();
        const jamSekarang = sekarang.getHours();
        const menitSekarang = sekarang.getMinutes();
        const tanggalSekarang = sekarang.toISOString().split('T')[0];

        jadwalMakan.forEach(jadwal => {
            const [jadwalJam, jadwalMenit] = jadwal.waktu.split(':').map(Number);
            const menitSekarangTotal = jamSekarang * 60 + menitSekarang;
            const menitJadwalTotal = jadwalJam * 60 + jadwalMenit;

            if (Math.abs(menitSekarangTotal - menitJadwalTotal) <= 1) {
                const notifKey = `${tanggalSekarang}-${jadwal.waktu}`;
                if (!lastNotified[notifKey]) {
                    tampilkanNotifikasi(jadwal.pesan, jadwal.timeOfDay);
                    lastNotified[notifKey] = true;
                    console.log(`Notifikasi dijadwalkan untuk ${jadwal.waktu}: ${jadwal.pesan}`);
                }
            }
        });

        Object.keys(lastNotified).forEach(key => {
            const date = key.split('-').slice(0, 3).join('-');
            if (date !== tanggalSekarang) {
                delete lastNotified[key];
            }
        });
    }

    function mulaiPemeriksaanNotifikasi() {
        cekWaktuMakan();
        setInterval(cekWaktuMakan, 60 * 1000);
    }

    function handleScroll() {
        const sections = document.querySelectorAll('.makanan-sehat, .container, .wrapper, .footer-content');
        sections.forEach(section => {
            const rect = section.getBoundingClientRect();
            if (rect.top <= window.innerHeight * 0.8) {
                section.classList.add('visible');
            }
        });
    }

    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        loadMakananData();
        loadStresData();
        handleScroll();
    });

    window.addEventListener('scroll', handleScroll);
})();