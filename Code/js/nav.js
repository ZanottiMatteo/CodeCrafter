document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('togglebtn');
    const sidebar = document.getElementById('sidebar');

    if(toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('expanded');
            
            // Animazione aggiuntiva
            toggleBtn.classList.toggle('active');
            console.log('Stato sidebar:', sidebar.classList.contains('expanded'));
        });
    } else {
        console.error('Elementi non trovati! Verifica:');
        console.log('Toggle Button:', toggleBtn);
        console.log('Sidebar:', sidebar);
    }
});