window.onscroll = function() {
  myFunction();
};
var navbar = document.getElementById("navbar");
function myFunction() {
  if (window.pageYOffset >= 89) {
    navbar.classList.add("sticky");
  } else {
    navbar.classList.remove("sticky");
  }
}
