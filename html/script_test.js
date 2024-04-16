document.addEventListener("DOMContentLoaded", function() {
    const button = document.querySelector("#profile-button");
    button.addEventListener("mouseover", function() {
      const link = document.querySelector("#login-register-anchor");
      link.classList.toggle("visible");
    });
  });