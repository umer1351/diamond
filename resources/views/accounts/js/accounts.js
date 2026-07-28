import {
  ajaxPostRequest,
  ajaxGetRequest,
} from "../../../../../js/common-methods/http-requests.js";
import {
  errorMessage,
  successMessage,
} from "../../../../../js/common-methods/toasters.js";

$(function () {
  loadMainAccount();
  let level = 3;
  let noOfdigits = 0;
  let codeStr = "";

  for (let i = 0; i < level; i++) {
    let jjlength = 0;

    if (noOfdigits == 0) jjlength = i + 1;

    for (let j = 0; j < jjlength; j++) {
      codeStr += "9";
    }

    if (i + 1 < level) codeStr += "-";
  }

  // $("#code").mask(codeStr);

  $("#openAddEditAccountModal").click(function (event) {
    event.preventDefault();
    $("#accountForm").trigger("reset");
    $("#accountForm #id").val("");
    $("#addEditAccountModal").modal("show");
  });

  $("#appendMainAccounts").on(
    "click",
    ".openAddChildAccount",
    function (event) {
      event.preventDefault();
      $("#modelHeading").html("Create Account");
      $("#accountChildForm").trigger("reset");
      $("#is_cash_account").prop("checked", false);
      $("#accountChildForm #id").val("");
      $("#addEditAccountChildModal").modal("show");
      $("#accountChildForm #parent_id").val($(this).data("parent-id"));
      $("#accountChildForm #account_type_id").val(
        $(this).data("account-type-id")
      );
    }
  );
});

// Show Children On Click of Main Accounts
$(document).on("click", "#appendMainAccounts .showchildren", function () {
  var account_id = $(this).data("id");

  ajaxGetRequest("accounts/getChildAccounts/" + account_id)
    .then(function (data) {
      $("#collapse-" + account_id + " .card-body").html(data);
    })
    .catch(function (err) {
      // Run this when promise was rejected via reject()
      console.log(err.Message);
      // toastr.error(err.message, "Status", {
      //     showMethod: "slideDown",
      //     hideMethod: "slideUp",
      //     timeOut: 2e3,
      // });
    });
});

$("#accountForm").submit(function (event) {
  event.preventDefault();
  console.log($(this).serializeArray())
  ajaxPostRequest("accounts/addEditAccount", $(this).serializeArray())
    .then(function (results) {
      $("#addEditAccountModal").modal("hide");
      loadMainAccount();
    })
    .catch(function (err) {
      errorMessage(err.Message);
    });
});

$("#accountChildForm").submit(function (event) {
  event.preventDefault();
  console.log($(this).serializeArray())
  ajaxPostRequest("accounts/addEditAccount", $(this).serializeArray())
    .then(function (results) {
      $("#addEditAccountChildModal").modal("hide");
      loadMainAccount();
    })
    .catch(function (err) {
      errorMessage(err.Message);
    });
});

$("#appendMainAccounts").on(
  "click",
  "#editParentAccount",
  function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    ajaxGetRequest($(this).attr("href"))
      .then(function (data) {
        console.log(data.parent_id);

        if (data.parent_id == 0) {
          $("#accountForm #id").val(data.id);
          $("#accountForm #parent_id").val(data.parent_id);
          $("#accountForm #account_type_id").val(data.account_type_id);
          $("#accountForm #code").val(data.code);
          $("#accountForm #name").val(data.name);
          $("#accountForm #opening_balance").val(data.opening_balance);
          $("#addEditAccountModal #modelHeading").html("Edit Account");
          if (data.is_cash_account == 1) {
            $("#accountForm #is_cash_account").prop("checked", true);
          } else {
            $("#accountForm #is_cash_account").prop("checked", false);
          }
          $("#addEditAccountModal").modal("show");
        } else {
          $("#accountChildForm #id").val(data.id);
          $("#accountChildForm #parent_id").val(data.parent_id);
          $("#accountChildForm #account_type_id").val(data.account_type_id);
          $("#accountChildForm #code").val(data.code);
          $("#accountChildForm #name").val(data.name);
          $("#accountChildForm #opening_balance").val(data.opening_balance);
          $("#addEditAccountChildModal #modelHeading").html("Edit Account");
          if (data.is_cash_account == 1) {
            $("#accountChildForm #is_cash_account").prop("checked", true);
          } else {
            $("#accountChildForm #is_cash_account").prop("checked", false);
          }
          $("#addEditAccountChildModal").modal("show");
        }
      })
      .catch(function (err) {
        errorMessage(err.Message);
      });
  }
);

function loadMainAccount() {
  ajaxGetRequest("accounts/getMainAccounts")
    .then(function (data) {
      $("#appendMainAccounts").html(data);
    })
    .catch(function (err) {
      errorMessage(err.Message);
    });
}

$("body").on("click", "#is_active", function () {
  var account_id = $(this).data("id");
  $("#preloader").show();
  ajaxGetRequest("accounts/statusAccounts/" + account_id)
    .then(function (data) {
      if (data) {
        $("#preloader").hide();
        successMessage(data.Message);
        loadMainAccount();
      } else {

      }
    })
    .catch(function (err) {
      // Run this when promise was rejected via reject()
      errorMessage(err.Message);
    });
});

// Delete Account Code

$("body").on("click", "#deleteChartAccount", function () {
  var account_id = $(this).data("id");
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      ajaxGetRequest("accounts/deleteAccounts" + "/" + account_id)
        .then(function (data) {
          successMessage(data.Message);
          loadMainAccount();
        })
        .catch(function (err) {
          errorMessage(err.Message);
        });
    }
  });
});
