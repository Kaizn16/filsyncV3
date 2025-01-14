let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const FETCH_ROOMS_ROUTE = route(`${authUserRole}.fetch.rooms`);


document.getElementById('NewRoom').addEventListener('click', function () {
    openRoomModal('create');
});

function editRoom(editButton) {
    var roomData = JSON.parse(editButton.getAttribute('data-room'));
    openRoomModal('edit', roomData);
}

function openRoomModal(mode, roomData = {})
{
    let isEditMode = mode === 'edit';
    let modalTitle = isEditMode ? 'Edit Room' : 'New Room';
    let buttonText = isEditMode ? 'SAVE' : 'ADD';

    let ROOM_ROUTE = isEditMode ? route(`${authUserRole}.update.room`) : route(`${authUserRole}.store.room`); 

    Swal.fire({
        html: `
             <section class="form-wrapper">
                <div class="form-container">
                    <header class="form-header">
                        <h1 class="title">${modalTitle}</h1>
                        <span class="close"><i class="material-icons icon">close</i></span>
                    </header>
                    <form class="form-content" method="POST" action="${ROOM_ROUTE}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="${isEditMode ? 'PUT' : 'POST'}">
                        <input type="hidden" name="room_id" value="${roomData ? roomData.room_id : ''}"> 


                        <div class="form-group-col">
                            <div class="form-group">
                                <label for="building_name">Building Name <strong class="required">*</strong></label>
                                <input type="text" name="building_name" id="building_name" value="${roomData?.building_name ?? formOldInputs?.building_name ?? ''}" placeholder="Building Name" />
                            </div>

                            <div class="form-group">
                                <label for="room_name">Room Name <strong class="required">*</strong></label>
                                <input type="text" name="room_name" id="room_name" value="${roomData?.room_name ?? formOldInputs?.room_name ?? ''}" placeholder="Room Name" />
                            </div>

                            <div class="form-group">
                                <label for="max_seat">Max Seats <strong class="required">*</strong></label>
                                <input type="number" name="max_seat" id="max_seat" value="${roomData?.max_seat ?? formOldInputs?.building_name ?? ''}" placeholder="Max Seats" />
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
        preConfirm: () => {
            const buildingName = document.getElementById('building_name').value.trim();
            const roomName = document.getElementById('room_name').value.trim();
            const maxSeat = document.getElementById('max_seat').value.trim();
            
            if (!buildingName && !roomName && !maxSeat) {
                Swal.showValidationMessage('All fields are required!');
                return false;
            }

            if (!buildingName) {
                Swal.showValidationMessage('The "Building Name" field is required!');
                return false;
            }
        
            if (!roomName) {
                Swal.showValidationMessage('The "Room Name" field is required!');
                return false;
            }
        
            if (!maxSeat) {
                Swal.showValidationMessage('The "Max Seats" field is required!');
                return false;
            }
        
            if (isNaN(maxSeat) || maxSeat <= 0) {
                Swal.showValidationMessage('The "Max Seats" field must be a greater than 0!');
                return false;
            }
        
            return true;
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

function deleteRoom(room_id) {
    var room_id = JSON.parse(room_id.getAttribute('data-room_id'));
    const DELETE_ROOM_ROUTE = route(`${authUserRole}.delete.room`);

    Swal.fire({
        html: `
          <section class="form-wrapper">
                <div class="form-container">
                    <header class="form-header">
                        <h1 class="title">Delete</h1>
                        <span class="close"><i class="material-icons icon">close</i></span>
                    </header>
                    <form id="deleteForm" class="form-content" method="POST" action="${DELETE_ROOM_ROUTE}">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="room_id" value="${room_id}">
                        <strong class="info">Are you sure you want to delete this room?</strong>
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



const searchInput = document.getElementById('search');
const buildingFilter = document.getElementById('buildingFilter');
const tableData = document.querySelector('.tableData');
let debounceTimer;
let currentPage = 1;

async function fetchRooms(search = '', building = '', page = 1) {
    try {
        const response = await fetch(`${FETCH_ROOMS_ROUTE}?search=${search}&building=${building}&page=${page}`);
        const data = await response.json();

        
        populateTable(data.data);
        updatePagination(data);
    } catch (error) {
        console.error('Error fetching users:', error);
    }
}

function populateTable(rooms) {
    tableData.innerHTML = '';
    if (rooms.length === 0) {
        tableData.innerHTML = '<tr><td colspan="4" style="text-align:center">No results found</td></tr>';
        return;
    }

    rooms.forEach(room => {
        const row = `
            <tr>
                <td>${room.building_name}</td>
                <td>${room.room_name}</td>
                <td>${room.max_seat}</td>
                <td>
                   <div class="actions">
                        <span class="action edit" data-room='${JSON.stringify(room)}' onclick="event.stopPropagation(); editRoom(this);"><i class="material-icons icon" title="Edit">edit_square</i></span>
                        <span class="action delete" data-room_id='${room.room_id}' onclick="event.stopPropagation(); deleteRoom(this);"><i class="material-icons icon" title="Delete">delete</i></span>
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
            fetchRooms(searchInput.value, buildingFilter.value, currentPage);
        };
    } else {
        previousButton.classList.add('disabled');
        previousButton.onclick = null;
    }

    
    if (data.next_page_url) {
        nextButton.classList.remove('disabled');
        nextButton.onclick = () => {
            currentPage++;
            fetchRooms(searchInput.value, buildingFilter.value, currentPage);
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
        const buildingValue = buildingFilter.value;
        currentPage = 1;
        fetchRooms(searchValue, buildingValue, currentPage);
    }, 300);
});

buildingFilter.addEventListener('change', () => {
    const searchValue = searchInput.value;
    const buildingValue = buildingFilter.value;
    currentPage = 1;
    fetchRooms(searchValue, buildingValue, currentPage);
});

fetchRooms();