// Obtener elementos
var modal = document.getElementById("editUserModal");
var btn = document.getElementById("openModalBtn");
var span = document.getElementsByClassName("close")[0];

// Abrir modal
btn.onclick = function() {
    modal.style.display = "block";
}

// Cerrar modal con 'x'
span.onclick = function() {
    modal.style.display = "none";
}

// Cerrar modal al hacer clic fuera de la ventana modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
