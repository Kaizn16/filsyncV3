const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const searchInput = document.getElementById('search');
const academic_year_filter = document.getElementById('academic_year_filter');
const semester_filter = document.getElementById('semester_filter');
const status_filter = document.getElementById('status_filter');
let debounceTimer;
let currentPage = 1;

function formatTime(time) {
    if (!time) return 'N/A';
    const [hour, minute] = time.split(':').map(Number);
    const formattedHour = hour % 12 || 12;
    const period = hour >= 12 ? 'PM' : 'AM';
    return `${formattedHour}:${minute.toString().padStart(2, '0')} ${period}`;
}

async function fetchSchedulesDraft(search = '', academic_year = '', semester = '', status = '', page = 1) {

    const FETCH_SCHEDULES_DRAFT = route(`${authUserRole}.fetch.schedules.draft`);

    try {
        const response = await fetch(`${FETCH_SCHEDULES_DRAFT}?search=${search}&academic_year=${academic_year}&semester=${semester}&status=${status}&page=${page}`);
        const data = await response.json();
        populateTable(data);
        updatePagination(data);
    } catch (error) {
        console.error('Error fetching schedules drafts:', error);
    }
}

function populateTable(data) {
    const tableBody = document.querySelector('.tableData');
    tableBody.innerHTML = '';

    const drafts = data.data; 

    if (drafts.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="4" style="text-align:center">No schedules found</td></tr>';
        return;
    }

    drafts.forEach((draft, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${(data.current_page - 1) * data.per_page + index + 1}</td>
            <td>${draft.draft_name}</td>
            <td>
                <span class="draft-status">
                    <p class="${draft.status.toUpperCase()}">${draft.status.toUpperCase()}</p>
                </span>
            </td>
            <td>
                <div class="actions">
                    <a href="${route('vpaa.view.schedules', {schedule_draft_id: draft.schedule_draft_id})}" class="action view viewFinalizedDraft">
                        <i class="material-icons icon" title="View Draft">visibility</i>
                    </a>
                </div>
            </td>
        `;
        tableBody.appendChild(row);
    });
}


function updatePagination(data) {
    const previousButton = document.querySelector('.pagination .previous');
    const nextButton = document.querySelector('.pagination .next');

    if (data.prev_page_url) {
        previousButton.classList.remove('disabled');
        previousButton.onclick = () => {
            currentPage--;
            fetchSchedulesDraft(
                searchInput.value,
                academic_year_filter.value,
                semester_filter.value,
                status_filter.value,
                currentPage
            );
        };
    } else {
        previousButton.classList.add('disabled');
        previousButton.onclick = null;
    }

    if (data.next_page_url) {
        nextButton.classList.remove('disabled');
        nextButton.onclick = () => {
            currentPage++;
            fetchSchedulesDraft(
                searchInput.value,
                academic_year_filter.value,
                semester_filter.value,
                status_filter.value,
                currentPage
            );
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
        const academicYear = academic_year_filter.value;
        const semester = semester_filter.value; 
        const status = status_filter.value;
        currentPage = 1;

        fetchSchedulesDraft(searchValue, academicYear, semester, status, currentPage);

    }, 300);
});

function handleFiltersChange() {
    const academicYear = academic_year_filter.value;
    const semester = semester_filter.value; 
    const status = status_filter.value;
    currentPage = 1;

    fetchSchedulesDraft(searchInput.value, academicYear, semester, status, currentPage);
}

academic_year_filter.addEventListener('change', handleFiltersChange);
semester_filter.addEventListener('change', handleFiltersChange);
status_filter.addEventListener('change', handleFiltersChange);

fetchSchedulesDraft();