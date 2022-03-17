import $ from "jquery";
import { isBrowser } from "react-device-detect";

const scroller = (element) => {
  var timer = null;
  $(window).on("scroll", function () {
    if (isBrowser) {
      $(element).addClass("is_stuck");
      if (timer !== null) {
        clearTimeout(timer);
      }
      timer = setTimeout(function () {
        console.log($(window).scrollTop(), element.offset().top);
        if ($(window).scrollTop() < element.offset().top && $(window).scrollTop() > 100) {
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

const isEven = (number) => {
  return number % 2 === 0 ? 1 : 0;
};

export { $, scroller, isEven };
