function filterItems(category) {
    
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            
            document.getElementById("index-products").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "/pages/filter.php?category=" + category, true);
    xhttp.send();
}
