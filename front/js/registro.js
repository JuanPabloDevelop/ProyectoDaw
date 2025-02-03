
function showPassword() {
    const passwordInput = document.getElementById('pwd');
    const passwordIcon = document.querySelector('.toggle-password');
    if (passwordInput.classList.contains('hidden-password')) { 
        passwordInput.type = 'text';
        passwordInput.classList.remove('hidden-password');
        passwordInput.classList.add('show-password');
        passwordIcon.textContent = '🙈';
    } else { 
        passwordInput.type = 'password';
        passwordInput.classList.remove('show-password');
        passwordInput.classList.add('hidden-password');
        passwordIcon.textContent = '🐵';
    } 
};

function mostrarMensajeSuccess(mensaje) {
    const mensajeError = document.getElementById("error-mensaje");
    mensajeError.classList.add('hidden-message');

    const mensajeContainer = document.getElementById("success-mensaje");
    mensajeContainer.classList.remove('hidden-message');
    mensajeContainer.value = "";
    mensajeContainer.innerHTML = mensaje;
}


function cleanInputs() {
    document.getElementById('name').value = '';
    document.getElementById('apellidos').value = '';
    document.getElementById('email').value = '';
    document.getElementById('pwd').value = '';
    document.getElementById('repeatPwd').value = '';
}


function validarFormulario(nombreCliente, apellidosCliente, emailCliente, contraseñaCliente, confirmarConstraseñaCliente) {
    let formuladoValidado = true;
    let mensaje = "";

    // Expresiones regulares para la validación
    const regexNombre = /^[A-Za-z]{2,}$/;
    const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const regexContraseñas = /^.{6,}$/;

    // Validación: nombre que sólo incluya letras y minimo 2 caracteres
    if (!regexNombre.test(nombreCliente.replace(/\s+/g, '')) || nombreCliente.trim() === "") {
        mensaje += 'El nombre sólo debe contener letras y un mínimo de dos caracteres.</br>';
        formuladoValidado = false;
    }

    // Validación: apellidos que sólo incluya letras y minimo 2 caracteres
    if (!regexNombre.test(apellidosCliente.replace(/\s+/g, '')) || apellidosCliente.trim() === "") {
        mensaje += 'Los apellidos deben contener letras y un mínimo de dos caracteres.</br>';
        formuladoValidado = false;
    }

    // Validación email:
    if (!regexEmail.test(emailCliente) || emailCliente.trim() === "") {
        mensaje += 'El email debe tener un formato válido</br>';
        formuladoValidado = false;
    }

    // Validación: contraseña minimo 6 caracteres
    if (!regexContraseñas.test(contraseñaCliente) || contraseñaCliente.trim() === "") {
        mensaje += 'La contraseña debe tener 6 caracteres.</br>';
        formuladoValidado = false;
    }

    // Validación: repetir contraseña minimo 6 caracteres
    if (!regexContraseñas.test(confirmarConstraseñaCliente) || confirmarConstraseñaCliente.trim() === "") {
        mensaje += 'La contraseña repetida debe tener 6 caracteres.</br>';
        formuladoValidado = false;
    }

    // Validación: contraseña y repetir contraseña deben coincidir
    if (contraseñaCliente.trim() !== confirmarConstraseñaCliente.trim()) {
        mensaje += 'Contraseña y repetir contraseña deben coincidir.</br>';
        formuladoValidado = false;
    }

    if(formuladoValidado) {
        return formuladoValidado;
    } else {
        mostrarMensajeError(mensaje);
        return false;
    }
}

function enviarDatosPost (user) {
    let xhttp = new XMLHttpRequest();
    let datosEnviar = user;

    let datosJson = JSON.stringify(datosEnviar);

    xhttp.open("POST", "http://localhost/ejercicios/entregaJS/server.php", true);
    xhttp.setRequestHeader("Content-type", "application/json; charset=UTF-8");


    xhttp.onload = function() {
        if (xhttp.status === 200) {
            let response = JSON.parse(xhttp.responseText);
            console.log('response', response)

            if (response.success) {
                localStorage.setItem('responseData', JSON.stringify(response.data));
                window.location.href = 'http://localhost/ejercicios/entregaJS/front/src/registro_exitoso.html';
                return;

            } else {
                mostrarMensajeError("Login incorrecto");

            }
        } else {
            mostrarMensajeError(`Error: ${xhttp.status}, ${xhttp.statusText}`);
        }
    }

    xhttp.onerror = function() {
        mostrarMensajeError("Error de red");
    };
    xhttp.send(datosJson)
    
};

function enviarDatos(event) {
    event.preventDefault();
    event.stopPropagation(); 
    // Obtener los valores de los campos del formulario
    const nombreCliente = document.getElementById('name').value;
    const apellidosCliente = document.getElementById('apellidos').value;
    const emailCliente = document.getElementById('email').value;
    const constraseñaCliente = document.getElementById('pwd').value;
    const confirmarConstraseñaCliente = document.getElementById('repeatPwd').value;
    
    if(validarFormulario(nombreCliente, apellidosCliente, emailCliente, constraseñaCliente, confirmarConstraseñaCliente)) {
        mostrarMensajeSuccess("Formulario enviado correctamente.");
        cleanInputs();
        const user = {
            nombre: nombreCliente,
            apellidos: apellidosCliente,
            email: emailCliente,
            pwd: constraseñaCliente,
        };
     
        enviarDatosPost(user);
        return;
    };

}