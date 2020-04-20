let admin_aside = document.querySelectorAll(".admin-aside")
let all_rep = document.querySelectorAll(".report")
let admin_title = document.getElementById("current_title")

let all_menu = document.getElementById("all_menu")
all_menu.addEventListener("click", function () {
    admin_title.innerHTML = "All Reports"
    for (let i = 0; i < admin_aside.length; i++) {
        admin_aside[i].setAttribute("class", "nav-border")
    }
    all_menu.setAttribute("class", "nav-border-active")

    for (let i = 0; i < all_rep.length; i++) {
        if (all_rep[i].style.display === "none") {
            all_rep[i].style.display = "block"
        }
    }
})

function report_tabs(type) {
    admin_title.innerHTML = type.charAt(0).toUpperCase() + type.substring(1) + " Reports"
    for (let i = 0; i < admin_aside.length; i++) {
        if (admin_aside[i].hasAttribute("nav-border-active"))
            admin_aside[i].removeAttribute("class", "nav-border-active")
        admin_aside[i].setAttribute("class", "nav-border")
    }
    users_menu.setAttribute("class", "nav-border-active")

    for (let i = 0; i < all_rep.length; i++) {
        if (all_rep[i].classList.contains(type + "-report")) {
            all_rep[i].style.display = "block"
        } else {
            all_rep[i].style.display = "none"
        }
    }
}

let users_menu = document.getElementById("users_menu")
users_menu.addEventListener("click", report_tabs.bind(this,"user"))

let comments_menu = document.getElementById("comments_menu")
comments_menu.addEventListener("click", report_tabs.bind(this,"comment"))

let posts_menu = document.getElementById("posts_menu")
posts_menu.addEventListener("click", report_tabs.bind(this,"post"))

let communities_menu = document.getElementById("communities_menu")
communities_menu.addEventListener("click", report_tabs.bind(this,"community"))