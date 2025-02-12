// CALENDAR
const header = document.querySelector('.calendar-container .header h3');
const dates = document.querySelector('.calendar-container .dates');
const navs = document.querySelectorAll('.calendar-container #prev, .calendar-container #next');

const months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

// calculate the holiday base on the Easter Date / Leap Year
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

// Get the accurate day of holidays that change based on year
function getVariableHolidays(year) {
    const easter = getEasterDate(year);

    // Calculate holidays based on Easter
    const maundyThursday = new Date(year, easter.month, easter.day - 3); // Maundy Thursday
    const goodFriday = new Date(year, easter.month, easter.day - 2); // Good Friday
    const blackSaturday = new Date(year, easter.month, easter.day + 1); // Black Saturday

    return [
        { month: maundyThursday.getMonth(), day: maundyThursday.getDate(), name: "Maundy Thursday" },
        { month: goodFriday.getMonth(), day: goodFriday.getDate(), name: "Good Friday" },
        { month: blackSaturday.getMonth(), day: blackSaturday.getDate(), name: "Black Saturday" }
    ];
}

function getLastMondayOfAugust(year) {
    const augustLastDay = new Date(year, 7, 31); // August 31 of the given year
    const lastMonday = new Date(augustLastDay);
    lastMonday.setDate(augustLastDay.getDate() - ((augustLastDay.getDay() + 6) % 7));
    return lastMonday;
}

function getHolidays(year) {
    const fixedHolidays = [
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
        { month: 11, day: 31, name: "New Year's Eve" }
    ];

    const variableHolidays = getVariableHolidays(year);

    return [...fixedHolidays, ...variableHolidays];
}

let date = new Date();
let month = date.getMonth();
let year = date.getFullYear();

function renderCalendar() {
    const holidays = getHolidays(year); // Fetch holidays for the current year

    const start = new Date(year, month, 1).getDay();
    const endDate = new Date(year, month + 1, 0).getDate();
    const end = new Date(year, month, endDate).getDay();
    const endDatePrev = new Date(year, month, 0).getDate();

    let datesHtml = '';

    // Previous month's last few days
    for (let i = start; i > 0; i--) {
        datesHtml += `<li class="inactive">${endDatePrev - i + 1}</li>`;
    }

    // Current month's days
    for (let i = 1; i <= endDate; i++) {
        let className = '';
        let title = '';

        if (i === date.getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) {
            className = 'today';
            title = 'Today';
        }

        // Check if the current day is a holiday
        const holiday = holidays.find(h => h.month === month && h.day === i);
        if (holiday) {
            className = className ? `${className} holiday` : 'holiday';
            title = holiday.name;
        }

        datesHtml += `<li class="${className}" title="${title}">${i}</li>`;
    }

    // Next month's first few days
    for (let i = end; i < 6; i++) {
        datesHtml += `<li class="inactive">${i - end + 1}</li>`;
    }

    dates.innerHTML = datesHtml;
    header.textContent = `${months[month]} ${year}`;
}


// Handle the Previous and Next Button
navs.forEach(nav => {
    nav.addEventListener('click', e => {
        const btnId = e.target.id;

        if (btnId === 'prev' && month === 0) {
            year--;
            month = 11;
        } else if (btnId === 'next' && month === 11) {
            year++;
            month = 0;
        } else {
            month = (btnId === 'next') ? month + 1 : month - 1;
        }

        date = new Date(year, month, date.getDate());
        renderCalendar();
    });
});

renderCalendar(); // Render the Calendar