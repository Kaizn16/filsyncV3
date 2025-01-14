const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const department_id = document.getElementById('department_id').value;
const FETCH_SUBJECTS_ROUTE = route(`${authUserRole}.fetch.subjects`);
const BULK_DELETE_SUBJECTS_ROUTE = route(`${authUserRole}.bulkdelete.subject`);


const searchInput = document.getElementById('search');
const courseFilter = document.getElementById('courseFilter');
const yearLevelFilter = document.getElementById('yearLevelFilter');
const semesterFilter = document.getElementById('semesterFilter');
const filterInfo = document.querySelector('.filterInfo');
const tableData = document.querySelector('.tableData');
let debounceTimer;

async function fetchSubjects(department_id, search = '', course = '', year_level = '', semester = '') {
    try {
        const response = await fetch(`${FETCH_SUBJECTS_ROUTE}?department_id=${department_id}&search=${search}&course=${course}&year_level=${year_level}&semester=${semester}`);
        const subject = await response.json();
        populateTable(subject.data);
    } catch (error) {
        console.error('Error fetching subjects:', error);
    }
}

function populateTable(subjects) {
    tableData.innerHTML = '';
    if (subjects.length === 0) {
        tableData.innerHTML = '<tr><td colspan="7" style="text-align:center">No subject found...</td></tr>';
        return;
    }

    subjects.forEach(subject => {
        const row = `
            <tr class="Subject">
                <td style="text-align: center"><input type="checkbox" name="selectionBoxSubject" class="selectionBoxSubject" value="${subject.subject_id}"></td>
                <td>${subject.course_no}</td>
                <td>${subject.descriptive_title}</td>
                <td>${subject.credits}</td>
                <td>${subject.semester}</td>
                <td>${subject.academic_year}</td>
                <td>
                    <div class="actions">
                        <span class="action edit" data-subject='${JSON.stringify(subject)}' onclick="event.stopPropagation(); editSubject(this);"><i class="material-icons icon" title="Edit">edit_square</i></span>
                    </div>
                </td>
            </tr>
        `;
        tableData.innerHTML += row;
    });
}

function handleFiltersChange() {
    const searchValue = searchInput.value;
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

    fetchSubjects(department_id, searchValue, courseValue, yearLevelValue, semesterValue);
}

searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(handleFiltersChange, 600);
});

courseFilter.addEventListener('change', handleFiltersChange);

yearLevelFilter.addEventListener('change', handleFiltersChange);

semesterFilter.addEventListener('change', handleFiltersChange);


fetchSubjects(department_id);


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


// ACTIONS //
$('#addSubject').click(function() {
    openSubjectModal('create', department_id);
});

function editSubject(editButton) {
    var subjectData = JSON.parse(editButton.getAttribute('data-subject'));
    openSubjectModal('edit', department_id, subjectData);
}

