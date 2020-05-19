let input = document.querySelector('input[name="community"]');
let search = document.querySelector('.search');
let container = document.querySelector('.dropdown-container');
let matches;
let privacy = document.querySelector('.privacy-toggle');
let allCommunities = [];
let addCommentForm = document.querySelector("#new-comment-form");
let addCommentInput = document.querySelector("#new-comment-input");
let replyButtons = document.querySelectorAll(".reply-btn");
let sendReplyButton = document.querySelector("#send-reply-btn");

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

    if (addCommentForm != null)
        addCommentForm.addEventListener('submit', sendNewComment);

    addReplyButtonsListener();
    addVotesEventListener();
}

function addReplyButtonsListener() {
    replyButtons = document.querySelectorAll(".reply-btn");
    if (replyButtons != null) {
        replyButtons.forEach(function (item, idx) {
            if (item.getAttribute('data-target').match(/comment[0-9]+/))
                item.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    let id = item.getAttribute('data-target');
                    addReplyForm(id);
                });
        });
    }
}

function addVotesEventListener() {
    let voteButtons = document.querySelectorAll(".vote-button");

    if (voteButtons != null) {
        voteButtons.forEach(function (item, idx) {
            //console.log("here");
            if (!item.classList.contains('disabled-voting')) {
                changeVoteColor(item);
                item.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    voteButtonClicked(item);
                });
            }
            else {
                item.classList.remove('vote-button');
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

function changeVoteColor(item) {
    if (item.getAttribute('data-vote-type') == "up" && item.children[0].hasAttribute('data-checked')) {
        item.children[0].style.color = "green";
    } else if (item.getAttribute('data-vote-type') == "down" && item.children[0].hasAttribute('data-checked')) {
        item.children[0].style.color = "red";
    }
}

function voteButtonClicked(item) {
    // method = "put", route, targetId, voteType
    let route = item.getAttribute('data-route');
    let voteType = item.getAttribute('data-vote-type');
    let targetId = item.getAttribute('data-target-id');
    let argumentsObject;
    let pattern = /\/post\/[0-9]+\/vote/i;

    let upvoteLableId = "upvote-label-" + targetId;
    let upvoteLable = document.getElementById(upvoteLableId);
    let downvoteLableId = "downvote-label-" + targetId;
    let downvoteLable = document.getElementById(downvoteLableId)

    if (item.children[0].hasAttribute('data-checked')) {
        item.children[0].style.color = "black";
        item.children[0].removeAttribute('data-checked');
        if (voteType == "up") {
            upvoteLable.innerHTML = parseInt(upvoteLable.innerHTML) - 1;
        } else {
            downvoteLable.innerHTML = parseInt(downvoteLable.innerHTML) - 1;
        }
        method = "delete";
    } else if (voteType == "up") {
        item.children[0].style.color = "green";
        item.children[0].setAttribute("data-checked", "data-checked");
        upvoteLable.innerHTML = parseInt(upvoteLable.innerHTML) + 1;
        let otherButton = document.getElementById("downvote-button-" + targetId);
        if (otherButton.hasAttribute('data-checked')) {
            // Frontend changes
            downvoteLable.innerHTML = parseInt(downvoteLable.innerHTML) - 1;
            otherButton.style.color = "black";
            otherButton.removeAttribute('data-checked');
            //Backend Changes
            //Edit Vote
            method = "put";
        } else {
            method = "post";
        }
    } else {
        item.children[0].style.color = "red";
        item.children[0].setAttribute("data-checked", "data-checked");
        downvoteLable.innerHTML = parseInt(downvoteLable.innerHTML) + 1;
        let otherButton = document.getElementById("upvote-button-" + targetId);
        if (otherButton.hasAttribute('data-checked')) {
            // Frontend changes
            upvoteLable.innerHTML = parseInt(upvoteLable.innerHTML) - 1;
            otherButton.style.color = "black";
            otherButton.removeAttribute('data-checked');
            //Backend Changes
            //Edit Vote
            method = "put";
        } else {
            method = "post"
        }
    }

    // console.log("route ->" + route);
    if (route.match(pattern)) {
        argumentsObject = { post_id: targetId, vote_type: voteType }
    } else {
        argumentsObject = { comment_id: targetId, vote_type: voteType };
    }
    sendAjaxRequest(method, route, argumentsObject, displayResponse);
}

function displayResponse() {
    let response = JSON.parse(this.responseText);
    // console.log(response)
}

function sendCheckCommunityRequest(event) {
    let checkCommunity = document.querySelector('div.new-post input[name="community"]');
    let name = checkCommunity.value;

    if (name != '')
        sendAjaxRequest('post', '/api/communities', { community_name: name }, communityCheckedHandler);

    event.preventDefault();
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
    // console.log(this.responseText);
    let response = JSON.parse(this.responseText);

    let comment = response['comment'];
    let commentSection = document.getElementById("post-comment-section");
    let newComment = document.createElement('div');

    let commentId = comment['id'];
    // console.log("O novo comentario tem id " + commentId);
    let commentContent = comment['content'];
    let commentUser = comment['id_author'];
    let commentPost = comment['id_post'];
    addCommentInput.value = "";
    addCommentInput.rows = 1;
    let authorUsername = response['extras']['author_username'];
    let authorImage = response['extras']['author_photo'];
    // console.log(authorImage);

    newComment.innerHTML = `<div id="comment${commentId}" class="card mb-2 post-container post-comment">
        <div class="row pt-4">

            <div class="d-flex align-items-end justify-content-end">
                <div class="col">
                    <div class="row">
                        <div class="d-flex justify-content-between pr-1">
                            <a>
                                <i class="fas fa-chevron-up fa-lg pb-2 disabled-voting"></i>
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
                                <i class="fas fa-chevron-down fa-lg pb-2 disabled-voting"></i>
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
                <a href="" data-target="comment${commentId}" class ="reply-btn"><i class="fas fa-reply"></i>Reply</a>
                    <a data-toggle="modal" data-dismiss="modal" data-target="#modalCommentReport">
                        <div class="a-report"><i class="fas fa-flag"></i>Report</div>
                    </a>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="row align-self-center justify-content-end">
                <a href="/user/${commentUser}">
                    <img class="profile-pic-small" height="35" width="35" src="${authorImage}" alt="">
                </a>
                <span class="px-1 align-self-center">Just now by </span>
                <a href="/user/${commentUser}" class="my-auto">
                <span>@</span>${authorUsername}</a>
                </div>
            </div>
        </div>
    </div>
    <div id="replies${commentId}"></div>`

    // console.log(commentSection);
    commentSection.insertBefore(newComment, commentSection.childNodes[0]);
    addReplyButtonsListener();
}

function addReplyForm(id) {
    let replyFormContainer = document.getElementById("reply-container");
    if (replyFormContainer == null) {
        let comment_id = id.substring(7, id.length);
        let targetComment = document.getElementById(id);
        targetComment.parentElement;
        replyFormContainer = document.createElement('div');
        replyFormContainer.innerHTML = `
        <div class="card post-container reply-container mb-2 " id="reply-container">
            <div class="card-body">
                <form id="reply-form">
                    <input hidden name="comment_id" value="${comment_id}">
                    <div class="row" style="font-size: 0.45rem;">
                        <div class="col-md-10 pr-md-0">
                            <textarea id="reply-input" rows="1" onclick="this.rows = '8';" type="text" class="form-control mr-0"
                                placeholder="New Comment"></textarea>
                        </div>
                        <!--<div class="col-md-1 my-auto mx-auto text-right">-->
                        <div class="col-md-1 my-auto mx-auto text-right px-0 text-center comment-button">
                            <button type="submit" class="btn btn-md btn-dark" id ="send-reply-btn"> Reply </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>`;

        targetComment.insertAdjacentElement('afterend', replyFormContainer);
        let replyForm = document.querySelector("#reply-form");
        if (replyForm != null) {
            replyForm.addEventListener('submit', function (event) {
                event.preventDefault();
                event.stopPropagation();
                sendCommentReply();
            });
        }
        // let replyInput = document.getElementById("reply-input-" + id);
        // replyInput.addEventListener('blur', () => { replyFormContainer.remove(); });
    }
}

function sendCommentReply() {
    let user_id = document.querySelector('input[name=user_id]').value;
    let post_id = document.querySelector('input[name=post_id]').value;
    let comment_id = document.querySelector('input[name=comment_id]').value;
    let replyBody = document.getElementById("reply-input").value;
    let targetComment = document.getElementById(comment_id);

    // console.log(user_id);
    // console.log(post_id);
    // console.log(comment_id);
    // console.log(replyBody);

    sendAjaxRequest('put', '/reply', {
        user_id: user_id,
        post_id: post_id,
        comment_id: comment_id,
        reply: replyBody,
    }, newReplyHandler);
    let replyFormContainer = document.getElementById("reply-container");
    replyFormContainer.remove();
}

function newReplyHandler() {
    // console.log(this.responseText);
    let response = JSON.parse(this.responseText);
    console.log(response);
    let reply = response['comment'];
    let newComment = document.createElement('div');

    let commentId = reply['id'];
    let commentContent = reply['content'];
    let commentUser = reply['id_author'];
    let commentPost = reply['id_post'];
    let commentParent = reply['id_parent'];
    let commentSection = document.getElementById("replies" + commentParent);
    let authorUsername = response['extras']['author_username'];
    let authorImage = response['extras']['author_photo'];
    // console.log(authorImage);

    newComment.innerHTML = `
    <div id="comment${commentId}" class="card mb-2 post-container post-reply">
        <div class="row pt-4">

            <div class="d-flex align-items-end justify-content-end">
                <div class="col">
                    <div class="row">
                        <div class="d-flex justify-content-between pr-1">
                            <a>
                                <i class="fas fa-chevron-up fa-lg pb-2 disabled-voting"></i>
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
                                <i class="fas fa-chevron-down fa-lg pb-2 disabled-voting"></i>
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
                <a href="" data-target="comment${commentId}" class ="reply-btn"><i class="fas fa-reply"></i>Reply</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row align-self-center justify-content-end">
                <a href="/user/${commentUser}">
                    <img class="profile-pic-small" height="35" width="35" src="${authorImage}" alt="">
                </a>
                <span class="px-1 align-self-center">Just now by</span>
                <a href="/user/${commentUser}" class="my-auto">
                <span>@</span>${authorUsername}</a>
            </div>
        </div>
    </div>
    </div>
    <div id="replies${commentId}"></div>`

    // console.log(commentSection);
    commentSection.insertBefore(newComment, commentSection.childNodes[0]);
    addReplyButtonsListener();
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

function searchArray() {
    if (input.value.length < 2) {
        search.classList.remove('show');
        return;
    }

    sendAjaxRequest('post', '/api/communities', '', communityCheckedHandler);

    matches = searching(input.value);
    // console.log(matches);

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

$(document).ready(function () {
    let posts_column_home = document.getElementById("posts-column-home");
    let posts_column_community = document.getElementById("posts-column-community");
    let loader = document.getElementById("loader");
    let num_posts = 20;
    let api_route;
    let page;
    let data_route;

    if (posts_column_home != null || posts_column_community != null) {
        let lock = false;
        loader.style.display = 'none';

        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
                if (posts_column_home != null) {
                    api_route = '/api/home';
                    page = "#posts-column-home";
                    data_route = { num_posts: num_posts };
                }
                else if (posts_column_community != null) {
                    api_route = '/api/community';
                    page = "#posts-column-community";
                    data_route = { num_posts: num_posts, community_id: document.querySelector('.community-page-container').getAttribute('data-object-id') };
                }

                if (lock == true) {
                    return;
                }

                loader.style.display = 'block';
                lock = true;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: api_route,
                    type: 'POST',
                    dataType: 'json',
                    data: data_route,
                    success: function (data) {
                        if (data.html.length == 0) {
                            lock = true;
                            loader.style.display = 'none';
                            return;
                        }

                        refreshPostHandler(data, page);
                        lock = false;
                        num_posts += 5;
                        loader.style.display = 'none';
                    }
                });
            }
        });
    }
});

function refreshPostHandler(response, page) {
    if (response.success === true) {
        $(page).append(response.html).fadeIn("slow");
        addVotesEventListener();
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

$(document).mouseup(function (e) {
    if ($(e.target).closest("#reply-container").length
        == 0) {
        $("#reply-container").remove();
    }
});