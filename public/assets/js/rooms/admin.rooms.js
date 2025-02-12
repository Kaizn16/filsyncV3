let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const FETCH_ROOMS_SCHEDULES = route(`${authUserRole}.fetch.rooms.schedule`);
const academic_year_filter = document.getElementById('academic_year_filter');
const semester_filter = document.getElementById('semester_filter');
const weekdays_filter = document.getElementById('weekdays_filter');

function formatTime(time) {
    const [hour, minute] = time.split(':');
    const hours = parseInt(hour);
    const period = hours >= 12 ? 'PM' : 'AM';
    const formattedHour = hours > 12 ? hours - 12 : (hours === 0 ? 12 : hours);
    return `${formattedHour}:${minute} ${period}`;
}

async function fetchRoomsSchedule(academic_year, semester, weekdaysValues) {
    try {
        const response = await fetch(`${FETCH_ROOMS_SCHEDULES}?academic_year=${academic_year}&semester=${semester}&weekdays=${weekdaysValues}`);
        const data = await response.json();
        populateTable(data);
    } catch (error) {
        console.error('Error fetching draft schedules:', error);
    }
}

function populateTable(schedules) {
    const tableHead = document.querySelector('.room-utilization thead .heading');
    const tableBody = document.querySelector('.tableData');

    tableHead.innerHTML = '<th id="weekday-filter-text">DAY</th>';
    tableBody.innerHTML = '';

    const weekdayOptionText = weekdays_filter.options[weekdays_filter.selectedIndex]?.innerText || 'DAY';

    document.getElementById('weekday-filter-text').textContent = weekdayOptionText;

    const rooms = [...new Set(schedules.map(schedule => schedule.room.room_name))];

    
    const timeSlots = [
        '06:00:00', '06:30:00', '07:00:00', '07:30:00', '08:00:00', '08:30:00',
        '09:00:00', '09:30:00', '10:00:00', '10:30:00', '11:00:00', '11:30:00',
        '12:00:00', '12:30:00', '13:00:00', '13:30:00', '14:00:00', '14:30:00',
        '15:00:00', '15:30:00', '16:00:00', '16:30:00', '17:00:00', '17:30:00',
        '18:00:00', '18:30:00',
        '19:00:00',
    ];

    
    rooms.forEach(room => {
        const th = document.createElement('th');
        th.textContent = room;
        tableHead.appendChild(th);
    });

    
    let timeSlotIndex = 0;
    while (timeSlotIndex < timeSlots.length - 1) {  
        const row = document.createElement('tr');
        
    
        const timeCell = document.createElement('td');
        let timeDisplay = `${formatTime(timeSlots[timeSlotIndex])} - ${formatTime(timeSlots[timeSlotIndex + 1])}`;
        
        
        if (timeSlotIndex !== 12) {
            timeDisplay += `<br>${formatTime(timeSlots[timeSlotIndex + 1])} - ${formatTime(timeSlots[timeSlotIndex + 2])}`;
            timeDisplay += `<br>${formatTime(timeSlots[timeSlotIndex + 2])} - ${formatTime(timeSlots[timeSlotIndex + 3])}`;
        } else {
            timeDisplay += `<br>${formatTime(timeSlots[timeSlotIndex + 1])} - ${formatTime(timeSlots[timeSlotIndex + 2])}`;
        }
        
        timeCell.innerHTML = timeDisplay;
        row.appendChild(timeCell);

        rooms.forEach(room => {
            const cell = document.createElement('td');

            for (let i = 0; i < 3; i++) {
                const timeSlotStart = timeSlots[timeSlotIndex + i];
                const timeSlotEnd = timeSlots[timeSlotIndex + i + 1];

                const overlappingSchedules = schedules.filter(item => {
                    const scheduleStartTime = item.start_time;
                    const scheduleEndTime = item.end_time;

                    const overlaps = (scheduleStartTime < timeSlotEnd && scheduleEndTime > timeSlotStart && scheduleStartTime !== timeSlotStart);

                    return item.room.room_name === room && overlaps;
                });

                cell.innerHTML = '';

                overlappingSchedules.forEach(schedule => {
                    const lastName = schedule.user?.last_name || 'TBA';

                    const scheduleInfo = `
                        <div class="rooms-schedule-info">
                            <span class="edit editSchedule" title="EDIT" id="editSchedule" data-schedule_id="${schedule.schedule_id}">
                                <i class="material-icons icon">edit</i>
                            </span>
                            <p>${schedule.course_no.course_no} - ${schedule.schedule_draft.year_level.replace('ST YEAR', '')} ${schedule.schedule_draft.section.name}</p>
                            <p class="instructor">${lastName}</p>
                        </div>
                    `;
                    cell.innerHTML += scheduleInfo + "<br>";
                });

                if (overlappingSchedules.length === 0) {
                    cell.innerHTML += '<br>';
                }
            }

            row.appendChild(cell);
        });

        tableBody.appendChild(row);
        timeSlotIndex += (timeSlotIndex === 12 ? 2 : 3);
    }
}

fetchRoomsSchedule();

const academicYearText = document.getElementById('academic-year-text');
const semesterText = document.getElementById('semester-text');
const weekdayText = document.getElementById('weekday-filter-text');

