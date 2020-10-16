(function($) {
  var ajUrl = ajObj.url;
  var ajax_nonce = ajObj.nonce;

  $(".edit-email__link").on("click", function(e) {
    $(this)
      .next("div")
      .slideToggle("slow");
  });

  $("#j-up-profile").on("submit", function(e) {
    $(".animate-svg").addClass("show");

    e.preventDefault();
    var formData = new FormData(document.getElementById("j-up-profile"));
    formData.append("action", "j_update_user_profile");
    formData.append("security", ajax_nonce);

    $.ajax({
      url: ajUrl,
      data: formData,
      type: "POST",
      contentType: false,
      processData: false,
      success: function(data) {
        if (data.success) {
          showToast(data.msg, "suc");
        }
        $(".animate-svg").removeClass("show");
      },
      error: function(err, textStatus, errorThrown) {
        err = JSON.parse(err.responseText);
        showToast(err.data, "err");
        $(".animate-svg").removeClass("show");
      }
    });
  });

  function showToast(msg = "", status = "") {
    var mainClass;

    if (status == "suc") {
      mainClass = "success-info";
    } else if (status == "err") {
      mainClass = "error-info";
    }

    var div = document.createElement("div");
    var msg = document.createTextNode(msg);
    div.setAttribute("class", `toast-msg ${mainClass}`);
    div.appendChild(msg);
    $(".toast-msg-container").append(div);
    $(".edit-email__link")
      .next("div")
      .slideUp("slow");
    setTimeout(() => {
      $(div).remove();
    }, 1500);
  }
})(jQuery);
