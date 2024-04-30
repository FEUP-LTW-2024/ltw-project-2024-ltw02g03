/*
function filterItems(category) {
    
    fetch(`/pages/filter.php?category=${category}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            document.body.innerHTML = data;
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}

const headerList = document.getElementById('header-list');
if (headerList) {
    headerList.addEventListener('click', function(event) {
        if (event.target.tagName === 'A') {
            // Evitar o comportamento padrão de seguir o link
            event.preventDefault();
            
            const category = event.target.textContent.trim();
            filterItems(category);
        }
    });
}
*/
function filterItems(category) {
    window.location.href = `/pages/filter.php?category=${category}`;
}

