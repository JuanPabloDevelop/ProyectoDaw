document.addEventListener("DOMContentLoaded", function() {
    // Eliminar el elemento de login
    const user = JSON.parse(localStorage.getItem('responseData'));
    if (user) {
        const isUserAdmin = user.rol === "0";
        const titleElement = document.getElementById('title');
        // Cambiar el título de la página
        if (titleElement) {
            const title = (isUserAdmin ? "Perfil Administrador: " : "Perfil Usuario: ") + user.nombre;
            titleElement.innerHTML = title;
        }
        // Añadir info del usuario
        setProfileInfo(user);

        // Pedir lista usuarios
        if (isUserAdmin) {
            handleGet({
                action: "user-get-all"
            });
        }
    };
});

async function handleGetUserById(userId) {
    const xhttp = new XMLHttpRequest();
    const user = {
        userId: userId,
        action: "user-get-by-id",
    };
    const datosJson = JSON.stringify(user);

    xhttp.open('POST', 'http://localhost/ejercicios/ProyectoDaw/server/server.php', true);
    xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8');

    xhttp.onload = function () {
        if (xhttp.status === 200) {
            const response = JSON.parse(xhttp.responseText);

            if (response.success) {
                if (response.data.length === 0) {
                    showErrorMessage('No hay usuarios registrados.');
                    return;
                } else {
                    const response = JSON.parse(xhttp.responseText);
                    document.getElementById("modalName").value = response.data.nombre;
                    document.getElementById("modalLastName").value = response.data.apellidos;
                    document.getElementById("modalEmail").value = response.data.email;
                    document.getElementById("modalPassword").value = response.data.password;
                    const userSession = JSON.parse(localStorage.getItem('responseData'));
                    const curreontUserRol = userSession.rol;
                    if(curreontUserRol !== '0') { 
                         document.getElementById("rol-container").classList.add('hidden')
                     } else {
                        document.getElementById("modalRol").value = response.data.rol;
                        document.getElementById("rol-container").classList.remove('hidden');
                     } 

                    // Añade más campos según sea necesario
                    let editModal = document.getElementById("editUserModal");
                    editModal.style.display = "block";
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

function handleGet(currentUser) {
    const xhttp = new XMLHttpRequest();
    const user = {
        ...currentUser,
        action: "user-get-all",
    };
    const datosJson = JSON.stringify(user);

    xhttp.open('POST', 'http://localhost/ejercicios/ProyectoDaw/server/server.php', true);
    xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8');

    xhttp.onload = function () {
        if (xhttp.status === 200) {
            const response = JSON.parse(xhttp.responseText);

            if (response.success) {
                if (response.data.length === 0) {
                    showErrorMessage('No hay usuarios registrados.');
                    return;
                } else {
                     // Mostramos la tabla
                    const table = document.getElementById('table');
                    table.classList.remove('hidden');
                    setUsers(response.data);
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

// Añadir usuarios a la tabla
function setUsers(data) {
    // Añadimos info a la tabla: primero limpiamos y luego añadimos
    const tbody = document.getElementById('table-body');
    tbody.innerHTML = '';
    const userSession = JSON.parse(localStorage.getItem('responseData'));

    data.forEach(user => {
        // No mostramos el usuario actual en la tabla
        if(userSession.email === user.email) {
            return;
        }

        const row = document.createElement('tr');
        
        // Añadir celdas
        Object.values(user).forEach(value => {
            const cell = document.createElement('td');
            cell.textContent = value;
            row.appendChild(cell);
        });

        // Añadir las acciones
        const actionsCell = document.createElement('td');
        const editButton = document.createElement('button');
        editButton.textContent = 'Editar';
        editButton.classList.add('button');
        editButton.classList.add('button-primary');
        editButton.onclick = () => editUser(user.id_usuario);

        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Eliminar';
        deleteButton.classList.add('button')
        deleteButton.classList.add('button-secondary');
        deleteButton.onclick = () => deleteUser(user.id_usuario);

        const actionsDiv = document.createElement('div');
        actionsDiv.classList.add('actions');
        actionsDiv.appendChild(editButton);
        actionsDiv.appendChild(deleteButton);

        actionsCell.appendChild(actionsDiv);
        row.appendChild(actionsCell);
        tbody.appendChild(row);
    });
}

//Añadir info del usuario
function setProfileInfo(user) {
    Object.entries(user).forEach(value => {
        const profileFile = document.getElementById(`profile-${value[0]}`);
        profileFile.textContent = '';
        const line = document.createElement('span');
        line.textContent = value[0] !== 'rol' ? value[1] : roleAdapter(value[1]);
        profileFile.appendChild(line);
    });

}


function roleAdapter(role) {
    switch (role) {
        case '0':
            return 'Administrador';
        case '1':
            return 'Usuario';
        default:
            return 'Desconocido';
    }
};

async function editUser(userId) {
    try {
        handleGetUserById(userId);
    } catch (error) {
        console.error('Error al obtener los datos del usuario:', error);
    }
}

function editCurrentUser() {
    const user = JSON.parse(localStorage.getItem('responseData'));
    const id = user.id_usuario;

    const loading = document.getElementById('profile-loading');
    const form = document.getElementById('user-info');

    form.classList.add('hidden');
    loading.classList.remove('hidden');
    
    // TimeOut to simulate a slow conection
    setTimeout(() => {
        form.classList.remove('hidden');
        loading.classList.add('hidden');
        handleGetUserById(id);
    }, 2000)
}


function deleteUser(userId) {
    handlePost({
        action: "user-delete",
        userId: userId,
    });
}


function handlePost(user) {
    const xhttp = new XMLHttpRequest();
    const datosJson = JSON.stringify(user);

    xhttp.open('POST', 'http://localhost/ejercicios/ProyectoDaw/server/server.php', true);
    xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8');

    xhttp.onload = function () {
        if (xhttp.status === 200) {
            const response = JSON.parse(xhttp.responseText);

            if (response.success) {

                if (response.data && response.data.length === 0) {
                    showErrorMessage('No hay usuarios registrados.');
                    return;
                } else {
                    let currentUser = JSON.parse(localStorage.getItem('responseData'));
                    if(response.user) {
                        // Solo actualizamos localStorage si estamos editando nuestro propio usuario
                        if (user.action === "user-update" && currentUser && currentUser.email === user.email) {
                            localStorage.removeItem('responseData');
                            localStorage.setItem('responseData', JSON.stringify(response.user));
                            currentUser = response.user;
                            setProfileInfo(response.user);
                        }
                    }
                    // Comprobamos de nuevo el localStorage no haya cambiado
                    const currentUserRol = currentUser.rol;
                    const isUserAdmin = currentUserRol === '0';
                    // Después de cualquier actualización, si somos admin, actualizamos la tabla
                    if (isUserAdmin) {
                        handleGet({
                            action: "user-get-all"
                        });
                    } else {
                        // La escondemos en caso de que no sea admin
                        const table = document.getElementById('table');
                        table.classList.add('hidden');
                    }
                    return;
                }
            } else {
                showErrorMessage('Error al realizar la operación');
            }
        } else {
            showErrorMessage(`Error: ${xhttp.status}, ${xhttp.statusText}`);
        }
    };
    xhttp.onerror = function () {
        showErrorMessage('Error de red');
    };
    xhttp.send(datosJson);
};

function showErrorMessage(mensaje) {
    const mensajeError = document.getElementById('error-success');
    mensajeError.classList.add('hidden');

    const mensajeContainer = document.getElementById('error-mensaje');
    mensajeContainer.classList.remove('hidden');
    mensajeContainer.innerHTML = mensaje;
}

function handleUpdate(event) {
    event.preventDefault();
    event.stopPropagation();

    const nombreCliente = document.getElementById('modalName').value;
    const apellidosCliente = document.getElementById('modalLastName').value;
    const emailCliente = document.getElementById('modalEmail').value;
    const constraseñaCliente = document.getElementById('modalPassword').value;
    const rol = document.getElementById('modalRol').value;
    const isRolHidden = document.getElementById('rol-container').classList.value === 'hidden';
    let userRol;

    if(isRolHidden) {
        const currentUser = JSON.parse(localStorage.getItem('responseData'));
        userRol = currentUser.rol;
    } else {
        userRol = rol;
    }

    handlePost({
        action: "user-update",
        nombre: nombreCliente,
        apellidos: apellidosCliente,
        email: emailCliente,
        pwd: constraseñaCliente,
        rol: userRol,
    });

    let modal = document.getElementById("editUserModal")
    modal.style.display = "none";
}