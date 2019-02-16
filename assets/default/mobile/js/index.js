$(function () {

    var main_slider = $("#mobile-headline-slider");
    main_slider.owlCarousel({
        items : 1,
        loop:true,
        autoplay:true,
        autoplayTimeout:3000,
        autoplayHoverPause:true,
        dotsContainer: '#mobile-headline-pager'
    });

    var category_slider = $("#mobile-slider-list");
    category_slider.owlCarousel({
        items : 1,
        loop:true,
        autoplay:true,
        autoplayTimeout:3000,
        autoplayHoverPause:true,
        dotsContainer: '#mobile-slider-list-pagination'
    });

    var surprise_slider = $("#mobile-surprise-slider");
    surprise_slider.owlCarousel({
        items : 2,
        loop:true,
        autoplay:true,
        autoplayTimeout:3000,
        autoplayHoverPause:true,
    });
});

