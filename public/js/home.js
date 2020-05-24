let posts = document.querySelectorAll('.post-box');
if (posts.length != 0) {
    let height;
    let readMore;
    posts.forEach((post) => {
        height = post.clientHeight;
        readMore = post.querySelector('.read-more');
        if (height < 260) {
            readMore.style.padding = 0;
        }
    });
}

let home_aside = document.querySelectorAll(".home-aside");
if (home_aside.length != 0) {
    let homes_menu = document.getElementById("home_menu");
    let populars_menu = document.getElementById("popular_menu");
    let recents_menu = document.getElementById("recent_menu");
    let home_content = document.getElementById("posts-column-home");

    function home_tabs() {
        tab_content("home");
        populars_menu.classList.remove("nav-border-active");
        populars_menu.classList.add("nav-border");
        populars_menu.addEventListener("click", popular_tabs);

        recents_menu.classList.remove("nav-border-active");
        recents_menu.classList.add("nav-border");
        recents_menu.addEventListener("click", recent_tabs);

        homes_menu.classList.remove("nav-border");
        homes_menu.classList.add("nav-border-active");
        homes_menu.removeEventListener("click", home_tabs);
    }

    function popular_tabs() {
        tab_content("popular");
        homes_menu.classList.remove("nav-border-active");
        homes_menu.classList.add("nav-border");
        homes_menu.addEventListener("click", home_tabs);

        recents_menu.classList.remove("nav-border-active");
        recents_menu.classList.add("nav-border");
        recents_menu.addEventListener("click", recent_tabs);

        populars_menu.classList.remove("nav-border");
        populars_menu.classList.add("nav-border-active");
        populars_menu.removeEventListener("click", popular_tabs);
    }

    function recent_tabs() {
        tab_content("recent");
        homes_menu.classList.remove("nav-border-active");
        homes_menu.classList.add("nav-border");
        homes_menu.addEventListener("click", home_tabs);

        populars_menu.classList.remove("nav-border-active");
        populars_menu.classList.add("nav-border");
        populars_menu.addEventListener("click", popular_tabs);

        recents_menu.classList.remove("nav-border");
        recents_menu.classList.add("nav-border-active");
        recents_menu.removeEventListener("click", recent_tabs);
    }

    homes_menu.addEventListener("click", home_tabs);
    populars_menu.addEventListener("click", popular_tabs);
    recents_menu.addEventListener("click", recent_tabs);
}

function tab_content(type) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/' + type + 'Tab',
        type: 'POST',
        dataType: 'json',
        // data: data_route,
        success: function (data) {
            if (data.html.length == 0)
                return;

            tabPostHandler(data);
        }
    });
}

function tabPostHandler(response) {
    if (response.success === true) {
        $('#posts-column-home').html(response.html).fadeIn("slow");
        addVotesEventListener();
    }
}