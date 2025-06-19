const hamburger = document.querySelector('.hamburger');
const menu = document.querySelector('nav ul');

hamburger.addEventListener('click', function () {
    this.classList.toggle('active');
    menu.classList.toggle('active');
});

window.addEventListener("scroll", function(){
    var nav = this.document.querySelector("nav");
    nav.classList.toggle("sticky", this.window.scrollY > 0);
})


const a = document.querySelectorAll('.answer');
const arrow = document.querySelectorAll('.btn');

for(let i=0; i<arrow.length; i++){
  arrow[i].addEventListener('click', () =>{
    a[i].classList.toggle('show');
  });
}
