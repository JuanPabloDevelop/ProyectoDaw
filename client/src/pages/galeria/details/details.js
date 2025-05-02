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
    const response = await fetch(`https://api.unsplash.com/photos/${id}?lang=es&client_id=${ACCESS_KEY}`);
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

function displayInfo(data) {
  document.getElementById('autor-imagen').src = data.user.profile_image.medium;
  document.getElementById('autor-imagen').alt = data.alt_description;
  document.getElementById('imagen').src = data.urls.small;
  document.getElementById('imagen').alt = data.alt_description;
  document.getElementById('titulo').textContent = data.alt_description;
  document.getElementById('autor').innerHTML = `Fotografía de: <a href="${data.user.links.html}" target="_blank">${data.user.name}</a>`;
  document.getElementById('descripcion').textContent = data.description || data.alt_description || 'No description available';
  document.getElementById('likes').textContent = data.likes;
  document.getElementById('descargas').textContent = data.downloads;
  document.getElementById('vistas').textContent = data.views;
  document.getElementById('fecha-creacion').textContent = new Date(data.created_at).toLocaleDateString();
  document.getElementById('download').href = data.links.html;
  document.getElementById('imagen').classList.remove('hidden');
  document.getElementById('autor-imagen').classList.remove('hidden');
  handleSkeleton();
}
