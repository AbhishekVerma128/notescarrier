(function () {
  "use strict";

  var toggle = document.querySelector(".menu-toggle");
  var navigation = document.querySelector("#primary-navigation");

  if (toggle && navigation) {
    toggle.addEventListener("click", function () {
      var isOpen = toggle.getAttribute("aria-expanded") === "true";
      toggle.setAttribute("aria-expanded", String(!isOpen));
      navigation.classList.toggle("is-open", !isOpen);
      document.body.classList.toggle("menu-is-open", !isOpen);
    });

    navigation.addEventListener("click", function (event) {
      if (event.target.closest("a")) {
        toggle.setAttribute("aria-expanded", "false");
        navigation.classList.remove("is-open");
        document.body.classList.remove("menu-is-open");
      }
    });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape" && toggle.getAttribute("aria-expanded") === "true") {
        toggle.setAttribute("aria-expanded", "false");
        navigation.classList.remove("is-open");
        document.body.classList.remove("menu-is-open");
        toggle.focus();
      }
    });
  }

  var notificationCarousel = document.querySelector("[data-notification-carousel]");
  if (notificationCarousel) {
    var notificationTrack = notificationCarousel.querySelector("[data-notification-track]");
    var notificationSlides = notificationCarousel.querySelectorAll("[data-notification-slide]");

    if (notificationTrack && notificationSlides.length > 1) {
      var activeNotification = 0;
      var rotationTimer = null;
      var rotationPaused = false;
      var userPausedRotation = false;
      var swipeStartX = null;
      var suppressNotificationClick = false;
      var carouselStatus = notificationCarousel.querySelector("[data-notification-status]");
      var carouselIndicators = notificationCarousel.querySelectorAll("[data-notification-go]");
      var reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
      var rotationInterval = Math.min(10000, Math.max(3500, Number(notificationCarousel.dataset.interval) || 5000));

      function stopNotificationRotation() {
        if (rotationTimer) {
          window.clearInterval(rotationTimer);
          rotationTimer = null;
        }
      }

      function showNotification(index) {
        activeNotification = (index + notificationSlides.length) % notificationSlides.length;
        notificationTrack.style.transform = "translate3d(-" + activeNotification * 100 + "%, 0, 0)";

        notificationSlides.forEach(function (slide, slideIndex) {
          var isActive = slideIndex === activeNotification;
          slide.setAttribute("aria-hidden", String(!isActive));
          if (isActive) slide.removeAttribute("inert");
          else slide.setAttribute("inert", "");
        });

        carouselIndicators.forEach(function (indicator, indicatorIndex) {
          indicator.setAttribute("aria-pressed", String(indicatorIndex === activeNotification));
        });

        if (carouselStatus) {
          carouselStatus.textContent = (activeNotification + 1) + " / " + notificationSlides.length;
        }
      }

      function startNotificationRotation() {
        stopNotificationRotation();
        if (!rotationPaused && !userPausedRotation && !reducedMotion.matches && document.visibilityState === "visible") {
          rotationTimer = window.setInterval(function () {
            showNotification(activeNotification + 1);
          }, rotationInterval);
        }
      }

      function navigateNotification(index) {
        showNotification(index);
        startNotificationRotation();
      }

      var previousNotification = notificationCarousel.querySelector("[data-notification-prev]");
      var nextNotification = notificationCarousel.querySelector("[data-notification-next]");
      var rotationToggle = notificationCarousel.querySelector("[data-notification-rotation]");
      if (previousNotification) {
        previousNotification.addEventListener("click", function () {
          navigateNotification(activeNotification - 1);
        });
      }
      if (nextNotification) {
        nextNotification.addEventListener("click", function () {
          navigateNotification(activeNotification + 1);
        });
      }
      if (rotationToggle) {
        rotationToggle.addEventListener("click", function () {
          userPausedRotation = !userPausedRotation;
          rotationToggle.setAttribute("aria-pressed", String(userPausedRotation));
          rotationToggle.setAttribute(
            "aria-label",
            userPausedRotation ? "Resume automatic notifications" : "Pause automatic notifications"
          );
          rotationToggle.querySelector("span").textContent = userPausedRotation ? "▶" : "Ⅱ";
          startNotificationRotation();
        });
      }

      carouselIndicators.forEach(function (indicator) {
        indicator.addEventListener("click", function () {
          navigateNotification(Number(indicator.dataset.notificationGo) || 0);
        });
      });

      notificationCarousel.addEventListener("mouseenter", function () {
        rotationPaused = true;
        stopNotificationRotation();
      });
      notificationCarousel.addEventListener("mouseleave", function () {
        rotationPaused = false;
        startNotificationRotation();
      });
      notificationCarousel.addEventListener("focusin", function () {
        rotationPaused = true;
        stopNotificationRotation();
      });
      notificationCarousel.addEventListener("focusout", function (event) {
        if (!notificationCarousel.contains(event.relatedTarget)) {
          rotationPaused = false;
          startNotificationRotation();
        }
      });
      document.addEventListener("visibilitychange", startNotificationRotation);
      if (typeof reducedMotion.addEventListener === "function") {
        reducedMotion.addEventListener("change", startNotificationRotation);
      }

      var notificationViewport = notificationCarousel.querySelector(".notification-carousel__viewport");
      if (notificationViewport) {
        notificationViewport.addEventListener("pointerdown", function (event) {
          swipeStartX = event.clientX;
        });
        notificationViewport.addEventListener("pointerup", function (event) {
          if (null === swipeStartX) return;
          var swipeDistance = event.clientX - swipeStartX;
          swipeStartX = null;
          if (Math.abs(swipeDistance) > 45) {
            event.preventDefault();
            suppressNotificationClick = true;
            navigateNotification(activeNotification + (swipeDistance < 0 ? 1 : -1));
            window.setTimeout(function () {
              suppressNotificationClick = false;
            }, 0);
          }
        });
        notificationViewport.addEventListener("pointercancel", function () {
          swipeStartX = null;
        });
        notificationViewport.addEventListener("click", function (event) {
          if (suppressNotificationClick) {
            event.preventDefault();
            event.stopPropagation();
            suppressNotificationClick = false;
          }
        }, true);
      }

      showNotification(0);
      startNotificationRotation();
    }
  }

  var popup = document.querySelector("[data-community-popup]");
  if (!popup) return;

  var dialog = popup.querySelector(".community-popup__dialog");
  var closeButton = popup.querySelector("[data-popup-close]");
  var popupKey = "notescarrier-community-popup-dismissed";
  var popupCooldown = 7 * 24 * 60 * 60 * 1000;
  var previousFocus = null;

  function saveDismissedState() {
    try {
      window.localStorage.setItem(popupKey, String(Date.now()));
    } catch (error) {
      // The popup still works when browser storage is unavailable.
    }
  }

  function closePopup() {
    popup.hidden = true;
    document.body.classList.remove("community-popup-open");
    saveDismissedState();
    if (previousFocus && typeof previousFocus.focus === "function") {
      previousFocus.focus();
    }
  }

  function showPopup() {
    popup.hidden = false;
    document.body.classList.add("community-popup-open");
    previousFocus = document.activeElement;
    window.requestAnimationFrame(function () {
      if (closeButton) closeButton.focus();
      else if (dialog) dialog.focus();
    });
  }

  var wasDismissedRecently = false;
  try {
    var dismissedAt = Number(window.localStorage.getItem(popupKey) || 0);
    wasDismissedRecently = dismissedAt > 0 && Date.now() - dismissedAt < popupCooldown;
  } catch (error) {
    wasDismissedRecently = false;
  }

  if (!wasDismissedRecently) {
    var delaySeconds = Math.min(60, Math.max(0, Number(popup.dataset.delay) || 0));
    window.setTimeout(showPopup, delaySeconds * 1000);
  }

  if (closeButton) {
    closeButton.addEventListener("click", closePopup);
  }

  popup.addEventListener("click", function (event) {
    if (event.target === popup) closePopup();
  });

  popup.querySelectorAll("[data-popup-action]").forEach(function (action) {
    action.addEventListener("click", closePopup);
  });

  document.addEventListener("keydown", function (event) {
    if (popup.hidden) return;

    if (event.key === "Escape") {
      closePopup();
      return;
    }

    if (event.key !== "Tab" || !dialog) return;
    var focusable = dialog.querySelectorAll('a[href], button:not([disabled])');
    if (!focusable.length) return;

    var first = focusable[0];
    var last = focusable[focusable.length - 1];
    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  });
})();
