let timeoutHandlerEdit, timeoutHandlerDelete, timeoutHandlerRecover;

let privacyToggleLabel = document.querySelector('label[for="privacyToggle"]');
let deleteToggleLabel = document.querySelector('label[for="deleteToggle"]');
let deleteToggle = document.querySelector('#deleteToggle');
let settingsForm = document.querySelector('#edit-user');
let deleteUserForm = document.querySelector('#delete-user');
let privacyToggle = document.querySelector(' #edit-user #privacyToggle');
let deleteWarningBox = document.getElementById('delete-warning-box');

function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
}

function sendAjaxRequestImage(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

    request.setRequestHeader('Content-type', 'multipart/form-data');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
}

function addUserEventListeners() {
    let deleteButton = document.getElementById('deleteAccount');
    let deleteUserInput = document.getElementById('delete-confirm-username');
    let deleteUserInputSolution = document.getElementById('delete-user-solution');
    let resetPassButton = document.getElementById('recoverPassword');
    let modal = document.getElementById('modalDelete');

    if (deleteButton != null)
        deleteButton.addEventListener('click', sendDeleteProfile);

    if (deleteUserInput != null)
        deleteUserInput.addEventListener('keyup', function () {
            if (deleteUserInput.value == deleteUserInputSolution.value) {
                deleteButton.removeAttribute("disabled");
            }
            else {
                if (!deleteButton.hasAttribute("disabled"))
                    deleteButton.setAttribute("disabled", "disabled");
            }
        });

    if (privacyToggle != null) {
        privacyToggle.addEventListener('change', () => {
            if (!privacyToggle.hasAttribute("checked")) {
                privacyToggleLabel.innerHTML = "Private Account";
                privacyToggle.setAttribute("checked", "checked");
            } else {
                privacyToggleLabel.innerHTML = "Public Account";
                privacyToggle.removeAttribute("checked");
            }
        });
    }

    if (deleteToggle != null) {
        deleteToggle.addEventListener('change', () => {
            if (!deleteToggle.hasAttribute("checked")) {
                deleteToggle.setAttribute("checked", "checked");
                deleteToggleLabel.innerHTML = "Delete my content";
                deleteWarningBox.removeAttribute("hidden");
            } else {
                deleteToggle.removeAttribute("checked");
                deleteToggleLabel.innerHTML = "Keep my content";
                deleteWarningBox.setAttribute("hidden", "hidden");
            }
        });
    }

    if (resetPassButton != null) {
        resetPassButton.addEventListener('submit', sendResetPassword);
    }
}

function sendResetPassword(e) {
    e.preventDefault();
    e.stopPropagation();

    let email = document.querySelector('#recoverPassword input[name=email]').value;
    sendAjaxRequest('put', '/reset_password_form', {
        email: email
    }, resetPassHandler);
}

function resetPassHandler() {
    console.log("did it");

    if (this.status != 200) {
        console.log("500 it");
        console.log(response)
        let response = JSON.parse(this.responseText);
        // window.location = '/';
        let string = "";
        for (let s in response.errors) {
            string += "<li>" + response.errors[s] + "</li>"
        }

        let feedbackMessage = document.querySelector('#feedback-message-recover');

        if (feedbackMessage != null)
            feedbackMessage.innerHTML = `
                <div class="alert alert-danger">
                    <ul class="my-auto">
                        ${string}
                    </ul>
                </div>`

        window.clearTimeout(timeoutHandlerRecover);
        timeoutHandlerRecover = setTimeout(function () {
            feedbackMessage.innerHTML = ``
        }, 5000);
    } else {
        window.location = 'reset_password_email_sent';
    }
}

function sendDeleteProfile(event) {
    console.log("send delete");
    event.preventDefault();

    let delete_content = document.querySelector('#deleteToggle').checked;
    sendAjaxRequest('delete', '/settings', { delete_content: delete_content }, profileDeletedHandler);
}

