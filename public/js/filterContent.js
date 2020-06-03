let menu_aside = document.querySelectorAll(".menu-aside");
if (menu_aside.length != 0) {
    let menus_menu = document.getElementById("menu_menu");
    let users_menu = document.getElementById("users_menu");
    let comments_menu = document.getElementById("comments_menu");
    let posts_menu = document.getElementById("posts_menu");
    let communities_menu = document.getElementById("communities_menu");

    let current_title = document.getElementById("current-title");
    let menu_content = document.querySelectorAll(".menu-content");
    let no_content = document.getElementById("no-content");

    let user_tab_menu = document.querySelectorAll(".user-tab-menu");
    let comment_tab_menu = document.querySelectorAll(".comment-tab-menu");
    let post_tab_menu = document.querySelectorAll(".post-tab-menu");
    let community_tab_menu = document.querySelectorAll(".community-tab-menu");

    function checkEmptyContent() {
        let current_content = document.querySelectorAll(".active-tab");

        if (current_content.length == 0)
            no_content.style.display = "block";
        else
            no_content.style.display = "none";
    }

    function menu_tabs() {
        if (current_title != null)
            current_title.innerHTML = 'All Reports';

        menu_content.forEach(tab => {
            tab.classList.remove("hidden-tab");
            tab.classList.add("active-tab");
            tab.style.display = "block";
        });

        users_menu.classList.remove("nav-border-active");
        users_menu.classList.add("nav-border");
        users_menu.addEventListener("click", user_tabs);

        comments_menu.classList.remove("nav-border-active");
        comments_menu.classList.add("nav-border");
        comments_menu.addEventListener("click", comment_tabs);

        posts_menu.classList.remove("nav-border-active");
        posts_menu.classList.add("nav-border");
        posts_menu.addEventListener("click", post_tabs);

        communities_menu.classList.remove("nav-border-active");
        communities_menu.classList.add("nav-border");
        communities_menu.addEventListener("click", community_tabs);

        menus_menu.classList.remove("nav-border");
        menus_menu.classList.add("nav-border-active");
        menus_menu.removeEventListener("click", menu_tabs);

        checkEmptyContent();
    }

    function user_tabs() {
        if (current_title != null)
            current_title.innerHTML = 'User Reports';

        menu_content.forEach(tab => {
            tab.classList.remove("active-tab");
            tab.classList.add("hidden-tab");
            tab.style.display = "none";
        });

        user_tab_menu.forEach(item => {
            item.classList.remove("hidden-tab");
            item.classList.add("active-tab");
            item.style.display = "block";
        });

        menus_menu.classList.remove("nav-border-active");
        menus_menu.classList.add("nav-border");
        menus_menu.addEventListener("click", menu_tabs);

        users_menu.classList.remove("nav-border");
        users_menu.classList.add("nav-border-active");
        users_menu.removeEventListener("click", user_tabs);

        comments_menu.classList.remove("nav-border-active");
        comments_menu.classList.add("nav-border");
        comments_menu.addEventListener("click", comment_tabs);

        posts_menu.classList.remove("nav-border-active");
        posts_menu.classList.add("nav-border");
        posts_menu.addEventListener("click", post_tabs);

        communities_menu.classList.remove("nav-border-active");
        communities_menu.classList.add("nav-border");
        communities_menu.addEventListener("click", community_tabs);

        checkEmptyContent();
    }

    function comment_tabs() {
        if (current_title != null)
            current_title.innerHTML = 'Comment Reports';

        menu_content.forEach(tab => {
            tab.classList.remove("active-tab");
            tab.classList.add("hidden-tab");
            tab.style.display = "none";
        });

        comment_tab_menu.forEach(item => {
            item.classList.remove("hidden-tab");
            item.classList.add("active-tab");
            item.style.display = "block";
        });

        menus_menu.classList.remove("nav-border-active");
        menus_menu.classList.add("nav-border");
        menus_menu.addEventListener("click", menu_tabs);

        users_menu.classList.remove("nav-border-active");
        users_menu.classList.add("nav-border");
        users_menu.addEventListener("click", user_tabs);

        comments_menu.classList.remove("nav-border");
        comments_menu.classList.add("nav-border-active");
        comments_menu.removeEventListener("click", comment_tabs);

        posts_menu.classList.remove("nav-border-active");
        posts_menu.classList.add("nav-border");
        posts_menu.addEventListener("click", post_tabs);

        communities_menu.classList.remove("nav-border-active");
        communities_menu.classList.add("nav-border");
        communities_menu.addEventListener("click", community_tabs);

        checkEmptyContent();
    }

    function post_tabs() {
        if (current_title != null)
            current_title.innerHTML = 'Post Reports';

        menu_content.forEach(tab => {
            tab.classList.remove("active-tab");
            tab.classList.add("hidden-tab");
            tab.style.display = "none";
        });

        post_tab_menu.forEach(item => {
            item.classList.remove("hidden-tab");
            item.classList.add("active-tab");
            item.style.display = "block";
        });

        menus_menu.classList.remove("nav-border-active");
        menus_menu.classList.add("nav-border");
        menus_menu.addEventListener("click", menu_tabs);

        users_menu.classList.remove("nav-border-active");
        users_menu.classList.add("nav-border");
        users_menu.addEventListener("click", user_tabs);

        comments_menu.classList.remove("nav-border-active");
        comments_menu.classList.add("nav-border");
        comments_menu.addEventListener("click", comment_tabs);

        posts_menu.classList.remove("nav-border");
        posts_menu.classList.add("nav-border-active");
        posts_menu.removeEventListener("click", post_tabs);

        communities_menu.classList.remove("nav-border-active");
        communities_menu.classList.add("nav-border");
        communities_menu.addEventListener("click", community_tabs);

        checkEmptyContent();
    }

    function community_tabs() {
        if (current_title != null)
            current_title.innerHTML = 'Community Reports';

        menu_content.forEach(tab => {
            tab.classList.remove("active-tab");
            tab.classList.add("hidden-tab");
            tab.style.display = "none";
        });

        community_tab_menu.forEach(item => {
            item.classList.remove("hidden-tab");
            item.classList.add("active-tab");
            item.style.display = "block";
        });

        menus_menu.classList.remove("nav-border-active");
        menus_menu.classList.add("nav-border");
        menus_menu.addEventListener("click", menu_tabs);

        users_menu.classList.remove("nav-border-active");
        users_menu.classList.add("nav-border");
        users_menu.addEventListener("click", user_tabs);

        comments_menu.classList.remove("nav-border-active");
        comments_menu.classList.add("nav-border");
        comments_menu.addEventListener("click", comment_tabs);

        posts_menu.classList.remove("nav-border-active");
        posts_menu.classList.add("nav-border");
        posts_menu.addEventListener("click", post_tabs);

        communities_menu.classList.remove("nav-border");
        communities_menu.classList.add("nav-border-active");
        communities_menu.removeEventListener("click", community_tabs);

        checkEmptyContent();
    }

    menus_menu.addEventListener("click", menu_tabs);
    users_menu.addEventListener("click", user_tabs);
    comments_menu.addEventListener("click", comment_tabs);
    posts_menu.addEventListener("click", post_tabs);
    communities_menu.addEventListener("click", community_tabs);
    checkEmptyContent();
}