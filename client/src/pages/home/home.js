document.addEventListener("DOMContentLoaded", () => {
    const miDiv = document.getElementById("posts-container");
    if (miDiv) {
    handleGetPosts();
    handleGetFilters({
        action: "filter-get-posts-types",
    });
    }
});
  
function handleGetFilters(action) {
  const xhttp = new XMLHttpRequest();
  const user = {
    ...action,
  };
  const datosJson = JSON.stringify(user);

  xhttp.open('POST', 'http://localhost/ejercicios/ProyectoDaw/server/server.php', true);
  xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8');

  xhttp.onload = function () {
    const select = document.getElementById('filter-select');
        if (xhttp.status === 200) {
          const response = JSON.parse(xhttp.responseText);

          if (response.success) {
            if (response.data.length === 0) {
                select.classList.add('hidden');
                return;
            } else {
                setSelectOptions(response.data);
                return;
            };
          } else {
            select.classList.add('hidden');;
          }
        } else {
            select.classList.add('hidden');
        }
  };
  xhttp.onerror = function () {
      showErrorMessage('Error de red');
  };
  xhttp.send(datosJson);
}

function handleGetPosts(filter= 'all') {
    const xhttp = new XMLHttpRequest();
    const user = {
        action: "post-get-all",
        filter: filter,
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

    data.forEach((post, index) => {
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

        // Crear imagen
        const userImg = document.createElement('img');
        userImg.classList.add('image');
        userImg.alt = 'user image';
       userImg.src = `client//assets/users/user-${post.autor_id}.jpg`;
        card.appendChild(userImg);


        // Añadir campos a la card
        Object.entries(post).forEach((value) => {
            const file = document.createElement('p');
            file.classList.add('file');
            file.id = `post-${value[0]}-${index}`;

            if(value[0] === 'fecha_modificacion') {
                value[1] = value[1] ? value[1] : '';
            }

            if(value[0] === 'tipo') {
                value[1] = perseTipo(value[1]);
            }

            if(value[0] === 'rol' || value[0] === 'autor_id' || value[0] === 'id_post') {
                return;
            }

            file.textContent = value[1];
            card.appendChild(file);
        });

        // Mostrar fecha de modificación solo si es distinta a la de creación
        const fechaCreacion = document.getElementById(`post-fecha_creacion-${0}`);
        const fechaModificacion = document.getElementById(`post-fecha_modificacion-${0}`);
        if(fechaCreacion && fechaModificacion) {
            fechaCreacion.textContent === fechaModificacion.textContent ? fechaModificacion.style.display = 'none' : fechaModificacion.style.display = 'block';
        }

        if(userSession && (userSession.id === post.author_id || userSession.rol === '0')) { 
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

function setSelectOptions(data) {
    const select = document.getElementById('filter-select');
    // Borrar duplicados
    const uniqueArray = [...new Set(data)];
    uniqueArray.forEach(item => {
        const option = document.createElement('option');
        option.value = item.tipo;
        option.textContent = perseTipo(item.tipo);
        select.appendChild(option);
    });
    select.addEventListener('change', (event) => handleGetPosts(event.target.value));
}

function colorAleatorio() {
    let color = "#" + Math.floor(Math.random() * 16777215).toString(16);
    return color;
}

function perseTipo(tipo) {
    const type = {
        "all": "Todos",
        "deco": "Estilos de decoración",
        "ilu": "Iluminación",
        "mobi": "Mobiliario",
        "text": "Textiles",
        "acc": "Accesorios",
    }
    return type[tipo];
}
