window.addEventListener("load", function() {
    setTimeout(function() {
        const loadingOverlay = document.getElementById("loading-overlay");
        const content = document.querySelector(".container");

        loadingOverlay.style.opacity = "0";

        setTimeout(() => {
            loadingOverlay.style.visibility = "hidden";
        }, 500);

        content.style.visibility = "visible";
        content.style.opacity = "1";

        document.body.classList.remove('loader-body');
    }, 1200);
});