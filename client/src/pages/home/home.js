document.addEventListener("DOMContentLoaded", () => {
    const mainContent = document.getElementById("content");
    const cardLoading = document.getElementById("skeleton-container");

    if (cardLoading || mainContent) {
        setTimeout(() => {
            mainContent.classList.remove('hidden');
            cardLoading.classList.add('hidden');

            handlePosts({
                action: "post-get-all",
                filter: "all",
            });
            const filters = handleGetFilters({
                action: "filter-get-posts-types",
            });

            if (filters) {
                setSelectOptions(filters, 'filter-select');
            }

        }, 2000);
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
                const filters = response.data.map(item =>perseTipo(item.tipo));
                localStorage.setItem('filters', filters);
                setSelectOptions(response.data, 'filter-select');
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

function handlePosts(data) {
    const xhttp = new XMLHttpRequest();
    const info = {
        ...data,
    };
    const datosJson = JSON.stringify(info);
  
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

async function handleGetPostById(postId) {
    return new Promise((resolve, reject) => {
        const xhttp = new XMLHttpRequest();
        const post = {
            id: postId,
            action: "post-get-by-id",
        };
        const datosJson = JSON.stringify(post);

        xhttp.open('POST', 'http://localhost/ejercicios/ProyectoDaw/server/server.php', true);
        xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8');

        xhttp.onload = function () {
            if (xhttp.status === 200) {
                const response = JSON.parse(xhttp.responseText);

                if (response.success) {
                    if (response.data.length === 0) {
                        reject('No hay Posts registrados.');
                    } else {
                        resolve(response.data);
                    }
                } else {
                    reject('Error al obtener los usuarios.');
                }
            } else {
                reject(`Error: ${xhttp.status}, ${xhttp.statusText}`);
            }
        };
        xhttp.onerror = function () {
            reject('Error de red');
        };
        xhttp.send(datosJson);
    });
}

async function editPost(id) {
    try {
        const response = await handleGetPostById(id);
        document.getElementById("modalPostTitle").value = response[0].titulo;
        document.getElementById("modalPostContent").value = response[0].contenido;
        document.getElementById("modalPostId").value = response[0].id_post;
        
        // Cargar las opciones del select
        const select = document.getElementById("modalPostType");
        // Limpiar opciones existentes
        select.innerHTML = '';
        
        // Crear las opciones basadas en los tipos disponibles
        const tipos = {
            "ilu": "Iluminación",
            "mobi": "Mobiliario",
            "text": "Textiles",
            "acc": "Accesorios"
        };

        // Agregar las opciones al select
        Object.entries(tipos).forEach(([valor, texto]) => {
            const option = document.createElement('option');
            option.value = valor;
            option.textContent = texto;
            // Si es el tipo actual del post, seleccionarlo
            if (valor === response[0].tipo) {
                option.selected = true;
            }
            select.appendChild(option);
        });

        const modal = document.getElementById("editPostModal");
        const closeBtn = document.getElementById("close");

        // Función para cerrar el modal
        const closeModal = () => {
            modal.style.display = "none";
        };

        // Event listener para el botón de cierre (×)
        closeBtn.onclick = closeModal;

        // Event listener para cerrar el modal al hacer clic fuera de él
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        };

        // Event listeners para los botones
        document.getElementById("modalPostSave").onclick = () => {
            const title = document.getElementById("modalPostTitle").value;
            const content = document.getElementById("modalPostContent").value;
            const type = document.getElementById("modalPostType").value;
            const postId = document.getElementById("modalPostId").value;
            
            if (!title || !content) {
                showErrorMessage('Por favor, rellena todos los campos obligatorios.');
                return;
            }

            handlePosts({
                action: "post-update",
                id: postId,
                titulo: title,
                contenido: content,
                tipo: type,
            });
            closeModal();
        };

        document.getElementById("modalPostCancel").onclick = closeModal;

        // Mostrar el modal
        modal.style.display = "block";
    } catch (error) {
        showErrorMessage(error);
    }
}

function deletePost(id) {
    handlePosts({
        action: "post-delete",
        id: id,
    });
};
    
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

function setSelectOptions(data, id) {
    const select = document.getElementById(id);
    // Borrar duplicados
    const uniqueArray = [...new Set(data)];
    uniqueArray.forEach(item => {
        const option = document.createElement('option');
        option.value = item.tipo;
        option.textContent = perseTipo(item.tipo);
        select.appendChild(option);
    });
    select.addEventListener('change', (event) => handlePosts(
        {
            action: "post-get-all",
            filter: event.target.value,
        }
    ));
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
