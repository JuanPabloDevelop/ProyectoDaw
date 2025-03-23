import { checkIfImgExists, colorAleatorio } from './helper.js'

  document.addEventListener("DOMContentLoaded", function() {

  const menuMobile = document.getElementById('menu-hamburguesa');
  menuMobile.addEventListener("click", openMenu);

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

          if(checkIfImgExists(`client/assets/users/user-${user.id_usuario}.jpg`)) {
              const commentUserImg = document.createElement('img');
              commentUserImg.classList.add('image');
              commentUserImg.classList.add('image-menu');
              commentUserImg.classList.add('avatar--comentario');
              commentUserImg.alt = 'user image';
              commentUserImg.src = `client/assets/users/user-${user.id_usuario}.jpg`;
              newMenuLinkPerfil.appendChild(commentUserImg);
          } else {
              const avatar = document.createElement('div');
              avatar.classList.add('image');
              avatar.classList.add('image-menu');
              
              let iniciales = user.nombre.charAt(0) + user.apellidos.charAt(0);
              iniciales = iniciales.toUpperCase();
              avatar.textContent = iniciales;
              avatar.style.backgroundColor = colorAleatorio();
              newMenuLinkPerfil.appendChild(avatar);
          }


          const newMenuItemLogout = document.createElement('li');
          const newMenuLinkLogout = document.createElement('a');
          newMenuLinkLogout.href = "./client/src/pages/logout/logout.html";
          newMenuLinkLogout.textContent = "LOGOUT";

          // Añadir los nuevos enlaces a los nuevos elementos de lista
          newMenuItemLogout.appendChild(newMenuLinkLogout);
          newMenuItemPerfil.appendChild(newMenuLinkPerfil);

          // Añadir los nuevos elementos de lista al menú
          const menu = document.getElementById('menu');
          menu.appendChild(newMenuItemLogout);
          menu.appendChild(newMenuItemPerfil);
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