function handleFiltersChange() {
    const academicYearValue = academic_year_filter.value;
    const semesterValue = semester_filter.value;
    const weekdaysValues = weekdays_filter.value;

    const semesterOptionText = semester_filter.options[semester_filter.selectedIndex]?.innerText;
    
    academicYearText.textContent = `A.Y ${academicYearValue}`;
    semesterText.textContent = semesterOptionText;

    fetchRoomsSchedule(academicYearValue, semesterValue, weekdaysValues);
}

semester_filter.addEventListener('change', handleFiltersChange);
weekdays_filter.addEventListener('change', handleFiltersChange)

handleFiltersChange();


document.addEventListener('click', async function(event) {
    const editButton = event.target.closest('.editSchedule');
    if (editButton) {
        const scheduleId = editButton.getAttribute('data-schedule_id');
        
        const EDIT_ROOM_SCHEDULE_ROUTE = route(`${authUserRole}.room.edit.schedule`);

        try {
            const response = await fetch(`${EDIT_ROOM_SCHEDULE_ROUTE}?schedule_id=${scheduleId}`);
            if (!response.ok) {
                throw new Error('Failed to fetch schedule data');
            }
            const scheduleData = await response.json();
            editForm(scheduleData);
        } catch (error) {
            showToast('error', error.message);
        }
    }
});

function editForm(scheduleData) {

    const SAVE_ROUTE = route(`${authUserRole}.room.update.schedule`);

    Swal.fire({
        html: `
             <section class="form-wrapper">
                <div class="form-container">
                    <header class="form-header">
                        <h1 class="title">Edit Room Schedule</h1>
                        <span class="close"><i class="material-icons icon">close</i></span>
                    </header>
                    <form class="form-content" method="POST" action="${SAVE_ROUTE}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="schedule_draft_id" value="${scheduleData.schedule_draft_id}">
                        <input type="hidden" name="schedule_id" value="${scheduleData.schedule_id}">
                        <header class="subject-info">
                           <strong>Course No.: ${scheduleData?.course_no.course_no}</strong>
                            <strong>Descriptive Title: ${scheduleData?.course_no.descriptive_title}</strong>
                            <p>Credits: ${scheduleData?.course_no.credits}</p>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <label for="rooms">Room <strong class="required">*</strong></label>
                                <select name="room" id="rooms"><option>Select Room</option></select>
                            </div>
                        </div>
                        <div class="form-group-col">
                            
                            <div class="form-group" style="width: 100%">
                                <label for="weekdays">Weekdays <strong class="required">*</strong></label>
                                <select style="width: 100%" name="weekdays[]" id="weekdays" multiple="multiple"></select>
                            </div>

                            <div class="form-group-row">
                                <div class="form-group">
                                    <label for="start_time">Start Time <strong class="required">*</strong></label>
                                    <input type="time" name="start_time" id="start_time" value="${scheduleData.start_time ?? ''}" />
                                </div>

                                <div class="form-group">
                                    <label for="end_time">End Time <strong class="required">*</strong></label>
                                    <input type="time" name="end_time" id="end_time" value="${scheduleData.end_time ?? ''}" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        `,
        confirmButtonText: 'SAVED',
        confirmButtonColor: '#5296BE',
        showCancelButton: true,
        cancelButtonColor: "#d33",
        cancelButtonText: 'CANCEL',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal-popup',
        },
        preConfirm: () => {
            
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.querySelector('.form-content');
            const formData = new FormData(form);

            fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const academicYearValue = academic_year_filter.value;
                    const semesterValue = semester_filter.value;
                    const weekdaysValues = weekdays_filter.value;
                    showToast('info', data.message);
                    fetchRoomsSchedule(academicYearValue, semesterValue, weekdaysValues);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });

    const roomsSelect = document.getElementById('rooms');
    ROOMS.forEach(room => {
        let option = document.createElement('option');
        option.value = room.room_id;

        if(scheduleData.room_id === room.room_id) {
            option.selected = true;
        }

        option.textContent = room.room_name;

        roomsSelect.appendChild(option);
    });

    if ($('#weekdays option').length === 0) {
        
        WEEKDAYS.forEach(weekday => {
            $('#weekdays').append(new Option(weekday, weekday));
        });
    }

    const weekdays = scheduleData.weekdays.map(weekday => weekday.day);

    $('#weekdays').select2({
        placeholder: "Select weekdays",
        allowClear: true,
        multiple: true
    }).val(weekdays).trigger('change');

    
    document.querySelector('.custom-swal-popup .close').addEventListener('click', () => {
        Swal.close();
    });

}

document.getElementById('printButton').addEventListener('click', function() {
    var table = document.querySelector('.table-wrapper');

    var contentToPrint = table.outerHTML;

    var printFrame = document.createElement('iframe');
    printFrame.style.display = 'none';
    document.body.appendChild(printFrame);
    var iframeDoc = printFrame.contentWindow.document;
    iframeDoc.open();
    iframeDoc.write('<html><head><title>Room Unitilization Schedule</title>');
    iframeDoc.write('<link rel="stylesheet" type="text/css" href="' + base_route + '">');
    iframeDoc.write('<link rel="stylesheet" type="text/css" href="' + MATERIAL_ICONS + '">');
    iframeDoc.write('</head><body>');
    iframeDoc.write(`<style>
        img {
            width: 120px;
            height: 120px;
        }
        </style>`);
    iframeDoc.write(contentToPrint);
    iframeDoc.write('</body></html>');
    iframeDoc.close();

    setTimeout(function() {
        iframeDoc.defaultView.print();
        document.body.removeChild(printFrame);
    }, 500);
});