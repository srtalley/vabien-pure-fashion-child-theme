jQuery(function($) {


    $('document').ready(function() {
        console.log('we are ready');
        setupSingleProductQTY();
        setupVaBienSlickSlider();
        // setupMegaMenu();
        setupScrollbars();

        setTimeout(function() {

        },5000);
    });

    function setupSingleProductQTY() {
        var qty = $('div.quantity:not(.plus_minus_added), td.quantity:not(.plus_minus_added)');
        qty.each(function() {
          var _this = $(this);

          _this.addClass('plus_minus_added').append('<div class="plus"></div>').prepend('<div class="minus"></div>').end().find('input[type="number"]').attr('type', 'text');

          $('.plus, .minus', _this).on('click', function() {
            // Get values
            var $qty = $(this).closest('.quantity').find('.qty'),
              currentVal = parseFloat($qty.val()),
              max = parseFloat($qty.attr('max')),
              min = parseFloat($qty.attr('min')),
              step = $qty.attr('step');

            // Format values
            if (!currentVal || currentVal === '' || currentVal === 'NaN') {
              currentVal = 0;
            }
            if (max === '' || max === 'NaN') {
              max = '';
            }
            if (min === '' || min === 'NaN') {
              min = 0;
            }
            if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') {
              step = 1;
            }

            // Change the value
            if ($(this).is('.plus')) {

              if (max && (max === currentVal || currentVal > max)) {
                $qty.val(max);
              } else {
                $qty.val(currentVal + parseFloat(step));
              }

            } else {

              if (min && (min === currentVal || currentVal < min)) {
                $qty.val(min);
              } else if (currentVal > 0) {
                $qty.val(currentVal - parseFloat(step));
              }

            }
            // Trigger change event
            $qty.trigger('change');
            return false;
          });
        });
    }

    function setupVaBienSlickSlider() {
        // $('.woocommerce-product-gallery__wrapper').slick({
        //     slidesToShow: 1,
        //     slidesToScroll: 1,
        //     arrows: true,
        //     fade: true,
        //     asNavFor: '#product_thumbnails'
        //   });
        //   $('#product_thumbnails').slick({
        //     slidesToShow: 3,
        //     slidesToScroll: 1,
        //     asNavFor: '.woocommerce-product-gallery__wrapper',
        //     dots: true,
        //     centerMode: true,
        //     focusOnSelect: true
        //   });
        // $('#product-images .woocommerce-product-gallery__wrapper').slick({
        //     slidesToShow: 1,
        //     slidesToScroll: 1,
        //     arrows: true,
        //     fade: true,
        //     asNavFor: '#product-thumbnails .woocommerce-product-gallery__wrapper',
        //     prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="slick-nav thb-prev" x="0" y="0" width="50" height="40" viewBox="0 0 50 40" enable-background="new 0 0 50 40" xml:space="preserve"><path class="border" fill-rule="evenodd" clip-rule="evenodd" d="M0 0v40h50V0H0zM48 38H2V2h46V38z"/><path d="M15.3 19.2c0 0 0 0-0.1 0.1 0 0 0 0 0 0 0 0 0 0 0 0 -0.1 0.2-0.2 0.4-0.2 0.7 0 0.2 0.1 0.5 0.2 0.7 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0.1l3.8 3.9c0.4 0.4 1.1 0.4 1.5 0 0.4-0.4 0.4-1.1 0-1.6l-2-2h15.3c0.6 0 1.1-0.5 1.1-1.1 0-0.6-0.5-1.1-1.1-1.1H18.6l2-2c0.4-0.4 0.4-1.1 0-1.6 -0.4-0.4-1.1-0.4-1.5 0l-3.8 3.9C15.3 19.2 15.3 19.2 15.3 19.2z"/></svg>'
        //   });
        //   $('#product-thumbnails .woocommerce-product-gallery__wrapper').slick({
        //     slidesToShow: 4,
        //     slidesToScroll: 1,
        //     asNavFor: '#product-images .woocommerce-product-gallery__wrapper',
        //     slide: ':not(style):not(.label-wrap):not(.thb-product-icon)',
        //     dots: false,
        //     centerMode: true,
        //     focusOnSelect: true,
        //     vertical: vertical,
        //   });



        var container = $('.slick-carousel');
        // console.log(container);
        // container.each(function() {
        //     var that = $(this),
        //       columns = that.data('columns'),
        //       navigation = (that.data('navigation') === true ? true : false),
        //       autoplay = (that.data('autoplay') === false ? false : true),
        //       pagination = (that.data('pagination') === true ? true : false),
        //       center = (that.data('center') ? that.data('center') : false),
        //       disablepadding = (that.data('disablepadding') ? that.data('disablepadding') : false),
        //       vertical = (that.data('vertical') === true ? true : false),
        //       asNavFor = that.data('asnavfor'),
        //       rtl = false;
            var columns = 4,
            disablepadding = false,
            vertical = false;
            var args = {
              dots: false,
              arrows: false,
              infinite: false,
              speed: 1000,
              rows: 0,
              centerMode: false,
              slidesToShow: 4,
              slidesToScroll: 1,
              slide: ':not(style):not(.label-wrap):not(.thb-product-icon)',
              asNavFor: false,
              rtl: false,
              autoplay: false,
              centerPadding: false,
              autoplaySpeed: 4000,
              pauseOnHover: true,
              vertical: false,
              verticalSwiping: false,
              accessibility: false,
              focusOnSelect: false,
              prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="slick-nav thb-prev" x="0" y="0" width="50" height="40" viewBox="0 0 50 40" enable-background="new 0 0 50 40" xml:space="preserve"><path class="border" fill-rule="evenodd" clip-rule="evenodd" d="M0 0v40h50V0H0zM48 38H2V2h46V38z"/><path d="M15.3 19.2c0 0 0 0-0.1 0.1 0 0 0 0 0 0 0 0 0 0 0 0 -0.1 0.2-0.2 0.4-0.2 0.7 0 0.2 0.1 0.5 0.2 0.7 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0.1l3.8 3.9c0.4 0.4 1.1 0.4 1.5 0 0.4-0.4 0.4-1.1 0-1.6l-2-2h15.3c0.6 0 1.1-0.5 1.1-1.1 0-0.6-0.5-1.1-1.1-1.1H18.6l2-2c0.4-0.4 0.4-1.1 0-1.6 -0.4-0.4-1.1-0.4-1.5 0l-3.8 3.9C15.3 19.2 15.3 19.2 15.3 19.2z"/></svg>',
              nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="slick-nav thb-next" x="0" y="0" width="50" height="40" viewBox="0 0 50 40" enable-background="new 0 0 50 40" xml:space="preserve"><path class="border" fill-rule="evenodd" clip-rule="evenodd" d="M0 0v40h50V0H0zM2 2h46v36H2V2z"/><path d="M34.7 19.2L30.9 15.3c-0.4-0.4-1.1-0.4-1.5 0 -0.4 0.4-0.4 1.1 0 1.6l2 2H16.1c-0.6 0-1.1 0.5-1.1 1.1 0 0.6 0.5 1.1 1.1 1.1h15.3l-2 2c-0.4 0.4-0.4 1.1 0 1.6 0.4 0.4 1.1 0.4 1.5 0l3.8-3.9c0 0 0 0 0.1-0.1 0 0 0 0 0 0 0 0 0 0 0 0 0.1-0.2 0.2-0.4 0.2-0.7 0-0.2-0.1-0.5-0.2-0.7 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0-0.1-0.1C34.7 19.2 34.7 19.2 34.7 19.2z"/></svg>',
            //   responsive: [{
            //       breakpoint: 1441,
            //       settings: {
            //         slidesToShow: (columns < 6 ? columns : (vertical ? columns - 1 : 6)),
            //         centerPadding: (disablepadding ? 0 : '40px')
            //       }
            //     },
            //     {
            //       breakpoint: 1201,
            //       settings: {
            //         slidesToShow: (columns < 4 ? columns : (vertical ? columns - 1 : 4)),
            //         centerPadding: (disablepadding ? 0 : '40px')
            //       }
            //     },
            //     {
            //       breakpoint: 1025,
            //       settings: {
            //         slidesToShow: (columns < 3 ? columns : (vertical ? columns - 1 : 3)),
            //         centerPadding: (disablepadding ? 0 : '40px')
            //       }
            //     },
            //     {
            //       breakpoint: 641,
            //       settings: {
            //         slidesToShow: 1,
            //         centerPadding: (disablepadding ? 0 : '15px')
            //       }
            //     }
            //   ]
            };

           
            // that.slick(args);
        //   });

        var product_image_args = args;
        product_image_args.arrows = true;
        product_image_args.slidesToShow = 1;
        product_image_args.asNavFor = '#product-thumbnails .woocommerce-product-gallery__wrapper';
        product_image_args.infinite = false;
        product_image_args.speed = 500;

        $('#product-images .woocommerce-product-gallery__wrapper').slick(product_image_args);


        // $('#product-images .woocommerce-product-gallery__wrapper').on('afterChange', function(event, slick, currentSlide) {
        //     var zoomTarget = slick.$slides.eq(currentSlide),
        //       galleryWidth = zoomTarget.width(),
        //       zoomEnabled = false,
        //       image = zoomTarget.find('img');

        //     if (image.data('large_image_width') > galleryWidth) {
        //       zoomEnabled = true;
        //     }
        //     if (zoomEnabled) {
        //       var zoom_options = $.extend({
        //         touch: false
        //       }, window.wc_single_product_params.zoom_options);

        //       if ('ontouchstart' in window) {
        //         zoom_options.touch = true;
        //         zoom_options.on = 'click';
        //       }

        //       zoomTarget.trigger('zoom.destroy');
        //       zoomTarget.zoom(zoom_options);
        //       zoomTarget.trigger('focus mouseenter.zoom');
        //     }
        // });



        var product_thumbnail_args = args;
        product_image_args.asNavFor = '#product-images .woocommerce-product-gallery__wrapper';
        product_thumbnail_args.infinite = false;
        product_thumbnail_args.focusOnSelect = true;
        product_thumbnail_args.speed = 500;
        product_thumbnail_args.centerPadding = 0;
        product_thumbnail_args.slidesToShow = 4;
        product_thumbnail_args.vertical = true;
        // product_thumbnail_args.responsive[2].settings.vertical = false;
        // product_thumbnail_args.responsive[2].settings.slidesToShow = 4;
        // product_thumbnail_args.responsive[3].settings.vertical = false;
        // product_thumbnail_args.responsive[3].settings.slidesToShow = 4;
        $('#product-thumbnails .woocommerce-product-gallery__wrapper').slick(product_thumbnail_args);
        $('#product-thumbnails a').on('click', function(e) {
            e.preventDefault();
        });
        $('#product-thumbnails .woocommerce-product-gallery').unbind('click');
    }
    function setupSlickSlider() {
        // woocommerce-product-gallery__wrapper

        // var that = $(this),
        columns = 1,
        navigation = true,
        autoplay = false,
        pagination = false,
        center = false,
        disablepadding = false,
        vertical = false,
        asNavFor = '#product-thumbnails',
        rtl = false;




        var args = {
            dots: pagination,
            arrows: navigation,
            infinite: false,
            speed: 1000,
            rows: 0,
            centerMode: false,
            slidesToShow: columns,
            slidesToScroll: 1,
            slide: ':not(style):not(.label-wrap):not(.thb-product-icon)',
            rtl: rtl,
            asNavFor: asNavFor,
            autoplay: autoplay,
            centerPadding: (disablepadding ? 0 : '50px'),
            autoplaySpeed: 4000,
            pauseOnHover: true,
            vertical: vertical,
            verticalSwiping: vertical,
            accessibility: false,
            focusOnSelect: false,
            prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="slick-nav thb-prev" x="0" y="0" width="50" height="40" viewBox="0 0 50 40" enable-background="new 0 0 50 40" xml:space="preserve"><path class="border" fill-rule="evenodd" clip-rule="evenodd" d="M0 0v40h50V0H0zM48 38H2V2h46V38z"/><path d="M15.3 19.2c0 0 0 0-0.1 0.1 0 0 0 0 0 0 0 0 0 0 0 0 -0.1 0.2-0.2 0.4-0.2 0.7 0 0.2 0.1 0.5 0.2 0.7 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0.1l3.8 3.9c0.4 0.4 1.1 0.4 1.5 0 0.4-0.4 0.4-1.1 0-1.6l-2-2h15.3c0.6 0 1.1-0.5 1.1-1.1 0-0.6-0.5-1.1-1.1-1.1H18.6l2-2c0.4-0.4 0.4-1.1 0-1.6 -0.4-0.4-1.1-0.4-1.5 0l-3.8 3.9C15.3 19.2 15.3 19.2 15.3 19.2z"/></svg>',
            nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="slick-nav thb-next" x="0" y="0" width="50" height="40" viewBox="0 0 50 40" enable-background="new 0 0 50 40" xml:space="preserve"><path class="border" fill-rule="evenodd" clip-rule="evenodd" d="M0 0v40h50V0H0zM2 2h46v36H2V2z"/><path d="M34.7 19.2L30.9 15.3c-0.4-0.4-1.1-0.4-1.5 0 -0.4 0.4-0.4 1.1 0 1.6l2 2H16.1c-0.6 0-1.1 0.5-1.1 1.1 0 0.6 0.5 1.1 1.1 1.1h15.3l-2 2c-0.4 0.4-0.4 1.1 0 1.6 0.4 0.4 1.1 0.4 1.5 0l3.8-3.9c0 0 0 0 0.1-0.1 0 0 0 0 0 0 0 0 0 0 0 0 0.1-0.2 0.2-0.4 0.2-0.7 0-0.2-0.1-0.5-0.2-0.7 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0-0.1-0.1C34.7 19.2 34.7 19.2 34.7 19.2z"/></svg>',
            responsive: [{
                breakpoint: 1441,
                settings: {
                  slidesToShow: (columns < 6 ? columns : (vertical ? columns - 1 : 6)),
                  centerPadding: (disablepadding ? 0 : '40px')
                }
              },
              {
                breakpoint: 1201,
                settings: {
                  slidesToShow: (columns < 4 ? columns : (vertical ? columns - 1 : 4)),
                  centerPadding: (disablepadding ? 0 : '40px')
                }
              },
              {
                breakpoint: 1025,
                settings: {
                  slidesToShow: (columns < 3 ? columns : (vertical ? columns - 1 : 3)),
                  centerPadding: (disablepadding ? 0 : '40px')
                }
              },
              {
                breakpoint: 641,
                settings: {
                  slidesToShow: 1,
                  centerPadding: (disablepadding ? 0 : '15px')
                }
              }
            ]
          };

          args.infinite = false;
          args.focusOnSelect = true;
          args.speed = 500;
          args.centerPadding = 0;
          args.slidesToShow = 4;
          args.slidesToShow = 4;

        //   $('.woocommerce-product-gallery__image').slick(args);
          $('#product-images').slick(args);

    }

    function setupMegaMenu() {
        // gsap.config({
        //     nullTargetWarn: false
        //   });
        var mega_menu_selector = $('.vabien-menu, .secondary-menu');
        // function createMenu() {
              var base = this,
                container = $(mega_menu_selector),
                li_org = container.find('a'),
                children = container.find('li.menu-item-has-children:not(.menu-item-mega-parent)'),
                mega_menu = container.find('li.menu-item-has-children.menu-item-mega-parent');
      
              children.each(function() {
                var _this = $(this),
                  menu = _this.find('>.sub-menu'),
                  li = menu.find('>li>a');
                //   tl = gsap.timeline({
                //     paused: true
                //   });
      
                // tl
                //   .to(menu, {
                //     duration: 0.5,
                //     autoAlpha: 1
                //   }, "start")
                //   .to(li, {
                //     duration: 0.1,
                //     opacity: 1,
                //     x: 0,
                //     stagger: 0.03
                //   }, "start");
      
                _this.hoverIntent({
                  sensitivity: 3,
                  interval: 20,
                  timeout: 70,
                  over: function() {
                    _this.addClass('sfHover');
                    // tl.timeScale(1).restart();
                  },
                  out: function() {
                    _this.removeClass('sfHover');
                    // tl.timeScale(1.5).reverse();
                  }
                });
              });
              mega_menu.each(function() {
                var _this = $(this),
                  menu = _this.find('>.sub-menu'),
                  li = menu.find('>li>a, .menu-item-mega-link>a');
                //   tl = gsap.timeline({
                //     paused: true
                //   });
      
                // tl
                //   .fromTo(menu, {
                //     autoAlpha: 0,
                //     display: 'none'
                //   }, {
                //     duration: 0.5,
                //     autoAlpha: 1,
                //     display: 'flex'
                //   }, "start")
                //   .to(li, {
                //     duration: 0.1,
                //     opacity: 1,
                //     x: 0,
                //     stagger: 0.02
                //   }, "start");
      
                _this.hoverIntent(
                  function() {
                    _this.addClass('sfHover');
                    // tl.timeScale(1).restart();
                  },
                  function() {
                    _this.removeClass('sfHover');
                    // tl.timeScale(1.5).reverse();
                  }
                );
              });
              li_org.on('click', function(e) {
                var _this = $(this),
                  url = _this.attr('href'),
                  ah = $('#wpadminbar').outerHeight() || 0,
                  fh = $('.header').outerHeight(),
                  hash = url.indexOf("#") !== -1 ? url.substring(url.indexOf("#") + 1) : '',
                  pos = hash ? $('#' + hash).offset().top - ah - fh : 0;
                if (hash) {
                  pos = (hash === 'footer') ? "max" : pos;
                  gsap.to(win, {
                    duration: 1,
                    scrollTo: {
                      y: pos,
                      autoKill: false
                    }
                  });
                  return false;
                } else {
                  return true;
                }
              });
            }
        //   }
    // }
    function setupScrollbars() {
        console.log('setup happening');
        var scrollbar_selector = $('.custom_scroll, #side-cart .woocommerce-mini-cart');
        scrollbar_selector.each(function() {
          console.log(this);
        if(!$(this).hasClass('ps-added')) {
            console.log('added to ');
            console.log(this);
            const vabien_perfect_sidebars = new PerfectScrollbar(this, {
                wheelPropagation: false,
                suppressScrollX: true
            });
            $(window).on('resize', function(){
                console.log('resize option');
                vabien_perfect_sidebars.update();
            });
            $(this).addClass('ps-added');
        }

    });
    // $('.header').on('click', '.thb-quick-cart', function() {
    //     console.log('clickeddd');
    //     setupScrollbars();
    // });

    $('.thb-quick-cart').on('click', function() {
        setupScrollbars();
    });

    $('body').on('removed_from_cart', function(e, fragments, cart_hash, $button) {
        setupScrollbars();
    });
    //     win.on('resize.customscroll', function() {
    //       base.resize(container);
    //     });
    //   },
    //   resize: function(container) {
    //     container.perfectScrollbar('update');
    //   }
});


