document.addEventListener("DOMContentLoaded", function() {
  // Eliminar el elemento de login
  const user = JSON.parse(localStorage.getItem('responseData'));
  if (user) {
      // Eliminar el elemento de login
      const loginElement = document.getElementById('login');
      if (loginElement) {
          loginElement.parentElement.remove();
      }

      // Crear nuevos elementos de lista: login y perfil
      const newMenuItemPerfil = document.createElement('li');
      const newMenuLinkPerfil = document.createElement('a');
      newMenuLinkPerfil.href = "./client/src/pages/perfil/perfil.html";
      newMenuLinkPerfil.textContent = "PERFIL";

      const newMenuItemLogout = document.createElement('li');
      const newMenuLinkLogout = document.createElement('a');
      newMenuLinkLogout.href = "./client/src/pages/logout/logout.html";  // Asegúrate de que esta página existe
      newMenuLinkLogout.textContent = "LOGOUT";

      // Añadir los nuevos enlaces a los nuevos elementos de lista
      newMenuItemPerfil.appendChild(newMenuLinkPerfil);
      newMenuItemLogout.appendChild(newMenuLinkLogout);

      // Añadir los nuevos elementos de lista al menú
      const menu = document.getElementById('menu');
      menu.appendChild(newMenuItemPerfil);
      menu.appendChild(newMenuItemLogout);
  };
});


// Evento Scroll
let header = document.getElementById("header");
window.addEventListener("scroll", function(){
  if(window.scrollY==0){
    header.setAttribute("style","height:80px");
  } else {
    header.setAttribute("style","height:54px;background:#FFFFFF");
  }
});


function openMenu() {
    const x = document.getElementById("menu-hamburguesa-lista");
    if (x.style.display === "block") {
      x.style.display = "none";
    } else {
      x.style.display = "block";
    }
  }