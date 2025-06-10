const heroSwiper = new Swiper('.hero-swiper', {
loop: true,
autoplay: {
    delay: 3500,
    disableOnInteraction: false,
},
pagination: {
    el: '.swiper-pagination',
    clickable: true,
},
navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
},
effect: 'slide',
speed: 520
});




const toggleBtn = document.querySelector('.bk-header__menu-toggle');
const closeBtn = document.querySelector('.bk-header__menu-close');
const mobileNav = document.querySelector('.bk-header__mobile-nav');
const mobileBackdrop = document.querySelector('.bk-header__mobile-backdrop');

function openMobileNav() {
  mobileNav.classList.add('active');
  mobileBackdrop.classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeMobileNav() {
  mobileNav.classList.remove('active');
  mobileBackdrop.classList.remove('show');
  document.body.style.overflow = '';
}
if(toggleBtn && closeBtn && mobileNav && mobileBackdrop) {
  toggleBtn.addEventListener('click', openMobileNav);
  closeBtn.addEventListener('click', closeMobileNav);
  mobileBackdrop.addEventListener('click', closeMobileNav);
}


