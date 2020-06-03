function addAdminEventListeners() {
    let adminDeleteButtons = document.querySelectorAll(".admin-delete");
    for (let i = 0; i < adminDeleteButtons.length; i++) {
        console.log(adminDeleteButtons[i]);
        adminDeleteButtons[i].addEventListener("click", (event) => {
            console.log("please");
            event.preventDefault();
            event.stopPropagation();
            deleteAdminEvent(adminDeleteButtons[i]);
        });
    }

    // if (adminDeleteButtons.length != 0) {
    //     console.log("please1");
    //     adminDeleteButtons.forEach((item) => {
    //         item.addEventListener('click', (event) => {
    //             console.log("please");
    //             event.preventDefault();
    //             event.stopPropagation();
    //             deleteAdminEvent(item);
    //         });
    //     });
    // }
}

function deleteAdminEvent(item) {
    console.log(item)
    let type = item.getAttribute('data-type');
    let id = item.getAttribute('data-object');
    let report_id = item.getAttribute('data-report');
    let report = [];

    if (type == "post") {
        report = document.querySelectorAll('.post-' + id);
        console.log(report.length);
        sendAjaxRequest('delete', '/admin/post/' + id, {});
    } else if (type == "comment") {

        report = document.getElementsByClassName('comment-' + id);
        console.log(report.length);
        sendAjaxRequest('delete', '/admin/comment/' + id, {});
    } else if (type == "community") {
        report = document.getElementsByClassName('community-' + id);
        console.log(report.length);
        sendAjaxRequest('delete', '/admin/community/' + id, {});

    } else if (type == "user") {
        report = document.querySelectorAll('.user-' + id + ", div.author-comment-" + id + ", div.author-post-" + id);
        // console.log(report);
        // user = (document.querySelectorAll("a[data-type='user-comment'][data-object='" + id + "']"));
        // console.log(user);
        // report = report.concat(document.querySelectorAll("a[data-type='user-post'][data-object='" + id + "']"));
        console.log(report);
        sendAjaxRequest('delete', '/admin/user/' + id, {});

    } else if (type == "user-comment") {
        let comment_id = item.getAttribute('data-target');
        report = document.querySelectorAll(".comment-" + comment_id + ", .user-" + id + ", div.author-post-" + id);
        // report = report.concat(document.querySelectorAll('.user-' + id));
        // report = report.concat(document.querySelectorAll("a[data-type='user-post'][data-object='" + id + "']"));
        console.log(report);
        sendAjaxRequest('delete', '/admin/user/' + id, {});

    } else if (type == "user-post") {
        let post_id = item.getAttribute('data-target');
        report = document.querySelectorAll('.post-' + post_id + ", .user-" + id + ", div.author-comment-" + id);
        console.log(report);
        // report = report.concat(document.querySelectorAll('.user-' + id));
        // report = report.concat(document.querySelectorAll("[data-type = user-comment][data-object='" + id + "']"));
        // console.log(report.length);
        sendAjaxRequest('delete', '/admin/user/' + id, {});
    }
    
    reportArray = [].slice.call(report);
    console.log(reportArray.length);
    reportArray.forEach(report => {
        console.log("111");
        report.remove();
    });
}

addAdminEventListeners();
