/* eslint-disable */

(function ($) {
    // Preloader
    window.addEventListener('load', function () {
        document.querySelector('body').classList.add("loaded")
    });


    /* Replace all SVG images with inline SVG */
    $('img.svg').each((i, e) => {

        const $img = $(e);

        const imgID = $img.attr('id');

        const imgClass = $img.attr('class');

        const imgURL = $img.attr('src');

        $.get(imgURL, (data) => {
            // Get the SVG tag, ignore the rest
            let $svg = $(data).find('svg');

            // Add replaced image's ID to the new SVG
            if (typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', `${imgClass} replaced-svg`);
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
            if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                $svg.attr(`viewBox 0 0  ${$svg.attr('height')} ${$svg.attr('width')}`);
            }

            // Replace image with new SVG
            $img.replaceWith($svg);
        }, 'xml');
    });


    /* Header mobile view */
    $(window)
        .bind("resize", function () {
            var screenSize = window.innerWidth;
            if ($(this).width() <= 767.98) {
                $(".navbar-right__menu").appendTo(".mobile-author-actions");
                $(".contents").addClass("expanded");
                $(".sidebar ").addClass("collapsed");
            } else {
                $(".navbar-right__menu").appendTo(".navbar-right");
            }

        })
        .trigger("resize");
    $(window)
        .bind("resize", function () {
            var screenSize = window.innerWidth;
            if ($(this).width() > 767.98) {
                $(".dm-mail-sidebar").addClass("show");
            }
        })
        .trigger("resize");
    $(window)
        .bind("resize", function () {
            var screenSize = window.innerWidth;
            if ($(this).width() <= 991) {
                $(".sidebar").addClass("collapsed");
                $(".sidebar-toggle").on("click", function () {
                    $(".overlay-dark-sidebar").toggleClass("show");
                });
                $(".overlay-dark-sidebar").on("click", function () {
                    $(this).removeClass("show");
                    $(".sidebar").addClass("collapsed");
                });
            }
        })
        .trigger("resize");

    /* Mobile Menu */
    $(window)
        .bind("resize", function () {
            var screenSize = window.innerWidth;
            if ($(this).width() <= 991.98) {
                $(".menu-horizontal").appendTo(".mobile-nav-wrapper");
            }
        })
        .trigger("resize");

    $(".btn-search").on("click", function () {
        $(this).toggleClass("search-active");
        $(".mobile-search").toggleClass("show");
        $(".mobile-author-actions").removeClass("show");
    });

    $(".kanban-items li").hover(function () {
        $(this).toggleClass("active");
    });

    $(".btn-author-action").on("click", function () {
        $(".mobile-author-actions").toggleClass("show");
        $(".mobile-search").removeClass("show");
        $(".btn-search").removeClass("search-active");
    });

    $(".menu-mob-trigger").on("click", function (e) {
        e.preventDefault();
        $(".mobile-nav-wrapper").toggleClass("show");
    });

    $(".nav-close").on("click", function (e) {
        e.preventDefault();
        $(".mobile-nav-wrapper").removeClass("show");
    });

    $('.list-thumb-gallery li a').click(function (e) {

        $('.list-thumb-gallery li a').removeClass('active');

        var $this = $(this);
        if (!$this.hasClass('active')) {
            $this.addClass('active');
        }
    });

    /* Dropdown Event */
    $(".dropdown-clickEvent a").on("click", function (e) {
        e.preventDefault();
        const text = $(this).text();
        const notice = `
            <div class="dm-notice">
                <span>${text} Clicked</span>
            </div>
        `;
        $(".dm-message").prepend(notice);
        $(".dm-message").toggleClass("show");

        setTimeout(function () {
            $(".dm-message").empty();
            $(".dm-message").removeClass("show");
        }, 3000);
    });

    /* Collapsable Menu */
    function mobileMenu(dropDownTrigger, dropDown) {
        $(".menu-wrapper .menu-collapsable " + dropDown).slideUp();

        $(".menu-wrapper " + dropDownTrigger).on("click", function (e) {
            if ($(this).parent().hasClass("has-submenu")) {
                e.preventDefault();
            }
            $(this)
                .toggleClass("open")
                .siblings(dropDown)
                .slideToggle()
                .parent()
                .siblings(".sub-menu")
                .children(dropDown)
                .slideUp()
                .siblings(dropDownTrigger)
                .removeClass("open");
        });
    }
    mobileMenu(".menu-collapsable .dm-menu__link", ".dm-submenu");


    /* Sidebar Change */
    const layoutChangeBtns = document.querySelectorAll("[data-layout]");

    function changeLayout(e) {
        e.preventDefault();
        if (this.dataset.layout === "light") {
            $('ul.l_sidebar li a,.l_sidebar a').removeClass('active');
            $(this).addClass("active");
            $("body").removeClass("layout-dark");
            $("body").addClass("layout-light");
        } else if (this.dataset.layout === "dark") {
            $('ul.l_sidebar li a,.l_sidebar a').removeClass('active');
            $(this).addClass("active");
            $("body").removeClass("layout-light");
            $("body").addClass("layout-dark");
        } else if (this.dataset.layout === "side") {
            $('ul.l_navbar li a,.l_navbar a').removeClass('active');
            $(this).addClass("active");
            $("body").removeClass("top-menu");
            $("body").addClass("side-menu");
        } else if (this.dataset.layout === "top") {
            $('ul.l_navbar li a,.l_navbar a').removeClass('active');
            $(this).addClass("active");
            $("body").removeClass("side-menu");
            $("body").addClass("top-menu");
        }
    }
    $('.enable-dark-mode').click(function () {
        $("body").toggleClass('layout-dark');
        $('.enable-dark-mode a').toggleClass('active');
    });

    $('.enable-dark-mode').on('click', function () {
        fetch('/theme/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.theme === 'dark') {
                    $('body').addClass('layout-dark');
                } else {
                    $('body').removeClass('layout-dark');
                }
            });
    });

    if (layoutChangeBtns) {
        layoutChangeBtns.forEach((layoutChangeBtn) =>
            layoutChangeBtn.addEventListener("click", changeLayout)
        );
        layoutChangeBtns.forEach((layoutChangeBtn) =>
            layoutChangeBtn.addEventListener("click", closeCustomizer)
        );
    }


})(jQuery);
