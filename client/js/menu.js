import { checkIfImgExists, colorAleatorio } from './helper.js';

document.addEventListener("DOMContentLoaded", function() {

  const menuMobileButton = document.getElementById('menu-hamburguesa');
  menuMobileButton.addEventListener("click", openMenu);

  const user = JSON.parse(localStorage.getItem('responseData'));

  const menu = document.getElementById('menu');
  const menuMobileContainer = document.getElementById('menu-hamburguesa-lista-container');

// Si hay user, se eliminan botones de login
  if (user) {
    const loginButtonDesktop = document.getElementById('login-button');
    if (loginButtonDesktop) {
      loginButtonDesktop.parentElement.remove();
    }

    const loginButtonMobile = document.getElementById('login-button-hamburguesa');
    if (loginButtonMobile) {
      loginButtonMobile.remove();
    }
  }

  // Crear botón de galería haya o no sesión iniciada
  const galeriaItemDesktop = document.createElement('li');
  const galeriaLinkDesktop = document.createElement('a');
  galeriaLinkDesktop.href = "./client/src/pages/galeria/galeria.html";
  if (user) {
    galeriaLinkDesktop.classList.add('button', 'button-primary'); // SI está logueado → botón primary
  } else {
    galeriaLinkDesktop.classList.add('button', 'button-secondary'); // SI NO está logueado → botón secondary
  }
  galeriaLinkDesktop.textContent = "GALERIA";
  galeriaItemDesktop.appendChild(galeriaLinkDesktop);
  menu.appendChild(galeriaItemDesktop);

  const galeriaLinkMobile = document.createElement('a');
  galeriaLinkMobile.href = "./client/src/pages/galeria/galeria.html";
  galeriaLinkMobile.textContent = "GALERIA";

  const menuMobile = document.getElementById('menu-hamburguesa-lista');
  menuMobile.appendChild(galeriaLinkMobile);

  // Si hay sesión 
  if (user) {
    const newMenuItemPerfil = document.createElement('li');
    const newMenuLinkPerfil = document.createElement('a');
    newMenuLinkPerfil.href = "./client/src/pages/perfil/perfil.html";

    if (checkIfImgExists(`client/assets/users/user-${user.id_usuario}.jpg`)) {
      const commentUserImg = document.createElement('img');
      commentUserImg.classList.add('image', 'image-menu', 'avatar--comentario');
      commentUserImg.alt = 'user image';
      commentUserImg.src = `client/assets/users/user-${user.id_usuario}.jpg`;
      newMenuLinkPerfil.appendChild(commentUserImg);
    } else {
      const avatar = document.createElement('div');
      avatar.classList.add('image', 'image-menu');
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

    newMenuItemLogout.appendChild(newMenuLinkLogout);
    newMenuItemPerfil.appendChild(newMenuLinkPerfil);

    menu.appendChild(newMenuItemLogout);
    menu.appendChild(newMenuItemPerfil);

    // Perfil y Logout mobile
    const newMenuLinkPerfilMobile = document.createElement('a');
    newMenuLinkPerfilMobile.href = "./client/src/pages/perfil/perfil.html";
    newMenuLinkPerfilMobile.textContent = "PERFIL";

    const newMenuLinkLogoutMobile = document.createElement('a');
    newMenuLinkLogoutMobile.href = "./client/src/pages/logout/logout.html";
    newMenuLinkLogoutMobile.textContent = "LOGOUT";

    const menuMobile = document.getElementById('menu-hamburguesa-lista');
    menuMobile.appendChild(newMenuLinkPerfilMobile);
    menuMobile.appendChild(newMenuLinkLogoutMobile);
  }
});

// Evento Scroll
let header = document.getElementById("header");
window.addEventListener("scroll", function(){
  if(window.scrollY == 0){
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
