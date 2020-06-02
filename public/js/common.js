let file = document.getElementById('customFile');
let notificationBell = document.getElementById('notificationBell');
let reportButtons = document.querySelectorAll(".report-button");

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

if (notificationBell != null) {
    notificationBell.addEventListener('click', getNotifications);
}

if (reportButtons.length > 0) {
    reportButtons.forEach((item) => {
        item.addEventListener('click', (event) => {
            event.preventDefault();
            let modalId = item.getAttribute('data-target');
            let modal = document.querySelector(modalId);
            let objectId = item.getAttribute('data-object');
            modal.setAttribute('data-object', objectId);
        })
    })
}

function getNotifications(e) {
    e.preventDefault();
    // e.stopPropagation();
    let notificationContainer = document.querySelectorAll('.notifications-wrapper')[0];

    if (notificationContainer.children.length == 0)
        notificationContainer.innerHTML = `<h4 class="item-title" style="padding-left: 10px;">No new notifications.</h4>`;
    sendAjaxRequest('get', '/notification', {}, displayNotifications);
}

$(document).ready(function () {
    sendAjaxRequest('get', '/notification', {}, notificationBellHandler);
});

function notificationBellHandler() {
    let info = JSON.parse(this.responseText);
    let allNotifications = info.follow_notifications.concat(info.join_notifications);
    let notNotice = document.querySelector(".fas.fa-exclamation-circle");

    if (allNotifications.length != 0) {
        notNotice.style.display = "block";
    }
}

function changeNotificationStatus(item) {
    let notificationId = item.getAttribute('data-target');
    let buttonType = item.getAttribute('data-type');
    sendAjaxRequest('put', '/notification/' + notificationId, { status: buttonType }, function (item) {
        let notification = document.getElementById("notification-" + notificationId);
        notification.remove();

        let notificationContainer = document.querySelectorAll('.notifications-wrapper')[0];
        let notNotice = document.querySelector(".fas.fa-exclamation-circle");
        let notCounter = document.querySelectorAll(".notification-content");

        if (notCounter.length == 0) {
            notificationContainer.innerHTML = `<h4 class="item-title" style="padding-left: 10px;">No new notifications.</h4>`;
            notNotice.style.display = "none";
        }
    });
}

function displayNotifications() {
    console.log(this.responseText);
    let info = JSON.parse(this.responseText)
    let notificationContainer = document.querySelectorAll('.notifications-wrapper')[0];
    console.log(info)
    let allNotifications = info.follow_notifications.concat(info.join_notifications);

    if (allNotifications.length != 0)
        notificationContainer.innerHTML = "";
    allNotifications.sort((a, b) => { (new Date(a.time_stamp).valueOf() > Date(b.time_stamp).valueOf()) ? 1 : -1 });
    console.log(allNotifications);
    allNotifications.forEach((item) => {
        console.log(item);
        let notification;
        if (item['image'] == null) {
            notification = followNotificationPartial(item);
        } else {
            notification = joinCommunityNotificationPartial(item);
        }
        let notificationPartial = document.createElement('div');
        notificationPartial.innerHTML = notification;
        notificationContainer.appendChild(notificationPartial);
    });

    let notificationButtons = document.querySelectorAll('.change-notification');
    if (notificationButtons.length != 0) {
        console.log(notificationButtons);

        notificationButtons.forEach((item) =>
            item.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                changeNotificationStatus(item);
            }));
    }
}

