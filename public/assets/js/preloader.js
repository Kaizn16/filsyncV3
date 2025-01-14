window.addEventListener("load", function() {
    if (document.readyState === 'complete') {
        setTimeout(function() {
            document.getElementById("loading-overlay").style.visibility = "hidden";
            document.querySelector(".content").style.visibility = "visible";
        }, 800);
    }
});