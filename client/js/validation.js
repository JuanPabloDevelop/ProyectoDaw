export function validateForm(nombreCliente, apellidosCliente, emailCliente, contraseñaCliente, confirmarConstraseñaCliente) {
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
        return {
            success: false,
            message: mensajesError.join('</br>'),
        }
    }

    return {success: true};
}

const palabrasProhibidas = ['mamón', 'mendrugo', 'zopenco', 'idiota', 'imbecil', 'tonto', 'gilipollas', 'zorra', 'puta', 'bobo', 'boba', 'puto', 'joder', 'hostia', 'concha', 'mierda', 'boludo', 'pija', 'verga', 'pene', 'enano', 'maricón', 'marica', 'pivita', 'fulana', 'judío', 'judía', 'jodas', 'mariquita', 'coño'];

// Función para validar el contenido
export function checkValidContent(texto) {
    let palabrasCensuradas = "No pueden usarse las siguientes palabras: </br>";
    let count = 0;

    for (let palabra of palabrasProhibidas) {
        if (texto.toLowerCase().includes(palabra.toLowerCase())) {

            palabrasCensuradas += ` ${palabra}  </br>`
            count ++;
        }
    }

    if(count > 0) {
        return {
            success: false,
            message: palabrasCensuradas,
        }
    } else {
        return {success: true}; // Contenido válido
    }
}