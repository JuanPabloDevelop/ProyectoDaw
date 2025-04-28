import { handleSkeleton, showErrorMessage } from '../../../js/helper.js';

const ACCESS_KEY = 'EUnkfYoD-vf16kTu2aEFsVhRy8ySh9JORf--ucs-X6o'; // Tu API Key de Unsplash

document.addEventListener("DOMContentLoaded", async () => {
  try {
    handleSkeleton();
    await loadImages('modern interior design'); // Cargamos imágenes de Modern por defecto
    handleSkeleton();
  } catch (error) {
    showErrorMessage(error.message);
    console.error(error);
  }

  const select = document.getElementById('style-select');
  select.addEventListener('change', async (e) => {
    const selectedStyle = e.target.value;
    if (selectedStyle) {
      handleSkeleton();
      await loadImages(selectedStyle);
      handleSkeleton();
    }
  });
});

async function loadImages(query) {
  try {
    const response = await fetch(`https://api.unsplash.com/search/photos?query=${encodeURIComponent(query)}&per_page=12&client_id=${ACCESS_KEY}`);
    if (!response.ok) {
      throw new Error('Error fetching images.');
    }
    const data = await response.json();
    displayImages(data.results);
  } catch (error) {
    showErrorMessage(error.message);
    console.error(error);
  }
}

function displayImages(images) {
  const container = document.getElementById('gallery-container');
  container.innerHTML = '';

  images.forEach(image => {
    const card = document.createElement('div');
    card.classList.add('card', 'image-card', 'animate-fade-in');

    const img = document.createElement('img');
    img.src = image.urls.small;
    img.alt = image.alt_description || 'Interior design';
    img.classList.add('gallery-image');
    card.appendChild(img);

    const author = document.createElement('p');
    author.classList.add('image-author');
    author.innerHTML = `Photo by <a href="${image.user.links.html}" target="_blank">${image.user.name}</a>`;
    card.appendChild(author);

    container.appendChild(card);
  });
}
