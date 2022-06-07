let user = null;

function updateUser(data) {
    user = data;
    user ? $('#login-form-layout').hide() : displayLoginForm();

    hello();
}

function checkUser() {
    $.ajax({
        url: '/me',
        contentType: 'json',
        success: updateUser
    });
}

function validate(form) {
    $(".error").remove();

    const login = isEmpty(form.get('login'), $('#login'), 'username');
    const pass = isEmpty(form.get('pass'), $('#pass'), 'password');

    return (login || pass);
}

function handleLogin() {
    $('#login-form').submit(function (event) {
        event.preventDefault();

        form = new FormData(event.target);

        if (!validate(form)) {
            login(this);
        }
    })
}

function login(form) {
    $.ajax({
        type: 'POST',
        url: '/login',
        data: $(form).serialize(),
        success: function (data) {
            if (typeof data === 'object' && data.hasOwnProperty('errors')) {
                $(".error").remove();
                message($('#pass'), data.errors);
            } else {
                window.location.assign('/');
            }
        }
    });
}

function hello() {
    if (user) {
        $('#hello').text('Hello, ' + user.username + '!');
        $('#logout').append("<a href=\"/logout\" class=\"btn\">Logout</a>");
    } else {
        $('#logout').append("<a href=\"/login\" class=\"btn\">Login</a>");
    }
}

function displayLoginForm() {
    $.ajax({
        url: '/login',
        success: function (data) {
            $('#index').html(data);
            handleLogin();
        }
    });
}

$(document).ready(function () {
    checkUser();
})
