$(document).ready(function () {
  // Prevent back navigation
  history.pushState(null, null, location.href);
  window.onpopstate = function () {
    history.go(1);
  };

  // Track if a toast is currently displayed
  let isToastActive = false;

  // Function to show toast notification
  function showToast(message, type) {
    console.log('showToast called with message:', message, 'type:', type);

    const toastContainer = $('#toastContainer');
    if (!toastContainer.length) {
      console.error('Toast container not found in DOM');
      return;
    }

    // If a toast is active, prevent new toasts
    if (isToastActive) {
      console.log('Toast already active, ignoring new toast');
      return;
    }

    // Remove any existing toasts
    toastContainer.empty();
    console.log('Cleared existing toasts');

    isToastActive = true;
    const toastId = 'toast-' + Date.now();
    const icon = type === 'success' ? '<i class="fas fa-check-circle toast-icon"></i>' : '<i data-feather="alert-circle" class="toast-icon"></i>';
    const toastHtml = `
      <div id="${toastId}" class="toast ${type}">
        ${icon}
        <span class="toast-message">${message}</span>
      </div>
    `;
    console.log('Appending toast HTML:', toastHtml);
    toastContainer.append(toastHtml);

    // Initialize Feather icons
    feather.replace();

    // Verify toast was added
    const toastElement = $(`#${toastId}`);
    if (toastElement.length) {
      console.log('Toast element added to DOM:', toastId);
    } else {
      console.error('Failed to add toast element to DOM:', toastId);
      isToastActive = false;
      return;
    }

    // Remove toast after 7 seconds or on click
    toastElement.on('click', function () {
      console.log('Toast clicked, removing:', toastId);
      $(this).remove();
      isToastActive = false;
    });
    setTimeout(() => {
      console.log('Auto-removing toast:', toastId);
      toastElement.remove();
      isToastActive = false;
    }, 7000);
  }

  $('#loginBtn').click(function (e) {
    e.preventDefault();
    console.log('Login button clicked');

    let username = $('#username').val().trim();
    let password = $('#password').val().trim();

    if (username === '') {
      showToast('Username is required', 'error');
      $('#username').focus();
      return;
    }

    if (password === '') {
      showToast('Password is required', 'error');
      $('#password').focus();
      return;
    }

    let formData = $('#loginForm').serialize();
    console.log('Form data:', formData);

    $.ajax({
      type: 'POST',
      url: 'ajaxscripts/queries/login.php',
      data: formData,
      beforeSend: function () {
        console.log('Sending AJAX request');
        $.blockUI({ message: '<h4>Please wait...</h4>' });
      },
      success: function (response) {
        $.unblockUI();
        console.log('AJAX success, response:', response);
        try {
          if (response.success) {
            showToast('Login successful', 'success');
            setTimeout(() => {
              console.log('Redirecting to:', response.redirect || 'index.php');
              window.location.href = response.redirect || 'index.php';
            }, 1000);
          } else {
            showToast(response.message, 'error');
          }
        } catch (err) {
          showToast('An error occurred. Please try again.', 'error');
          console.error('JSON Parse Error:', err, 'Response:', response);
        }
      },
      error: function (xhr, status, error) {
        $.unblockUI();
        showToast('Network error. Please try again.', 'error');
        console.error('AJAX Error:', status, error);
      }
    });
  });
});