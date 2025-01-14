let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const FETCH_SCHEDULE_ROUTE = route(`${authUserRole}.fetch.schedules`);
const DEPARTMENT_ID = document.getElementById('department_id').value;

function toggleBulkButton() {
    let anyChecked = $('.selectionBoxSubject:checked').length > 0;
    $('#bulkDeleteSubject').toggle(anyChecked);
}

$('.selectionBox').click(function() {
    let isChecked = $(this).is(':checked');
    $('.selectionBoxSubject').prop('checked', isChecked);
    toggleBulkButton();
});

$(document).on('change', '.selectionBoxSubject', function() {
    if (!$(this).is(':checked')) {
        $('.selectionBox').prop('checked', false);
    } else {
        let allChecked = $('.selectionBoxSubject').length === $('.selectionBoxSubject:checked').length;
        $('.selectionBox').prop('checked', allChecked);
    }
    toggleBulkButton();
});

$('#bulkDeleteSubject').hide();



document.getElementById('addSubject').addEventListener('click', function() {
    scheduleModal('create');
});

function scheduleModal(mode, scheduleData = {}) {
    let isEditMode = mode === 'edit';
    let modalTitle = isEditMode ? 'Edit Schedule' : 'New Schedule';
    let buttonText = isEditMode ? 'SAVE' : 'ADD';

    const SCHEDULE_ROUTE = isEditMode ? route(`${authUserRole}.update.schedule`) : route(`${authUserRole}.store.schedule`); 

    Swal.fire({
        html: `
             <section class="form-wrapper">
                <div class="form-container">
                    <header class="form-header">
                        <h1 class="title">${modalTitle}</h1>
                        <span class="close"><i class="material-icons icon">close</i></span>
                    </header>
                    <form class="form-content" method="POST" action="${SCHEDULE_ROUTE}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="${isEditMode ? 'PUT' : 'POST'}">
                        <input type="hidden" name="department_id" value="${DEPARTMENT_ID}">

                        <div class="form-group-col">
                            <div class="form-group">
                                <label for="course">Course <strong class="required">*</strong></label>
                                <select name="course" id="course">
                                    <option selected disabled>Select Course</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject <strong class="required">*</strong></label>
                                <select name="subject" id="subject">
                                    <option>Select Subject</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="semester">Semester <strong class="required">*</strong></label>
                                <select name="semester" id="semester"><option>Select Semester</option></select>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="form-group">
                                <label for="teacher">Teacher <strong class="required">*</strong></label>
                                <select name="teacher" id="teacher"></select>
                            </div>
                            <div class="form-group">
                                <label for="room">Room <strong class="required">*</strong></label>
                                <select name="room" id="room"><option>Select Room</option></select>
                            </div>
                        </div>


                        <div class="form-group-row">
                            <div class="form-group">
                                <label for="yearLevels">Year Level <strong class="required">*</strong></label>
                                <select name="year_level" id="yearLevels"></select>
                            </div>

                            <div class="form-group">
                                <label for="sections">Section <strong class="required">*</strong></label>
                                <select name="sections[]" id="sections" multiple="multiple"></select>
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
                                    <input type="time" name="start_time" id="start_time" />
                                </div>

                                <div class="form-group">
                                    <label for="end_time">End Time <strong class="required">*</strong></label>
                                    <input type="time" name="end_time" id="end_time" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        `,
        confirmButton: true,
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
            form.submit();
        }
    });

    const semesterSelect = document.getElementById('semester');
    SEMESTERS.forEach(semester => {
        let option = document.createElement('option');
        option.value = semester;
        option.textContent = semester;
        semesterSelect.appendChild(option);
    });


    const teacherSelecct = document.getElementById('teacher');
    TEACHERS.forEach(teacher => {
        let option = document.createElement('option');
        option.value = teacher.user_id;
        
        let displayName = teacher.middle_name ? 
            `${teacher.first_name} ${teacher.middle_name.charAt(0)} ${teacher.last_name}` :
            `${teacher.first_name} ${teacher.last_name}`;

        option.textContent = `${displayName} (${teacher.position})`;
        teacherSelecct.appendChild(option);
    });

    const roomSelect = document.getElementById('room');
    ROOMS.forEach(room => {
        let option = document.createElement('option');
        option.value = room.room_id;
        option.textContent = `${room.room_name} (${room.building_name})`;
        roomSelect.appendChild(option);
    });


    $('#sections').select2({
        placeholder: "Select sections",
        allowClear: true,
        multiple: true,
        data: scheduleData.section || []
    }).val(scheduleData.section).trigger('change');

    SECTIONS.forEach(section => {
        $('#sections').append(new Option(section, section));
    });

    $('#weekdays').select2({
        placeholder: "Select weekdays",
        allowClear: true,
        multiple: true,
        data: scheduleData.weekdays || []
    }).val(scheduleData.weekdays).trigger('change');

    WEEKDAYS.forEach(weekday => {
        $('#weekdays').append(new Option(weekday, weekday));
    });


    const courseSelect = document.getElementById('course');
    COURSES.forEach(course => {
        let option = document.createElement('option');
        option.value = course.course_id;
        option.textContent = course.course_name;
        courseSelect.appendChild(option);
    });

    const yearLevelSelect = document.getElementById('yearLevels');
    YEARLEVELS.forEach(yearLevel => {
        let option = document.createElement('option');
        option.value = yearLevel.year_level_id;
        option.textContent = yearLevel.year_level;
        yearLevelSelect.appendChild(option);
    });

    function populateSubjects() {
        const courseSelect = document.getElementById('course');
        const subjectSelect = document.getElementById('subject');
    
        let debounceTimeout;
    
        courseSelect.addEventListener('change', function () {
            clearTimeout(debounceTimeout);
    
            debounceTimeout = setTimeout(() => {
                const selectedCourse = courseSelect.value;
    
                subjectSelect.innerHTML = '';
                
                const FETCH_SUBJECTS_ROUTE = route('fetch.subjects');
                
                fetch(`${FETCH_SUBJECTS_ROUTE}?course_id=${selectedCourse}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            data.forEach(subject => {
                                console.log(data);
                                let option = document.createElement('option');
                                option.value = subject.subject_id;
                                option.textContent = `${subject.course_no.course_no} - ${subject.course_no.descriptive_title}`;
                                subjectSelect.appendChild(option);
                            });
                        } else {
                            let option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'No subjects available';
                            subjectSelect.appendChild(option);
                        }
                        $('#subject').val(null).trigger('change');
                    })
                    .catch(error => {
                        console.error('Error fetching subjects:', error);
                        let option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Error loading subjects';
                        subjectSelect.appendChild(option);
                    });
            }, 300);
        });
    }

    populateSubjects();


    document.querySelector('.custom-swal-popup .close').addEventListener('click', () => {
        Swal.close();
    });
}


const searchInput = document.getElementById('search');
const courseFilter = document.getElementById('courseFilter');
const yearLevelFilter = document.getElementById('yearLevelFilter');
const semesterFilter = document.getElementById('semesterFilter');
const filterInfo = document.querySelector('.filterInfo');
const tableData = document.querySelector('.tableData');
let debounceTimer;
let currentPage = 1;

async function fetchSchedules(DEPARTMENT_ID, search = '', course_id = '', year_level_id = '', semester = '', page = 1) {
    try {
        const response = await fetch(`${FETCH_SCHEDULE_ROUTE}?department_id=${DEPARTMENT_ID}&search=${search}&course_id=${course_id}&year_level_id=${year_level_id}&semester=${semester}&page=${page}`);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (data && data.data) {
            populateTable(data.data);
            updatePagination(data);
        } else {
            tableData.innerHTML = '<tr><td colspan="8" style="text-align:center">No schedules found</td></tr>';
        }
    } catch (error) {
        console.error('Error fetching schedules:', error);
    }
}

function populateTable(response) {
    tableData.innerHTML = '';

    if (!response.data || response.data.length === 0) {
        tableData.innerHTML = '<tr><td colspan="8" style="text-align:center">No schedules found</td></tr>';
        return;
    }

    response.data.forEach(schedule => {
        const row = `
            <tr>
                <td style="text-align: center">
                    <input type="checkbox" name="selectionBoxSubject" class="selectionBoxSubject" value="${schedule.schedule_id}">
                </td>
                <td>
                    ${schedule.start_time || 'N/A'} - ${schedule.end_time || 'N/A'}
                    <br>
                    ${Array.isArray(schedule.weekdays) ? schedule.weekdays.map(day => day.substring(0, 3)).join(', ') : 'N/A'}
                </td>
                <td>${schedule.subject?.course_no.course_no || 'N/A'}</td>
                <td>${schedule.subject?.course_no?.descriptive_title || 'N/A'}</td>
                <td>${schedule.room?.room_name || 'N/A'}</td>
                <td>${schedule.year_level?.year_level || 'N/A'} | ${schedule.sections || 'N/A'}</td>
                <td>${schedule.user 
                    ? `${schedule.user.first_name || ''} ${schedule.user.middle_name ? schedule.user.middle_name[0] + '.' : ''} ${schedule.user.last_name || ''}`.trim()
                    : 'TBA'}
                </td>
                <td>
                    <div class="actions">
                        <span class="action delete" data-schedule_id='${schedule.schedule_id}' onclick="event.stopPropagation(); deleteSchedule(this);">
                            <i class="material-icons icon" title="Delete">delete</i>
                        </span>
                    </div>
                </td>
            </tr>
        `;
        tableData.innerHTML += row;
    });
}


function updatePagination(data) {
    const previousButton = document.querySelector('.pagination .previous');
    const nextButton = document.querySelector('.pagination .next');

    if (data.prev_page_url) {
        previousButton.classList.remove('disabled');
        previousButton.onclick = () => {
            currentPage--;
            fetchSchedules(DEPARTMENT_ID, searchInput.value, courseFilter.value, yearLevelFilter.value, semesterFilter.value, currentPage);
        };
    } else {
        previousButton.classList.add('disabled');
        previousButton.onclick = null;
    }

    if (data.next_page_url) {
        nextButton.classList.remove('disabled');
        nextButton.onclick = () => {
            currentPage++;
            fetchSchedules(DEPARTMENT_ID, searchInput.value, courseFilter.value, yearLevelFilter.value, semesterFilter.value, currentPage);
        };
    } else {
        nextButton.classList.add('disabled');
        nextButton.onclick = null;
    }
}


searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        const searchValue = searchInput.value;
        const courseValue = courseFilter.value;
        const yearLevelValue = yearLevelFilter.value;
        const semesterValue = semesterFilter.value;
        currentPage = 1;
        fetchSchedules(DEPARTMENT_ID, searchValue, courseValue, yearLevelValue, semesterValue, currentPage);
    }, 300);
});

function handleFiltersChange() {
    const courseValue = courseFilter.value;
    const yearLevelValue = yearLevelFilter.value;
    const semesterValue = semesterFilter.value;

    const courseOptionText = courseFilter.options[courseFilter.selectedIndex]?.innerText;
    const yearLevelOptionText = yearLevelFilter.options[yearLevelFilter.selectedIndex]?.innerText;
    const semesterOptionText = semesterFilter.options[semesterFilter.selectedIndex]?.innerText;

    let filterText = ``;

    if(courseValue)
    {
        filterText += ` ${courseOptionText} -`;
    }

    if(yearLevelValue)
    {
        filterText += ` ${yearLevelOptionText} -`;
    }

    if(semesterValue)
    {
        filterText += ` ${semesterOptionText} `;
    }

    filterInfo.textContent = filterText;

    fetchSchedules(DEPARTMENT_ID, searchInput.value, courseFilter.value, yearLevelFilter.value, semesterFilter.value, 1);
}

courseFilter.addEventListener('change', handleFiltersChange);

yearLevelFilter.addEventListener('change', handleFiltersChange);

semesterFilter.addEventListener('change', handleFiltersChange);



fetchSchedules(DEPARTMENT_ID); // initialize fetching


const BULK_DELETE_SUBJECTS_ROUTE = route(`${authUserRole}.bulkdelete.schedules`);

$('#bulkDeleteSubject').click(function() {
    let selectedSchedules = [];
    $('.selectionBoxSubject:checked').each(function() {
        selectedSchedules.push($(this).val());
    });

    if (selectedSchedules.length === 0) {
        showToast('info', 'Select one subject to delete!.');
        return;
    }

    Swal.fire({
        html: `
            <section class="form-wrapper">
                <div class="form-container">
                    <header class="form-header">
                        <h1 class="title">Delete</h1>
                        <span class="close"><i class="material-icons icon">close</i></span>
                    </header>
                    <form class="form-content">
                        <strong class="info">Are you sure you want to delete selected subject?</strong>
                    </form>
                </div>
            </section>`,
        confirmButtonText: 'YES',
        confirmButtonColor: '#5296BE',
        showCancelButton: true,
        cancelButtonColor: "#d33",
        cancelButtonText: 'CANCEL',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal-popup',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: BULK_DELETE_SUBJECTS_ROUTE,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: { schedule_ids: selectedSchedules },
                success: function(response) {
                    showToast('success', 'Selected schedules has been deleted.');
                    $('.selectionBox').prop('checked', false);
                    fetchSchedules(DEPARTMENT_ID);
                },
                error: function(xhr, status, error) {
                    showToast('error', 'Failed to delete selected schedules.');
                }
            });
        }
    });
});



function deleteSchedule(schedule_id) {
    var schedule_id = JSON.parse(schedule_id.getAttribute('data-schedule_id'));
    const DELETE_SCHEDULE_ROUTE = route(`${authUserRole}.delete.schedule`);

    Swal.fire({
        html: `
            <section class="form-wrapper">
                <div class="form-container">
                    <header class="form-header">
                        <h1 class="title">Delete</h1>
                        <span class="close"><i class="material-icons icon">close</i></span>
                    </header>
                    <form class="form-content">
                        <strong class="info">Are you sure you want to delete selected subject?</strong>
                    </form>
                </div>
            </section>`,
        confirmButtonText: 'YES',
        confirmButtonColor: '#5296BE',
        showCancelButton: true,
        cancelButtonColor: "#d33",
        cancelButtonText: 'CANCEL',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal-popup',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: DELETE_SCHEDULE_ROUTE,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: { schedule_id: schedule_id },
                success: function(response) {
                    showToast('success', 'Selected schedule has been deleted.');
                    $('.selectionBox').prop('checked', false);
                    fetchSchedules(DEPARTMENT_ID);
                },
                error: function(xhr, status, error) {
                    showToast('error', 'Failed to delete selected schedule.');
                }
            });
        }
    });

}


document.getElementById('printButton').addEventListener('click', function() {
    var table = document.querySelector('.table');
    var departmentInfo = document.getElementById('departmentInfo').textContent;
    var contentToPrint = document.querySelector('.table-info').innerHTML + 
                         `<style>
                            @media print {

                                h1, h2, {
                                    padding: 16px
                                }
                                .table tr td:first-child,
                                .table tr th:first-child,
                                .table tr td:last-child,
                                .table tr th:last-child {
                                    display: none;
                                }
                                 th.pagination,
                                .pagination {
                                    display: none !important;
                                }
                            }
                          </style>` + table.outerHTML;

    var printFrame = document.createElement('iframe');
    printFrame.style.display = 'none';
    document.body.appendChild(printFrame);

    var iframeDoc = printFrame.contentWindow.document;
    iframeDoc.open();
    iframeDoc.write('<html><head><title>Print Schedules</title>');
    iframeDoc.write('<link rel="stylesheet" type="text/css" href="' + base_route + '">');
    iframeDoc.write('</head><body>');
    iframeDoc.write(`<center><strong>${departmentInfo}</strong></center>`);
    iframeDoc.write('<center><strong>Schedules</strong></center>');
    iframeDoc.write(contentToPrint);
    iframeDoc.write('</body></html>');
    iframeDoc.close();

    setTimeout(function() {
        iframeDoc.defaultView.print();
        document.body.removeChild(printFrame);
    }, 500);
});