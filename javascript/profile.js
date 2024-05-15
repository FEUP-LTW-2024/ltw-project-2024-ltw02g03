document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.stars');
    stars.forEach(star => {
        const rating = parseFloat(star.getAttribute('data-rating'));
        const filledStars = '★'.repeat(Math.floor(rating));
        const halfStar = (rating % 1 !== 0) ? '★' : '';
        star.innerHTML = filledStars + halfStar;
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const averageRating = parseFloat(document.getElementById('average-rating').textContent);
    const averageStars = document.getElementById('average-stars');
    const filledStars = '★'.repeat(Math.floor(averageRating));
    const halfStar = (averageRating % 1 !== 0) ? '★' : '';
    averageStars.textContent = filledStars + halfStar;
});
function toggleAdminSection() {
    var adminSection = document.getElementById("admin-section");
    var presentedProducts = document.getElementById("profile-presented");
    var profileEdit = document.getElementById("edit-profile-section");
    var changepass = document.getElementById("change-password");
    var purchaseHistory = document.getElementById("purchase-history");
    var reviewsSection = document.getElementById("reviews-section");
    reviewsSection.style.display = "none";
    adminSection.style.display = "block";
    presentedProducts.style.display = "none";
    profileEdit.style.display = "none";
    changepass.style.display = "none";
    purchaseHistory.style.display = "none";
    
}
function toggleProfileProd() {
    var adminSection = document.getElementById("admin-section");
    var presentedProducts = document.getElementById("profile-presented");
    var profileEdit = document.getElementById("edit-profile-section");
    var changepass = document.getElementById("change-password");
    var purchaseHistory = document.getElementById("purchase-history");
    var reviewsSection = document.getElementById("reviews-section");
    reviewsSection.style.display = "none";
    adminSection.style.display = "none";
    presentedProducts.style.display = "block";
    profileEdit.style.display = "none";
    changepass.style.display = "none";
    purchaseHistory.style.display = "none";
}
    function toggleReviewsSection() {
    var adminSection = document.getElementById("admin-section");
    var presentedProducts = document.getElementById("profile-presented");
    var profileEdit = document.getElementById("edit-profile-section");
    var changepass = document.getElementById("change-password");
    var purchaseHistory = document.getElementById("purchase-history");
    var reviewsSection = document.getElementById("reviews-section");

    adminSection.style.display = "none";
    presentedProducts.style.display = "none";
    profileEdit.style.display = "none";
    changepass.style.display = "none";
    purchaseHistory.style.display = "none";
    reviewsSection.style.display = "block";
}

function toggleEditProfile() {
    var adminSection = document.getElementById("admin-section");
    var presentedProducts = document.getElementById("profile-presented");
    var profileEdit = document.getElementById("edit-profile-section");
    var changepass = document.getElementById("change-password");
    var purchaseHistory = document.getElementById("purchase-history");
    var reviewsSection = document.getElementById("reviews-section");
    reviewsSection.style.display = "none";
    adminSection.style.display = "none";
    presentedProducts.style.display = "none";
    profileEdit.style.display = "block";
    changepass.style.display = "none";
    purchaseHistory.style.display = "none";
}

function toggleChangePass() {
    var adminSection = document.getElementById("admin-section");
    var presentedProducts = document.getElementById("profile-presented");
    var profileEdit = document.getElementById("edit-profile-section");
    var changepass = document.getElementById("change-password");
    var purchaseHistory = document.getElementById("purchase-history");
    var reviewsSection = document.getElementById("reviews-section");
    reviewsSection.style.display = "none";
    adminSection.style.display = "none";
    presentedProducts.style.display = "none";
    profileEdit.style.display = "none";
    changepass.style.display = "block";
    purchaseHistory.style.display = "none";
}
function togglePurchaseHistory() {
    var adminSection = document.getElementById("admin-section");
    var presentedProducts = document.getElementById("profile-presented");
    var profileEdit = document.getElementById("edit-profile-section");
    var changepass = document.getElementById("change-password");
    var purchaseHistory = document.getElementById("purchase-history");
    var reviewsSection = document.getElementById("reviews-section");
    reviewsSection.style.display = "none";
    adminSection.style.display = "none";
    presentedProducts.style.display = "none";
    profileEdit.style.display = "none";
    changepass.style.display = "none";
    purchaseHistory.style.display = "block"; 
}


function previewImage(event) {
const file = event.target.files[0]; // Get the first file from the selected files

if (file) {
    const reader = new FileReader();
    const previewImage = document.getElementById('profile-img');

    reader.onloadend = function () {
        previewImage.style.display = "block";
        previewImage.src = reader.result;
    }

    reader.readAsDataURL(file);
} else {
    previewImage.style.display = "none"; // Hide the preview image if no file is selected
    previewImage.src = "";
}
}



    
function previewImages(event, startIndex) {
const files = event.target.files;

for (let i = 0; i < files.length; i++) {
    const file = files[i];
    const reader = new FileReader();
    const previewImage = document.getElementById(`preview-image-${startIndex + i}`);

    reader.onloadend = function () {
        previewImage.style.display = "block";
        previewImage.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        previewImage.style.display = "none"; // Hide the preview image if no file is selected
        previewImage.src = "";
    }
}
}
