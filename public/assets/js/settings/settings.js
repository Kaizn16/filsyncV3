let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userRoleMeta = document.querySelector('meta[name="user-role"]');
const authUserRole = userRoleMeta ? userRoleMeta.getAttribute('content') : null;
const changeThemeRoute = route(`${authUserRole}.changeTheme`);
let debounceTimeout;

function debounce(func, delay) {
    return function (...args) {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => func.apply(this, args), delay);
    };
}

function changeTheme(theme) {
    fetch(changeThemeRoute, {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
            theme: theme,
            _method: 'PUT',
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (theme === 'light') {
                body.classList.remove("dark");
                document.querySelector('.setting-options .option .toggle-theme .icon.active')?.classList.remove('active');
                document.querySelector('.light-mode').classList.add('active');
            } else {
                body.classList.add("dark");
                document.querySelector('.setting-options .option .toggle-theme .icon.active')?.classList.remove('active');
                document.querySelector('.dark-mode').classList.add('active');
            }
            showToast("info", `Theme has been set to ${theme} mode.`);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

document.querySelector('.light-mode').addEventListener('click', debounce(function() {
    changeTheme('light');
}, 600));

document.querySelector('.dark-mode').addEventListener('click', debounce(function() {
    changeTheme('dark');
}, 600));