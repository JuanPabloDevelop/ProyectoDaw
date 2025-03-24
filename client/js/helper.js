export function loadingButton (id) {
    const button = document.getElementById(id);

    if (button.classList.contains("button-loader")) {
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

export function scrollToElementId(id) {
    const content = document.getElementById(id);

    setTimeout(() => {
        if(content) {
            content.scrollIntoView({
                behavior: 'smooth',
                block: 'center',    
                inline: 'center'
            })
        };
    }, 100);
}

export function parseTipo(tipo) {
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

export function colorAleatorio() {
    let color = "#" + Math.floor(Math.random() * 16777215).toString(16);
    return color;
}

export function handleSkeleton() {
    const mainContent = document.getElementById("content");
    const cardLoading = document.getElementById("skeleton-container");

    if (mainContent.classList.contains('hidden')) {
        mainContent.classList.remove('hidden');
        cardLoading.classList.add('hidden');
        return;
    } else {
        mainContent.classList.add('hidden');
        cardLoading.classList.remove('hidden');
    };
}

export function showErrorMessage(mensaje) {
    const mensajeContainer = document.getElementById('error-mensaje');
    mensajeContainer.classList.remove('hidden');
    mensajeContainer.innerHTML = mensaje;

    setTimeout(() => {
        mensajeContainer.classList.add('hidden');
    }, 5000);
}

export function checkIfImgExists(image_url) {
    var http = new XMLHttpRequest();

    http.open('HEAD', image_url, false);
    http.send();

    return http.status != 404;
}
