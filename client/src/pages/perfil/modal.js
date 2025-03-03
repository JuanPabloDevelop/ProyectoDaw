// Obtener elementos
let modal = document.getElementById("editUserModal");
let btn = document.getElementById("openModalBtn");
let closeModal = document.getElementById("close");


// Cerrar modal con 'x'
closeModal.addEventListener("click", function() {
    modal.style.display = "none";
});

// Abrir modal
btn.onclick = function() {
    modal.style.display = "block";
}
