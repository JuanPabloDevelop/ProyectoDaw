document.addEventListener("DOMContentLoaded", function() {
    localStorage.clear();
    handleLogout();
});

function handleLogout() {
    const xhttp = new XMLHttpRequest();
    const user = {
        action: "user-logout",
    };
    const datosJson = JSON.stringify(user);

    xhttp.open('POST', 'http://localhost/ejercicios/ProyectoDaw/server/server.php', true);
    xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8');

    xhttp.onload = function () {
        if (xhttp.status === 200) {
            const response = JSON.parse(xhttp.responseText);

            if (response.success) {
                window.location.href = 'http://localhost/ejercicios/ProyectoDaw/index.html';
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

