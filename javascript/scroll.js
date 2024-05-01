window.addEventListener('scroll', function() {
    var footer = document.getElementById('footer-page');
    var scrollPosition = window.scrollY + window.innerHeight;
    var pageHeight = document.body.scrollHeight;
    
    if (scrollPosition >= pageHeight) {
        footer.style.visibility = 'visible';
    } else {
        footer.style.visibility = 'hidden';
    }
});
