const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const department = document.getElementById('department').value;
const course = document.getElementById('course').value;
const showDeletedData = document.getElementById('showDeletedData');
let debounceTimer;

function formatTime(time) {
    if (!time) return 'N/A';
    const [hour, minute] = time.split(':').map(Number);
    const formattedHour = hour % 12 || 12;
    const period = hour >= 12 ? 'PM' : 'AM';
    return `${formattedHour}:${minute.toString().padStart(2, '0')} ${period}`;
}


async function fetchMyDrafts(isDeleted) {

    const FETCH_MYSCHEDULE_DRAFTS = route(`${authUserRole}.fetch.myschedule.drafts`);

    try {
        const response = await fetch(`${FETCH_MYSCHEDULE_DRAFTS}?isDeleted=${isDeleted}`);
        const data = await response.json();
        populateTable(data);
    } catch (error) {
        console.error('Error fetching my schedule drafts:', error);
    }
}

function populateTable(drafts) {
    const tableBody = document.querySelector('.tableData');
    tableBody.innerHTML = '';

    drafts.forEach((draft, index) => {
        
        const editDraftRoute = route(`${authUserRole}.edit.schedule.draft`, { 
            department: department, 
            course: course,
            schedule_draft_id: draft.schedule_draft_id,
        });
    
        const editDraftUrl = `${editDraftRoute}`;
        
        let ShowAction = draft.is_deleted === 1 ? `
            <span class="action delete" id="delete-draft-${draft.schedule_draft_id}"><i class="material-icons icon" title="Permanently Delete">delete</i></span>
            <span class="action restore" id="restore-draft-${draft.schedule_draft_id}"><i class="material-icons icon" title="Restore">restore</i></span>` 
        : (draft.status === 'pending' || draft.status === 'approved') ? ''
        : `
            ${draft.status === 'saved' ? 
                `<span class="action send sendDraft" data-schedule_draft_id="${draft.schedule_draft_id}" onclick="event.stopPropagation(); sendScheduleDraft(this);">
                    <i class="material-icons icon" title="Send Draft">send</i>
                </span>` 
            : ''}
            <a href="${editDraftUrl}" class="action edit"><i class="material-icons icon" title="Edit Draft">edit_square</i></a>
            <span class="action delete" id="delete-draft-${draft.schedule_draft_id}"><i class="material-icons icon" title="Delete Draft">delete</i></span>
        `;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${draft.draft_name}</td>
            <td>
                <span class="draft-status">
                    <p class="${draft.status === 'pending' ? 'SUBMITTED' : draft.status.toUpperCase()}">${draft.status === 'pending' ? 'SUBMITTED' : draft.status.toUpperCase()}</p>
                </span>
            </td>
            <td>
                <div class="actions">
                    <span class="action view viewFinalizedDraft" data-schedule_draft_id="${draft.schedule_draft_id}" onclick="event.stopPropagation(); viewFinalizedDraft(this);">
                        <i class="material-icons icon" title="View Draft">visibility</i>
                    </span>
                    ${ShowAction}
                </div>
            </td>
        `;
        tableBody.appendChild(row);
    });

    drafts.forEach(draft => {
        document.getElementById(`delete-draft-${draft.schedule_draft_id}`).addEventListener('click', function (event) {
            const DELETE_DRAFT_ROUTE = route(`${authUserRole}.delete.schedule.draft`);
            
            Swal.fire({
                title: 'Are you sure?',
                html: `<strong class="info">Are you sure you want to delete this draft?</strong>`,
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
                    fetch(`${DELETE_DRAFT_ROUTE}?schedule_draft_id=${draft.schedule_draft_id}`, {
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
                            fetchMyDrafts();
                        } else {
                            showToast('error', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'An error occurred while deleting the draft.');
                    });
                }
            });
        });
    });    

    drafts.forEach(draft => {
        document.getElementById(`restore-draft-${draft.schedule_draft_id}`).addEventListener('click', function (event) {
            const RESTORE_DRAFT_ROUTE = route(`${authUserRole}.restore.schedule.draft`);
            
            Swal.fire({
                title: 'Are you sure?',
                html: `<strong class="info">Are you sure you want to restore this draft?</strong>`,
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
                    fetch(`${RESTORE_DRAFT_ROUTE}?schedule_draft_id=${draft.schedule_draft_id}`, {
                        method: 'PUT',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('info', data.message);
                            const initialIsDeleted = showDeletedData.checked;
                            fetchMyDrafts(initialIsDeleted);
                        } else {
                            showToast('error', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'An error occurred while restoring the draft.');
                    });
                }
            });
        });
    });
}

showDeletedData.addEventListener('change', () => {
    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(() => {
        const isDeleted = showDeletedData.checked;
        fetchMyDrafts(isDeleted);
    }, 600);
});


const initialIsDeleted = showDeletedData.checked;
fetchMyDrafts(initialIsDeleted);

function sendScheduleDraft(schedule_draft_id) {
    const scheduleDraftId = JSON.parse(schedule_draft_id.getAttribute('data-schedule_draft_id'));
    
    const SEND_SCHEDULE_DRAFT = route(`${authUserRole}.scheduledraft.send`);

    Swal.fire({
        title: 'Are you sure?',
        html: `<strong class="info">Are you sure you want send this schedule draf to VPAA for review?</strong>`,
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
            fetch(`${SEND_SCHEDULE_DRAFT}?schedule_draft_id=${scheduleDraftId}`, {
                method: 'PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('info', data.message);
                    const initialIsDeleted = showDeletedData.checked;
                    fetchMyDrafts(initialIsDeleted);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while restoring the draft.');
            });
        }
    });
}


function viewFinalizedDraft(schedule_draft_id) {
    const scheduleDraftId = JSON.parse(schedule_draft_id.getAttribute('data-schedule_draft_id'));

    const SHOW_SCHEDULE_DRAFT = route(`${authUserRole}.scheduledraft.show`);

    fetch(`${SHOW_SCHEDULE_DRAFT}?schedule_draft_id=${scheduleDraftId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.schedules) {
            let tableRows = `
                <tr>
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

            data.schedules.forEach(scheduleGroup => {
                scheduleGroup.schedules.forEach(schedule => {
                    tableRows += `
                        <tr>
                            <td></td>
                            <td>${scheduleGroup.course_no}</td>
                            <td>${scheduleGroup.descriptive_title}</td>
                            <td>${scheduleGroup.credits}</td>
                            <td>${schedule.weekdays.map(day => day.slice(0, 3).toUpperCase()).join('-')}</td>
                            <td>${formatTime(schedule.start_time)}</td>
                            <td>${formatTime(schedule.end_time)}</td>
                            <td>${scheduleGroup.teacher}</td>
                            <td>${schedule.room ? schedule.room : 'TBA'}</td>
                        </tr>
                    `;
                });
            });

            const content = `
                <div class="table-container">
                    <div class="table-wrapper">
                        <header class="header">
                            <strong>Academic Year: ${data.scheduleDraft.academic_year || 'N/A'}</strong>
                            <strong>${data.scheduleDraft.semester || 'N/A'}</strong>
                            <strong>
                                ${data.scheduleDraft.course.abbreviation} ${data.scheduleDraft.year_level.replace('ST YEAR', '')} - ${data.scheduleDraft.section.name}
                            </strong>
                        </header>
                        <table class="table">
                            <thead>
                                <tr class="heading">
                                    <th>CID</th>
                                    <th>Course No</th>
                                    <th>Descriptive Title</th>
                                    <th>Credit</th>
                                    <th colspan="3">Lecture</th>
                                    <th>Teacher</th>
                                    <th>Room</th>
                                </tr>
                            </thead>
                            <tbody class="tableData">
                                ${tableRows}
                            </tbody>
                        </table>
                    </div>
                </div>`;

            Swal.fire({
                html: `${content}`,
                showConfirmButton: true,
                confirmButtonColor: '#5296BE',
                confirmButtonText: 'PRINT',
                showCancelButton: true,
                cancelButtonColor: "#d33",
                cancelButtonText: 'CLOSE',
                customClass: {
                    popup: 'custom-swal-popup',
                    title: 'custom-swal-title',
                    content: 'custom-swal-text',
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    var table = document.querySelector('.table-wrapper');
                    var contentToPrint = table.outerHTML;

                    var printFrame = document.createElement('iframe');
                    printFrame.style.display = 'none';
                    document.body.appendChild(printFrame);
                    var iframeDoc = printFrame.contentWindow.document;
                    iframeDoc.open();
                    iframeDoc.write('<html><head><title>Room Utilization Schedule</title>');
                    iframeDoc.write('<link rel="stylesheet" type="text/css" href="' + base_route + '">');
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
                }
            });
        } else {
            showToast('error', data.message || 'No schedules found.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'An error occurred while fetching the schedule draft.');
    });
}