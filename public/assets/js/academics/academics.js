let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const FETCH_DEPARTMENTS_ROUTE = route('fetch.departments');
const SUBJECTS_ROUTE = route(`${authUserRole}.academics.subjects`);
const DEBOUNCE_DELAY = 600;

let debounceTimeout = null;

async function fetchAndPopulateDepartments(searchTerm = '') {
    try {
        const url = new URL(FETCH_DEPARTMENTS_ROUTE);
        if (searchTerm) {
            url.searchParams.append('search', searchTerm);
        }

        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`Error: ${response.statusText}`);
        }

        const departments = await response.json();

        const listContainer = document.querySelector('.departments-list');
        listContainer.innerHTML = '';

        if (departments.length === 0) {
            listContainer.innerHTML = '<p>No departments found.</p>';
            return;
        }

        departments.forEach(department => {
            const listItemHTML = `
                <li class="item">
                    <a href="${SUBJECTS_ROUTE}?department=${department.abbreviation}" class="link">
                        <strong class="title">${department.department_name} (${department.abbreviation})</strong>
                        <i class="material-icons icon">chevron_right</i>
                    </a>
                </li>
            `;

            listContainer.insertAdjacentHTML('beforeend', listItemHTML);
        });
    } catch (error) {
        console.error('Error fetching departments:', error);
    }
}

document.getElementById('search').addEventListener('input', (event) => {
    const searchTerm = event.target.value;

    clearTimeout(debounceTimeout);

    debounceTimeout = setTimeout(() => {
        fetchAndPopulateDepartments(searchTerm);
    }, DEBOUNCE_DELAY);
});

fetchAndPopulateDepartments();