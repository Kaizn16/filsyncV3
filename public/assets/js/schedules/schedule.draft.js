const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const schedule_draft_id = document.getElementById('schedule_draft_id').value;
const course_id = document.getElementById('course_id').value;
const academic_year = document.getElementById('academic_year').value;
const semester = document.getElementById('semester').value;
const year_level = document.getElementById('year_level').value;
const section = document.getElementById('section').value;
const FETCH_SUBJECTS_ROUTE = route(`${authUserRole}.fetch.subjects`);

let debounceTimer;

async function fetchSubjects(course_id, search, semester, year_level, section) {
    const url = `${FETCH_SUBJECTS_ROUTE}?course_id=${course_id}&search=${encodeURIComponent(search)}&year_level=${year_level}&section=${section}&semester=${semester}`;
    
    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const { data } = await response.json();
        populateListItems(data);
    } catch (error) {
        console.error('Error fetching subjects:', error);
    }
}

function populateListItems(subjects) {
    const subjectListContainer = document.querySelector('.subject-lists');
    subjectListContainer.innerHTML = '';

    if (!subjects.length) {
        subjectListContainer.innerHTML = '<p class="no-results">No subjects found.</p>';
        return;
    }

    const fragment = document.createDocumentFragment();

    subjects.forEach(subject => {
        const listItem = document.createElement('li');
        listItem.className = 'subject-item';
        listItem.innerHTML = `
            <div class="front">
                <strong>Course No: ${subject.course_no || 'N/A'}</strong>
                <strong>Descriptive Title: ${subject.descriptive_title || 'N/A'}</strong>
                <p>Credit/Units: ${subject.credits || '0'}</p>
            </div>
            <div class="back">
                <span><i class="material-icons icon">add</i></span>
            </div>
        `;
        listItem.addEventListener('click', () => subjectScheduleModal('create', subject, {}));
        fragment.appendChild(listItem);
    });

    subjectListContainer.appendChild(fragment);
}

const searchInput = document.getElementById('search');
searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        fetchSubjects(course_id, searchInput.value, semester, year_level, section);
    }, 600);
});

fetchSubjects(course_id, searchInput.value, semester, year_level, section);

async function fetchSchedulesByDraft() {
    const FETCH_DRAFT_SCHEDULES = route('fetch.draftschedules');

    try {
        const response = await fetch(`${FETCH_DRAFT_SCHEDULES}?schedule_draft_id=${schedule_draft_id}`);
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const data = await response.json();
        populateTable(data);
    } catch (error) {
        console.error('Error fetching draft schedules:', error);
    }
}

function populateTable(schedulesData) {
    const tableBody = document.querySelector('.tableData');
    tableBody.innerHTML = `
        <tr class="extra-header">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Days</td>
            <td>Time</td>
            <td>Room</td>
            <td></td>
            <td></td>
        </tr>
    `;

    if (!schedulesData.length) {
        tableBody.innerHTML += `<tr><td colspan="9" class="no-results">No schedules available.</td></tr>`;
        return;
    }

    const fragment = document.createDocumentFragment();

    schedulesData.forEach((record) => {
        const { course_no, descriptive_title, credits, teacher, schedules } = record;

        schedules.forEach((schedule, index) => {
            const row = document.createElement('tr');
            row.setAttribute("data-course-id", `${course_no}-${record.course_no_id}`);

            if (index === 0) {
                row.innerHTML += `
                    <td rowspan="${schedules.length}"></td>
                    <td rowspan="${schedules.length}">${course_no}</td>
                    <td rowspan="${schedules.length}">${descriptive_title || ''}</td>
                    <td rowspan="${schedules.length}">${credits || '0'}</td>
                `;
            }

            const scheduleData = {
                schedule_id: schedule.schedule_id,
                course_no_id: record.course_no_id,
                course_no: course_no,
                descriptive_title: descriptive_title,
                credits: credits,
                weekdays: schedule.weekdays,
                start_time: schedule.start_time,
                end_time: schedule.end_time,
                room_id: schedule.room_id,
                user_id: record.user_id,
                schedule_draft_id: schedule.schedule_draft_id,
            };

            row.innerHTML += `
                <td>${schedule.weekdays.map(day => day.slice(0, 3).toUpperCase()).join('-')}</td>
                <td>${formatTime(schedule.start_time)} - ${formatTime(schedule.end_time)}</td>
                <td>${schedule.room || 'TBA'}</td>
                ${index === 0 ? `<td rowspan="${schedules.length}">${teacher.toUpperCase()}</td>` : ''}
                <td>
                    <div class="actions">
                        <span class="action edit" data-scheduleData='${JSON.stringify(scheduleData)}' onclick="event.stopPropagation(); editSelectedTableData(this);">
                            <i class="material-icons icon" title="Edit">edit_square</i>
                        </span>
                         <span class="action delete" data-schedule_id="${schedule.schedule_id}" onclick="event.stopPropagation(); deleteSelectedTableData(this);">
                            <i class="material-icons icon" title="Delete">delete</i>
                        </span>
                    </div>
                </td>
            `;

            fragment.appendChild(row);
        });
    });

    tableBody.appendChild(fragment);
}


