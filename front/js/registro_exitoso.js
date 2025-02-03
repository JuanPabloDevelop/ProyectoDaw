
document.addEventListener('DOMContentLoaded', (event) => { 
    event.preventDefault();

    const responseData = JSON.parse(localStorage.getItem('responseData')); 

    setData(responseData);
});

function setData(data) {
    let message = '';
    Object.entries(data).map(([key, value], index) => message += `</br> - <b>${key}</b>: ${value}`);
    document.getElementById('dataInfo').innerHTML =`<p>${message}</p>`;
};
