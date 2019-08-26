function showBoxSearch() {
    var divBoxSearch = document.getElementById("display_box_search");
    var icon = document.getElementById("icon_drown_up");
    var boxDisplay = document.getElementById("box_display");
    if (divBoxSearch.style.display === "block") {
        divBoxSearch.style.display = "none";
        icon.className = "arrow down";
        boxDisplay.style.marginBottom = "10px";
    } else {
        divBoxSearch.style.display = "block";
        icon.className = "arrow up";
        boxDisplay.style.marginBottom = "10px"
    }
}