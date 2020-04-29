let file = document.getElementById('customFile');

if (file != null) {
    file.addEventListener('change', function (event) {
        let fileLabel = document.getElementById('customFileLabel');
        let n = file.value.lastIndexOf('\\');
        let filename = file.value.substring(n + 1);

        fileLabel.innerHTML = filename;
    });
}

let signUpButton = document.querySelector('#sign-up-nav-btn');
let loginButton = document.querySelector('#log-in-nav-btn');
let continueEmailButton = document.querySelector('#continue-email-button');

if (signUpButton != null) {
    signUpButton.addEventListener('click', function () {
        continueEmailButton.setAttribute("data-target", "#modalSignup");
    });
}

if (loginButton != null) {
    loginButton.addEventListener('click', function () {
        continueEmailButton.setAttribute("data-target", "#modalLogin");
    });
}

