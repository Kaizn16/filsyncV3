const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const schedule_draft_id = document.getElementById('schedule_draft_id').value;

let debounceTimer;

async function fetchSchedulesByDraft() {
    const FETCH_DRAFT_SCHEDULES = route('vpaa.fetch.schedule.draft.subjects');

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
        </tr>
    `;

    if (!schedulesData.length) {
        tableBody.innerHTML += `<tr><td colspan="8" class="no-results">No schedules available.</td></tr>`;
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


document.getElementById('printButton').addEventListener('click', function() {
    var table = document.querySelector('.table-wrapper');

    var contentToPrint = table.outerHTML;

    var printFrame = document.createElement('iframe');
    printFrame.style.display = 'none';
    document.body.appendChild(printFrame);
    var iframeDoc = printFrame.contentWindow.document;
    iframeDoc.open();
    iframeDoc.write('<html><head><title>Print</title>');
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
});