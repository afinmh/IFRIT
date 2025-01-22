// Select The Elements
var toggle_btn;
var big_wrapper;
var hamburger_menu;

function declare() {
  toggle_btn = document.querySelector(".toggle-btn");
  big_wrapper = document.querySelector(".big-wrapper");
  hamburger_menu = document.querySelector(".hamburger-menu");
}

const main = document.querySelector("main");

declare();

let dark = false;

function toggleAnimation() {
  // Clone the wrapper
  dark = !dark;
  let clone = big_wrapper.cloneNode(true);

  // Update the theme class
  if (dark) {
    clone.classList.remove("light");
    clone.classList.add("dark");
    clone.querySelector(".logo img").src = "./img/logo2.png"; // Update logo in the clone
  } else {
    clone.classList.remove("dark");
    clone.classList.add("light");
    clone.querySelector(".logo img").src = "./img/logo1.png"; // Update logo in the clone
  }

  clone.classList.add("copy");
  main.appendChild(clone);

  document.body.classList.add("stop-scrolling");

  clone.addEventListener("animationend", () => {
    document.body.classList.remove("stop-scrolling");
    big_wrapper.remove();
    clone.classList.remove("copy");
    // Reset Variables
    declare();
    events();
  });
}

function events() {
  toggle_btn.addEventListener("click", toggleAnimation);
  hamburger_menu.addEventListener("click", () => {
    big_wrapper.classList.toggle("active");
  });
}

events();
