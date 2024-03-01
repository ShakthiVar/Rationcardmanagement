$(document).ready(function() {
    $('#registerButton').click(function() {
        submitRegistration();
    });

    $('#forgotPassword').click(function(e) {
        e.preventDefault();
        var email = prompt("Please enter your email address:");

        if (email !== null && email !== "") {
            // Proceed with sending the email for password reset
            sendPasswordResetEmail(email);
        }
    });
});

function submitRegistration() {
    var username = $('#usrname').val();
    var password = $('#password').val();
    var shopName = $('#shopName').val();
    var address = $('#address').val();
    var contactInfo = $('#contactInfo').val();

    // Perform validation
    if (!username || !password || !shopName || !address || !contactInfo) {
        alert("Please fill in all fields.");
        return;
    }

    $.ajax({
        type: 'POST',
        url: '../php/register.php',
        data: {
            'action': 'register',
            'username': username,
            'password': password,
            'shopName': shopName,
            'address': address,
            'contactInfo': contactInfo
        },
        success: function(response) {
            if (response.error) {
                alert(response.error);
            } else {
                alert(response.message);
                if(response.message === 'Registration successful'){
                    window.location.href = 'login.html';
                }
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function sendPasswordResetEmail(email) {
    $.ajax({
        type: 'POST',
        url: '../php/register.php',
        data: {
            'action': 'forgotPassword',
            'email': email
        },
        success: function(response) {
            alert(response.message);
        },
        error: function(error) {
            console.log(error);
        }
    });
}
