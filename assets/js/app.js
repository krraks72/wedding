// Template Name: Audio Beats
// Template URL: https://techpedia.co.uk/template/audiobeats
// Description: Audio Beats Innovative Audio Music Theme
// Version: 1.0.0
(function (window, document, $, undefined) {
  "use strict";
  // ==========================================================
  // Detect mobile device and add class "is-mobile" to </body>
  // ==========================================================

  // Detect mobile device (Do not remove!!!)
  var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Nokia|Opera Mini/i.test(navigator.userAgent) ? true : false;

  // Add class "is-mobile" to </body>
  if (isMobile) {
    $("body").addClass("is-mobile");
  }

  var Init = {
    i: function (e) {
      Init.s();
      Init.methods();
    },
    s: function (e) {
      (this._window = $(window)),
        (this._document = $(document)),
        (this._body = $("body")),
        (this._html = $("html"));
    },
    methods: function (e) {
      Init.w();
      Init.BackToTop();
      Init.smoothScroll();
      Init.preloader();
      Init.initializeSlick();
      Init.hamburgerMenu();
      Init.countdownInit(".countdown", "2024/08/01");
      Init.rsvpFormSubmit();
    },
    w: function (e) {
      this._window.on("load", Init.l).on("scroll", Init.res);
    },
    BackToTop: function () {
      var btn = $("#backto-top");
      $(window).on("scroll", function () {
        if ($(window).scrollTop() > 300) {
          btn.addClass("show");
        } else {
          btn.removeClass("show");
        }
      });
      btn.on("click", function (e) {
        e.preventDefault();
        $("html, body").animate(
          {
            scrollTop: 0,
          },
          "300"
        );
      });
    },
    preloader: function () {
      setTimeout(function () { $('#preloader').hide('slow') }, 2000);
    },
    smoothScroll: function () {
      // =======================================================================================
      // Smooth Scrollbar
      // Source: https://github.com/idiotWu/smooth-scrollbar/
      // =======================================================================================

      if ($("body").hasClass("ui-smooth-scroll")) {

        // Not for mobile devices!
        if (!isMobile) {

          var Scrollbar = window.Scrollbar;

          // AnchorPlugin (URL with hash links load in the right position)
          // https://github.com/idiotWu/smooth-scrollbar/issues/440
          // ==================================
          class AnchorPlugin extends Scrollbar.ScrollbarPlugin {
            static pluginName = 'anchor';

            onHashChange = () => {
              this.jumpToHash(window.location.hash);
            };

            onClick = (event) => {
              const { target } = event;
              if (target.tagName !== 'A') {
                return;
              }
              const hash = target.getAttribute('href');
              if (!hash || hash.charAt(0) !== '#') {
                return;
              }
              this.jumpToHash(hash);
            };

            jumpToHash = (hash) => {
              if (!hash) {
                return;
              }
              const { scrollbar } = this;
              scrollbar.containerEl.scrollTop = 0;
              const target = document.querySelector(hash);
              if (target) {
                scrollbar.scrollIntoView(target, {
                  offsetTop: parseFloat(target.getAttribute('data-offset')) || 0 // Change to set default offset
                });
              }
            };

            onInit() {
              this.jumpToHash(window.location.hash);
              window.addEventListener('hashchange', this.onHashChange);
              this.scrollbar.contentEl.addEventListener('click', this.onClick);
            };

            onDestory() {
              window.removeEventListener('hashchange', this.onHashChange);
              this.scrollbar.contentEl.removeEventListener('click', this.onClick);
            };
          };

          // usage
          Scrollbar.use(AnchorPlugin);


          // Init Smooth Scrollbar
          // ======================
          Scrollbar.init(document.querySelector("#scroll-container"), {
            damping: 0.06,
            renderByPixel: true,
            continuousScrolling: true,
            alwaysShowTracks: true
          });


          // 3rd party library setup
          // More info: https://greensock.com/docs/v3/Plugins/ScrollTrigger/static.scrollerProxy()
          // ========================
          let scrollPositionX = 0,
            scrollPositionY = 0,
            bodyScrollBar = Scrollbar.init(document.getElementById("scroll-container"));

          bodyScrollBar.addListener(({ offset }) => {
            scrollPositionX = offset.x;
            scrollPositionY = offset.y;
          });

          bodyScrollBar.setPosition(0, 0);
          bodyScrollBar.track.xAxis.element.remove();

          // Enable regular scrollbar inside a smooth scrollbar (#scroll-container). IMPORTANT: use class "tt-overflow" on inside scroll elements!
          // ===================================================
          if ($(".tt-overflow").length) {
            // Determine if an element is scrollable
            $.fn.ttIsScrollable = function () {
              return this[0].scrollWidth > this[0].clientWidth || this[0].scrollHeight > this[0].clientHeight;
            };

            $(".tt-overflow").each(function () {
              var $this = $(this);
              if ($this.ttIsScrollable()) {
                $this.on("wheel", function (e) {
                  e.stopPropagation();
                });
              }
            });
          }


          // Prevent input[type=number] to scroll on focus 
          // ==============================================
          $("input[type=number]").on("focus", function () {
            $(this).on("wheel", function (e) {
              e.stopPropagation();
            });
          });

        }

      }
    },
    
    initializeSlick: function (e) {
      if ($(".events-slider").length) {
        $(".events-slider").slick({
          infinite: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true,
          autoplay: true,
          cssEase: 'linear',
          draggable: true,
          touchThreshold: 10,
          fade: true,
          autoplaySpeed: 3000,
          responsive: [
            {
              breakpoint: 575,
              settings: {
                slidesToShow: 1,
                arrows: false,
              },
            },
          ],
        });
      }
      if ($(".blogs-slider").length) {
        $(".blogs-slider").slick({
          infinite: true,
          slidesToShow: 3,
          slidesToScroll: 1,
          arrows: true,
          autoplay: true,
          cssEase: 'linear',
          draggable: true,
          autoplaySpeed: 3000,
          responsive: [
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 2,
              },
            },
            {
              breakpoint: 575,
              settings: {
                slidesToShow: 1,
              },
            },
          ],
        });
      }
    },
    hamburgerMenu: function () {
      if ($(".hamburger-menu").length) {
        $('.hamburger-menu').on('click', function() {
          $('.bar').toggleClass('animate');
          $('.mobile-navar').toggleClass('active');
          return false;
        })
        $('.has-children').on ('click', function() {
             $(this).children('ul').slideToggle('slow', 'swing');
             $('.icon-arrow').toggleClass('open');
        });
      }
    },
    rsvpFormSubmit: function () {
      var rsvpForm = document.getElementById("rsvp-form");
      if (!rsvpForm) {
        return;
      }

      var feedback = document.getElementById("rsvp-feedback");

      rsvpForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        var data = new FormData(rsvpForm);
        var payload = {
          name: data.get("name"),
          email: data.get("email"),
          guests: data.get("Number_Of_Guests"),
          mealPreference: data.get("Meal_Preferences"),
          attending: data.get("radio") === "1",
        };

        try {
          var response = await fetch("/api/rsvps.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
          });

          var result = await response.json();

          if (!response.ok) {
            throw new Error(result.message || "No se pudo guardar la confirmación.");
          }

          if (feedback) {
            feedback.textContent = "✅ Tu confirmación fue enviada correctamente.";
          }
          rsvpForm.reset();
        } catch (error) {
          if (feedback) {
            feedback.textContent = "❌ " + error.message;
          }
        }
      });
    },
    countdownInit: function (countdownSelector, countdownTime) {
      var eventCounter = $(countdownSelector);
      if (eventCounter.length) {
        eventCounter.countdown(countdownTime, function (e) {
          $(this).html(
            e.strftime(
              '<li><h4 class="number">%D</h4><h5 class="number-text">Days</h5></li>\
              <li><h4 class="number">%H</h4><h5 class="number-text">Hrs</h5></li>\
              <li><h4 class="number">%M</h4><h5 class="number-text">Min</h5></li>\
              <li><h4 class="number">%S</h4><h5 class="number-text">Sec</h5></li>'
            )
          );
        });
      }
    },
  }
  Init.i();
})(window, document, jQuery);





