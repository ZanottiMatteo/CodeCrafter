document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('togglebtn');
    const sidebar = document.getElementById('sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('expanded');
            toggleBtn.classList.toggle('active');
            console.log('Stato sidebar:', sidebar.classList.contains('expanded'));
        });
    } else {
        console.error('Elementi non trovati! Verifica:');
        console.log('Toggle Button:', toggleBtn);
        console.log('Sidebar:', sidebar);
    }

    const path = window.location.pathname.split('/').pop();

    document.querySelectorAll('#sidebar nav ul li a')
        .forEach(a => {
            const href = a.getAttribute('href').split('/').pop();
            if (href === path) {
                a.parentElement.classList.add('active');
            }
        });
});

document.addEventListener('DOMContentLoaded', () => {
    const bigliettiLink = document.getElementById('biglietti-link');
    const searchBtn = document.querySelector('.btn-search');
    const bookBtns = document.querySelectorAll('.btn-book');
    const ticketBtns = document.querySelectorAll('.ticket-btn');

    if (localStorage.getItem('bigliettiAttivo') === 'true' && bigliettiLink) {
        bigliettiLink.classList.remove('disabled-link');
    }

    function attivaLinkBiglietti() {
        if (bigliettiLink) {
            bigliettiLink.classList.remove('disabled-link');
            localStorage.setItem('bigliettiAttivo', 'true');
        }
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', attivaLinkBiglietti);
    }

    bookBtns.forEach(btn => {
        btn.addEventListener('click', attivaLinkBiglietti);
    });

    ticketBtns.forEach(btn => {
        btn.addEventListener('click', attivaLinkBiglietti);
    });
});