// added by Jared
// v1.2.2
var _InitialSiteLoad;
var _BraSizeCalculator;

jQuery(document).ready(function () {
    //_InitialSiteLoad.init();
    _BraSizeCalculator.init();
});

(function ($) {

    'use strict';

    _InitialSiteLoad = {

        init: function () {

        }
    };

    _BraSizeCalculator = {

        init: function () {

            var bcEl = $('.bc-container');

            _BraSizeCalculator.calc(bcEl);

            var popup = $('.popup-bc-calculator');

            if (popup.length > 0) {

                popup.on('click', function (e) {

                    var popbcEl = $('.pum-content .bc-container');

                    _BraSizeCalculator.calc(popbcEl);
                });
            }

            var bcLink = $('.popup-bc-calculator');

            if (bcLink.length > 0) {

                bcLink.magnificPopup({
                    type: 'inline',
                    preloader: false
                });

            }
        },

        calc: function (bcEl) {


            if (bcEl.length > 0) {

                var inputEls = bcEl.find('input[name="band_size"], input[name="bust_size"] '),
                    band = bcEl.find('input[name="band_size"]'),
                    bust = bcEl.find('input[name="bust_size"]'),
                    result = bcEl.find('.bc-result');

                result.html('');

                inputEls.on('input', function (e) {

                    result.html('');

                    inputEls.each(function () {

                        var inputEl = $(this),
                            size = parseInt(inputEl.val());

                        var isnum = /^\d+$/.test(size);

                        if (isnum && size > 20 && size < 50) {
                            inputEl.next().hide();
                        } else if (inputEl.val().length > 1) {
                            inputEl.next().show();
                        }

                    });

                    // do the actual calculation
                    var band_val = 2 * Math.round(Math.round(band.val()) / 2);
                    var bust_val = Math.round(bust.val());
                    // are they both numbers
                    var is_band_num = /^\d+$/.test(band_val);
                    var is_bust_num = /^\d+$/.test(bust_val);

                    if (!is_band_num) {
                        return false;
                    }

                    if (!is_bust_num) {
                        return false;
                    }

                    if (band_val < 30 || band_val > 50) {
                        return false;
                    }

                    if (bust_val < 30 || bust_val > 50) {
                        return false;
                    }

                    if ((band_val > bust_val) || (band_val == bust_val)) {
                        result.html('Band size cannot be the same or bigger than bust size');
                        result.addClass('error');
                        result.show();
                        return false;
                    }

                    var diff = (bust_val - band_val) - 1;
                    var keyArray = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

                    if (diff >= 0 && diff <= 8) {
                        result.removeClass('error');
                        result.html(band_val + keyArray[diff]);

                    } else {
                        result.addClass('error');
                        result.html('No result found');
                    }
                });
            }


        }
    };

    // 2019/05/09 Fix an issue in the current theme where the site title isn't properly
    // url encoded, and the | character prevents Twitter share links from working.
    var sharing_urls = $('.share-article a');
    sharing_urls.each(function(){
        var current_href = $(this).attr('href');
        try {
            var new_href = current_href.toString().replace(/\|/g, '%7C');
            new_href = new_href.toString().replace(/\ /g, '%20');
            new_href = new_href.toString().replace(/\,/g, '%2C');
            // Replace the link
            $(this).attr('href', new_href);
        } catch {
            // Nothing
        }
    });

})(jQuery);

// Accessibility
function handleFirstTab(e) {
    if (e.keyCode === 9) { // the "I am a keyboard user" key
        document.body.classList.add('user-is-tabbing');
        window.removeEventListener('keydown', handleFirstTab);
    }
}

window.addEventListener('keydown', handleFirstTab);


