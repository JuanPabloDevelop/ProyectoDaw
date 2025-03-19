// Obtener elementos
let modal = document.getElementById("editUserModal") || document.getElementById("editPostModal");
let btn = document.getElementById("openModalBtn");
let closeModal = document.getElementById("close");


// Cerrar modal con 'x'
closeModal.addEventListener("click", function() {
    modal.style.display = "none";
});

// Abrir modal
if (btn) {
    btn.onclick = function() {
        modal.style.display = "block";
    }
}
