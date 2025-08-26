/**
 * Signup page form switching
 */
const signinFormTitle = document.querySelector('.signin-form-title'); // Sign in form title
const signupFormTitle = document.querySelector('.signup-form-title'); // Sign up form title
const nameGroup = document.querySelector('.name-group'); // Input field name
const emailGroup = document.querySelector('.email-group'); // Input field email
const mobileGroup = document.querySelector('.mobile-group'); // Input field mobile
const passwordGroup = document.querySelector('.password-group'); // Input field password
const signupButton = document.querySelector('.btn-signup'); // Singup button
const signinButton = document.querySelector('.btn-signin'); // Signin button
const signupLink = document.querySelector('.signup-link'); // Signup form link
const signinLink = document.querySelector('.signin-link'); // Signin form link
const forgotpasswordLink = document.querySelector('.forgotpassword-link'); // Forgotpassword link

/** 
 * Function for switch to sign up form
*/
function switchToSignup(event) {
    event.preventDefault();
    signupFormTitle.classList.add('mb-4');
    signupFormTitle.style.height = 'initial';
    signinFormTitle.style.height = '0';
    signinFormTitle.classList.remove('mb-4');
    nameGroup.style.height = '88px';
    nameGroup.classList.add('mb-3');
    emailGroup.style.height = '88px';
    emailGroup.classList.add('mb-3');
    signupButton.classList.remove('d-none');
    signinButton.classList.add('d-none');
    signupLink.classList.add('d-none');
    forgotpasswordLink.classList.add('d-none');
    signinLink.classList.remove('d-none');
}

/** 
 * Function for switch to sign in form
*/
function switchToSignin(event) {
    event.preventDefault();
    signinFormTitle.style.height = 'initial';
    signupFormTitle.style.height = '0';
    nameGroup.style.height = '0';
    nameGroup.classList.remove('mb-3');
    emailGroup.style.height = '0';
    emailGroup.classList.remove('mb-3');
    signinButton.classList.remove('d-none');
    signupButton.classList.add('d-none');
    forgotpasswordLink.classList.remove('d-none');
    signinLink.classList.add('d-none');
    signupLink.classList.remove('d-none');
}


/** 
 * Script for Dashboard Sidebar
*/
feather.replace();

const sidebar      = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');

// Helper → true when mobile width
const isMobile = () => window.innerWidth <= 768;

// Toggle button works on every device
sidebarToggle.addEventListener('click', event => {
  event.stopPropagation(); // prevent document click from firing
  sidebar.classList.toggle('sidebar-hidden');
});

// Auto‑hide on mobile when tapping elsewhere
document.addEventListener('click', event => {
  // Only run on mobile
  if (!isMobile()) return;

  // Do nothing if sidebar is already hidden
  if (sidebar.classList.contains('sidebar-hidden')) return;

  // Ignore clicks inside sidebar or on the toggle button
  if (sidebar.contains(event.target) || sidebarToggle.contains(event.target)) return;

  // Otherwise hide sidebar
  sidebar.classList.add('sidebar-hidden');
});

// Keep behaviour correct on resize/orientation change
window.addEventListener('resize', () => {
  if (!isMobile()) {
    // Make sure sidebar is visible on desktop if you wish
    sidebar.classList.remove('sidebar-hidden');
  } else {
    // (optional) hide it when switching to mobile
    sidebar.classList.add('sidebar-hidden');
  }
});


/** 
 * Function for Showing SweetAlert Message
*/
function alertMessage(event, icon, title) {
  if (event) event.preventDefault();

  // Set background color based on icon type
  let backgroundColor = '#ffffff';
  switch (icon) {
    case 'success':
      backgroundColor = '#28a745'; // green
      break;
    case 'error':
      backgroundColor = '#dc3545'; // red
      break;
    case 'warning':
      backgroundColor = '#ffc107'; // yellow
      break;
    case 'info':
      backgroundColor = '#17a2b8'; // blue
      break;
    default:
      backgroundColor = '#6c757d'; // gray
  }

  Swal.fire({
    toast: true,
    position: 'top-end',
    icon: icon,
    iconColor: '#ffffff', // Always white
    title: title,
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
    background: backgroundColor,
    color: '#ffffff', // Make title text white too for consistency
    customClass: {
      popup: 'thin-toast'
    }
  });
}


