let privacyToggleLabel = document.querySelector('label[for="privacyToggle"]');

function addUserEventListeners() {
    // let settingsForm = document.querySelector('form#edit-user');
    let deleteForm = document.querySelector('form#delete-user');

    // if (settingsForm != null)
    //     settingsForm.addEventListener('submit', sendEditProfile);

    if (deleteForm != null)
        deleteForm.addEventListener('submit', sendDeleteProfile);

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
}

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

function sendEditProfile() {
    // event.preventDefault();
    let first_name = document.querySelector('input[name=first_name]').value;
    let last_name = document.querySelector('input[name=last_name]').value;
    let username = document.querySelector('input[name=username]').value;
    let email = document.querySelector('input[name=email]').value;
    let password = document.querySelector('input[name=password]').value;
    let password_confirmation = document.querySelector('input[name=password_confirmation]').value;
    let gender = document.querySelector('*[name=gender]').value;
    let image = document.querySelector('*[name=image]').files[0];
    console.log(image);
    let birthday = document.querySelector('input[name=birthday]').value;
    let private = document.querySelector('#privacyToggle').checked;
    console.log("private = " + typeof private);

    sendAjaxRequest('put', '/settings', {
        username: username,
        first_name: first_name,
        last_name: last_name,
        email: email,
        gender: gender,
        image: image,
        birthday: birthday,
        password: password,
        password_confirmation: password_confirmation,
        private: private
    }, profileEditedHandler);
}

function sendDeleteProfile(event) {
    console.log("send delete");
    event.preventDefault();
    let delete_content = document.querySelector('#deleteContentSwitch').checked;
    console.log("private is " + delete_content);
    sendAjaxRequest('delete', '/settings', { delete_content: delete_content }, profileDeletedHandler);
}

function profileDeletedHandler() {

    let response = JSON.parse(this.responseText);
    console.log(response);
    if (this.status == 200) {
        // console.log("200 OK!" + this.status);
        window.location = '/';
    }
    else {
        // console.log(this.status);
        window.location = '/';
    }
}

function profileEditedHandler() {
    event.preventDefault();
    console.log(this.status);
    if (this.status == 200) {
        // console.log("200 OK!" + this.status);
    }
    else if (this.status == 500) {
        console.log(this.status);
    }

    let response = JSON.parse(this.responseText);
    console.log(this.response);
    let string = "";
    for (let s in response.errors) {
        string += "<li>" + response.errors[s] + "</li>"
    }

    // window.location('/settings');
    let feedbackMessage = document.querySelector('#feedback-message');

    if (response.success == true) {
        feedbackMessage.innerHTML = `
        <div class="alert alert-success">
            <div class="my-auto">
                <p class="text-align-center">Changes saved successfuly!</p>
            </div>
        </div>`}
    else {
        feedbackMessage.innerHTML = `
        <div class="alert alert-danger">
            <ul class="my-auto">
                ${string}
            </ul>
        </div>`
    }
}

let settingsForm = document.querySelector('#edit-user');
let privacyToggle = document.querySelector(' #edit-user #privacyToggle');
if (settingsForm != null && privacyToggle != null) {
    settingsForm.reset()
    addUserEventListeners();

    if (privacyToggle.hasAttribute('checked')) {
        privacyToggleLabel.innerHTML = "Private Account";
    } else {
        privacyToggleLabel.innerHTML = "Public Account";
    }
}

function mySubmitFunction() {
    sendEditProfile();
    return false;
}