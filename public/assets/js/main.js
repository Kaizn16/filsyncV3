function updateDarkModeFromDatabase() {
    fetch('/api/user/settings')
        .then(response => response.json())
        .then(data => {
            if (data.mode === "dark") {
                body.classList.add("dark");
            } else {
                body.classList.remove("dark");
            }
        })
        .catch(error => console.error("Failed to fetch user settings:", error));
}
updateDarkModeFromDatabase();