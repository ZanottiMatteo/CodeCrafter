document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.db-tab');
    const containers = document.querySelectorAll('.db-table-container');

    function showTab(tabName) {
        containers.forEach(c => {
            c.style.display = c.id === 'tab-' + tabName ? 'block' : 'none';
        });
        tabs.forEach(t => {
            t.classList.toggle('active', t.dataset.table === tabName);
        });
    }

    showTab('Biglietto');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            showTab(tab.dataset.table);
        });
    });
});
