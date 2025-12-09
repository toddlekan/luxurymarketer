$(document).ready(function () {
  $(window).on("load", function () {
    $(".fp-watermark").addClass("d-none");
  });

  $(".scroll a[href*=#]").on("click", function (e) {
    var element = e.target;
    if (element.href.split("/").slice(-1) != "#top") {
      e.preventDefault();
      fullpage_api.moveSectionDown();
    } else {
      e.preventDefault();
      window.scrollTo({top: 0, behavior: 'smooth'});
    }
  });

  $("#homePage").fullpage({
    navigation: false,
    showActiveTooltip: false,
    slidesNavigation: false,
    slidesNavPosition: "bottom",
    controlArrows: false,
    scrollBar: true,
    fixedElements:
      "#lr-header, footer, #hero-section-6, #hero-section-7, #hero-section-8, #footer-divider", 
  });
});

function checkInView(elem, partial) {
  var container = $(".home-page.slides");
  var contHeight = container.height();
  var contTop = container.scrollTop();
  var contBottom = contTop + contHeight;

  var elemTop = $(elem).offset().top - container.offset().top;
  var elemBottom = elemTop + $(elem).height();

  var isTotal = elemTop >= 0 && elemBottom <= contHeight;
  var isPart =
    ((elemTop < 0 && elemBottom > 0) ||
      (elemTop > 0 && elemTop <= container.height())) &&
    partial;

  return isTotal || isPart;
}
