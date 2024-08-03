<!DOCTYPE html>
<html lang="en">
<?php
            $user=$user?:$clients?:$client;
    var_dump($user->type);
 ?>;
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional FullCalendar Integration</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        #calendar {
            max-width: 1200px;
            height: auto;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            border: none;
            /* Remove border */
        }

        .fc-header-toolbar {
            background-color: #1a73e8;
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            border: none;
            /* Remove border */
        }

        .fc-button {
            background-color: #fff;
            color: #1a73e8;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            /* Adjust padding */
        }

        .fc-button:hover {
            background-color: #e8f0fe;
        }

        .fc-event {
            background-color: #1a73e8;
            color: #fff;
            border: none;
            /* Remove border */
        }
    </style>
</head>

<body>
    <div id="calendar"></div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof FullCalendar !== 'undefined') {
                console.log("FullCalendar is loaded");

                // Use the PHP variable $events here
                var events = <?= $events ?>;
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    minTime: '06:00:00',  // Plage horaire minimale affichée
                    maxTime: '24:00:00',  // Plage horaire maximale affichée
                    
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: events,
                    eventClick: function (info) {
                        showEventDetails(info.event);
                    }
                });

                calendar.render();
            } else {
                console.error("FullCalendar is not loaded");
            }
        });

        function showEventDetails(event) {
            let actionButton = '';
            let actionLabel = '';
            let actionFunction = null;
            var userType = <?= json_encode($user->type) ?>;
            console.log(userType);

            if (userType === 'professeur' && event.extendedProps.state === 'programmed') {
                actionLabel = 'Cancel';
                actionFunction = cancelSession;
            } else if (userType === 'etudiant') {
                const now = new Date();
                const eventStart = new Date(event.start);
                const eventEnd = new Date(eventStart.getTime() + 30 * 60000); // 30 minutes after start

                if (now >= eventStart && now <= eventEnd) {
                    actionLabel = 'Mark Presence';
                    actionFunction = markPresence;
                }
            }

            if (actionLabel && actionFunction) {
                actionButton = `<button id="action-button" class="swal2-confirm swal2-styled">${actionLabel}</button>`;
            }

            Swal.fire({
                title: event.title,
                html: `
                    <p><strong>Start:</strong> ${event.start.toLocaleString()}</p>
                    <p><strong>End:</strong> ${event.end ? event.end.toLocaleString() : 'N/A'}</p>
                    <p><strong>Status:</strong> ${event.extendedProps.state}</p>
                    ${actionButton}
                `,
                icon: 'info',
                showConfirmButton: false
            });

            if (actionFunction) {
                document.getElementById('action-button').addEventListener('click', function () {
                    actionFunction(event.id);
                });
            }
        }

        function cancelSession(sessionId) {
            fetch(`http://127.0.0.1:2000/session/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: sessionId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Session canceled',
                            text: 'The session has been successfully canceled.',
                            confirmButtonText: 'Close'
                        }).then(() => {
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Session canceled',
                            text: data.message || 'An error occurred while canceling the session.',
                            confirmButtonText: 'Close'
                        });
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Session canceled',
                        text: 'An error occurred while canceling the session.',
                        confirmButtonText: 'Close'
                    });
                });
        }

        function markPresence(sessionId) {
    fetch(`http://127.0.0.1:2000/savepresence`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: sessionId })
    })
        .then(response => response.json().catch(() => { 
            throw new Error('Invalid JSON response');
        }))
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Presence marked',
                    text: 'Your presence has been successfully marked.',
                    confirmButtonText: 'Close'
                }).then(() => {
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Presence not marked',
                    text: data.message || 'An error occurred while ing your presence.',
                    confirmButtonText: 'Close'
                });
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Presence not marked',
                text: error.message || 'An error occurred while markin your presence.',
                confirmButtonText: 'Close'
            });
        });
}
    </script>
</body>

</html>