function timeSince(date) {
    var msPerMinute = 60 * 1000;
    var msPerHour = msPerMinute * 60;
    var msPerDay = msPerHour * 24;
    var msPerMonth = msPerDay * 30;
    var msPerYear = msPerDay * 365;

    var elapsed = new Date() - date;

    if (elapsed < msPerMinute) {
        let seconds = Math.round(elapsed / 1000);
        let message = '';
        seconds < 5 ? message = 'Just now' : message = (seconds + ' seconds ago');
        return message;
    }

    else if (elapsed < msPerHour) {
        let minutes = Math.round(elapsed / msPerMinute);

        let message = '';
        minutes == 1 ? message = '1 minute ago' : message = (minutes + ' minutes ago');
        return message;
    }

    else if (elapsed < msPerDay) {
        let hours = Math.round(elapsed / msPerHour);

        let message = '';
        hours == 1 ? message = '1 hour ago' : message = (hours + ' hours ago');
        return message;
    }

    else if (elapsed < msPerMonth) {
        let days = Math.round(elapsed / msPerDay);

        let message = '';
        days == 1 ? message = '1 day ago' : message = (days + ' days ago');
        return message;
    }

    else if (elapsed < msPerYear) {
        let months = Math.round(elapsed / msPerMonth);

        let message = '';
        months == 1 ? message = '1 month ago' : message = (months + ' months ago');
        return message;
    }

    else {
        let years = Math.round(elapsed / msPerYear);

        let message = '';
        years == 1 ? message = '1 year ago' : message = (years + ' years ago');
        return message;
    }
}

function followNotificationPartial(notification) {
    // Split timestamp into [ Y, M, D, h, m, s ]
    let date_time = notification['time_stamp'].split(/[.]/);
    let t = date_time[0].split(/[- :]/);
    // Apply each element to the Date function
    let d = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
    console.log(notification['photo']);
    let userPhoto = notification['photo'];
    if (userPhoto.search(/google/) == -1)
        userPhoto = "/" + userPhoto;

    let read = notification['is_read'] ? "read-notification" : "unread-notification";
    let not = `
    <div class="notification-content ${read}" id="notification-${notification['id']}">
        <div class="notification-item">
            <div class="row">
                <div class="col-3">
                    <a href="/user/${notification['id_sender']}">
                        <img class="profile-pic-small" height="50" width="50"
                            src="${userPhoto}" alt="Profile Image"></a>
                </div>
                <div class="col-8 p-0">
                    <h4 class="item-title"><a href="/user/${notification['id_sender']}">@${notification['username']}</a> sent you a follow request</h4>
                    <h6 class="item-info"> <i class="fas fa-calendar-alt"></i> ${timeSince(d)} </h6 >
                </div>
                <div class="d-flex align-items-start pt-1">
                    <div class="col-2">
                        <div class="row mb-3">
                            <a href="">
                                <i class="fas fa-check change-notification" data-type="accept" data-target="${notification['id']}"></i>
                            </a>
                        </div>
                        <div class="row">
                            <a href="">
                                <i class="fas fa-times change-notification" data-type="deny" data-target="${notification['id']}"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div >
        </div >
    </div >
    <hr class="my-0" style="width: 80%;">`
    return not;
}

function joinCommunityNotificationPartial(notification) {
    // Split timestamp into [ Y, M, D, h, m, s ]
    let date_time = notification['time_stamp'].split(/[.]/);
    let t = date_time[0].split(/[- :]/);
    // Apply each element to the Date function
    let d = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));

    let userPhoto = notification['photo'];
    if (userPhoto.search(/google/) == -1)
        userPhoto = "/" + userPhoto;

    let read = notification['is_read'] ? "read-notification" : "unread-notification";
    let not = `
    <div class="notification-content ${read}" id="notification-${notification['id']}">

            <div class="notification-item">

                <div class="row">
                    <div class="col-3">
                        <a href="/user/${notification['']}">
                            <img class="profile-pic-small" height="50" width="50"
                                src="${userPhoto}" alt="Profile Image">
                        </a>
                    </div>
                        <div class="col-8 p-0">
                            <h4 class="item-title text-muted"><a href="/user/${notification['id_sender']}">@${notification['username']}</a> asked to
                            join your communnity <a href="/community/${notification['community_id']}">/${notification['name']}</a></h4>
                            <h6 class="item-info"> <i class="fas fa-calendar-alt mr-1"></i> ${timeSince(d)} </h6>
                        </div>
                        <div class="d-flex align-items-start pt-1">
                            <div class="col-2">
                                <div class="row mb-3">
                                    <a href="">
                                        <i class="fas fa-check change-notification" data-type="accept" data-target="${notification['id']}"></i>
                                    </a>
                                </div>
                                <div class="row">
                                    <a href="">
                                        <i class="fas fa-times change-notification" data-type="deny" data-target="${notification['id']}"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`
    return not;
}
