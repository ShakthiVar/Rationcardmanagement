function submitLogin() {
  var username = $('#username').val();
  var password = $('#password').val();

  if (username.trim() === '' || password.trim() === '') {
      $('#error-message').text('Please fill in all fields.').show();
      return;
  }

  $.ajax({
      url: '../php/login.php',
      type: 'POST',
      data: {
          'username': username,
          'password': password
      },
      dataType: 'json',
      success: function(response) {
          if (response.message === 'Login successful') {
              window.location.href = 'dashboard.html';
          } else {
              alert(response.message);
          }
      },
      error: function(xhr, status, error) {
          console.log(xhr.responseText);
          alert('Error: ' + status + ' - ' + error);
      }
  });
}