/**
 * Functions to show and hide loader based on API request processing
 */

// 1. Function to show loader
function showLoader() {
  $('.btn').attr('disabled', true);
  $('.button-text').hide();
  $('.spinner-border').removeClass('d-none');
}

// 2. Function to hide loader
function hideLoader() {
  $('.btn').attr('disabled', false);
  $('.button-text').show();
  $('.spinner-border').addClass('d-none');
}


/**
 * Functions to show and hide password input
 */
function showInputPassword () {
  const hidePasswordIcon = document.getElementById('hidePasswordIcon');
  const showPasswordIcon = document.getElementById('showPasswordIcon');
  const passwordInput = document.getElementById('signup-password');

  showPasswordIcon.classList.remove('d-none');
  hidePasswordIcon.classList.add('d-none');
  passwordInput.setAttribute("type", "text");
}

function hideInputPassword () {
  const hidePasswordIcon = document.getElementById('hidePasswordIcon');
  const showPasswordIcon = document.getElementById('showPasswordIcon');
  const passwordInput = document.getElementById('signup-password');

  showPasswordIcon.classList.add('d-none');
  hidePasswordIcon.classList.remove('d-none');
  passwordInput.setAttribute("type", "password");
}


/**
 * Toastify notifications for success and error messages
 */
function successToast(message) {
  Toastify({
    gravity: "top", // `top` or `bottom`
    position: "center", // `left`, `center` or `right`
    text: `
      <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" style="width: 30px; height: 30px; vertical-align: middle; margin-right: 5px;">
        <circle cx="24" cy="24" r="24" fill="#ffffff"/>
        <path fill="#28CC74" d="M20.2 31.4L14.8 26c-0.8-0.8-0.8-2 0-2.8s2-0.8 2.8 0l4.2 4.2 9.4-9.4c0.8-0.8 2-0.8 2.8 0s0.8 2 0 2.8l-11 11c-0.8 0.8-2 0.8-2.8 0z"/>
      </svg>
      ${message}
    `, // Prepend the SVG to the message using template literals
    escapeMarkup: false, // Essential: Allows HTML (SVG) to be rendered
    className: "mb-5",
    style: {
      background: "#28CC74",
    },
    newWindow: true, // Open in a new window
    close: true // Allow closing the toast
  }).showToast();
}

function warningToast(message) {
  Toastify({
    gravity: "top", // `top` or `bottom`
    position: "center", // `left`, `center` or `right`
    text: `
      <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" style="width: 25px; height: 25px; vertical-align: middle; margin-right: 5px;">
          <circle cx="24" cy="24" r="24" fill="#ffffff"/>
          <rect x="21" y="12" width="6" height="16" fill="#ffc107"/>
          <rect x="21" y="32" width="6" height="4" fill="#ffc107"/>
      </svg>
      ${message}
    `, // Prepend the SVG to the message using template literals
    escapeMarkup: false, // Essential: Allows HTML (SVG) to be rendered
    className: "mb-5",
    style: {
      background: "#ffc107",
    },
    newWindow: true, // Open in a new window
    close: true // Allow closing the toast
  }).showToast();
}

function errorToast(message) {
  Toastify({
    gravity: "top", // `top` or `bottom`
    position: "center", // `left`, `center` or `right`
    text: `
      <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" style="width: 30px; height: 30px; vertical-align: middle; margin-right: 5px;">
          <circle cx="24" cy="24" r="24" fill="#ffffff"/>
          <rect x="21" y="12" width="6" height="16" fill="#FF4C4C"/>
          <rect x="21" y="32" width="6" height="4" fill="#FF4C4C"/>
      </svg>
      ${message}
    `, // Prepend the SVG to the message using template literals
    escapeMarkup: false, // Essential: Allows HTML (SVG) to be rendered
    className: "mb-5",
    style: {
      background: "#FF4C4C", // Red gradient
    },
    newWindow: true, // Open in a new window
    close: true // Allow closing the toast
  }).showToast();
}