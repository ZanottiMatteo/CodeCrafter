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
        tab.addEventListener('click', () => showTab(tab.dataset.table));
    });

    document.body.addEventListener('click', function (e) {
        const deleteBtn = e.target.closest('.btn-delete');
        if (deleteBtn) {
            const table = encodeURIComponent(deleteBtn.dataset.table);
            const id = encodeURIComponent(deleteBtn.dataset.id);

            Swal.fire({
                title: 'Sei sicuro?',
                text: "Questa azione non può essere annullata!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sì, elimina!',
                cancelButtonText: 'Annulla'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`../utils/delete_row.php`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `table=${table}&id=${id}`
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Eliminato!', data.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Errore', data.message, 'error');
                            }
                        });
                }
            });

            return;
        }

        const editBtn = e.target.closest('.btn-edit');
        if (editBtn) {
            const table = encodeURIComponent(editBtn.dataset.table);
            const id = encodeURIComponent(editBtn.dataset.id);
            window.location.href = `../utils/edit_row.php?table=${table}&id=${id}`;
        }
    });
});
