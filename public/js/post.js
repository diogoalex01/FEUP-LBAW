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

let input = document.querySelector('input[name="community"]');
let search = document.querySelector('.search');
let container = document.querySelector('.dropdown-container');
let matches;
let privacy = document.querySelector('.privacy-toggle');
let allCommunities = [];

function communityCheckedHandler() {
    // if (this.status != 200) window.location = '/';
    allCommunities = JSON.parse(this.responseText);
    // console.log(allCommunities);
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

    addPostEventListeners();
}

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