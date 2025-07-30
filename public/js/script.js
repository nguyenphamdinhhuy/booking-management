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


const decreaseBtn = document.getElementById('decrease-guests');
  const increaseBtn = document.getElementById('increase-guests');
  const guestCount = document.getElementById('guest-count');
  
  
  


  // Set minimum date to today
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('checkin-date').min = today;
  document.getElementById('checkout-date').min = today;

  // Update checkout min date when checkin changes
  document.getElementById('checkin-date').addEventListener('change', function() {
      const checkinDate = new Date(this.value);
      checkinDate.setDate(checkinDate.getDate() + 1);
      document.getElementById('checkout-date').min = checkinDate.toISOString().split('T')[0];
  });

function selectPaymentMethod(element) {
  // Remove selected class from all methods
  document.querySelectorAll('.payment-method').forEach(method => {
      method.classList.remove('selected');
      const radio = method.querySelector('.method-radio i');
      radio.className = 'far fa-circle';
      radio.style.color = '#ccc';
  });
  
  // Add selected class to clicked method
  element.classList.add('selected');
  const radio = element.querySelector('.method-radio i');
  radio.className = 'fas fa-check-circle';
  radio.style.color = '#667eea';
}

function processPayment() {
  const button = document.querySelector('.checkout-button');
  const originalContent = button.innerHTML;
  
  button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Đang xử lý...</span>';
  button.disabled = true;
  
  setTimeout(() => {
      alert('Chuyển hướng đến trang thanh toán MoMo...');
      button.innerHTML = originalContent;
      button.disabled = false;
  }, 2000);
}

// Add smooth scrolling and animations
document.addEventListener('DOMContentLoaded', function() {
  // Animate payment methods on load
  const methods = document.querySelectorAll('.payment-method');
  methods.forEach((method, index) => {
      setTimeout(() => {
          method.style.opacity = '0';
          method.style.transform = 'translateY(20px)';
          method.style.transition = 'all 0.6s ease';
          
          setTimeout(() => {
              method.style.opacity = '1';
              method.style.transform = 'translateY(0)';
          }, 100);
      }, index * 100);
  });
});