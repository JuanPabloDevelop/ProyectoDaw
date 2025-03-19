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
    // Pongo el skeleton
    handleSkeleton();

    setTimeout(() => {
        // Oculto el skeleton y redigo a la card
        handleSkeleton();
        const content = document.getElementById(id);
        content.classList.remove('comments-container-close');
        content.classList.add('comments-container-open');
        content.scrollIntoView(alignToTop);
    }, 1000);
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