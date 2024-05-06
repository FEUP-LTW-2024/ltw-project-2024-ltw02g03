var priceSlider = document.getElementById("price-slider");
var minPriceDisplay = document.getElementById("min-price");
var maxPriceDisplay = document.getElementById("max-price");
var priceDisplay = document.getElementById("price-display");

noUiSlider.create(priceSlider, {
    start: [0, 10000], 
    connect: true, 
    step: 10, 
    range: {
        'min': 0,
        'max': 10000
    }
});

priceSlider.noUiSlider.on('update', function (values, handle) {
    var value = values[handle];
    
    if (handle) {
        maxPriceDisplay.textContent = "€" + value;
    } else {
        minPriceDisplay.textContent = "€" + value;
    }

    priceDisplay.textContent = "€" + minPriceDisplay.textContent.substring(1) + " - €" + maxPriceDisplay.textContent.substring(1);
});

function applyFilters() {
    var selectedCategory = document.getElementById("category-select").value;
    var selectedBrand = document.getElementById("brand-select").value;
    var selectedCondition = document.getElementById("condition-select").value;
    var selectedSize = document.getElementById("size-select").value;
    var selectedModel = document.getElementById("model-select").value;
    var minPrice = minPriceDisplay.textContent.substring(1); 
var maxPrice = maxPriceDisplay.textContent.substring(1);
    if (selectedCategory === "" && selectedBrand === "" && selectedCondition === "" && selectedSize === "" && selectedModel === "") {
        return;
    }
    
    var url = "/pages/filter.php?";
    if (selectedCategory) {
        url += "category=" + selectedCategory + "&";
    }
    if (selectedBrand) {
        url += "brand=" + selectedBrand + "&";
    }
    if (selectedCondition) {
        url += "condition=" + selectedCondition + "&";
    }
    if (selectedSize) {
        url += "size=" + selectedSize + "&";
    }
    if (selectedModel) {
        url += "model=" + selectedModel + "&";
    }
    url += "minPrice=" + minPrice + "&";
    url += "maxPrice=" + maxPrice;
    
    window.location.href = url;
}

function filterItems(category) {
    
    window.location.href = `/pages/filter.php?category=${category}`;
}

function populateSelect(selectId, data) {
    var selectElement = document.getElementById(selectId);
    selectElement.innerHTML = ''; 
    
    if (data.length > 0) {
        var defaultOption = document.createElement('option');
        defaultOption.value = "";
        defaultOption.textContent = "Selecione...";
        selectElement.appendChild(defaultOption);
    }
    data.forEach(function(item) {
        var option = document.createElement('option');
        option.textContent = item;
        selectElement.appendChild(option);
    });
}



fetch('/templates/route.php?action=get-categories')
    .then(response => response.json())
    .then(data => populateSelect('category-select', data));

fetch('/templates/route.php?action=get-brands')
    .then(response => response.json())
    .then(data => populateSelect('brand-select', data));

fetch('/templates/route.php?action=get-conditions')
    .then(response => response.json())
    .then(data => populateSelect('condition-select', data));

fetch('/templates/route.php?action=get-sizes')
    .then(response => response.json())
    .then(data => populateSelect('size-select', data));

fetch('/templates/route.php?action=get-models')
    .then(response => response.json())
    .then(data => populateSelect('model-select', data));