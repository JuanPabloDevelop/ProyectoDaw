import { handleSkeleton, showErrorMessage } from '../../../../js/helper.js';

const ACCESS_KEY = 'EUnkfYoD-vf16kTu2aEFsVhRy8ySh9JORf--ucs-X6o';

document.addEventListener("DOMContentLoaded", async () => {

  try {
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");
    if(!id) {
      handleSkeleton();
      showErrorMessage("No se ha encontrado el id de la imagen.");
      return;
    }
    getImageById(id); 

  } catch (error) {
    showErrorMessage(error.message);
    console.error(error);
  }
  
});

// Función para cargar imágenes desde Unsplash
async function getImageById(id) {
  try { 
    const response = await fetch(`https://api.unsplash.com/photos/${id}?client_id=${ACCESS_KEY}`);
    if (!response.ok) {
      throw new Error('Error fetching images.');
    }
    const data = await response.json();

    displayInfo(data);
  } catch (error) {
    showErrorMessage(error.message);
    console.error(error);
  }
}

// Función para mostrar imágenes en la galería
function displayInfo(data) {
  document.getElementById('imagen').src = data.urls.small;
  document.getElementById('imagen').alt = data.alt_description;
  document.getElementById('imagen').classList.remove('hidden');
  document.getElementById('titulo').textContent = data.alt_description;
  document.getElementById('autor').innerHTML = `Photo by <a href="${data.user.links.html}" target="_blank">${data.user.name}</a>`;
  document.getElementById('descripcion').textContent = data.description || 'No description available';
  document.getElementById('likes').textContent = data.likes;
  document.getElementById('descargas').textContent = data.downloads;
  document.getElementById('vistas').textContent = data.views;
  document.getElementById('fecha-creacion').textContent = new Date(data.created_at).toLocaleDateString();
  document.getElementById('color').textContent = data.color;
  handleSkeleton();
}
