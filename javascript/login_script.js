document.addEventListener("DOMContentLoaded", function() {
  
  const button = document.querySelector("#profile-button");
  const link = document.querySelector("#login-register-anchor");
  
  button.addEventListener("mouseenter", function() {
    link.classList.add("visible");
  });
  
  button.addEventListener("mouseleave", function(event) {
    if (!link.contains(event.relatedTarget)) {
      link.classList.remove("visible");
    }
  });
  
  link.addEventListener("mouseenter", function() {
    link.classList.add("visible");
  });
  
  link.addEventListener("mouseleave", function(event) {
    if (!button.contains(event.relatedTarget)) {
      link.classList.remove("visible");
    }
  });
});