function profileDeletedHandler() {
    if (this.status == 200) {
        // console.log("200 OK!" + this.status);
        window.location = '/';

        let feedbackMessage = document.querySelector('#feedback-message-home');

        if (feedbackMessage != null)
            feedbackMessage.innerHTML = `
                <div class="alert alert-success">
                    <div class="my-auto">
                        <p class="my-0">hanges saved successfuly!</p>
                    </div>
                </div>`

        window.clearTimeout(timeoutHandlerDelete);
        timeoutHandlerDelete = setTimeout(function () {
            feedbackMessage.innerHTML = ``
        }, 5000);
    }
    else {
        // console.log(this.status);
        window.location = '/';
    }
}

function profileEditedHandler(responseText) {
    console.log(responseText);
    let response = JSON.parse(responseText);
    console.log(response);
    let string = "";
    for (let s in response.errors) {
        string += "<li>" + response.errors[s] + "</li>"
    }

    // window.location('/settings');
    let feedbackMessage = document.querySelector('#feedback-message');

    if (response.success == true) {
        let username = document.querySelector('input[name=username]').value;
        let deleteUserInputSolution = document.getElementById('delete-user-solution');
        let imgPath = response.imgPath;
        let photoSettings = document.getElementById('photoSettings');
        let photoNavbar = document.getElementById('profileNav');

        photoSettings.src = imgPath;
        photoNavbar.src = imgPath;

        deleteUserInputSolution.value = username;

        feedbackMessage.innerHTML = `
        <div class="alert alert-success">
            <div class="my-auto">
                <p class="my-0">Changes saved successfuly!</p>
            </div>
        </div>`
    }
    else {
        feedbackMessage.innerHTML = `
        <div class="alert alert-danger">
            <ul class="my-auto">
                ${string}
            </ul>
        </div>`
    }

    let password_confirmation = document.getElementById('password_confirmation');
    password_confirmation.value = "";
    password_confirmation.style.boxShadow = "initial";

    let fileLabel = document.getElementById('customFileLabel');
    fileLabel.innerHTML = "No file selected.";

    window.clearTimeout(timeoutHandlerEdit);
    timeoutHandlerEdit = setTimeout(function () {
        feedbackMessage.innerHTML = ``
    }, 5000);
}

if (settingsForm != null && privacyToggle != null) {
    settingsForm.reset();
    if (privacyToggle.hasAttribute('checked')) {
        privacyToggleLabel.innerHTML = "Private Account";
    } else {
        privacyToggleLabel.innerHTML = "Public Account";
    }
}

addUserEventListeners();

function mySubmitFunction() {
    // sendEditProfile();
    return false;
}

$(window).bind('load', resetDeleteForm);

$('#modalDelete').on('hidden.bs.modal', resetDeleteForm);

function resetDeleteForm() {
    $('#deleteAccountForm')
        .find("input#delete-confirm-username,select")
        .val('')
        .end()
        .find("input[type=checkbox]")
        .prop("checked", false)
        .removeAttr("checked")
        .end()
        .find("#delete-warning-box")
        .prop("hidden", "hidden")
        .end()
        .find("label[for='deleteToggle']")
        .html("Keep my content")
        .end();
}

$(document).ready(function () {
    $('#edit-user').on('submit', function (event) {
        event.preventDefault();
        let private = $('#privacyToggle').attr('checked');
        if (private == null) {
            private = false;
        } else {
            private = true;
        }

        let password = document.querySelector('#password');
        let password_confirmation = document.querySelector('#password_confirmation');
        if (password != null) {
            password = password.value;
            password_confirmation = password_confirmation.value;
        } else {
            password = "password";
            password_confirmation = "password";
        }

        let data = new FormData(this);
        data.append('private', private);
        data.append('password', password);
        data.append('password_confirmation', password_confirmation);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/settings",
            method: "PUT",
            data: data,
            dataType: 'JSON',
            contentType: "multipart/form-data",
            cache: false,
            processData: false,
            success: function (response) {
                profileEditedHandler(JSON.stringify(response));
            },
            error: function (response) {
                profileEditedHandler(response.responseText);
            }
        })
    });
});
