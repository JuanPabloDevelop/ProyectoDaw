document.addEventListener("DOMContentLoaded", function() {
    // Eliminar el elemento de login
    const user = JSON.parse(localStorage.getItem('responseData'));
    if (user) {
        const isUserAdmin = user.rol === "0";
        const titleElement = document.getElementById('title');
        // Cambiar el título de la página
        if (titleElement) {
            const title = (isUserAdmin ? "Perfil Administrador: " : "Perfil Usuario: ") + user.name;
            titleElement.innerHTML = title;
        }
        // Añadir info del usuario
        setProfileInfo(user);

        // Pedir lista usuarios
        if (isUserAdmin) {
            handleGet({
                action: "getUsers"
            });
        }
    };
});

async function handleGetUserById(userId) {
    const xhttp = new XMLHttpRequest();
    const user = {
        userId: userId,
        action: "getUserById",
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
                    document.getElementById("modalRol").value = response.data.rol;

                    // Añade más campos según sea necesario
                    var editModal = document.getElementById("editUserModal");
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
        action: "getUsers",
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
    data.forEach(user => {
        const row = document.createElement('tr');
        
        // Añadir celdas
        Object.values(user).forEach(value => {
            const cell = document.createElement('td');
            cell.textContent = value;
            row.appendChild(cell);
        });

        // Añadir las acciones salvo para el usuario actual
        const userSession = JSON.parse(localStorage.getItem('responseData'));

        if(userSession.email !== user.email) {
            const actionsCell = document.createElement('td');
            const editButton = document.createElement('button');
            editButton.textContent = 'Editar';
            editButton.onclick = () => editUser(user.id_usuario);

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Eliminar';
            deleteButton.onclick = () => deleteUser(user.id_usuario);

            const actionsDiv = document.createElement('div');
            actionsDiv.classList.add('actions');
            actionsDiv.appendChild(editButton);
            actionsDiv.appendChild(deleteButton);

            actionsCell.appendChild(actionsDiv);
            row.appendChild(actionsCell);
        };
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

function deleteUser(userId) {
    handlePost({
        action: "deleteUser",
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
                if (response.data.length === 0) {
                    showErrorMessage('No hay usuarios registrados.');
                    return;
                } else {
                    // Mostramos la tabla actualizada
                    const table = document.getElementById('table');
                    table.classList.remove('hidden');
                    setUsers(response.data);
                    return;
                };
            } else {
                showErrorMessage('Login incorrecto');
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



function handleUpdate(event) {
    event.preventDefault();
    event.stopPropagation();

    const nombreCliente = document.getElementById('modalName').value;
    const apellidosCliente = document.getElementById('modalLastName').value;
    const emailCliente = document.getElementById('modalEmail').value;
    const constraseñaCliente = document.getElementById('modalPassword').value;
    const rol = document.getElementById('modalRol').value;

    handlePost({
        action: "updateUser",
        nombre: nombreCliente,
        apellidos: apellidosCliente,
        email: emailCliente,
        pwd: constraseñaCliente,
        rol: rol,
    });

    var modal = document.getElementById("editUserModal")
    modal.style.display = "none";
}