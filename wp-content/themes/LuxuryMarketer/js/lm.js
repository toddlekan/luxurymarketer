var posting = false;
var animating = false;

$(document).ready(function () {
  /*INIT*/
  checkLogin();

  /*RESPONSIVE*/
  if (!$("body").hasClass("pdf")) {
    responsiveImages();
  } else {
    $("p > iframe").each(function () {
      var elm = $(this);

      var src = elm.attr("src");

      var parent = elm.parent();

      elm.remove();

      parent.append(
        '<div>Embedded Video: <a href="' + src + '">' + src + "</a></div>"
      );
    });
  }

  /*SUBSCRIPTION PROMOTION*/
  $(".subscription-promotion h1, .subscription-promotion h2")
    .mouseenter(function () {
      $(this).addClass("hover");
    })
    .mouseleave(function () {
      $(this).removeClass("hover");
    });

  $(document).on(
    "click",
    ".subscription-promotion h1, .subscription-promotion h2",
    function () {
      location.href = "cambey";
    }
  );

  /*CAMBEY LOGIN*/
  $("form#cambey-login").submit(function (event) {
    var form = $(this);

    var redirect = $(this).find(".redirect").val();

    // process the form
    $.ajax({
      type: form.attr("method"), // define the type of HTTP verb we want to use (POST for our form)
      url: form.attr("action"),
      data: form.serialize(),
      dataType: "json",
    }).done(function (data) {
      if (data.msg) {
        $(".msg").html(data.msg);
        $(".msg").show();
      } else {
        $(".msg").hide();
      }

      if (data.url) {
        $(".action a").attr("href", data.url);
        $(".action a").html(data.url_label);

        $(".action").show();
      } else {
        $(".action").hide();
        if (redirect) {
          location.href = redirect;
        } else {
          location.href = "/";
        }
      }
    });

    // stop the form from submitting the normal way and refreshing the page
    event.preventDefault();
  });

  $(document).on("click", ".logout-link", function (e) {
    e.preventDefault();

    $.ajax({
      url: window.location.origin + "/wp-content/plugins/cambey/logout.php",
    }).done(function (data) {
      $(".logout-link").hide();
      $(".logout-status").show();
      checkLogin();
      location.href = "/log-in";
    });
  });

  $(document).on("click", ".band .close", function (e) {
    e.preventDefault();

    $(".band").hide();

    setCookie("luxuryroundtable_promo", 1);
  });

  function afterDecrypt(body, html) {
    body.html(html);

    $(".heading.pdf").show();

    $(".locked").removeClass("locked");

    if (!$("body").hasClass("pdf")) {
      responsiveImages();
    } else {
      $("p > iframe").each(function () {
        var elm = $(this);

        var src = elm.attr("src");

        var parent = elm.parent();

        elm.remove();

        parent.append(
          '<div>Embedded Video: <a href="' + src + '">' + src + "</a></div>"
        );
      });
    }
  }

  function responsiveImages() {
    var new_width = $(".navbar").width();

    if (new_width < "501") {
      $("img").each(function () {
        var img = $(this);

        var width = img.css("max-width");

        if (width > new_width || width == "none" || width == "100%") {
          var height = img.attr("height");

          var new_height = (new_width * height) / width;

          img.css("max-width", new_width);

          img.css("max-height", new_height);

          var src = img.attr("src");

          img.attr("width", "");
          img.attr("height", "");

          $("<img />", {
            src: img.attr("src"),
          }).load(function () {
            if (img.width() == new_width) {
              var left = img.offset().left;

              img.css("margin-left", "-" + left + "px");
            }

            var container = img.parent().parent();
            if (container.hasClass("iframe-container")) {
              container.css("height", img.height() + "px");
            }

            img.css("visibility", "visible");
          });
        }
      });

      $("iframe").each(function () {
        var iframe = $(this);

        var new_width = window.innerWidth - 45;

        var width = iframe.attr("width");

        if (width > new_width) {
          var height = iframe.attr("height");

          var new_height = (new_width * height) / width;

          iframe.attr("width", new_width);

          iframe.attr("height", new_height);
        }
      });
    }
  }

  function checkLogin() {
    var body = $(".body.locked");

    var post_id = body.attr("post-id");

    var cookie = getCookie("_QAS3247adjl");

    if (cookie) {
      $(".label.subscribe .sign-in").hide();
      $(".label.subscribe .sign-in-subscribe").hide();
      $(".forgot").hide();

      $(".free").removeClass("free");

      //handle my account links
      var acctno = getCookie("luxuryroundtable_acctno");
      var acctno_href =
        "https://www.cambeywest.com/LXM/?f=custcare&a=" + acctno;

      if (!acctno) {
        acctno_href = "https://www.cambeywest.com/subscribe2/LXM/?f=pa";
        $(".label.subscribe .my").html("Forgot Password");
      }

      $(".label.subscribe .my").attr("href", acctno_href);
      $(".label.subscribe .my").show();
      $(".label.subscribe .logout-link").show();

      if ($(window).width() < 501) {
        $(".page-header.logo").css("border-bottom", "0px");
        $(".page-header.logo").css("border-top", "0px");
      }

      //show pdf link
      $(".download-pdf").show();

      var url =
        window.location.origin +
        "/wp-content/plugins/cambey/check_login.php?post_id=" +
        post_id;

      $.ajax({
        url: url,
      }).done(function (data) {
        //logged in
        if (data) {
          $("#footer-login").attr(
            "href",
            "/wp-content/plugins/cambey/logout.php"
          );
          $("#footer-login").html("Log Out");

          $(".band").hide();
          $(".most-popular li").find("img:last").remove();
          // $(".key").removeClass("key");

          //show rest of article

          if (body.length) {
            data = JSON.parse(data);

            var token = data.token;
            var token2 = data.token2;

            var encrypted = $("#encrypted").html();

            var html = "";
            var catch_err = false;

            try {
              html = JSON.parse(
                CryptoJS.AES.decrypt(encrypted, token, {
                  format: CryptoJSAesJson,
                }).toString(CryptoJS.enc.Utf8)
              );
            } catch (err) {
              catch_err = err;

              console.log("error:");
              console.log(catch_err);
              console.log("encrypted:");
              console.log(encrypted);
              console.log("token: " + token);
              console.log("token2: " + token2);
              console.log("url: " + url);
            }

            if (catch_err) {
              try {
                html = JSON.parse(
                  CryptoJS.AES.decrypt(encrypted, token2, {
                    format: CryptoJSAesJson,
                  }).toString(CryptoJS.enc.Utf8)
                );
              } catch (err) {
                console.log("error2:");
                console.log(err);
                console.log("encrypted:");
                console.log(encrypted);
                console.log("token: " + token);
                console.log("token2: " + token2);
                console.log("url: " + url);
              }
            }

            if (!html) {
              var token = body.attr("token");

              $.ajax({
                url: window.location.origin + "/get-body/token/" + token + "/",
              }).done(function (data) {
                if (data) {
                  console.log("GOT BODY");

                  var html = data;
                  afterDecrypt(body, html);
                } else {
                  var d = new Date();
                  var n = d.getTime();
                  var new_url = location.href + "?" + n;

                  console.log("REDIRECT: " + new_url);
                  location.href = new_url;
                }
              });
            } else {
              afterDecrypt(body, html);
            }
          } else {
            $(".locked").removeClass("locked");
          }

          //not logged in
        } else {
          notLoggedIn();
        }
      });
    } else {
      notLoggedIn();
    }
  }

  function notLoggedIn() {
    $(".body").show();

    $(".promo").show();
    $(".acc-divider").hide();
    if (getCookie("luxuryroundtable_promo")) {
    } else {
      $(".band-container").show();
    }

    $(".label.subscribe .my").hide();
    $(".label.subscribe .logout-link").hide();
    $(".forgot").show();

    $(".label.subscribe .sign-in").show();
    $(".label.subscribe .sign-in-subscribe").show();

    // $('.most-popular a').addClass('key');

    //hide pdf link
    $(".download-pdf").hide();
  }

  /*SAFARI*/

  // Opera 8.0+
  var isOpera =
    (!!window.opr && !!opr.addons) ||
    !!window.opera ||
    navigator.userAgent.indexOf(" OPR/") >= 0;
  // Firefox 1.0+
  var isFirefox = typeof InstallTrigger !== "undefined";
  // At least Safari 3+: "[object HTMLElementConstructor]"
  var isSafari =
    Object.prototype.toString.call(window.HTMLElement).indexOf("Constructor") >
    0;
  // Internet Explorer 6-11
  var isIE = /*@cc_on!@*/ false || !!document.documentMode;
  // Edge 20+
  var isEdge = !isIE && !!window.StyleMedia;
  // Chrome 1+
  var isChrome = !!window.chrome && !!window.chrome.webstore;
  // Blink engine detection

  /* Detect Safari */
  if (isSafari) {
    /* Do something for Safari */
    $("h1, h2, h3, h4, h5, h6, a.column-one.reverse.bold").css(
      "font-weight",
      "500"
    );
  }

  /*DOWNLOAD PDF*/

  /*
	$(document).on('click', '.download-pdf', function(e) {

		e.preventDefault();

		var href = $(this).attr('href');

		var post_id = $('.body').attr('post-id');

		var token = $('.body').attr('token');

		if(token){

			location.href = href + '?post_id=' + post_id + '&token=' + token;

		}

	});
	*/

  /*MENUS*/

  $(document).on("click", ".navbar-toggle", function (e) {
    e.preventDefault();
    $("#popular-mobile, #date-mobile, .pop-subscribe").toggle();
  });

  /*COMMENTS*/

  $(document).on("click", "a.share", function (e) {
    e.preventDefault();
    $(this).find(".click").hide();
    $(".comment-form").show();
  });


  $(document).on("click", "a.comment-button, a.comment-link", function (e) {
    e.preventDefault();
    $("a.share .click").hide();
    $(".comment-form").show();
    $("html, body").animate(
      {
        scrollTop: $(".comment-form").offset().top - 100,
      },
      1
    );
  });


  if (window.location.search.indexOf("mobile=true") > -1) {
    // Get the comment form
    var commentform = $("#commentform");
    // Add a Comment Status message
    commentform.prepend('<div id="comment-status" ></div>');
    // Defining the Status message element
    var statusdiv = $("#comment-status");
    commentform.submit(function (e) {
      e.preventDefault();

      if (!posting) {
        posting = true;

        // Serialize and store form data
        var formdata = commentform.serialize();
        //Add a status message
        statusdiv.html('<p class="ajax-placeholder">Processing...</p>');
        //Extract action URL from commentform
        var formurl = commentform.attr("action");
        //Post Form with data
        $.ajax({
          type: "post",
          url: formurl,
          data: formdata,
          dataType: "json", // Expect JSON response from admin-ajax
          error: function (XMLHttpRequest, textStatus, errorThrown) {
            posting = false;

            var errorMsg = "There was an error with your comment";
            try {
              var errorResponse = JSON.parse(XMLHttpRequest.responseText);
              if (errorResponse && errorResponse.data && errorResponse.data.message) {
                errorMsg = errorResponse.data.message;
              }
            } catch (e) {
              // If not JSON, use default message
            }

            var status = '<p class="ajax-error">' + errorMsg + '</p>';
            statusdiv.html(status);
          },
          success: function (response, textStatus) {
            posting = false;

            // Handle JSON response from admin-ajax
            if (response && response.success) {
              var status = '<p class="ajax-success">Thanks for your comment. We appreciate your response.</p>';
              statusdiv.html(status);
              commentform.find("textarea[name=comment]").val("");
            } else {
              var errorMsg = (response && response.data && response.data.message) 
                ? response.data.message 
                : "There was an error with your comment";
              var status = '<p class="ajax-error">' + errorMsg + '</p>';
              statusdiv.html(status);
            }
          },
        });
      }

      return false;
    });
  } else {
    $("form#commentform").submit(function (event) {
      // stop the form from submitting the normal way and refreshing the page
      event.preventDefault();

      $("#comment-status").html("");

      if ($(this).find("[name=g-recaptcha-response]").val()) {
        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
        var formData = $(this).serialize();

        // process the form
        $.ajax({
          type: "POST", // define the type of HTTP verb we want to use (POST for our form)
          url: $(this).attr("action"), // the url where we want to POST
          data: formData, // our data object
          dataType: "json", // Expect JSON response from admin-ajax
          encode: true,
          success: function (response) {
            // Handle JSON response from admin-ajax
            if (response && response.success) {
              // Comment submitted successfully
              location.reload();
            } else {
              // Error in response
              var errorMsg = (response && response.data && response.data.message) 
                ? response.data.message 
                : "There was a problem with your comment, please try again.";
              $("#comment-status").html('<p class="ajax-error">' + errorMsg + '</p>');
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
            console.log(xhr.responseText);

            // Try to parse JSON error response
            var errorMsg = "There was a problem with your comment, please try again.";
            try {
              var errorResponse = JSON.parse(xhr.responseText);
              if (errorResponse && errorResponse.data && errorResponse.data.message) {
                errorMsg = errorResponse.data.message;
              }
            } catch (e) {
              // If not JSON, use default message
            }

            $("#comment-status").html('<p class="ajax-error">' + errorMsg + '</p>');
          },
        });
      } else {
        $("#comment-status").html("Please solve the captcha.");
      }
    });
  }

  /*SUBSCRIBE AND SEARCH*/

  $(document).on("click", "input", function (e) {
    $(this).attr("placeholder", "");
  });

  $(document).on("click", "footer input", function (e) {
    $(this).css("paddingTop", "4px");
  });

  $(document).on("click", "footer input", function (e) {
    $(this).css("paddingTop", "4px");
  });

  /*SUBSCRIBE*/
  $(document).on("click", ".footer-subscribe", function (e) {
    e.preventDefault();

    var val = $(this).closest(".clr").find(".form-control").val();

    //location.href="/newsletter?email=" + encodeURI(val);
    location.href = "/subscription-form-2?f=newsletter&email=" + encodeURI(val);
  });

  $(".subscribe .form-control").keypress(function (e) {
    var keycode = event.keyCode ? event.keyCode : event.which;

    if (keycode == "13") {
      e.preventDefault();

      var val = $(this).val();

      //location.href="/subscribe?email=" + encodeURI(val);
      location.href =
        "/subscription-form-2/?f=newsletter&email=" + encodeURI(val);
    }
  });

  /*SEARCH*/

  $(document).on("click", ".magnify", function (e) {
    e.preventDefault();
    toggleUtil(".navbar.search", $(this));
  });

  $(document).on("click", ".desktop.dropdown-toggle, .overlay", function (e) {
    hideUtil();
  });

  $(document).on(
    "click",
    ".popup-magnify, .header-magnify, .footer-magnify",
    function (e) {
      e.preventDefault();

      //http://stage.luxuryroundtable.com/?s=sadf
      var val = $(this).closest(".clr").find(".form-control").val();

      location.href = "/?s=" + encodeURI(val);
    }
  );

  $(".search .form-control").keypress(function (e) {
    var keycode = event.keyCode ? event.keyCode : event.which;

    if (keycode == "13") {
      e.preventDefault();

      var val = $(this).val();

      location.href = "/?s=" + encodeURI(val);
    }
  });

  /*MOBILE*/
  $(document).on("click", ".navbar-toggle, .magnify", function (e) {
    if ($(this).hasClass("red")) {
      $(this).removeClass("red");
    } else {
      $(this).addClass("red");
    }
  });

  $(document).on("click", "#popular-mobile", function (e) {
    e.preventDefault();

    toggleUtil(".navbar.popular", $(this));
  });

  /*UTIL FUNCTIONS*/
  /*
  function toggleUtil(sel, caller) {
    if (!animating) {
      animating = true;

      var overlay = $(".overlay");

      var util = $(sel);

      //item is showing
      if (overlay.css("display") != "none") {
        var fold = $(".navbar.unfolded");

        var reopen = true;

        if (fold.hasClass(caller.attr("target"))) {
          reopen = false;
        } else {
        }

        fold.animate(
          {
            height: 0,
          },
          1000,
          function () {
            //item showing is current item
            if (!reopen) {
              util.removeClass("unfolded");

              overlay.fadeOut("fast", function () {
                util.css("height", "0");

                animating = false;
              });

              //item showing is not current item
            } else {
              fold.removeClass("unfolded");

              util.addClass("unfolded");

              util.animate(
                {
                  height: util.get(0).scrollHeight,
                },
                1000,
                function () {
                  animating = false;
                }
              );
            }
          }
        );

        //item is not showing
      } else {
        util.addClass("unfolded");

        util.css("height", "0px");

        util.css("width", window.innerWidth + "px");
        let fadeAnimation = true;
        overlay.fadeIn("fast", function () {
          fadeAnimation = false;
        });

        util.animate(
          {
            height: util.get(0).scrollHeight + 2,
          },
          1000,
          function () {
            if (!fadeAnimation) {
              animating = false;
            }
          }
        );
      }
    }
  }

  function hideUtil() {
    var overlay = $(".overlay");

    if (overlay.css("display") != "none") {
      var fold = $(".navbar.unfolded");

      fold.animate(
        {
          height: 0,
        },
        1000,
        function () {
          fold.removeClass("unfolded");

          overlay.fadeOut("fast");
        }
      );
    }
  }
  */

  /* VIDEOS */
  /*
  $(document).on("click", ".videos .collapsed a", function (e) {
    e.preventDefault();

    //get collapsed element
    var collapsed = $(this).closest(".collapsed");

    //get row
    var row = collapsed.attr("row");

    //get contents
    var contents = collapsed.html();

    //clear viewer
    $(".viewer").html("");

    //get closest viewer
    var selector = ".viewer[row=" + row + "]";
    var viewer = $(selector);

    viewer.html(contents);

    var a = viewer.find("a");

    a.hide();

    var iframe = viewer.find("iframe");

    var width = window.innerWidth - 60;

    if (width > 462) {
      width = 462;
    }

    height = (width * 9) / 16;

    iframe.attr("src", iframe.attr("no_src"));
    iframe.width(width);
    iframe.height(height);
    iframe.unwrap();

    var headline = viewer.find(".headline a").html();

    viewer.find(".headline").html(headline);

    //$('body').scrollTo(selector);

    //var p = $( "p:last" );

    var offset = viewer.offset();

    //p.html( "left: " + offset.left + ", top: " + offset.top );

    $("body").scrollTop(offset.top - 30);
  });
*/

  /* GALLERIES */
  $(document).on("load", ".viewer.main", function (e) {
    var viewer = $(this);

    var imgs = viewer.find(".slideshow-container-inner li img");

    if (imgs.length > 1) {
      $(".arrow").css("visibility", "visible");
    } else {
      $(".arrow").css("visibility", "hidden");
    }
  });

  $(document).on("click", ".galleries .collapsed a", function (e) {
    e.preventDefault();

    //get collapsed element
    var collapsed = $(this).closest(".collapsed");

    //get row
    var row = collapsed.attr("row");

    //get contents
    var contents = collapsed.html();

    //clear viewer
    $(".viewer").html("");

    //get closest viewer
    var selector = ".viewer[row=" + row + "]";
    var viewer = $(selector);

    viewer.hide();

    viewer.html(contents);

    viewer.find(".img-container").hide();

    viewer.find(".slideshow-container-inner ul").css("left", "0");

    viewer.find(".slideshow-container-inner .left-arrow").css("opacity", "0.5");

    var imgs = viewer.find(".slideshow-container-inner li img");

    if (imgs.length > 1) {
      $(".arrow").css("visibility", "visible");
    } else {
      $(".arrow").css("visibility", "hidden");
    }

    viewer.find(".slideshow-container").show();

    viewer.show();

    //$(document).scrollTo(selector);
  });

  $(document).on(
    "click",
    ".galleries .left-arrow:not(.disabled)",
    function (e) {
      e.preventDefault();

      var elm = $(this);

      var slideshow = $(this).closest(".slideshow-container");

      var inner_ul = slideshow.find(".slideshow-container-inner ul");

      var right = slideshow.find(".right-arrow");

      inner_ul.animate(
        {
          left: "+=420",
        },
        1000,
        function () {
          if (parseInt(inner_ul.css("left"), 10) >= 0) {
            elm.addClass("disabled");
            right.removeClass("disabled");
          }
        }
      );
    }
  );

  $(document).on(
    "click",
    ".galleries .right-arrow:not(.disabled)",
    function (e) {
      e.preventDefault();

      var elm = $(this);

      var slideshow = $(this).closest(".slideshow-container");

      var inner_ul = slideshow.find(".slideshow-container-inner ul");

      var left = slideshow.find(".left-arrow");

      var count = inner_ul.find("img").length;

      var max_left = count * -420 + 420;

      inner_ul.animate(
        {
          left: "-=420",
        },
        1000,
        function () {
          var ul_left = parseInt(inner_ul.css("left"), 10);

          //console.log(ul_left + '<=' + max_left);

          if (ul_left <= max_left) {
            //console.log('lock');

            elm.addClass("disabled");
          }

          left.removeClass("disabled");
        }
      );
    }
  );
});

$(document).on("click", ".dropdown-toggle", function (e) {
  e.preventDefault();
  console.log("click");
});

function setCookie(cname, cvalue) {
  var d = new Date();
  d.setTime(d.getTime() + 100 * 24 * 60 * 60 * 1000);
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(";");
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

// The debounce function receives our function as a parameter
const debounce = (fn) => {
  // This holds the requestAnimationFrame reference, so we can cancel it if we wish
  let frame;

  // The debounce function returns a new function that can receive a variable number of arguments
  return (...params) => {
    // If the frame variable has been defined, clear it now, and queue for next frame
    if (frame) {
      cancelAnimationFrame(frame);
    }

    // Queue our function call for the next frame
    frame = requestAnimationFrame(() => {
      // Call our function and pass any params we received
      fn(...params);
    });
  };
};

// Reads out the scroll position and stores it in the data attribute
// so we can use it in our stylesheets
/*
const storeScroll = () => {
  const scrollY = window.scrollY;
  let lastHeroTop = 0;

  if ($("#hero-section-7").position()) {
    lastHeroTop = $("#hero-section-7").position().top;
  }

  if (scrollY >= lastHeroTop) {
    $("body").removeClass("home");
  } else {
    if (!$("body").hasClass("home")) {
      $("body").addClass("home");
    }
  }
};

// Listen for new scroll events, here we debounce our `storeScroll` function
document.addEventListener("scroll", debounce(storeScroll), { passive: true });

// Update scroll position for first time
storeScroll();
*/