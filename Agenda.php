<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
   
     <style>
.calendar {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    overflow: hidden;
    max-width: 100%;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.calendar-weekdays {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem;
    background-color: #e9ecef;
}

.calendar-weekdays div {
    width: 100%;
    text-align: center;
}

.calendar-days {
    display: flex;
    flex-wrap: wrap;
}

.calendar-days div {
    width: 14.28%;
    text-align: center;
    padding: 0.75rem;
    box-sizing: border-box;
    cursor: pointer;
}

.calendar-days div:hover {
    background-color: #e9ecef;
}

.calendar-days div.event {
    background-color: #007bff;
    color: #fff;
    border-radius: 0.25rem;
}

     </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4 text-center">Agenda</h1>

        <div class="row mb-4">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Buscar eventos" id="search">
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">Añadir Evento</button>
            </div>
        </div>

        <div class="calendar">
            <div class="calendar-header">
                <button class="btn btn-light" id="prevMonth">&lt;</button>
                <h2 id="currentMonth">Agosto 2024</h2>
                <button class="btn btn-light" id="nextMonth">&gt;</button>
            </div>

            <div class="calendar-weekdays">
                <div>Dom</div>
                <div>Lun</div>
                <div>Mar</div>
                <div>Mié</div>
                <div>Jue</div>
                <div>Vie</div>
                <div>Sab</div>
            </div>

            <div class="calendar-days">
            </div>
        </div>
    </div>

    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Añadir Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Título</label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="eventDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Descripción</label>
                            <textarea class="form-control" id="eventDescription"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const currentMonthElem = document.getElementById('currentMonth');
    const calendarDaysElem = document.querySelector('.calendar-days');

    function loadCalendar(month, year) {
        calendarDaysElem.innerHTML = '';

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);

        for (let i = 0; i < firstDay.getDay(); i++) {
            calendarDaysElem.innerHTML += '<div></div>'; 
        }

        for (let i = 1; i <= lastDay.getDate(); i++) {
            calendarDaysElem.innerHTML += `<div>${i}</div>`;
        }
    }

    function updateMonth(delta) {
        const [month, year] = currentMonthElem.textContent.split(' ');
        const monthIndex = new Date(Date.parse(month +" 1, 2012")).getMonth();
        const newDate = new Date(year, monthIndex + delta, 1);
        loadCalendar(newDate.getMonth(), newDate.getFullYear());
        currentMonthElem.textContent = `${newDate.toLocaleString('default', { month: 'long' })} ${newDate.getFullYear()}`;
    }

    document.getElementById('prevMonth').addEventListener('click', function () {
        updateMonth(-1);
    });

    document.getElementById('nextMonth').addEventListener('click', function () {
        updateMonth(1);
    });

    loadCalendar(new Date().getMonth(), new Date().getFullYear());
});

    </script>
</body>
</html>
