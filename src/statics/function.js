import $ from "jquery";
import { isBrowser } from "react-device-detect";

const scroller = (element) => {
  var timer = null;
  $(window).on("scroll", function () {
    if (isBrowser) {
      if (timer !== null) {
        clearTimeout(timer);
      }
      timer = setTimeout(function () {
        if ($(window).scrollTop() > element.offset().top) {
          if (!$(element).hasClass("is_stuck")) {
            $(element).addClass("is_stuck");
          }
        } else {
          if ($(element).hasClass("is_stuck")) {
            $(element).removeClass("is_stuck");
          }
        }
      }, 300);
    }
  });
};

export { $, scroller };
