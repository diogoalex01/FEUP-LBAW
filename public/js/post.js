let input = document.querySelector('input[name="community"]');
let search = document.querySelector('.search');
let container = document.querySelector('.dropdown-container');
let matches;
let privacy = document.querySelector('.privacy-toggle');
let allCommunities = [];
let addCommentForm = document.querySelector("#new-comment-form");
let addCommentInput = document.querySelector("#new-comment-input");

function addPostEventListeners() {
    let checkCommunity = document.querySelector('div.new-post input[name="community"]');
    if (checkCommunity != null)
        checkCommunity.addEventListener('input', sendCheckCommunityRequest);

    if (newPostPrivacyToggle != null) {
        newPostPrivacyToggle.addEventListener('change', () => {

            if (!newPostPrivacyToggle.hasAttribute("checked")) {
                newPostPrivacyToggleLabel.innerHTML = "Private Account";
                newPostPrivacyToggle.setAttribute("checked", "checked");
            } else {
                newPostPrivacyToggleLabel.innerHTML = "Public Account";
                newPostPrivacyToggle.removeAttribute("checked");
            }
        });
    }

    if (addCommentForm != null) {
        console.log("existe");
        addCommentForm.addEventListener('submit', sendNewComment);

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

function sendCheckCommunityRequest(event) {
    let checkCommunity = document.querySelector('div.new-post input[name="community"]');
    let name = checkCommunity.value;

    if (name != '')
        sendAjaxRequest('post', '/api/communities', { community_name: name }, communityCheckedHandler);

    event.preventDefault();
}


function communityCheckedHandler() {
    // if (this.status != 200) window.location = '/';
    allCommunities = JSON.parse(this.responseText);
}

function searching(word) {
    return allCommunities.filter(community => {
        let regex = new RegExp(word, 'gi');
        return community.name.match(regex);
    });
}

function sendNewComment(event) {
    event.preventDefault();
    event.stopPropagation();

    let user_id = document.querySelector('input[name=user_id]').value;
    let post_id = document.querySelector('input[name=post_id]').value;
    let comment_content = addCommentInput.value;

    sendAjaxRequest('put', '/comment', {
        user_id: user_id,
        post_id: post_id,
        content: comment_content
    }, newCommentHandler);
}

function newCommentHandler() {
    console.log(this.responseText);
    let comment = JSON.parse(this.responseText)['comment'];
    let commentSection = document.getElementById("post-comment-section");
    let newComment = document.createElement('div');

    let commentId = comment['id'];
    let commentContent = comment['content'];
    let commentUser = comment['id_author'];
    let commentPost = comment['id_post'];
    addCommentInput.value = "";
    addCommentInput.rows = 1;

    newComment.innerHTML = `<div id=${commentId} class="card mb-2 post-container post-comment">
        <div class="row pt-4">

            <div class="d-flex align-items-end justify-content-end">
                <div class="col">
                    <div class="row">
                        <div class="d-flex justify-content-between pr-1">
                            <a>
                                <i class="fas fa-chevron-up fa-lg pb-2"></i>
                            </a>
                        </div>
                        <div class="d-flex justify-content-center pb-2">
                            <a>
                                <p class="mb-0"> 0 </p>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex justify-content-between pr-1">
                            <a>
                                <i class="fas fa-chevron-down fa-lg pb-2"></i>
                            </a>
                        </div>
                        <div class="d-flex justify-content-center">
                            <a>
                                <p> 0 </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-10 mx-auto">
                <p class="card-text">
                    ${commentContent}
                </p>
            </div>
        </div>
        <div class="card-footer row text-muted p-3"
            style="border-top: 3px solid rgba(76, 25, 27, 0.444); background-color: white;">
            <div class="col-md-6 align-self-center">
                <div class="card-footer-buttons row align-content-center justify-content-start">
                    <a href="/post/${commentPost}#new-comment-input"><i class="fas fa-reply"></i>Reply</a>
                    <a data-toggle="modal" data-dismiss="modal" data-target="#modalCommentReport">
                        <div class="a-report"><i class="fas fa-flag"></i>Report</div>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row align-self-center justify-content-end">
                    <!--<span class="px-1 align-self-center">{{date('F d, Y', strtotime($comment->time_stamp))}}</span>-->
                    <a class="align-self-center">
                    </a>
                </div>
            </div>
        </div>
    </div>`

    console.log(commentSection);
    commentSection.insertBefore(newComment, commentSection.childNodes[0]);


    //todo add comment
}

function searchArray() {
    if (input.value.length < 2) {
        search.classList.remove('show');
        return;
    }

    sendAjaxRequest('post', '/api/communities', '', communityCheckedHandler);

    matches = searching(input.value);

    let html;

    if (matches.length == 0) {
        search.classList.remove('show');
        html = ``;
    }

    html = matches.map(match => {
        search.classList.add('show');
        return `
            <div class="notification-item search-result py-0">
                <div class="row">
                    <div class="col-4 text-center pr-0">
                        <a href="">
                            <img class="notification-pic" id="login" height="40" width="40"
                                src="${match.image}"
                                alt="Profile Image"></a>
                    </div>
                    <div class="col-6 p-0 my-auto">
                        <h6 class="item-info mb-0">${match.name}</h6>
                    </div>
                </div>
            </div>

            <hr class="my-0" style="width: 80%;">
            `;
    }).join('');

    search.innerHTML = html;
    clickSearchResult();
}

function clickSearchResult() {
    let search_results = document.querySelectorAll('.search-result');
    if (search_results != null) {
        search_results.forEach((search_result) => {
            if (search_result != null) {
                search_result.addEventListener('click', function () {
                    input.value = search_result.getElementsByClassName("item-info")[0].innerHTML;
                    search.classList.remove('show');
                    privacy.style.visibility = 'hidden';
                })
            }
        });
    }
}

if (input != null) {
    input.addEventListener('click', searchArray);

    input.addEventListener('keyup', function () {
        searchArray();
        privacy.style.visibility = 'visible';
    });
}

document.addEventListener("click", function () {
    if (search != null)
        search.classList.remove('show');
});

let newPostForm = document.querySelector('#new-post-form');
let newPostPrivacyToggle = document.querySelector('#new-post-form #communityPrivacyToggle');
let newPostPrivacyToggleLabel = document.querySelector('#new-post-form label[for="communityPrivacyToggle"]');

if (newPostForm != null) {
    newPostForm.reset()
    if (newPostPrivacyToggle.hasAttribute('checked')) {
        newPostPrivacyToggleLabel.innerHTML = "Private Account";
    } else {
        newPostPrivacyToggleLabel.innerHTML = "Public Account";
    }
}

addPostEventListeners();

(function () {
    'use strict';
    window.addEventListener('load', function () {
        let forms = document.getElementsByClassName('needs-validation');
        let validateGroup = document.getElementsByClassName('validate-me');

        let validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                for (let i = 0; i < validateGroup.length; i++) {
                    validateGroup[i].classList.add('was-validated');
                }
            }, false);
        });
    }, false);
})();

