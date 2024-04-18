document.addEventListener("DOMContentLoaded", function() {
  
  const button = document.querySelector("#profile-button");
  const link = document.querySelector("#login-register-anchor");
  
  button.addEventListener("mouseenter", function() {
    link.classList.add("visible");
  });
  
  button.addEventListener("mouseleave", function(event) {
    // Verifica se o mouse entrou no link, se sim, não remova a classe "visible"
    if (!link.contains(event.relatedTarget)) {
      link.classList.remove("visible");
    }
  });
  
  link.addEventListener("mouseenter", function() {
    link.classList.add("visible");
  });
  
  link.addEventListener("mouseleave", function(event) {
    // Verifica se o mouse voltou para o botão, se sim, não remova a classe "visible"
    if (!button.contains(event.relatedTarget)) {
      link.classList.remove("visible");
    }
  });
});
