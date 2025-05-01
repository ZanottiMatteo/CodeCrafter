document.addEventListener("DOMContentLoaded", function () {
    const filmInput = document.getElementById('film');
    const suggestionsContainer = document.getElementById('film-suggestions');
    const salaSelect = document.getElementById('sala');
    const dataSelect = document.getElementById('data');
    const orarioSelect = document.getElementById('orario');

    salaSelect.disabled = true;
    dataSelect.disabled = true;
    orarioSelect.disabled = true;

    function aggiornaOpzioni(filmId) {
        if (filmId) {
            fetch(`get_options_by_film.php?film_id=${filmId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Risposta aggiornaOpzioni:', data);

                    const formattedDates = data.date.map(d => {
                        const [day, month, year] = d.data.split('/');
                        return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
                    });

                    if (!dataSelect._flatpickr) {
                        flatpickr("#data", {
                            enable: formattedDates,
                            dateFormat: "Y-m-d",
                            locale: "it"
                        });
                    } else {
                        dataSelect._flatpickr.set("enable", formattedDates);
                    }

                    dataSelect.disabled = false;
                })
                .catch(error => console.error('Errore nella richiesta per le opzioni:', error));
        }
    }

    dataSelect.addEventListener('change', function () {
        const selectedDate = this.value;
        if (selectedDate) {
            const [year, month, day] = selectedDate.split('-');
            const formattedDate = `${day}/${month}/${year}`;

            fetch(`get_options_by_film_and_date.php?film_id=${filmInput.dataset.id}&data=${formattedDate}`)
                .then(response => response.json())
                .then(data => {
                    orarioSelect.disabled = false;

                    orarioSelect.innerHTML = '<option value="">Seleziona un orario</option>';
                    data.orari.forEach(orario => {
                        const option = document.createElement('option');
                        option.value = orario.ora;
                        option.textContent = orario.ora.substring(0, 5);
                        orarioSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Errore nella richiesta per le opzioni della data:', error));
        }
    });

    orarioSelect.addEventListener('change', function () {
        const selectedDate = dataSelect.value;
        const selectedTime = this.value;

        if (selectedDate && selectedTime) {
            const [year, month, day] = selectedDate.split('-');
            const formattedDate = `${day}/${month}/${year}`;

            fetch(`get_options_by_film_and_date_and_time.php?film_id=${filmInput.dataset.id}&data=${formattedDate}&ora=${selectedTime}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Risposta sale:', data);
                    if (data.sale.length > 0) {
                        const salaInfo = data.sale[0];

                        const salaInput = document.getElementById('sala');
                        let iconHtml = '';
                        if (salaInfo.tipo === '3-D') {
                            iconHtml = `<img src="https://cdn-icons-png.flaticon.com/128/83/83596.png" alt="3D Icon" class="sala-icon">`;
                            salaInput.innerHTML = `Sala ${salaInfo.numero} ${iconHtml}`;
                        } else if (salaInfo.tipo === 'tradizionale') {
                            iconHtml = `<img src="https://cdn-icons-png.flaticon.com/128/83/83467.png" alt="Traditional Icon" class="sala-icon">`;
                            salaInput.innerHTML = `Sala ${salaInfo.numero} ${iconHtml}`;
                        }
                    } else {
                        console.log('Nessuna sala disponibile per questa combinazione');
                        const salaInput = document.getElementById('sala');
                        salaInput.value = '';
                    }
                })
                .catch(error => console.error('Errore nella richiesta per la sala:', error));
        }
    });

    function mostraTuttiIFilm() {
        fetch('search_film.php?term=')
            .then(response => response.json())
            .then(renderSuggestions)
            .catch(error => {
                suggestionsContainer.style.display = 'none';
                document.body.classList.remove('no-scroll');
            });
    }

    function renderSuggestions(data) {
        suggestionsContainer.innerHTML = '';
        suggestionsContainer.style.display = 'block';
        document.body.classList.add('no-scroll');

        data.forEach(film => {
            const suggestionItem = document.createElement('div');
            suggestionItem.classList.add('film-suggestion');
            suggestionItem.innerHTML = `
                <img src="${film.immagine}" alt="${film.titolo}" class="film-thumbnail" />
                <span>${film.titolo}</span>
            `;

            suggestionItem.dataset.id = film.id;
            suggestionsContainer.appendChild(suggestionItem);

            suggestionItem.addEventListener('click', function () {
                filmInput.value = film.titolo;
                filmInput.dataset.id = film.id;

                dataSelect.value = '';
                dataSelect.disabled = true;
                if (dataSelect._flatpickr) {
                    dataSelect._flatpickr.clear();
                    dataSelect._flatpickr.set("enable", []);
                }

                orarioSelect.innerHTML = '<option value="">Seleziona un orario</option>';
                orarioSelect.disabled = true;

                salaSelect.innerHTML = '';
                salaSelect.disabled = true;

                suggestionsContainer.style.display = 'none';
                document.body.classList.remove('no-scroll');

                aggiornaOpzioni(film.id);
            });
        });
    }

    filmInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length === 0) {
            mostraTuttiIFilm();
        } else {
            fetch('search_film.php?term=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    renderSuggestions(data);
                })
                .catch(error => {
                    console.error('Errore nella richiesta AJAX:', error);
                    suggestionsContainer.style.display = 'none';
                    document.body.classList.remove('no-scroll');
                });
        }
    });

    filmInput.addEventListener('focus', function () {
        const query = filmInput.value;

        if (query === "") {
            mostraTuttiIFilm();
        }
    });

    document.addEventListener('click', function (event) {
        if (!filmInput.contains(event.target) && !suggestionsContainer.contains(event.target)) {
            suggestionsContainer.style.display = 'none';
            document.body.classList.remove('no-scroll');
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const carousel = document.querySelector(".movie-carousel");
    const prevBtn = document.querySelector(".carousel-btn.prev");
    const nextBtn = document.querySelector(".carousel-btn.next");
    const scrollAmt = 320;

    prevBtn.addEventListener("click", () => {
        carousel.scrollBy({ left: -scrollAmt, behavior: "smooth" });
    });
    nextBtn.addEventListener("click", () => {
        carousel.scrollBy({ left: scrollAmt, behavior: "smooth" });
    });

    const modal = document.getElementById("movieModal");
    const modalBody = document.getElementById("modalBody");
    const modalClose = document.getElementById("modalClose");

    carousel.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-details");
        if (!btn) return;
        e.preventDefault();

        const filmId = btn.dataset.filmId;
        const filmDate = btn.dataset.filmDate;
        if (!filmId || !filmDate) return;

        fetch(`movie_details.php?film_id=${encodeURIComponent(filmId)}&film_date=${encodeURIComponent(filmDate)}`)
            .then(res => {
                if (!res.ok) throw new Error("Network error");
                return res.text();
            })
            .then(html => {
                modalBody.innerHTML = html;
                document.body.classList.add("no-scroll");
                modal.style.display = "flex";
            })
            .catch(() => {
                modalBody.innerHTML = "<p>Errore nel recuperare i dettagli.</p>";
                document.body.classList.add("no-scroll");
                modal.style.display = "flex";
            });
    });

    function closeModal() {
        modal.style.display = "none";
        document.body.classList.remove("no-scroll");
    }
    modalClose.addEventListener("click", closeModal);
    modal.addEventListener("click", e => {
        if (e.target === modal) closeModal();
    });

    const tooltip = document.getElementById("salaTooltip");

    modalBody.addEventListener("mouseover", e => {
        const item = e.target.closest(".showtime-item");
        if (!item) return;

        const d = item.dataset;
        tooltip.innerHTML = `
        <strong>Sala ${d.numero} – ${d.tipo}</strong><br>
        Dimensioni schermo: ${d.dim}"<br>
        Posti totali: ${d.posti}<br>
        File: ${d.file}<br>
        Posti per fila: ${d.postiFila}
      `;
        const rect = item.getBoundingClientRect();
        tooltip.style.top = (rect.top - tooltip.offsetHeight - 8) + "px";
        tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + "px";
        tooltip.style.display = "block";
    });

    modalBody.addEventListener("mouseout", e => {
        if (e.target.closest(".showtime-item")) {
            tooltip.style.display = "none";
        }
    });
});












