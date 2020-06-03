let timeoutHandlerEdit, timeoutHandlerDelete, timeoutHandlerRecover;

let privacyToggleLabel = document.querySelector('label[for="privacyToggle"]');
let deleteToggleLabel = document.querySelector('label[for="deleteToggle"]');
let deleteToggle = document.querySelector('#deleteToggle');
let settingsForm = document.querySelector('#edit-user');
let deleteUserForm = document.querySelector('#delete-user');
let privacyToggle = document.querySelector('#edit-user #privacyToggle');
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
    let notificationButtons = document.getElementsByClassName('change-notification');

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
    if (this.status != 200) {
        // console.log(this.responseText)
        let response = JSON.parse(this.responseText);
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
    // console.log(responseText);
    // let response = JSON.parse(responseText);
    // console.log(response);
    let string = "";
    for (let s in response.errors) {
        string += "<li>" + response.errors[s] + "</li>"
    }

    // window.location('/settings');
    let feedbackMessage = document.querySelector('#feedback-message');

    if (response.success == true) {
        let username = document.querySelector('#edit-user #username').value;
        let deleteUserInputSolution = document.getElementById('delete-user-solution');
        let imgPath = response.imgPath;
        let photoSettings = document.getElementById('photoSettings');
        let photoNavbar = document.getElementById('profileNav');

        photoSettings.src = imgPath;
        photoNavbar.src = imgPath;

        deleteUserInputSolution.value = username;

        feedbackMessage.innerHTML = `
        <div class="alert alert-md-0 card mb-4 alert-success">
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

    if (password_confirmation != null) {
        password_confirmation.value = "";
        password_confirmation.style.boxShadow = "initial";
    }

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

function blockUser(event, blocked) {
    event.preventDefault();
    event.stopPropagation();
    blockButton = document.getElementById("block-button");
    // console.log(blockButton);
    let followButton = document.getElementById("follow-button");
    console.log(blockButton)
    if (blockButton.value == "Block") {
        blockButton.value = "Unblock";
        sendAjaxRequest('post', '/block/' + blocked, {
        }, blockHandler);
        followButton.style.display = "none";
    } else if (blockButton.value == "Unblock") {
        sendAjaxRequest('delete', '/block/' + blocked, {
        }, blockHandler);
        blockButton.value = "Block";
        followButton.style.display = 'block';
    }
}

function reportUser(event) {
    event.preventDefault();
    event.stopPropagation();
    let user_id = document.getElementById('modalUserReport').getAttribute('data-object');
    let reasonSelect = document.getElementById('userReportReason');
    let reasonOption = reasonSelect.options[reasonSelect.selectedIndex].innerHTML;
    // console.log(reasonOption);
    sendAjaxRequest('post', '/user/' + user_id + '/report', { reason: reasonOption });

    $('#modalUserReport').modal('hide');
}

function followUser(event, followed) {
    event.preventDefault();
    event.stopPropagation();
    followButton = document.getElementById("follow-button");
    if (followButton.value == "Follow") {
        sendAjaxRequest('post', '/follow/' + followed, {
        }, followHandler);
        followButton.value = "Pending";
    } else if (followButton.value == "Unfollow" || followButton.value == "Pending") {
        sendAjaxRequest('delete', '/follow/' + followed, {
        }, followHandler);
        followButton.value = "Follow";
    }
}

function joinPrivateCommunity(event, communityId) {
    event.preventDefault();
    event.stopPropagation();
    joinButton = document.getElementById("join-button");
    if (joinButton.value == "Join") {
        sendAjaxRequest('post', '/community/' + communityId + '/membership/', {
        }, () => { console.log(this.responseText); });
        joinButton.value = "Pending";
    } else if (joinButton.value == "Leave" || joinButton.value == "Pending") {
        sendAjaxRequest('delete', '/community/' + communityId + '/membership/', {
        }, () => { console.log(this.responseText); });
        joinButton.value = "Join";
    }
}

function joinCommunity(event, communityId) {
    event.preventDefault();
    event.stopPropagation();
    joinButton = document.getElementById("join-button");
    if (joinButton.value == "Join") {
        sendAjaxRequest('post', '/community/' + communityId + '/membership/', {
        }, () => { console.log(this.responseText); });
        joinButton.value = "Leave";
    } else if (joinButton.value == "Leave") {
        sendAjaxRequest('delete', '/community/' + communityId + '/membership/', {
        }, () => { console.log(this.responseText); });
        joinButton.value = "Join";
    }
}

function followHandler() {
    // console.log(this.responseText);
}

function blockHandler() {
    // console.log(this.responseText);
}

// Delete user
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

// Update user settings
$(document).ready(function () {
    $('#edit-user').on('submit', function (event) {
        event.preventDefault();
        let private = $('#privacyToggle').attr('checked');
        if (private == null) {
            private = false;
        } else {
            private = true;
        }

        let password = document.querySelector('#edit-user #password');
        let password_confirmation = document.querySelector('#edit-user #password_confirmation');

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

// Profile aside menu
let activities = document.querySelectorAll('.sidebar-box');
if (activities.length != 0) {
    let height;
    let readMore;
    activities.forEach((activity) => {
        height = activity.clientHeight;
        readMore = activity.querySelector('.read-more');
        if (height < 80) {
            readMore.style.padding = 0;
        }
    });
}

let communities_menu = document.getElementById("community_menu")
if (communities_menu != null)
    communities_menu.addEventListener("click", profile_tabs);

function profile_tabs() {
    let profile_aside = document.querySelectorAll(".profile-aside");

    if (profile_aside.length != 0) {
        let profile_content = document.querySelectorAll(".profile-content");

        if (profile_content.length != 0) {
            profile_content.forEach(tab => {
                if (tab.classList.contains("active-tab")) {
                    tab.classList.remove("active-tab");
                    tab.classList.add("hidden-tab");
                    tab.style.display = "none";
                } else {
                    tab.classList.remove("hidden-tab");
                    tab.classList.add("active-tab");
                    tab.style.display = "block";
                }
            });
        }

        for (let i = 0; i < profile_aside.length; i++) {
            // remove selected
            if (profile_aside[i].classList.contains("nav-border-active")) {
                profile_aside[i].classList.remove("nav-border-active");
                profile_aside[i].classList.add("nav-border");
                profile_aside[i].addEventListener("click", profile_tabs);
            }
            // add selected
            else {
                profile_aside[i].classList.remove("nav-border");
                profile_aside[i].classList.add("nav-border-active");
                profile_aside[i].removeEventListener("click", profile_tabs);
            }
        }
    }
}
