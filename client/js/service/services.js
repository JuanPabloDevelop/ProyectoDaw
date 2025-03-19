async function handleFetchData(data) {
    return new Promise((resolve, reject) => {
        const xhttp = new XMLHttpRequest();
        const post = {
            ...data
        };
        const datosJson = JSON.stringify(post);

        xhttp.open('POST', 'http://localhost/ejercicios/ProyectoDaw/server/server.php', true);
        xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8');

        xhttp.onload = function () {
            if (xhttp.status === 200) {
                const response = JSON.parse(xhttp.responseText);

                if (response.success) {
                    if (response.data.length === 0) {
                        reject(response.message);
                    } else {
                        resolve(response.data);
                    }
                } else {
                    reject(response.message);
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

export default handleFetchData;