let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const FETCH_USERS_ROUTE = route(`${authUserRole}.fetch.users`);


const searchInput = document.getElementById('search');
const positionFilter = document.getElementById('positionFilter');
const tableData = document.querySelector('.tableData');
let debounceTimer;
let currentPage = 1;

async function fetchUsers(search = '', position = '', page = 1) {
    try {
        const response = await fetch(`${FETCH_USERS_ROUTE}?search=${search}&position=${position}&page=${page}`);
        const data = await response.json();

        
        populateTable(data.data);
        updatePagination(data);
    } catch (error) {
        console.error('Error fetching users:', error);
    }
}

function populateTable(users) {
    tableData.innerHTML = '';
    if (users.length === 0) {
        tableData.innerHTML = '<tr><td colspan="4" style="text-align:center">No results found</td></tr>';
        return;
    }

    users.forEach(user => {
        const row = `
            <tr>
                <td>${user.first_name} ${user.middle_name ? user.middle_name[0] + '.' : ''} ${user.last_name}</td>
                <td>${user.email}</td>
                <td>
                    <span class="position">
                        <p class="${user.position}">${user.position}</p>
                    </span>
                </td>
                <td>
                   <div class="actions">
                        <span class="action view" data-user='${JSON.stringify(user)}' onclick="event.stopPropagation(); viewUser(this);"><i class="material-icons icon" title="View">visibility</i></span>
                        <span class="action edit" data-user='${JSON.stringify(user)}' onclick="event.stopPropagation(); editUser(this);"><i class="material-icons icon" title="Edit">edit_square</i></span>
                        <span class="action delete" data-user_id='${user.user_id}' onclick="event.stopPropagation(); deleteUser(this);"><i class="material-icons icon" title="Delete">delete</i></span>
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
            fetchUsers(searchInput.value, positionFilter.value, currentPage);
        };
    } else {
        previousButton.classList.add('disabled');
        previousButton.onclick = null;
    }

    
    if (data.next_page_url) {
        nextButton.classList.remove('disabled');
        nextButton.onclick = () => {
            currentPage++;
            fetchUsers(searchInput.value, positionFilter.value, currentPage);
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
        currentPage = 1;
        fetchUsers(searchValue, currentPage);
    }, 300);
});


fetchUsers();


document.getElementById('NewUser').addEventListener('click', function () {
    openUserModal('create');
});

function editUser(editButton) {
    var userData = JSON.parse(editButton.getAttribute('data-user'));
    openUserModal('edit', userData);
}


function openUserModal(mode, userData = {})
{
    let isEditMode = mode === 'edit';
    let modalTitle = isEditMode ? 'Edit User' : 'New User';
    let buttonText = isEditMode ? 'SAVE' : 'ADD';

    let username = userData.username || '';
    let first_name = userData.first_name || '';
    let middle_name = userData.middle_name || '';
    let last_name = userData.last_name || '';
    let gender = userData.gender_id || '';
    let email = userData.email || '';
    let contact_no = userData.contact_no || '';
    let position = userData.position || '';

    const USER_ROUTE = isEditMode ? route(`${authUserRole}.update.user`) : route(`${authUserRole}.store.user`);

    Swal.fire({
        html: `
             <section class="form-wrapper">
                <div class="form-container">
                    <header class="form-header">
                        <h1 class="title">${modalTitle}</h1>
                        <span class="close"><i class="material-icons icon">close</i></span>
                    </header>
                    <form class="form-content" method="POST" action="${USER_ROUTE}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="${isEditMode ? 'PUT' : 'POST'}">
                        <input type="hidden" name="user_id" value="${userData.user_id ?? ''}">
                        
                        <strong class="form-group-title">Account Info</strong>
                        <div class="form-group-row">
                            <div class="form-group">
                                <label for="username">Username <strong class="required">*</strong></label>
                                <input type="text" name="username" id="username" value="${username}" placeholder="Username" />
                            </div>

                            <div class="form-group">
                                <label for="password">Password <strong class="required">*</strong></label>
                                <input  type="password" name="password" id="password" placeholder="Password" />
                                <span id="toggle-eye-icon">
                                    <i class="material-icons icon">visibility</i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-group-col">
                            <div class="form-group-row">
                                <div class="form-group">
                                    <label for="first_name">First Name <strong class="required">*</strong></label>
                                    <input type="text" name="first_name" id="first_name" value="${first_name}" placeholder="First Name" />
                                </div>
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" value="${middle_name}" placeholder="Middle Name" />
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name <strong class="required">*</strong></label>
                                    <input type="text" name="last_name" id="last_name" value="${last_name}" placeholder="Last Name" />
                                </div>
                                <div class="form-group">
                                    <label for="gender_id">Gender <strong class="required">*</strong></label>
                                    <select name="gender_id" id="gender_id">
                                        <option selected disabled>Gender</option>
                                        <option value="1" ${gender == 1 ? 'selected' : ''}>Male</option>
                                        <option value="2" ${gender == 2 ? 'selected' : ''}>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group-row">
                                <div class="form-group">
                                    <label for="position">Position <strong class="required">*</strong></label>
                                    <select name="position" id="position">
                                        <option selected disabled>Position</option>
                                        <option value="SECRETARY" ${position == 'SECRETARY' ? 'selected' : ''}>SECRETARY</option>
                                        <option value="TEACHER" ${position == 'TEACHER' ? 'selected' : ''}>TEACHER</option>
                                    </select>
                                </div>
                                <div class="form-group" id="departmentSelection" style="display: none">
                                    <label for="department">Department <strong class="required">*</strong></label>
                                    <select name="department" id="department">
                                        <option selected disabled>Department</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <strong class="form-group-title">Contact Info</strong>

                        <div class="form-group-row">
                            <div class="form-group">
                                <label for="contact_no">Contact No <strong class="required">*</strong></label>
                                <input type="tel" name="contact_no" id="contact_no" value="${contact_no}" placeholder="Contact Number" />
                            </div>
                            <div class="form-group">
                                <label for="email">Email <strong class="required">*</strong></label>
                                <input type="email" name="email" id="email" value="${email}" placeholder="Email" />
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
        didOpen: () => {
            document.getElementById('toggle-eye-icon').addEventListener('click', function (e) {
                e.preventDefault(); 
                const passwordField = document.getElementById('password');
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                this.querySelector('.material-icons').innerText = type === 'password' ? 'visibility' : 'visibility_off';
            });

            const positionSelect = document.getElementById('position');
            const departmentSelect = document.getElementById('department');
            const departmentSelection = document.getElementById('departmentSelection');

            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    const context = this;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }

            function fetchDepartments() {
                const FETCH_DEPARTMENTS_ROUTE = route('fetch.departments');
                const selectedPosition = positionSelect.value;
                departmentSelect.innerHTML = '';

                if (selectedPosition === 'VPAA' || selectedPosition === 'REGISTRAR') {
                    departmentSelection.style.display = 'none';
                } else {
                    departmentSelection.style.display = 'block';

                    fetch(FETCH_DEPARTMENTS_ROUTE + `?position=${selectedPosition}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(dep => {
                                const option = document.createElement('option');
                                option.value = dep.department_id;
                                option.text = dep.department_name;
                                departmentSelect.appendChild(option);
                            });

                            if (isEditMode && userData.department_id) {
                                departmentSelect.value = userData.department_id;
                            }
                        })
                        .catch(error => console.error('Error fetching departments:', error));
                }
            }

            positionSelect.addEventListener('change', debounce(fetchDepartments, 600));

            if (userData.position) {
                fetchDepartments();
            }
            
        },
        preConfirm: () => {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const firstName = document.getElementById('first_name').value;
            const lastName = document.getElementById('last_name').value;
            const gender = document.getElementById('gender_id').value;
            const position = document.getElementById('position').value;
            const contactNo =  document.getElementById('contact_no').value;
            const email =  document.getElementById('email').value;

            if (!username || !firstName || !lastName || !gender || !position || !contactNo || !email) {
                Swal.showValidationMessage('Please fill out all fields');
                return false;
            }
            return { username, password, firstName, lastName, gender, position, contactNo, email};
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.querySelector('.form-content');
            form.submit();
        }
    });
    document.querySelector('.custom-swal-popup .close').addEventListener('click', () => {
        Swal.close();
    });    
}


function viewUser(userData)
{
    var user = JSON.parse(userData.getAttribute('data-user'));

    const htmlContent = `
    <section class="info-container">
        <header class="header">
            <h1>View User</h1>
        </header>
        <div class="info-details">

            <div class="info-group">
                <strong>Username:</strong>
                <p>${user.username}</p>
            </div>

            <div class="info-group-row">
                <div class="info-group">
                    <strong>Fullname:</strong>
                    <p>${user.first_name} ${user.middle_name ? user.middle_name[0] + '.' : ''} ${user.last_name}</p>
                </div>
                <div class="info-group">
                    <strong>Gender:</strong>
                    <p>${user.gender_id == 1 ? 'Male' : 'Female'}</p>
                </div>
            </div>
            
            <div class="info-group-row">
                <div class="info-group">
                    <strong>Position:</strong>
                    <p>${user.position}</p>
                </div>
                ${user.department && user.department.department_name
                    ? `<div class="info-group">
                        <strong>Department:</strong>
                        <p>${user.department.department_name}</p>
                    </div>`: ''
                }
            </div>

            <div class="info-group-row">
                <div class="info-group">
                    <strong>Contact Number:</strong>
                    <p>${user.contact_no}</p>
                </div>

                <div class="info-group">
                    <strong>Email:</strong>
                    <p>${user.email}</p>
                </div>

            </div>
        </div>
    </section>
    `;

    Swal.fire({
        html: htmlContent,
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonColor: "#d33",
        cancelButtonText: 'CLOSE',
        customClass: {
            popup: 'custom-swal-popup',
        },
    });
}


function deleteUser(user_id) {
    var user_id = JSON.parse(user_id.getAttribute('data-user_id'));
    const DELETE_USER_ROUTE = route(`${authUserRole}.delete.user`);

    Swal.fire({
        html: `
          <section class="form-wrapper">
                <div class="form-container">
                    <header class="form-header">
                        <h1 class="title">Delete</h1>
                        <span class="close"><i class="material-icons icon">close</i></span>
                    </header>
                    <form id="deleteForm" class="form-content" method="POST" action="${DELETE_USER_ROUTE}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="user_id" value="${user_id}">
                        <strong class="info">Are you sure you want to delete this user?</strong>
                    </form>
                </div>
            </section>
        `,
        confirmButton: true,
        confirmButtonText: 'YES',
        confirmButtonColor: '#5296BE',
        showCancelButton: true,
        cancelButtonColor: "#d33",
        cancelButtonText: 'CANCEL',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal-popup',
        },
        preConfirm: () => {
            document.getElementById('deleteForm').submit();
        }
    });

    document.querySelector('.custom-swal-popup .close').addEventListener('click', () => {
        Swal.close();
    });  
}