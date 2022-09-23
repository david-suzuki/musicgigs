$(document).ready(function () {
  $(".modal-close-btn").on("click", function () {
    const modal_id = $(this).closest(".modal").attr("id");
    $(`#${modal_id}`).modal("toggle");
  });

  $("#signin_btn").on("click", function () {
    const useremail = $("#useremail").val();
    const userpass = $("#userpass").val();

    jQuery.ajax({
      type: "post",
      url: myAjax.ajaxurl,
      data: {
        useremail,
        userpass,
        action: "user_signin",
      },
      success: function (response) {
        if (response === "success") {
          document.location.reload();
        } else {
          $("#signin_alert").empty();
          $("#signin_alert").css("display", "block");
          $("#signin_alert").append(response);
          $("#signin_alert").append(
            '<button type="button" class="btn-close" aria-label="Close" onclick="closeAlert(this)"></button>'
          );
        }
      },
      error: function (err) {
        console.log(err);
      },
    });
  });

  $("#signup_btn").on("click", function () {
    const userrole = $('input[name="user_role"]:checked').val();
    const username = $("#user_name").val();
    const useremail = $("#user_email").val();
    const userpass = $("#user_pass").val();
    const confirmpass = $("#confirm_pass").val();

    if (userpass !== confirmpass) {
      $("#signup_alert").empty();
      $("#signup_alert").css("display", "block");
      $("#signup_alert").append(
        "Password and Confirm password must be matched."
      );
      $("#signup_alert").append(
        '<button type="button" class="btn-close" aria-label="Close" onclick="closeAlert(this)"></button>'
      );
      return;
    }

    jQuery.ajax({
      type: "post",
      url: myAjax.ajaxurl,
      data: {
        userrole,
        username,
        useremail,
        userpass,
        action: "user_signup",
      },
      success: function (response) {
        if (response === "success") {
          document.location.reload();
        } else {
          $("#signup_alert").empty();
          $("#signup_alert").css("display", "block");
          $("#signup_alert").append(response);
          $("#signup_alert").append(
            '<button type="button" class="btn-close" aria-label="Close" onclick="closeAlert(this)"></button>'
          );
        }
      },
      error: function (err) {
        console.log(err);
      },
    });
  });
});

function openSignupModal() {
  $("#signinModal").modal("hide");
  $("#signupModal").modal("show");
}

function openSigninModal() {
  $("#signinModal").modal("show");
  $("#signupModal").modal("hide");
}

function closeAlert(ele) {
  $(ele).parent().css("display", "none");
}