function formatTime(time) {
    if (!time) return 'N/A';
    const [hour, minute] = time.split(':').map(Number);
    const formattedHour = hour % 12 || 12;
    const period = hour >= 12 ? 'PM' : 'AM';
    return `${formattedHour}:${minute.toString().padStart(2, '0')} ${period}`;
}


// Initial fetch of schedules
fetchSchedulesByDraft();


document.getElementById('cancel-button').addEventListener('click', function (event) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will cancel the schedule draft!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'YES',
        confirmButtonColor: '#5296BE',
        cancelButtonColor: "#d33",
        cancelButtonText: 'CANCEL',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            content: 'custom-swal-text',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancel-schedule-form').submit();
        }
    });
});

function subjectScheduleModal(mode, subject, scheduleData = {}) {
    let isEditMode = mode === 'edit';
    let modalTitle = isEditMode ? 'Edit' : 'Create';
    let buttonText = isEditMode ? 'SAVE' : 'ADD';

    const SAVE_SCHEDULE_ROUTE = isEditMode ? route(`${authUserRole}.schedule.update`) : route(`${authUserRole}.schedule.store`);

    Swal.fire({
        html: `
             <section class="form-wrapper">
                <div class="form-container">
                    <header class="form-header">
                        <h1 class="title">${modalTitle}</h1>
                        <span class="close"><i class="material-icons icon">close</i></span>
                    </header>
                    <form class="form-content" method="POST" action="${SAVE_SCHEDULE_ROUTE}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="${isEditMode ? 'PUT' : 'POST'}">
                        <input type="hidden" name="schedule_id" value="${scheduleData.schedule_id ?? ''}">
                        <input type="hidden" name="schedule_draft_id" value="${scheduleData.schedule_draft_id ?? schedule_draft_id}">
                        <input type="hidden" name="course_no_id" value="${scheduleData.course_no_id ?? subject.course_no_id}">
                        <header class="subject-info">
                           <strong>Course No.: ${scheduleData?.course_no ?? subject.course_no}</strong>
                            <strong>Descriptive Title: ${scheduleData?.descriptive_title ?? subject.descriptive_title}</strong>
                            <p>Credits: ${scheduleData?.credits ?? subject.credits}</p>
                        </header>
                        <div class="form-group-row">
                            <div class="form-group">
                                <label for="teachers">Teacher <strong class="required">*</strong></label>
                                <select name="teacher" id="teachers">
                                    <option value="">TBA</option>
                                </select>
                            </div>
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
        confirmButtonText: buttonText,
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
                    showToast('info', data.message);
                    fetchSchedulesByDraft();
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });

    const teachersSelect = document.getElementById('teachers');
    TEACHERS.forEach(teacher => {
        let option = document.createElement('option');
        option.value = teacher.user_id;

        if (scheduleData.user_id === teacher.user_id) {
            option.selected = true;
        }

        let displayName = teacher.middle_name ? 
            `${teacher.first_name} ${teacher.middle_name.charAt(0)} ${teacher.last_name}` :
            `${teacher.first_name} ${teacher.last_name}`;

        option.textContent = `${displayName} (${teacher.position})`;

        teachersSelect.appendChild(option);
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

    $('#weekdays').select2({
        placeholder: "Select weekdays",
        allowClear: true,
        multiple: true
    }).val(scheduleData.weekdays).trigger('change');

    
    document.querySelector('.custom-swal-popup .close').addEventListener('click', () => {
        Swal.close();
    });
}


function editSelectedTableData(scheduleDataButton) {
    const scheduleData = JSON.parse(scheduleDataButton.getAttribute('data-scheduleData'));
    subjectScheduleModal('edit', {}, scheduleData);
}


function deleteSelectedTableData(schedule_id) {
    const scheduleId = JSON.parse(schedule_id.getAttribute('data-schedule_id'));
    
    const DELETE_SCHEDULE_ROUTE = route(`${authUserRole}.delete.schedule`);

    Swal.fire({
        title: 'Are you sure?',
        html: `<strong class="info">Are you sure you want to delete this schedule?</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'YES',
        confirmButtonColor: '#5296BE',
        cancelButtonColor: "#d33",
        cancelButtonText: 'CANCEL',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            content: 'custom-swal-text',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${DELETE_SCHEDULE_ROUTE}?schedule_id=${scheduleId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    fetchSchedulesByDraft();
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while deleting the schedule.');
            });
        }
    });
}