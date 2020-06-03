function addAdminEventListeners() {
    let adminDeleteButtons = document.querySelectorAll(".admin-delete");
    for (let i = 0; i < adminDeleteButtons.length; i++) {
        //console.log(adminDeleteButtons[i]);
        adminDeleteButtons[i].addEventListener("click", (event) => {
            //console.log("please");
            event.preventDefault();
            event.stopPropagation();
            deleteAdminEvent(adminDeleteButtons[i]);
        });
    }

    let adminDeletePost = document.querySelectorAll(".admin-delete-post");
    for (let i = 0; i < adminDeletePost.length; i++) {
        //console.log(adminDeleteButtons[i]);
        adminDeletePost[i].addEventListener("click", (event) => {
            //console.log("please");
            event.preventDefault();
            event.stopPropagation();
            deleteAdminPost(adminDeletePost[i]);
        });
    }

    let adminDeleteComment = document.querySelectorAll(".admin-delete-comment");
    for (let i = 0; i < adminDeleteComment.length; i++) {
        //console.log(adminDeleteButtons[i]);
        adminDeleteComment[i].addEventListener("click", (event) => {
            //console.log("please");
            event.preventDefault();
            event.stopPropagation();
            deleteAdminComment(adminDeleteComment[i]);
        });
    }

}

function deleteAdminComment(item){
    let comment_id = item.getAttribute("data-object");
    console.log("hello");
    sendAjaxRequest('delete', '/admin/comment/' + comment_id, {}, deleteHandler);
}

function deleteAdminPost(item){
    let post_id = item.getAttribute("data-object");
    console.log("hello");
    sendAjaxRequest('delete', '/admin/post/' + post_id, {}, deleteHandler);
}

function deleteUser(event, id) {
    event.preventDefault();
    event.stopPropagation();
    sendAjaxRequest('delete', '/admin/user/' + id, {}, deleteHandler);
}
function deleteComment(event, id) {
    event.preventDefault();
    event.stopPropagation();
    sendAjaxRequest('delete', '/admin/comment/' + id, {}, deleteHandler);
}

function deletePost(event, id) {
    event.preventDefault();
    event.stopPropagation();
    sendAjaxRequest('delete', '/admin/post/' + id, {}, deleteHandler);
}

function deleteCommunity(event, id) {
    event.preventDefault();
    event.stopPropagation();
    sendAjaxRequest('delete', '/admin/community/' + id, {}, deleteHandler);
}

function deleteHandler() {
    console.log(this.responseText);
    let response = JSON.parse(this.responseText);
    let success = response['success'];
    console.log(success);
    if (success) {
        window.location = '/admin/';
    }
}

function deleteAdminEvent(item) {
    // console.log(item)
    let type = item.getAttribute('data-type');
    let id = item.getAttribute('data-object');
    let report_id = item.getAttribute('data-report');
    let report = [];

    if (type == "post") {
        report = document.querySelectorAll('.post-' + id);
        // console.log(report.length);
        sendAjaxRequest('delete', '/admin/post/' + id, {});
    } else if (type == "comment") {
        report = document.getElementsByClassName('comment-' + id);
        // console.log(report.length);
        sendAjaxRequest('delete', '/admin/comment/' + id, {});
    } else if (type == "community") {
        report = document.getElementsByClassName('community-' + id);
        // console.log(report.length);
        sendAjaxRequest('delete', '/admin/community/' + id, {});
    } else if (type == "user") {
        report = document.querySelectorAll('.user-' + id + ", div.author-comment-" + id + ", div.author-post-" + id);
        // console.log(report);
        // user = (document.querySelectorAll("a[data-type='user-comment'][data-object='" + id + "']"));
        // console.log(user);
        // report = report.concat(document.querySelectorAll("a[data-type='user-post'][data-object='" + id + "']"));
        // console.log(report);
        sendAjaxRequest('delete', '/admin/user/' + id, {});
    } else if (type == "user-comment") {
        let comment_id = item.getAttribute('data-target');
        report = document.querySelectorAll(".comment-" + comment_id + ", .user-" + id + ", div.author-post-" + id);
        // report = report.concat(document.querySelectorAll('.user-' + id));
        // report = report.concat(document.querySelectorAll("a[data-type='user-post'][data-object='" + id + "']"));
        // console.log(report);
        sendAjaxRequest('delete', '/admin/user/' + id, {});
    } else if (type == "user-post") {
        let post_id = item.getAttribute('data-target');
        report = document.querySelectorAll('.post-' + post_id + ", .user-" + id + ", div.author-comment-" + id);
        // console.log(report);
        // report = report.concat(document.querySelectorAll('.user-' + id));
        // report = report.concat(document.querySelectorAll("[data-type = user-comment][data-object='" + id + "']"));
        // console.log(report.length);
        sendAjaxRequest('delete', '/admin/user/' + id, {});
    }

    reportArray = [].slice.call(report);
    // console.log(reportArray.length);
    reportArray.forEach(report => {
        report.remove();
    });
}

addAdminEventListeners();
