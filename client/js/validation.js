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