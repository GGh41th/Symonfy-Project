const nav = document.querySelectorAll('.navigation-panel .nav-item');
const indicator = document.querySelector('.indicator');
const navState = [];
for (let i = 0; i < nav.length; i++) {
    navState.push({ item: nav[i], inTransition: false });
}
nav.forEach((item) => {
    if (item == nav[0]) {
        return;
    }
    item.addEventListener('click', () => setActive(item));
});
indicator.style.top = nav[0].offsetTop + 'px';
indicator.height = nav[0].offsetHeight;
for (let i = 0; i < nav.length; i++) {
    nav[i].addEventListener('click', () => {
        indicator.style.backgroundColor = "green";
        icon = nav[i].querySelector('ion-icon');
    });
    if (i == nav.length - 1) {
        nav[i].addEventListener('click', () => {
            indicator.style.backgroundColor = "red";
        });
    }
}

function setActive(item) {
    const container = document.querySelector('.content');
    const loadingDiv = document.querySelector('#loading');
    container.classList.remove("shown");
    loadingDiv.classList.add("shown");
    $(".content").html("");
    $.ajax({
        type: "POST",
        url: "/" + item.getAttribute("data-url"),
        success: function (response) {
            $(".content").html(response);
            container.classList.add("shown");
            loadingDiv.classList.remove("shown");
        },
        error: function (xhr, status, error) {
            document.querySelector(".content").innerHTML = "Error: " + error;
        }
    });
    nav.forEach((child) => {
        child.classList.remove('active');
    });
    item.classList.add('active');
    indicator.style.top = item.offsetTop + 'px';
    if (navState[getNavIndex(item)].inTransition) return; // to prevent multiple clicks
    navState[getNavIndex(item)].inTransition = true;
    const icon = item.querySelector('ion-icon');
    icon.style.transition = "0s";
    icon.style.transform = "rotate(0deg)";

    setTimeout(() => {
        icon.style.transition = "0.5s";
        setTimeout(() => {
            icon.style.transform = "rotate(400deg)";
        }, 4);
        timeout = setTimeout(() => {
            icon.style.transform = "rotate(360deg)";
            setTimeout(() => {
                navState[getNavIndex(item)].inTransition = false;
            }, 500);
        }, 500);
    }, 4);


}

function getNavIndex(item) {
    for (let i = 0; i < nav.length; i++) {
        if (nav[i] == item) return i;
    }
    return -1;
}

function navigateTo(page_name) {
    page_name = page_name.toLowerCase();
    for (let i = 0; i < nav.length; i++) {
        if (nav[i].querySelector("p").innerHTML.toLowerCase().includes(page_name)) {
            setActive(nav[i]);
            return;
        }
    }
}

setActive(nav[1]);