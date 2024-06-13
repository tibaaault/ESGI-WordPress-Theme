// jQuery(document).ready(function($) {
//     $(".category-list").owlCarousel({
//         items: 4,
//         loop: true,
//         margin: 10,
//         nav: true,
//         autoplay: true,
//         autoplayTimeout: 3000,
//         autoplayHoverPause: true
//     });
// });

jQuery(document).ready(function($) {
    // Initialiser Owl Carousel
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });
});