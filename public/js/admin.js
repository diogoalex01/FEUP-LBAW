let admin_aside = document.querySelectorAll(".admin-aside");
if (admin_aside.length != 0) {
    let admins_menu = document.getElementById("admin_menu");
    let users_menu = document.getElementById("users_menu");
    let comments_menu = document.getElementById("comments_menu");
    let posts_menu = document.getElementById("posts_menu");
    let communities_menu = document.getElementById("communities_menu");

    let current_title = document.getElementById("current-title");
    let admin_content = document.querySelectorAll(".admin-content");

    let user_tab_admin = document.getElementById("user-tab-admin");
    let comment_tab_admin = document.getElementById("comment-tab-admin");
    let post_tab_admin = document.getElementById("post-tab-admin");
    let community_tab_admin = document.getElementById("community-tab-admin");

    if (admin_content.length != 0) {

        function admin_tabs() {
            current_title.innerHTML = 'All Reports';

            admin_content.forEach(tab => {
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

            admins_menu.classList.remove("nav-border");
            admins_menu.classList.add("nav-border-active");
            admins_menu.removeEventListener("click", admin_tabs);
        }

        function user_tabs() {
            current_title.innerHTML = 'User Reports';

            admin_content.forEach(tab => {
                tab.classList.remove("active-tab");
                tab.classList.add("hidden-tab");
                tab.style.display = "none";
            });

            user_tab_admin.classList.remove("hidden-tab");
            user_tab_admin.classList.add("active-tab");
            user_tab_admin.style.display = "block";

            admins_menu.classList.remove("nav-border-active");
            admins_menu.classList.add("nav-border");
            admins_menu.addEventListener("click", admin_tabs);

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
        }

        function comment_tabs() {
            current_title.innerHTML = 'Comment Reports';

            admin_content.forEach(tab => {
                tab.classList.remove("active-tab");
                tab.classList.add("hidden-tab");
                tab.style.display = "none";
            });

            comment_tab_admin.classList.remove("hidden-tab");
            comment_tab_admin.classList.add("active-tab");
            comment_tab_admin.style.display = "block";

            admins_menu.classList.remove("nav-border-active");
            admins_menu.classList.add("nav-border");
            admins_menu.addEventListener("click", admin_tabs);

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
        }

        function post_tabs() {
            current_title.innerHTML = 'Post Reports';

            admin_content.forEach(tab => {
                tab.classList.remove("active-tab");
                tab.classList.add("hidden-tab");
                tab.style.display = "none";
            });

            post_tab_admin.classList.remove("hidden-tab");
            post_tab_admin.classList.add("active-tab");
            post_tab_admin.style.display = "block";

            admins_menu.classList.remove("nav-border-active");
            admins_menu.classList.add("nav-border");
            admins_menu.addEventListener("click", admin_tabs);

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
        }

        function community_tabs() {
            current_title.innerHTML = 'Community Reports';

            admin_content.forEach(tab => {
                tab.classList.remove("active-tab");
                tab.classList.add("hidden-tab");
                tab.style.display = "none";
            });

            community_tab_admin.classList.remove("hidden-tab");
            community_tab_admin.classList.add("active-tab");
            community_tab_admin.style.display = "block";

            admins_menu.classList.remove("nav-border-active");
            admins_menu.classList.add("nav-border");
            admins_menu.addEventListener("click", admin_tabs);

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
        }

        admins_menu.addEventListener("click", admin_tabs);
        users_menu.addEventListener("click", user_tabs);
        comments_menu.addEventListener("click", comment_tabs);
        posts_menu.addEventListener("click", post_tabs);
        communities_menu.addEventListener("click", community_tabs);
    }
}