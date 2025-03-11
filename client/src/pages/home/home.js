document.addEventListener("DOMContentLoaded", () => {
    const miDiv = document.getElementById("posts-container");
    if (miDiv) {
    console.log("Estamos en la página de posts");
      handleGetPosts({
        action: "user-get-all"
    });
    }
});
  
function handleGetPosts(currentUser) {
  const xhttp = new XMLHttpRequest();
  const user = {
      ...currentUser,
      action: "post-get-all",
  };
  const datosJson = JSON.stringify(user);

  xhttp.open('POST', 'http://localhost/ejercicios/ProyectoDaw/server/server.php', true);
  xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8');

  xhttp.onload = function () {
      if (xhttp.status === 200) {
          const response = JSON.parse(xhttp.responseText);

          if (response.success) {
              if (response.data.length === 0) {
                  const postsContainer = document.getElementById('posts-container');
                  postsContainer.innerHTML = 'No hay posts registrados.';
                  return;
              } else {
                  setPosts(response.data);
                  return;
              };
          } else {
              showErrorMessage('Error al obtener los usuarios.');
          }
      } else {
          showErrorMessage(`Error: ${xhttp.status}, ${xhttp.statusText}`);
      }
  };
  xhttp.onerror = function () {
      showErrorMessage('Error de red');
  };
  xhttp.send(datosJson);
}

function setPosts(data) {
    // Primero limpiamos y luego añadimos los posts
    const postsContainer = document.getElementById('posts-container');
    postsContainer.innerHTML = '';

    data.forEach(post => {
        const card = document.createElement('div');
        card.classList.add('card');
        const userSession = JSON.parse(localStorage.getItem('responseData'));

        // Crear avatar
        const avatar = document.createElement('div');
        avatar.classList.add('avatar');
        let iniciales = post.nombre.charAt(0) + post.apellidos.charAt(0);
        iniciales = iniciales.toUpperCase();
        avatar.textContent = iniciales;
        avatar.style.backgroundColor = colorAleatorio();
        card.appendChild(avatar);

        // Añadir campos a la card
        Object.values(post).forEach(value => {
            const cell = document.createElement('p');
            cell.textContent = value;
            card.appendChild(cell);
        });

    
        if(userSession.id === post.author_id || userSession.rol === '0') { 
            // Añadir las acciones
            const actionsCell = document.createElement('div');
            actionsCell.classList.add('actions');
            const editButton = document.createElement('button');
            editButton.textContent = 'Editar';
            editButton.classList.add('button')
            editButton.onclick = () => editPost(post.id_post);

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Eliminar';
            deleteButton.classList.add('button')
            deleteButton.onclick = () => deletePost(post.id_post);

            actionsCell.appendChild(editButton);
            actionsCell.appendChild(deleteButton);

            card.appendChild(actionsCell);        
        }

        postsContainer.appendChild(card);
    });
}

function colorAleatorio() {
    let color = "#" + Math.floor(Math.random() * 16777215).toString(16);
    return color;
}