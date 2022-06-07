function checkLogin(username, node, fieldName, count) {
    if (isEmpty(username, node, fieldName)) {
        return false;
    }

    const checkLogin = new RegExp('^\\w+$', 'i').test(username);
    if (!checkLogin) {
        message(node, 'Not valid username');
        return false;
    }

    if (username.length < count) {
        message(node, `Minimum ${count} characters`);
        return false;
    }

    return true;
}

function checkPassword(password, node, fieldName, count) {
    if (isEmpty(password, node, fieldName)) {
        return false;
    }

    if (password.length < count) {
        message(node, `Minimum ${count} characters`);
        return false;
    }

    const checkPassword = new RegExp('^(?:\\d+[a-z]|[a-z]+\\d)[a-z\\d]*$', 'i').test(password);
    if (!checkPassword) {
        message(node, 'Letters and numbers are required');
        return false;
    }

    return true;
}

function checkConfirmPassword(confirmPassword, password, node, fieldName) {
    if (isEmpty(confirmPassword, node, fieldName)) {
        return false;
    }

    if (password !== confirmPassword) {
        message(node, "Confirm password doesn't match");
        return false;
    }

    return true;
}

function checkEmail(email, node, fieldName) {
    if (isEmpty(email, node, fieldName)) {
        return false;
    }

    const checkEmail = new RegExp('^[A-Z0-9._%+-]+@[A-Z0-9.-]+\\.[A-Z]{2,4}$', 'i').test(email);
    if (!checkEmail) {
        message(node, 'Not valid email');
        return false;
    }

    return true;
}

function checkName(name, node, fieldName) {
    if (isEmpty(name, node, fieldName)) {
        return false;
    }

    const checkName = new RegExp('^[a-z]+$', 'i').test(name);
    if (!checkName) {
        message(node, 'Only letters');
        return false;
    }

    if (2 !== name.length) {
        message(node, 'Two characters');
        return false;
    }

    return true;
}

function validate(form) {
    $(".error").remove();

    const login = checkLogin(form.get('login'), $('#login'), 'username', 6);
    const pass = checkPassword(form.get('pass'), $('#pass'), 'password', 6);
    const confirmPass = checkConfirmPassword(form.get('confirm_pass'), form.get('pass'), $('#confirm_pass'), 'confirm password');
    const email = checkEmail(form.get('email'), $('#email'), 'email');
    // const name = checkName(form.get('name'), $('#name'), 'name');

    return (confirmPass && login && pass && email && name);
}

function checkErrors(errors) {
    if (errors['username']) {
        message($('#login'), errors['username']);
    }
    if (errors['password']) {
        message($('#pass'), errors['password']);
    }
    if (errors['confirm_password']) {
        message($('#confirm_pass'), errors['confirm_password']);
    }
    if (errors['email']) {
        message($('#email'), errors['email']);
    }
    // if (errors['name']) {
    //     message($('#name'), errors['name']);
    // }
}

function checkUser(form) {
    $.ajax({
        type: 'POST',
        url: '/register',
        data: form,
        processData: false,
        contentType: false,
        success: function (data) {
            if (typeof data === 'object' && data.hasOwnProperty('errors')) {
                checkErrors(data.errors);
            } else {
                window.location.assign('/');
            }
        }
    });
}

$(document).ready(function () {
    $('#register-form').submit(function (event) {
        event.preventDefault();
        const form = new FormData(event.target);

        if (validate(form)) {
            checkUser(form);
        }
    })
})