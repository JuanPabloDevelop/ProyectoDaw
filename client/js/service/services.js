async function handleFetchData(data) {
    try {
        const response = await new Promise((resolve, reject) => {
            const xhttp = new XMLHttpRequest();
            const post = { ...data };
            const datosJson = JSON.stringify(post);

            xhttp.open('POST', 'http://localhost/ejercicios/ProyectoDaw/server/server.php', true);
            xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8');

            xhttp.onload = function () {
                if (xhttp.status === 200) {
                    const res = JSON.parse(xhttp.responseText);

                    if (res.success) {
                        if (res.data.length === 0) {
                            resolve({
                                success: res.success,
                                message: res.message,
                            });
                        } else {
                            resolve({
                                success: res.success,
                                message: res.message,
                                data: res.data,
                            });
                        }
                    } else {
                        resolve({
                            success: res.success,
                            message: res.message,
                        });
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

        return response;
    } catch (error) {
        console.error('Error durante la obtención de datos:', error);
        throw error; // Propagar el error para que pueda ser manejado externamente
    }
}

export default handleFetchData;