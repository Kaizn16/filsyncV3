let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const course = document.getElementById('course').value;
const academic_year_filter = document.getElementById('academic_year_filter');
const semester_filter = document.getElementById('semester_filter');
const year_level_filter = document.getElementById('year_level_filter');
const section_filter = document.getElementById('section_filter');

const academicYearText = document.getElementById('academic-year-text');
const semesterText = document.getElementById('semester-text');
const classIdentifierInfoText = document.getElementById('classIdentifierInfo');

const FETCH_DRAFT_SCHEDULES = route(`fetch.draftschedules`);
let debounceTimer;

async function fetchSchedulesByDraft(academic_year, semester, yearLevel, section) {
    try {
        const response = await fetch(
            `${FETCH_DRAFT_SCHEDULES}?academic_year=${academic_year}&semester=${semester}&year_level=${yearLevel}&section=${section}`
        );
        const data = await response.json();
        populateTable(data);
    } catch (error) {
        console.error('Error fetching draft schedules:', error);
    }
}

function populateTable(schedules) {
    const tableBody = document.querySelector('.tableData');
    let totalCredits = 0;

    tableBody.innerHTML = `
        <tr class="extra-header">
            <td></td>
            <td>Course No</td>
            <td>Descriptive Title</td>
            <td>Credits</td>
            <td>Days</td>
            <td>Time</td>
            <td>Room</td>
            <td>Teacher</td>
        </tr>
    `;

    if (!schedules.length) {
        tableBody.innerHTML += `<tr><td colspan="8" class="no-results" style="text-align:center">No schedules available.</td></tr>`;
        return;
    }

    const fragment = document.createDocumentFragment();

    schedules.forEach((schedule) => {
        const mergedDays = schedule.schedules.map(s => s.weekdays.map(day => day.slice(0, 3).toUpperCase()).join('-')).join(', ');
        const mergedTimes = schedule.schedules.map(s => `${formatTime(s.start_time)} - ${formatTime(s.end_time)}`).join(', ');
        const mergedRooms = schedule.schedules.map(s => s.room || 'TBA').join(', ');

        totalCredits += parseFloat(schedule.credits) || 0;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td></td>
            <td>${schedule.course_no}</td>
            <td>${schedule.descriptive_title || ''}</td>
            <td>${schedule.credits || '0'}</td>
            <td>${mergedDays}</td>
            <td>${mergedTimes}</td>
            <td>${mergedRooms}</td>
            <td>${schedule.teacher.toUpperCase()}</td>
        `;
        fragment.appendChild(row);
    });

    tableBody.appendChild(fragment);

    const totalRow = document.createElement('tr');
    totalRow.innerHTML = `
        <td></td>
        <td></td>
        <td></td>
        <td>Total: ${totalCredits}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    `;
    tableBody.appendChild(totalRow);
}

function formatTime(time) {
    if (!time) return 'N/A';
    const [hour, minute] = time.split(':').map(Number);
    const formattedHour = hour % 12 || 12;
    const period = hour >= 12 ? 'PM' : 'AM';
    return `${formattedHour}:${minute.toString().padStart(2, '0')} ${period}`;
}


function handleFiltersChange() {
    const academicYearValue = academic_year_filter.value;
    const yearLevelValue = year_level_filter.value;
    const semesterValue = semester_filter.value;
    const sectionValue = section_filter.value;

    const semesterOptionText = semester_filter.options[semester_filter.selectedIndex]?.innerText;
    const yearLevelOptionText = year_level_filter.options[year_level_filter.selectedIndex]?.innerText;
    const sectionOptionText = section_filter.options[section_filter.selectedIndex]?.innerText;

    let filterText = `${course} `;

    if (yearLevelValue) {
        filterText += `${yearLevelOptionText.replace("ST YEAR", "").trim()}`;
    }

    if(yearLevelValue && sectionValue) {
        filterText += ' - ';
    }


    if (sectionValue) {
        filterText += `${sectionOptionText.replace("Section", "").trim()}`;
    }

    academicYearText.textContent = `A.Y ${academicYearValue}`;
    semesterText.textContent = semesterOptionText;
    classIdentifierInfoText.textContent = filterText;

    fetchSchedulesByDraft(academicYearValue, semesterValue, yearLevelValue, sectionValue);
}

academic_year_filter.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        const academicYearValue = academic_year_filter.value;
        const yearLevelValue = year_level_filter.value;
        const semesterValue = semester_filter.value;
        const sectionValue = section_filter.value;

        academicYearText.textContent = `A.Y ${academicYearValue}`;

        fetchSchedulesByDraft(academicYearValue, semesterValue, yearLevelValue, sectionValue);
    }, 300);
});

semester_filter.addEventListener('change', handleFiltersChange);
year_level_filter.addEventListener('change', handleFiltersChange);
section_filter.addEventListener('change', handleFiltersChange);


function initializeTextContent() {
    const academicYearValue = academic_year_filter.value;
    const yearLevelValue = year_level_filter.value;
    const sectionValue = section_filter.value;

    const semesterOptionText = semester_filter.options[semester_filter.selectedIndex]?.innerText;
    const yearLevelOptionText = year_level_filter.options[year_level_filter.selectedIndex]?.innerText;
    const sectionOptionText = section_filter.options[section_filter.selectedIndex]?.innerText;

    let filterText = `${course} `;

    if (yearLevelValue) {
        filterText += `${yearLevelOptionText.replace("ST YEAR", "").trim()}`;
    }

    if (yearLevelValue && sectionValue) {
        filterText += ' - ';
    }

    if (sectionValue) {
        filterText += `${sectionOptionText.replace("Section", "").trim()}`;
    }

    academicYearText.textContent = `A.Y ${academicYearValue}`;
    semesterText.textContent = semesterOptionText;
    classIdentifierInfoText.textContent = filterText;
}

initializeTextContent();

fetchSchedulesByDraft(academic_year_filter.value, semester_filter.value, year_level_filter.value, section_filter.value);


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