function openSubjectModal(mode, department_id, subjectData = {}) {
    let isEditMode = mode === 'edit';
    let modalTitle = isEditMode ? 'Edit Subject' : 'New Subject';
    let buttonText = isEditMode ? 'SAVE' : 'ADD';

    const SUBJECT_ROUTE = isEditMode ? route(`${authUserRole}.update.subject`) : route(`${authUserRole}.store.subject`);

    Swal.fire({
        html: `
            <section class="form-wrapper">
                <div class="form-container">
                    <header class="form-header">
                        <h1 class="title">${modalTitle}</h1>
                        <span class="close"><i class="material-icons icon">close</i></span>
                    </header>
                    <form class="form-content" method="POST" action="${SUBJECT_ROUTE}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="${isEditMode ? 'PUT' : 'POST'}">
                        <input type="hidden" name="department_id" value="${department_id}">
                        <input type="hidden" name="subject_id" value="${subjectData.subject_id}">

                        <div class="form-group-row">
                            <div class="form-group">
                                <label for="course_id">Course <strong class="required">*</strong></label>
                                <select name="course_id" id="course_id">
                                    <option selected disabled>Select a course</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group-row"> 
                            <div class="form-group">
                                <label for="course_no">Course No <strong class="required">*</strong></label>
                                <select name="course_no" id="course_no">
                                    <option selected disabled>Course No</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="descriptive_title">Descriptive Title</label>
                                <input type="text" name="descriptive_title" id="descriptive_title" value="" placeholder="Descriptive title" readonly />
                            </div>

                            <div class="form-group">
                                <label for="credit">Credit/Units</label>
                                <input type="text" name="credit" id="credit" value="" placeholder="Credit/Units" readonly />
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="form-group">
                                <label for="semester">Semester <strong class="required">*</strong></label>
                                <select name="semester" id="semester">
                                    <option selected disabled>Semester</option>
                                    <option value="1st Semester" ${subjectData.semester == '1st Semester' ? 'selected' : ''}>1st Semester</option>
                                    <option value="2nd Semester" ${subjectData.semester == '2nd Semester' ? 'selected' : ''}>2nd Semester</option>
                                    <option value="Summer" ${subjectData.semester == 'Summer' ? 'selected' : ''}>Summer</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="year_level">Year Level <strong class="required">*</strong></label>
                                <select name="year_level" id="year_level">
                                    <option selected disabled>Year Level</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="academic_year">Academic Year<strong class="required">*</strong></label>
                                <select name="academic_year" id="academic_year">
                                    <option selected disabled>Academic Year</option>
                                </select>
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
        didOpen: () => {
            const courseSelect = document.getElementById('course_id');
            const courseNoDropdown = document.getElementById('course_no');
            const descriptive_title = document.getElementById('descriptive_title');
            const credit = document.getElementById('credit');
            const yearLevelDropdown = document.getElementById('year_level');
            const academicYearDropdown = document.getElementById('academic_year');

            fetch(route('fetch.courses.department', { department_id }))
                .then(response => response.json())
                .then(data => {
                    courseSelect.innerHTML = '<option selected disabled>Select a course</option>';
                    data.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.course_id;
                        option.textContent = course.abbreviation;

                        if (isEditMode && course.course_id === subjectData.course_id) {
                            option.selected = true;
                        }

                        courseSelect.appendChild(option);
                    });

                    if (isEditMode) {
                        fetch(route('fetch.courses.no', { course_id: subjectData.course_id }))
                            .then(response => response.json())
                            .then(courseNos => {
                                courseNoDropdown.innerHTML = '<option selected disabled>Course No</option>';
                                courseNos.forEach(courseNo => {
                                    const option = document.createElement('option');
                                    option.value = courseNo.course_no_id;
                                    option.textContent = courseNo.course_no;
                                    if (courseNo.course_no_id === subjectData.course_no_id) {
                                        option.selected = true;
                                        fetchCourseNoDetails(courseNo.course_no_id);
                                    }
                                    courseNoDropdown.appendChild(option);
                                });
                            });
                    }
                })
                .catch(error => {
                    console.error('Error fetching courses:', error);
                    courseSelect.innerHTML = '<option selected disabled>Error loading courses</option>';
                });

            yearLevelDropdown.innerHTML = '<option selected disabled>Year Level</option>';
            yearLevels.forEach(yearLevel => {
                const option = document.createElement('option');
                option.value = yearLevel.year_level_id;
                option.textContent = yearLevel.year_level;
                if (isEditMode && yearLevel.year_level_id === subjectData.year_level_id) {
                    option.selected = true;
                }
                yearLevelDropdown.appendChild(option);
            });

            academicYearDropdown.innerHTML = '<option selected disabled>Academic Year</option>';
            academic_years.forEach(academic_year => {
                const option = document.createElement('option');
                option.value = academic_year.academic_year;
                option.textContent = academic_year.academic_year;
                if (isEditMode && academic_year.academic_year === subjectData.academic_year) {
                    option.selected = true;
                }
                academicYearDropdown.appendChild(option);
            });

            let debounceTimer;

            courseSelect.addEventListener('change', function () {
                const courseId = this.value;

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    courseNoDropdown.innerHTML = '<option selected disabled>Course No</option>';

                    fetch(route('fetch.courses.no', { course_id: courseId }))
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(courseNo => {
                                const option = document.createElement('option');
                                option.value = courseNo.course_no_id;
                                option.textContent = courseNo.course_no;
                                if (isEditMode && courseNo.course_no_id === subjectData.course_no_id) {
                                    option.selected = true;
                                    fetchCourseNoDetails(courseNo.course_no_id);
                                }
                                courseNoDropdown.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching course no:', error);
                            courseNoDropdown.innerHTML = '<option selected disabled>Error loading course numbers</option>';
                        });
                }, 300);
            });

            courseNoDropdown.addEventListener('change', function () {
                const course_no_id = this.value;

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetchCourseNoDetails(course_no_id);
                }, 300);
            });

            function fetchCourseNoDetails(course_no_id) {
                fetch(route('fetch.courseno.details', { course_no_id: course_no_id }))
                    .then(response => response.json())
                    .then(data => {
                        descriptive_title.value = data.descriptive_title;
                        credit.value = data.credits;
                        if (isEditMode && subjectData.course_no_id === course_no_id) {
                            descriptive_title.value = subjectData.descriptive_title;
                            credit.value = subjectData.credits;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching course no details:', error);
                    });
            }
        },
        preConfirm: () => {
            const form = document.querySelector('.form-content');
            form.submit();
        }
    });

    document.querySelector('.custom-swal-popup .close').addEventListener('click', () => {
        Swal.close();
    });
}


$('#bulkDeleteSubject').click(function() {
    let selectedSubjects = [];
    $('.selectionBoxSubject:checked').each(function() {
        selectedSubjects.push($(this).val());
    });

    if (selectedSubjects.length === 0) {
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
                data: { subject_ids: selectedSubjects },
                success: function(response) {
                    showToast('success', 'Selected subjects has been deleted.');
                    $('.selectionBox').prop('checked', false);
                    fetchSubjects(department_id);
                },
                error: function(xhr, status, error) {
                    showToast('error', 'Failed to delete selected subjects.');
                }
            });
        }
    });
});


document.getElementById('printButton').addEventListener('click', function() {
    var table = document.querySelector('.table');

    var contentToPrint = document.querySelector('.table-info').innerHTML + 
                         `<style>
                            @media print {
                                .table tr td:first-child,
                                .table tr th:first-child,
                                .table tr td:last-child,
                                .table tr th:last-child {
                                    display: none;
                                }
                            }
                          </style>` + table.outerHTML;

    var printFrame = document.createElement('iframe');
    printFrame.style.display = 'none';
    document.body.appendChild(printFrame);

    var iframeDoc = printFrame.contentWindow.document;
    iframeDoc.open();
    iframeDoc.write('<html><head><title>Print</title>');
    iframeDoc.write('<link rel="stylesheet" type="text/css" href="' + base_route + '">');
    iframeDoc.write('</head><body>');
    iframeDoc.write(contentToPrint);
    iframeDoc.write('</body></html>');
    iframeDoc.close();

    setTimeout(function() {
        iframeDoc.defaultView.print();
        document.body.removeChild(printFrame);
    }, 500);
});







