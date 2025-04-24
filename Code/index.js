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

    salaSelect.disabled = true;
    dataSelect.disabled = true;
    orarioSelect.disabled = true;

    function aggiornaOpzioni(filmId) {
        if (filmId) {
            fetch(`get_options_by_film.php?film_id=${filmId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Risposta aggiornaOpzioni:', data);

                    salaSelect.innerHTML = '<option value="">Seleziona una sala</option>';
                    data.sale.forEach(sala => {
                        const option = document.createElement('option');
                        option.value = sala.numero;
                        option.textContent = `Sala ${sala.numero} (${sala.tipo})`;
                        salaSelect.appendChild(option);
                    });

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
    
            console.log('Data selezionata (formato italiano):', formattedDate);
            
            console.log('ID del film selezionato:', filmInput.dataset.id);
    
            fetch(`get_options_by_film_and_date.php?film_id=${filmInput.dataset.id}&data=${formattedDate}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Risposta orari:', data);
                    orarioSelect.disabled = false;
    
                    orarioSelect.innerHTML = '<option value="">Seleziona un orario</option>';
                    data.orari.forEach(orario => {
                        const option = document.createElement('option');
                        option.value = orario.ora;
                        option.textContent = orario.ora.substring(0, 5);
                        orarioSelect.appendChild(option);
                    });
    
                    salaSelect.innerHTML = '<option value="">Seleziona una sala</option>';
                    data.sale.forEach(sala => {
                        const option = document.createElement('option');
                        option.value = sala.numero;
                        option.textContent = `Sala ${sala.numero} (${sala.tipo})`;
                        salaSelect.appendChild(option);
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
            
            console.log(filmInput.dataset.id)
            console.log('Data selezionata (formato italiano):', formattedDate);
            console.log('Ora selezionata:', selectedTime);
            
            fetch(`get_options_by_film_and_date_and_time.php?film_id=${filmInput.dataset.id}&data=${formattedDate}&ora=${selectedTime}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Risposta sale:', data);
                    if (data.sale.length > 0) {
                        const salaInfo = data.sale[0];
                        console.log(`Sala disponibile: ${salaInfo.numero} - ${salaInfo.tipo}`);
    
                        const salaInput = document.getElementById('sala');
                        salaInput.value = `Sala ${salaInfo.numero} (${salaInfo.tipo})`;
                    } else {
                        console.log('Nessuna sala disponibile per questa combinazione');
                        const salaInput = document.getElementById('sala');
                        salaInput.value = '';
                    }
                })
                .catch(error => console.error('Errore nella richiesta per la sala:', error));
        }
    });
    
    

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
                    
                    suggestionItem.dataset.id = film.id;
                
                    suggestionsContainer.appendChild(suggestionItem);
                
                    suggestionItem.addEventListener('click', function () {
                        filmInput.value = film.titolo;
                        filmInput.dataset.id = film.id;
                
                        suggestionsContainer.style.display = 'none';
                        document.body.classList.remove('no-scroll');
                
                        aggiornaOpzioni(film.id); // Passa l'ID del film alla funzione
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






