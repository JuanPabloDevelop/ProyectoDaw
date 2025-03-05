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
