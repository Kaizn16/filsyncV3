// CALENDAR
const header = document.querySelector('.calendar-container .header h3');
const dates = document.querySelector('.calendar-container .dates');
const navs = document.querySelectorAll('.calendar-container #prev, .calendar-container #next');
const todayBtn = document.querySelector('#today');
const monthSelect = document.createElement('select');
const yearInput = document.createElement('input');
yearInput.type = 'number';
yearInput.min = 1900;
yearInput.max = 2100;

const months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

// Populate month dropdown
months.forEach((month, index) => {
    let option = document.createElement('option');
    option.value = index;
    option.textContent = month;
    monthSelect.appendChild(option);
});

// Append controls to the header
header.innerHTML = "";
header.appendChild(monthSelect);
header.appendChild(yearInput);

// Get Easter Date
function getEasterDate(year) {
    const a = year % 19;
    const b = Math.floor(year / 100);
    const c = year % 100;
    const d = Math.floor(b / 4);
    const e = b % 4;
    const f = Math.floor((b + 8) / 25);
    const g = Math.floor((b - f + 1) / 3);
    const h = (19 * a + b - d - g + 15) % 30;
    const i = Math.floor(c / 4);
    const k = c % 4;
    const l = (32 + 2 * e + 2 * i - h - k) % 7;
    const m = Math.floor((a + 11 * h + 22 * l) / 451);
    const month = Math.floor((h + l - 7 * m + 114) / 31);
    const day = ((h + l - 7 * m + 114) % 31) + 1;
    return { month: month - 1, day: day };
}

// Get Holidays
function getVariableHolidays(year) {
    const easter = getEasterDate(year);
    return [
        { month: easter.month, day: easter.day - 3, name: "Maundy Thursday" },
        { month: easter.month, day: easter.day - 2, name: "Good Friday" },
        { month: easter.month, day: easter.day + 1, name: "Black Saturday" }
    ];
}

function getLastMondayOfAugust(year) {
    const augustLastDay = new Date(year, 7, 31);
    const lastMonday = new Date(augustLastDay);
    lastMonday.setDate(augustLastDay.getDate() - ((augustLastDay.getDay() + 6) % 7));
    return lastMonday;
}

function getHolidays(year) {
    return [
        { month: 0, day: 1, name: "New Year's Day" },
        { month: 1, day: 14, name: "Valentine's Day" },
        { month: 4, day: 1, name: "Labor Day" },
        { month: 5, day: 12, name: "Independence Day" },
        { month: 7, day: getLastMondayOfAugust(year).getDate(), name: "National Heroes Day" },
        { month: 10, day: 30, name: "Bonifacio Day" },
        { month: 11, day: 25, name: "Christmas Day" },
        { month: 11, day: 30, name: "Rizal Day" },
        { month: 0, day: 22, name: "Chinese New Year" },
        { month: 1, day: 25, name: "EDSA People Power Revolution Anniversary" },
        { month: 7, day: 21, name: "Ninoy Aquino Day" },
        { month: 10, day: 1, name: "All Saints' Day" },
        { month: 10, day: 2, name: "All Souls' Day" },
        { month: 11, day: 8, name: "Feast of the Immaculate Conception" },
        { month: 11, day: 24, name: "Christmas Eve" },
        { month: 11, day: 31, name: "New Year's Eve" },
        ...getVariableHolidays(year)
    ];
}

let date = new Date();
let month = date.getMonth();
let year = date.getFullYear();

function renderCalendar() {
    const holidays = getHolidays(year);
    const start = new Date(year, month, 1).getDay();
    const endDate = new Date(year, month + 1, 0).getDate();
    const endDatePrev = new Date(year, month, 0).getDate();

    let datesHtml = '';

    for (let i = start; i > 0; i--) {
        datesHtml += `<li class="inactive">${endDatePrev - i + 1}</li>`;
    }

    for (let i = 1; i <= endDate; i++) {
        let className = '';
        let title = '';

        if (i === date.getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) {
            className = 'today';
            title = 'Today';
        }

        const holiday = holidays.find(h => h.month === month && h.day === i);
        if (holiday) {
            className = className ? `${className} holiday` : 'holiday';
            title = holiday.name;
        }

        datesHtml += `<li class="${className}" title="${title}">${i}</li>`;
    }

    const end = new Date(year, month, endDate).getDay();
    for (let i = end; i < 6; i++) {
        datesHtml += `<li class="inactive">${i - end + 1}</li>`;
    }

    dates.innerHTML = datesHtml;
    monthSelect.value = month;
    yearInput.value = year;
}

// Handle Navigation Buttons
navs.forEach(nav => {
    nav.addEventListener('click', e => {
        if (e.target.id === 'prev') {
            if (month === 0) {
                year--;
                month = 11;
            } else {
                month--;
            }
        } else if (e.target.id === 'next') {
            if (month === 11) {
                year++;
                month = 0;
            } else {
                month++;
            }
        }
        renderCalendar();
    });
});

todayBtn.addEventListener('click', () => {
    date = new Date();
    month = date.getMonth();
    year = date.getFullYear();
    renderCalendar();
});

// Handle Month Selection
monthSelect.addEventListener('change', () => {
    month = parseInt(monthSelect.value);
    renderCalendar();
});

// Handle Year Input
yearInput.addEventListener('input', () => {
    const newYear = parseInt(yearInput.value);
    if (newYear >= 1900 && newYear <= 2100) {
        year = newYear;
        renderCalendar();
    }
});

renderCalendar();