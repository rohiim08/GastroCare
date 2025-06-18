document.addEventListener('DOMContentLoaded', () => {
    const scrollRevealOption = {
        distance: "50px",
        origin: "bottom",
        duration: 1000,
        easing: 'ease-in-out',
        reset: false 
    };

    ScrollReveal().reveal(".hero-subtitle", { ...scrollRevealOption, delay: 200 });
    ScrollReveal().reveal(".hero-title", { ...scrollRevealOption, delay: 400 });

    ScrollReveal().reveal(".penyakit-lambung h1", { ...scrollRevealOption, origin: 'top' });
    ScrollReveal().reveal(".penyakit-lambung p", { ...scrollRevealOption, delay: 200 });

    ScrollReveal().reveal(".jenis-penyakit h2", { ...scrollRevealOption, origin: 'top' });
    ScrollReveal().reveal(".card-penyakit", { ...scrollRevealOption, interval: 200 });

    ScrollReveal().reveal(".info-card", { ...scrollRevealOption, interval: 200 });

    ScrollReveal().reveal(".mitos-fakta h2", { ...scrollRevealOption, origin: 'top' });
    ScrollReveal().reveal(".item-mitos-fakta", { ...scrollRevealOption, interval: 200 });

    ScrollReveal().reveal(".footer-content-logo", { ...scrollRevealOption, delay: 200 });
    ScrollReveal().reveal(".footer-content-title", { ...scrollRevealOption, delay: 300 });
    ScrollReveal().reveal(".footer-content-desc", { ...scrollRevealOption, delay: 400 });
});