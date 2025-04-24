document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('togglebtn').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('expanded');
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const filmInput = document.getElementById('film');
    const suggestionsContainer = document.getElementById('film-suggestions');

    if (!filmInput || !suggestionsContainer) {
        console.warn("Elemento #film o #film-suggestions non trovato!");
        return;
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

                data.forEach(titolo => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.classList.add('film-suggestion');
                    suggestionItem.textContent = titolo;
                    suggestionsContainer.appendChild(suggestionItem);

                    suggestionItem.addEventListener('click', function () {
                        filmInput.value = titolo;
                        suggestionsContainer.style.display = 'none';
                        document.body.classList.remove('no-scroll');
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
