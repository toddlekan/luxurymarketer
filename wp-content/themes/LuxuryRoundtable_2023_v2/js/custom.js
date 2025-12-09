$(document).ready(function () {
  $(document).on("click", "#mfPreviewBar", function (e) {
    $(this).hide();
  });
});

$(document).ready(function() {
  const getCookie = (name) => {
    const value = " " + document.cookie;
    console.log("value", `==${value}==`);
    const parts = value.split(" " + name + "=");
    return parts.length < 2 ? undefined : parts.pop().split(";").shift();
  };

  const setCookie = function (name, value, expiryDays, domain, path, secure) {
    const exdate = new Date();
    exdate.setHours(
      exdate.getHours() +
        (typeof expiryDays !== "number" ? 365 : expiryDays) * 24
    );
    document.cookie =
      name +
      "=" +
      value +
      ";expires=" +
      exdate.toUTCString() +
      ";path=" +
      (path || "/") +
      (domain ? ";domain=" + domain : "") +
      (secure ? ";secure" : "");
  };

  const cookiesBanner = document.querySelector(".cookie-policy-box");
  const cookiesAcceptButton = cookiesBanner.querySelector(".accept");
  const cookiesCancelButton = cookiesBanner.querySelector(".cancel");
  const cookieName = "cookies_policy_2023";
  const hasCookie = getCookie(cookieName);

  if (!hasCookie) {
    cookiesBanner.classList.remove("hidden");
  }

  cookiesAcceptButton.addEventListener("click", () => {
    setCookie(cookieName, "closed");
    cookiesBanner.remove();
  });

  cookiesCancelButton.addEventListener("click", () => {
    cookiesBanner.remove();
  });
});

(function () {
  /*$(window).scroll(function () {
      var top = $(document).scrollTop();
      $('.splash').css({
        'background-position': '0px -'+(top/3).toFixed(2)+'px'
      });
      if(top > 50)
        $('#home > .navbar').removeClass('navbar-transparent');
      else
        $('#home > .navbar').addClass('navbar-transparent');
  });*/

  $("a[href='#']").click(function (e) {
    e.preventDefault();
  });

  var $button = $(
    "<div id='source-button' class='btn btn-primary btn-xs'>&lt; &gt;</div>"
  ).click(function () {
    var html = $(this).parent().html();
    html = cleanSource(html);
    $("#source-modal pre").text(html);
    $("#source-modal").modal();
  });

  $('.bs-component [data-toggle="popover"]').popover();
  $('.bs-component [data-toggle="tooltip"]').tooltip();

  $(".bs-component").hover(
    function () {
      $(this).append($button);
      $button.show();
    },
    function () {
      $button.hide();
    }
  );

  function cleanSource(html) {
    var lines = html.split(/\n/);

    lines.shift();
    lines.splice(-1, 1);

    var indentSize = lines[0].length - lines[0].trim().length,
      re = new RegExp(" {" + indentSize + "}");

    lines = lines.map(function (line) {
      if (line.match(re)) {
        line = line.substring(indentSize);
      }

      return line;
    });

    lines = lines.join("\n");

    return lines;
  }
})();
