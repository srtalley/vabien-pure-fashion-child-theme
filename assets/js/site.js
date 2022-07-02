jQuery(function($) {


    $('document').ready(function() {
        setupSingleProductQTY();
        setupVaBienSlickSlider();
        setupScrollbarsWrapper();
        setupSingleProductSharing();
        setupAjaxAddToCart();
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
    } // end function
 
    /**
     * Setup the sharing
     */
    function setupSingleProductSharing() {
        var container = $('.share-article'),
          link = container.find('.thb_share'),
          icons = container.find('.icons'),
          social = container.find('.social');
        //   tl = gsap.timeline({
        //     paused: true,
        //     onStart: function() {
        //       icons.css('display', 'block');
        //     },
        //     onReverseComplete: function() {
        //       icons.css('display', 'none');
        //     }
        //   });

        link.on('click', function() {
          return false;
        });

        social.on('click', function() {
          var left = (screen.width / 2) - (640 / 2),
            top = (screen.height / 2) - (440 / 2) - 100;
          window.open($(this).attr('href'), 'mywin', 'left=' + left + ',top=' + top + ',width=640,height=440,toolbar=0');
          return false;
        });

        var x_perc = $('body').hasClass('rtl') ? '50%' : '-50%';
        // tl
        //   .fromTo(icons, {
        //     y: '6',
        //     x: x_perc,
        //     autoAlpha: 0
        //   }, {
        //     duration: 0.25,
        //     y: '-2',
        //     x: x_perc,
        //     autoAlpha: 1
        //   });

        // container.hoverIntent(function() {
        //   tl.timeScale(1).play();
        // }, function() {
        //   tl.timeScale(1.5).reverse();
        // });


    }
    /**
     * Set up the slick slider for products
     */
    function setupVaBienSlickSlider() {
        var columns = 1,
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
            centerPadding: '40px',
            autoplaySpeed: 4000,
            pauseOnHover: true,
            vertical: false,
            verticalSwiping: false,
            accessibility: false,
            focusOnSelect: false,
            prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="slick-nav thb-prev" x="0" y="0" width="50" height="40" viewBox="0 0 50 40" enable-background="new 0 0 50 40" xml:space="preserve"><path class="border" fill-rule="evenodd" clip-rule="evenodd" d="M0 0v40h50V0H0zM48 38H2V2h46V38z"/><path d="M15.3 19.2c0 0 0 0-0.1 0.1 0 0 0 0 0 0 0 0 0 0 0 0 -0.1 0.2-0.2 0.4-0.2 0.7 0 0.2 0.1 0.5 0.2 0.7 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0.1l3.8 3.9c0.4 0.4 1.1 0.4 1.5 0 0.4-0.4 0.4-1.1 0-1.6l-2-2h15.3c0.6 0 1.1-0.5 1.1-1.1 0-0.6-0.5-1.1-1.1-1.1H18.6l2-2c0.4-0.4 0.4-1.1 0-1.6 -0.4-0.4-1.1-0.4-1.5 0l-3.8 3.9C15.3 19.2 15.3 19.2 15.3 19.2z"/></svg>',
            nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="slick-nav thb-next" x="0" y="0" width="50" height="40" viewBox="0 0 50 40" enable-background="new 0 0 50 40" xml:space="preserve"><path class="border" fill-rule="evenodd" clip-rule="evenodd" d="M0 0v40h50V0H0zM2 2h46v36H2V2z"/><path d="M34.7 19.2L30.9 15.3c-0.4-0.4-1.1-0.4-1.5 0 -0.4 0.4-0.4 1.1 0 1.6l2 2H16.1c-0.6 0-1.1 0.5-1.1 1.1 0 0.6 0.5 1.1 1.1 1.1h15.3l-2 2c-0.4 0.4-0.4 1.1 0 1.6 0.4 0.4 1.1 0.4 1.5 0l3.8-3.9c0 0 0 0 0.1-0.1 0 0 0 0 0 0 0 0 0 0 0 0 0.1-0.2 0.2-0.4 0.2-0.7 0-0.2-0.1-0.5-0.2-0.7 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0-0.1-0.1C34.7 19.2 34.7 19.2 34.7 19.2z"/></svg>',
       
        };


        var product_image_args = args;
        product_image_args.arrows = true;
        product_image_args.slidesToShow = 1;
        product_image_args.asNavFor = '#product-thumbnails .woocommerce-product-gallery__wrapper';
        product_image_args.infinite = false;
        product_image_args.speed = 500;

        $('#product-images .woocommerce-product-gallery__wrapper').slick(product_image_args);

        var product_thumbnail_args = args;
        product_image_args.asNavFor = '#product-images .woocommerce-product-gallery__wrapper';
        product_thumbnail_args.infinite = false;
        product_thumbnail_args.focusOnSelect = true;
        product_thumbnail_args.speed = 500;
        product_thumbnail_args.slidesToShow = 4;
        product_thumbnail_args.vertical = true;
        product_thumbnail_args.responsive = [{
            breakpoint: 735,
                settings: {
                    // slidesToShow: 4,
                    vertical: false
                }
            }
        ];

        $('#product-thumbnails .woocommerce-product-gallery__wrapper').slick(product_thumbnail_args);
        $('#product-thumbnails a').on('click', function(e) {
            e.preventDefault();
        });
        $('#product-thumbnails .woocommerce-product-gallery').unbind('click');
    }


    /**
     * Set up the perfect scrollbar options
     */
    function setupScrollbarsWrapper() {

        // initial call 
        setupScrollbars();

      $('.thb-quick-cart').on('click', function() {
          setupScrollbars();
      });
  
      $('body').on('removed_from_cart', function(e, fragments, cart_hash, $button) {
          setupScrollbars();
      });
      function setupScrollbars() {
          var scrollbar_selector = $('.custom_scroll, #side-cart .woocommerce-mini-cart');
          scrollbar_selector.each(function() {
          if(!$(this).hasClass('ps-added')) {
              const vabien_perfect_sidebars = new PerfectScrollbar(this, {
                  wheelPropagation: false,
                  suppressScrollX: true
              });
              $(window).on('resize', function(){
                  vabien_perfect_sidebars.update();
              });
              $(this).addClass('ps-added');
            }
        });
      } 
    }
    
    function setupAjaxAddToCart() {
  
        // var selector = '.thb-single-product-ajax-on.single-product .product-type-variable form.cart, .thb-single-product-ajax-on.single-product .product-type-simple form.cart';
          var btn = $('.single_add_to_cart_button');
  
          if (typeof wc_add_to_cart_params !== 'undefined') {
            if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
              return;
            }
          }
          $(document).on('submit', 'body.single-product form.cart', function(e) {
            e.preventDefault();
            var _this = $(this),
              btn_text = btn.text();

            if (btn.is('.disabled') || btn.is('.wc-variation-selection-needed')) {
              return;
            }
  
            var data = {
              product_id: _this.find("[name*='add-to-cart']").val(),
              product_variation_data: _this.serialize()
            };
  
            $.ajax({
              method: 'POST',
              data: data.product_variation_data,
              dataType: 'html',
              url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add-to-cart=' + data.product_id + '&thb-ajax-add-to-cart=1'),
              cache: false,
              headers: {
                'cache-control': 'no-cache'
              },
              beforeSend: function() {
                $('body').trigger('adding_to_cart');
                btn.addClass('disabled').text('Adding to Cart');
              },
              success: function(data) {
                var parsed_data = $.parseHTML(data);
  
                var thb_fragments = {
                  '.thb-cart-count': $(parsed_data).find('.thb-cart-count').html(),
                  '.thb_prod_ajax_to_cart_notices': $(parsed_data).find('.thb_prod_ajax_to_cart_notices').html(),
                  '.widget_shopping_cart_content': $(parsed_data).find('.widget_shopping_cart_content').html()
                };
  
                $.each(thb_fragments, function(key, value) {
                  $(key).html(value);
                });
                $('body').trigger('wc_fragments_refreshed');
                btn.removeClass('disabled').text(btn_text);
              },
              error: function(response) {
                $('body').trigger('wc_fragments_ajax_error');
                btn.removeClass('disabled').text(btn_text);
              }
            });
          });
        
      
    }
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



/**
 * Mega menu support
 */


(function($, window, _) {
  'use strict';

  var $doc = $(document),
    win = $(window),
    body = $('body'),
    header = $('.header'),
    wrapper = $('#wrapper'),
    cc = $('.click-capture'),
    adminbar = $('#wpadminbar'),
    thb_ease = new BezierEasing(0.25, 0.46, 0.45, 0.94);
    // thb_md = new MobileDetect(window.navigator.userAgent);

  gsap.config({
    nullTargetWarn: false
  });

  var SITE = SITE || {};

  SITE = {
    init: function() {
      var self = this,
        obj;

      // win.on('resize.thb-page-padding', _.debounce(function() {
      //   if ($('.header:not(.fixed)').outerHeight() > 0) {
      //     $('.page-padding').css({
      //       'paddingTop': function() {
      //         return $('.header:not(.fixed)').outerHeight() + ($('.thb-global-notification').outerHeight() || 0) + 'px';
      //       }
      //     });
      //   }
      // }, 10)).trigger('resize.thb-page-padding');

      // $('.header').imagesLoaded(function() {
      //   win.trigger('resize.thb-page-padding');
      // });

      for (obj in self) {
        if (self.hasOwnProperty(obj)) {
          var _method = self[obj];
          if (_method.selector !== undefined && _method.init !== undefined) {
            if ($(_method.selector).length > 0) {
              _method.init();
            }
          }
        }
      }
    },
    fullMenu: {
      selector: '.vabien-menu, .secondary-menu',
      init: function() {
        var base = this,
          container = $(base.selector),
          li_org = container.find('a'),
          children = container.find('li.menu-item-has-children:not(.menu-item-mega-parent)'),
          mega_menu = container.find('li.menu-item-has-children.menu-item-mega-parent');

        children.each(function() {
          var _this = $(this),
            menu = _this.find('>.sub-menu'),
            li = menu.find('>li>a'),
            tl = gsap.timeline({
              paused: true
            });

          tl
            .to(menu, {
              duration: 0.5,
              autoAlpha: 1
            }, "start")
            .to(li, {
              duration: 0.1,
              opacity: 1,
              x: 0,
              stagger: 0.03
            }, "start");

          _this.hoverIntent({
            sensitivity: 3,
            interval: 20,
            timeout: 70,
            over: function() {
              _this.addClass('sfHover');
              tl.timeScale(50).restart();
            },
            out: function() {
              _this.removeClass('sfHover');
              tl.timeScale(50).reverse();
            }
          });
        });
        mega_menu.each(function() {
          var _this = $(this),
            menu = _this.find('>.sub-menu'),
            li = menu.find('>li>a, .menu-item-mega-link>a'),
            tl = gsap.timeline({
              paused: true
            });

          tl
            .fromTo(menu, {
              autoAlpha: 0,
              display: 'none'
            }, {
              duration: 0.5,
              autoAlpha: 1,
              display: 'flex'
            }, "start")
            .to(li, {
              duration: 0.1,
              opacity: 1,
              x: 0,
              stagger: 0.02
            }, "start");

          _this.hoverIntent(
            function() {
              _this.addClass('sfHover');
              tl.timeScale(1).restart();
            },
            function() {
              _this.removeClass('sfHover');
              tl.timeScale(1.5).reverse();
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
    }
  };

  $(function() {
    SITE.init();

   
  });

})(jQuery, this);