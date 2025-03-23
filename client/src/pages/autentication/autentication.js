let mostrarLogin = true;

function toggleView() {
    if (mostrarLogin) {
        document.getElementById('login').classList.remove('hidden');
        document.getElementById('registro').classList.add('hidden');
        document.getElementById('toggleView').innerHTML = 'Registrarse';
    } else {
        document.getElementById('login').classList.add('hidden');
        document.getElementById('registro').classList.remove('hidden');
        document.getElementById('toggleView').innerHTML = 'Iniciar sesión';
    }
    mostrarLogin = !mostrarLogin;
}

function submitLogin() {
    alert('Login submitted');
}

function submitRegistro() {
    alert('Registro submitted');
}

// Inicializa la vista
toggleView();

// Cambié esto para que aparezca el icono del ojo
function togglePasswordVisibility(inputId) {
    const passwordInput = document.getElementById(inputId);
    const passwordIcon = passwordInput.nextElementSibling.querySelector('i');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}

function showSuccessMessage(mensaje) {
    showMessage('error-mensaje', 'success-mensaje', mensaje);
}

function showErrorMessage(mensaje) {
    showMessage('success-mensaje', 'error-mensaje', mensaje);
}

function showMessage(idToHide, idToShow, mensaje) {
    const mensajeError = document.getElementById(idToHide);
    mensajeError.classList.add('hidden');

    const mensajeContainer = document.getElementById(idToShow);
    mensajeContainer.classList.remove('hidden');
    mensajeContainer.innerHTML = mensaje;

    setTimeout(() => {
        mensajeContainer.classList.add('hidden');
    }, 5000);
}

function cleanInputs() {
    const inputIds = ['name', 'apellidos', 'email', 'pwd', 'repeatPwd'];
    inputIds.forEach(id => document.getElementById(id) ? document.getElementById(id).value = '' : null);
}

function validateForm(nombreCliente, apellidosCliente, emailCliente, contraseñaCliente, confirmarConstraseñaCliente) {
    const regexNombre = /^[A-Za-z\s]{2,}$/;
    const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const regexContraseñas = /^.{6,}$/;

    const errores = [
        { regex: regexNombre, value: nombreCliente, mensaje: 'El nombre sólo debe contener letras y un mínimo de dos caracteres.' },
        { regex: regexNombre, value: apellidosCliente, mensaje: 'Los apellidos deben contener letras y un mínimo de dos caracteres.' },
        { regex: regexEmail, value: emailCliente, mensaje: 'El email debe tener un formato válido.' },
        { regex: regexContraseñas, value: contraseñaCliente, mensaje: 'La contraseña debe tener un mínimo de 6 caracteres.' },
        { regex: regexContraseñas, value: confirmarConstraseñaCliente, mensaje: 'La contraseña repetida debe tener un mínimo de 6 caracteres.' },
        { regex: null, value: contraseñaCliente !== confirmarConstraseñaCliente, mensaje: 'Contraseña y repetir contraseña deben coincidir.' }
    ];

    const mensajesError = errores.filter(error => (error.regex && !error.regex.test(error.value)) || error.value === true)
        .map(error => error.mensaje);

    if (mensajesError.length) {
        showErrorMessage(mensajesError.join('</br>'));
        return false;
    }

    return true;
}

function handleRegister(event) {
    event.preventDefault();
    event.stopPropagation();

    const nombreCliente = document.getElementById('register-name').value;
    const apellidosCliente = document.getElementById('register-apellidos').value;
    const emailCliente = document.getElementById('register-email').value;
    const constraseñaCliente = document.getElementById('register-password').value;
    const confirmarConstraseñaCliente = document.getElementById('register-repeat-password').value;

    if (validateForm(nombreCliente, apellidosCliente, emailCliente, constraseñaCliente, confirmarConstraseñaCliente)) {
        showSuccessMessage('Formulario enviado correctamente.');
        handlePost({
            action: "user-register",
            nombre: nombreCliente,
            apellidos: apellidosCliente,
            email: emailCliente,
            pwd: constraseñaCliente,
        });
    }
}

function handleLogin(event) {
    event.preventDefault();
    event.stopPropagation();
    loadingButton('login-button');


    const emailCliente = document.getElementById('login-email').value;
    const constraseñaCliente = document.getElementById('login-password').value;
    setTimeout(() => {
        handlePost({
            action: "user-login",
            email: emailCliente,
            pwd: constraseñaCliente,
        });
    }, 2000); // Dos segundos de delay para simular una petición más lenta
    return;
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
                localStorage.setItem('responseData', JSON.stringify(response.data));
                window.location.href = 'http://localhost/ejercicios/ProyectoDaw/index.html';
            } else {
                showErrorMessage(response.message);
            }

        } else {
            showErrorMessage(`Error: ${xhttp.status}, ${xhttp.statusText}`);
        }
        loadingButton(`${user.action}-button`);
    };
    xhttp.onerror = function () {
        console.log('Error de red');
        loadingButton(`${user.action}-button`);
        showErrorMessage('Error de red');
    };
    xhttp.send(datosJson);
}

function loadingButton (id) {
    const button = document.getElementById(id);

    if (document.getElementById('child-loading')) {
        const loading = document.getElementById('child-loading');
        loading.remove();
        button.innerHTML = 'Login';
    } else {
        const loading = document.createElement('span');
        button.innerHTML = '';
        loading.id = 'child-loading';
        loading.classList.add('button-loader');

        button.appendChild(loading);
    }
}
