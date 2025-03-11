document.addEventListener("DOMContentLoaded", function() {
  // Eliminar el elemento de login
  const user = JSON.parse(localStorage.getItem('responseData'));
  if (user) {
      // Eliminar el elemento de login
      const loginElement = document.getElementById('login-button');
      const loginMobileElement = document.getElementById('login-button-hamburguesa');

      // Version Desktop
      if (loginElement) {
          loginElement.parentElement.remove();

          // Crear nuevos elementos de lista: login y perfil
          const newMenuItemPerfil = document.createElement('li');
          const newMenuLinkPerfil = document.createElement('a');
          newMenuLinkPerfil.href = "./client/src/pages/perfil/perfil.html";
          newMenuLinkPerfil.textContent = "PERFIL";

          const newMenuItemLogout = document.createElement('li');
          const newMenuLinkLogout = document.createElement('a');
          newMenuLinkLogout.href = "./client/src/pages/logout/logout.html";
          newMenuLinkLogout.textContent = "LOGOUT";

          // Añadir los nuevos enlaces a los nuevos elementos de lista
          newMenuItemPerfil.appendChild(newMenuLinkPerfil);
          newMenuItemLogout.appendChild(newMenuLinkLogout);

          // Añadir los nuevos elementos de lista al menú
          const menu = document.getElementById('menu');
          menu.appendChild(newMenuItemPerfil);
          menu.appendChild(newMenuItemLogout);
      }

      // Version mobile
      if (loginMobileElement) {
    
          loginMobileElement.parentElement.remove();

          // Crear nuevos elementos de lista: login y perfil
          const newMenuLinkPerfil = document.createElement('a');
          newMenuLinkPerfil.href = "./client/src/pages/perfil/perfil.html";
          newMenuLinkPerfil.textContent = "PERFIL";

          const newMenuLinkLogout = document.createElement('a');
          newMenuLinkLogout.href = "./client/src/pages/logout/logout.html"; 
          newMenuLinkLogout.textContent = "LOGOUT";

          // Añadir los nuevos elementos de lista al menú
          const menuMobile = document.getElementById("menu-hamburguesa-lista");
          menuMobile.appendChild(newMenuLinkPerfil);
          menuMobile.appendChild(newMenuLinkLogout);
      }
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
  const icon = document.getElementById('menu-hamburguesa');
  if (x.style.display === "block") {
    icon.innerHTML = '☰';
    x.style.display = "none";
  } else {
    icon.innerHTML = 'X';
    x.style.display = "block";
  }
}