let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const academic_year = document.getElementById("academic-year-text").getAttribute("data-academic_year");
const semester_filter = document.getElementById('semester_filter');
const course_filter = document.getElementById('course_filter');
const year_level_filter = document.getElementById('year_level_filter');
const section_filter = document.getElementById('section_filter');

const academicYearText = document.getElementById('academic-year-text');
const semesterText = document.getElementById('semester-text');
const classIdentifierInfoText = document.getElementById('classIdentifierInfo');

const FETCH_SCHEDULES = route(`fetch.approved.schedules`);
let debounceTimer;

async function fetchSchedules(academic_year, semester, course, yearLevel, section) {
    try {
        const response = await fetch(
            `${FETCH_SCHEDULES}?academic_year=${academic_year}&semester=${semester}&course=${course}&year_level=${yearLevel}&section=${section}`
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
    const semesterValue = semester_filter.value;
    const courseValue = course_filter.value;
    const yearLevelValue = year_level_filter.value;
    const sectionValue = section_filter.value;

    const semesterOptionText = semester_filter.options[semester_filter.selectedIndex]?.innerText;
    const courseOptionText = course_filter.options[course_filter.selectedIndex]?.innerText;
    const yearLevelOptionText = year_level_filter.options[year_level_filter.selectedIndex]?.innerText;
    const sectionOptionText = section_filter.options[section_filter.selectedIndex]?.innerText;

    let filterText = `${courseOptionText} `;

    if (yearLevelValue) {
        filterText += `${yearLevelOptionText.replace("ST YEAR", "").trim()}`;
    }

    if(yearLevelValue && sectionValue) {
        filterText += ' - ';
    }


    if (sectionValue) {
        filterText += `${sectionOptionText.replace("Section", "").trim()}`;
    }

    academicYearText.textContent = `A.Y ${academic_year}`;
    semesterText.textContent = semesterOptionText;
    classIdentifierInfoText.textContent = filterText;

    fetchSchedules(academic_year, semesterValue, courseValue, yearLevelValue, sectionValue);
}

course_filter.addEventListener('change', handleFiltersChange);
semester_filter.addEventListener('change', handleFiltersChange);
year_level_filter.addEventListener('change', handleFiltersChange);
section_filter.addEventListener('change', handleFiltersChange);


function initializeTextContent() {
    const yearLevelValue = year_level_filter.value;
    const sectionValue = section_filter.value;

    const semesterOptionText = semester_filter.options[semester_filter.selectedIndex]?.innerText;
    const yearLevelOptionText = year_level_filter.options[year_level_filter.selectedIndex]?.innerText;
    const sectionOptionText = section_filter.options[section_filter.selectedIndex]?.innerText;

    let filterText = `COURSE - YEAR LEVEL - SECTION `;

    if (yearLevelValue) {
        filterText += `${yearLevelOptionText.replace("ST YEAR", "").trim()}`;
    }

    if (yearLevelValue && sectionValue) {
        filterText += ' - ';
    }

    if (sectionValue) {
        filterText += `${sectionOptionText.replace("Section", "").trim()}`;
    }

    academicYearText.textContent = `A.Y ${academic_year}`;
    semesterText.textContent = semesterOptionText;
    classIdentifierInfoText.textContent = filterText;
}

initializeTextContent();

fetchSchedules(academic_year, semester_filter.value, course_filter.value, year_level_filter.value, section_filter.value);