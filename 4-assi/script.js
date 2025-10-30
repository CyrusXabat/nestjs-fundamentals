$(document).ready(function() {
  // Show/Hide Password
  $('#togglePwd').on('click', function() {
    const pwdField = $('#password');
    const type = pwdField.attr('type') === 'password' ? 'text' : 'password';
    pwdField.attr('type', type);
    $(this).text(type === 'password' ? 'Show' : 'Hide');
  });

  // Form Submission Event
  $('#validationForm').on('submit', function(e) {
    e.preventDefault();
    $('#message').html('');
    let errors = [];

    // Name validation
    const name = $('#name').val().trim();
    if(name === '') {
      errors.push('Name is required.');
    }
    // Email validation
    const email = $('#email').val().trim();
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
    if(email === '') {
      errors.push('Email is required.');
    } else if(!emailPattern.test(email)) {
      errors.push('Invalid email format.');
    }
    // Phone validation
    const phone = $('#phone').val().trim();
    const phoneDigits = /^\d{10}$/;
    if(phone === '') {
      errors.push('Phone number is required.');
    } else if(!phoneDigits.test(phone)) {
      errors.push('Phone number must be exactly 10 digits.');
    }
    // Password validation
    const password = $('#password').val();
    const pwdPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if(password === '') {
      errors.push('Password is required.');
    } else if(!pwdPattern.test(password)) {
      errors.push('Password must be minimum 8 characters, include uppercase, lowercase, and a number.');
    }

    // Show errors
    if(errors.length > 0) {
      $('#message').html('<div class="errorBox">' + errors.join('<br>') + '</div>');
      return;
    }
    // Success message
    $('#message').html('<div class="successBox">Form submitted successfully!</div>');
    // Optionally, reset form after a short delay
    setTimeout(function() {
      $('#validationForm')[0].reset();
      $('#togglePwd').text('Show');
    }, 1500);
  });
});
