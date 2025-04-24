document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('togglebtn').addEventListener('click', function () {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('expanded');
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const filmInput = document.getElementById('film');
    const suggestionsContainer = document.getElementById('film-suggestions');
    const salaSelect = document.getElementById('sala');
    const dataSelect = document.getElementById('data');
    const orarioSelect = document.getElementById('orario');

    if (!filmInput || !suggestionsContainer || !salaSelect || !dataSelect || !orarioSelect) {
        console.warn("Alcuni elementi del DOM non sono stati trovati!");
        return;
    }

    function aggiornaOpzioni(filmId) {
        if (filmId) {
            fetch(`get_options.php?film_id=${filmId}`)
                .then(response => response.json())
                .then(data => {
                    salaSelect.innerHTML = '<option value="">Seleziona una sala</option>';
                    data.sale.forEach(sala => {
                        const option = document.createElement('option');
                        option.value = sala.id;
                        option.textContent = `Sala ${sala.numero} (${sala.tipo})`;
                        salaSelect.appendChild(option);
                    });

                    dataSelect.innerHTML = '<option value="">Seleziona una data</option>';
                    data.date.forEach(d => {
                        const option = document.createElement('option');
                        option.value = d.data;
                        option.textContent = d.data;
                        dataSelect.appendChild(option);
                    });

                    orarioSelect.innerHTML = '<option value="">Seleziona un orario</option>';
                    data.orari.forEach(orario => {
                        const option = document.createElement('option');
                        option.value = orario.orario;
                        option.textContent = orario.orario;
                        orarioSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Errore nella richiesta per le opzioni:', error));
        }
    }

    filmInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length < 2) {
            suggestionsContainer.style.display = 'none';
            document.body.classList.remove('no-scroll');
            return;
        }

        fetch('search_film.php?term=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.style.display = 'block';
                document.body.classList.add('no-scroll');

                data.forEach(film => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.classList.add('film-suggestion');
                    suggestionItem.textContent = film.titolo;
                    suggestionsContainer.appendChild(suggestionItem);

                    suggestionItem.addEventListener('click', function () {
                        filmInput.value = film.titolo;
                        suggestionsContainer.style.display = 'none';
                        document.body.classList.remove('no-scroll');

                        aggiornaOpzioni(film.id);
                    });
                });
            })
            .catch(error => {
                console.error('Errore nella richiesta AJAX:', error);
                suggestionsContainer.style.display = 'none';
                document.body.classList.remove('no-scroll');
            });
    });

    document.addEventListener('click', function (event) {
        if (!filmInput.contains(event.target) && !suggestionsContainer.contains(event.target)) {
            suggestionsContainer.style.display = 'none';
            document.body.classList.remove('no-scroll');
        }
    });
});

