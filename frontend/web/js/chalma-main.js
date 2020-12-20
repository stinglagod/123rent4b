(function ($) {
	"use strict";

    jQuery(document).ready(function($){
        
        $(function() {
        var owl = $('.filter-item').owlCarousel({
            loop    :true,
            margin  :10,
            nav     :true,
            navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:3
                },
                1000:{
                    items:5
                }
            }
        }); 
        
        /* animate filter */
        var owlAnimateFilter = function(even) {
            $(this)
            .addClass('__loading')
            .delay(70 * $(this).parent().index())
            .queue(function() {
                $(this).dequeue().removeClass('__loading')
            })
        };

        $('.filter-menu').on('click', '.filter-btn', function(e) {
            var filter_data = $(this).data('filter');

            /* return if current */
            if($(this).hasClass('btn-active')) return;

            /* active current */
            $(this).addClass('btn-active').siblings().removeClass('btn-active');

            /* Filter */
            owl.owlFilter(filter_data, function(_owl) { 
                $(_owl).find('.item').each(owlAnimateFilter); 
            });
        });
    })

    });


    jQuery(window).load(function(){
    });


}(jQuery));
(function ($) {
    "use strict";

    jQuery(document).ready(function($){

        $(function() {
            var owl = $('.filter-item2').owlCarousel({
                loop    :true,
                margin  :10,
                nav     :true,
                navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:3
                    },
                    1000:{
                        items:5
                    }
                }
            });

            /* animate filter */
            var owlAnimateFilter = function(even) {
                $(this)
                    .addClass('__loading')
                    .delay(70 * $(this).parent().index())
                    .queue(function() {
                        $(this).dequeue().removeClass('__loading')
                    })
            };

            $('.filter-menu2').on('click', '.filter-btn2', function(e) {
                var filter_data = $(this).data('filter');

                /* return if current */
                if($(this).hasClass('btn-active')) return;

                /* active current */
                $(this).addClass('btn-active').siblings().removeClass('btn-active');

                /* Filter */
                owl.owlFilter(filter_data, function(_owl) {
                    $(_owl).find('.item').each(owlAnimateFilter);
                });
            });
        })

    });


    jQuery(window).load(function(){
    });


}(jQuery));
(function ($) {
    "use strict";

    jQuery(document).ready(function($){

        $(function() {
            var owl = $('.filter-item3').owlCarousel({
                loop    :true,
                margin  :10,
                nav     :true,
                navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:3
                    },
                    1000:{
                        items:5
                    }
                }
            });

            /* animate filter */
            var owlAnimateFilter = function(even) {
                $(this)
                    .addClass('__loading')
                    .delay(70 * $(this).parent().index())
                    .queue(function() {
                        $(this).dequeue().removeClass('__loading')
                    })
            };

            $('.filter-menu3').on('click', '.filter-btn3', function(e) {
                var filter_data = $(this).data('filter');

                /* return if current */
                if($(this).hasClass('btn-active')) return;

                /* active current */
                $(this).addClass('btn-active').siblings().removeClass('btn-active');

                /* Filter */
                owl.owlFilter(filter_data, function(_owl) {
                    $(_owl).find('.item').each(owlAnimateFilter);
                });
            });
        })

    });


    jQuery(window).load(function(){
    });


}(jQuery));
(function ($) {
    "use strict";

    jQuery(document).ready(function($){

        $(function() {
            var owl = $('.filter-item4').owlCarousel({
                loop    :true,
                margin  :10,
                nav     :true,
                navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:3
                    },
                    1000:{
                        items:5
                    }
                }
            });

            /* animate filter */
            var owlAnimateFilter = function(even) {
                $(this)
                    .addClass('__loading')
                    .delay(70 * $(this).parent().index())
                    .queue(function() {
                        $(this).dequeue().removeClass('__loading')
                    })
            };

            $('.filter-menu4').on('click', '.filter-btn4', function(e) {
                var filter_data = $(this).data('filter');

                /* return if current */
                if($(this).hasClass('btn-active')) return;

                /* active current */
                $(this).addClass('btn-active').siblings().removeClass('btn-active');

                /* Filter */
                owl.owlFilter(filter_data, function(_owl) {
                    $(_owl).find('.item').each(owlAnimateFilter);
                });
            });
        })

    });


    jQuery(window).load(function(){
    });


}(jQuery));
(function ($) {
    "use strict";

    jQuery(document).ready(function($){

        $(function() {
            var owl = $('.filter-item5').owlCarousel({
                loop    :true,
                margin  :10,
                nav     :true,
                navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:3
                    },
                    1000:{
                        items:5
                    }
                }
            });

            /* animate filter */
            var owlAnimateFilter = function(even) {
                $(this)
                    .addClass('__loading')
                    .delay(70 * $(this).parent().index())
                    .queue(function() {
                        $(this).dequeue().removeClass('__loading')
                    })
            };

            $('.filter-menu5').on('click', '.filter-btn5', function(e) {
                var filter_data = $(this).data('filter');

                /* return if current */
                if($(this).hasClass('btn-active')) return;

                /* active current */
                $(this).addClass('btn-active').siblings().removeClass('btn-active');

                /* Filter */
                owl.owlFilter(filter_data, function(_owl) {
                    $(_owl).find('.item').each(owlAnimateFilter);
                });
            });
        })

    });


    jQuery(window).load(function(){
    });


}(jQuery));
