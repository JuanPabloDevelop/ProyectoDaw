/* let goToTop = document.querySelector('#backToTop');
goToTop.addEventListener("click", function(e){
  window.scroll({top: 0, left: 0, behavior: 'smooth'});
}); */
let header = document.getElementById("header");
console.log(document)
window.addEventListener("scroll", function(){
  if(window.scrollY==0){
    // goToTop.style.display = "";
    header.setAttribute("style","height:80px");
  } else {
    header.setAttribute("style","height:54px;background:#FFFFFF");
    // goToTop.style.display = "block";
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