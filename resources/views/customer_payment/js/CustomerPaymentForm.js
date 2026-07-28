import {
    ajaxPostRequest,
    ajaxGetRequest,
} from "../../../../../js/common-methods/http-requests.js";
import {
    errorMessage,
    successMessage,
} from "../../../../../js/common-methods/toasters.js";

$(document).ready(function () {
    $("#customer").select2();
    $("#account_id").select2();
    $("#tax_account_id").select2();
    $("#customer_id").select2();
});

$("body").on("change", "#tax,#sub_total", function (event) {
    var amount = $("#sub_total").val();
    var taxPrecent = $("#tax").val();
    var sub = 0;
    var tax_amount = 0;
    var taxRate = 0;
    var tax = taxPrecent / 100;
    if (tax > 0) {
        taxRate = tax;
        tax_amount = amount * taxRate;
        sub = amount - tax_amount;
    } else {
        sub = amount;
    }

    $("#sub_total").val(sub);
    $("#total").val(amount);
    $("#tax_amount").val(tax_amount);
});
$(function () {
  // Click to Button

  $("#createNewPayment").click(function () {
    $("#saveBtn").val("creating..");
    $("#saveBtn").show();
    $("#customer_id").removeAttr("disabled");
    $("#account_id").removeAttr("disabled");
    $("#payment_date").removeAttr("disabled");
    $("#refernce").removeAttr("disabled");
    $("#sub_total").removeAttr("disabled");
    $("#tax").removeAttr("disabled");
    $("#tax_account_id").removeAttr("disabled");
    $("#CustomerPaymentForm").trigger("reset");
    $("#modelHeading").html(t("create_new") + " " + t("customer_payment"));
    $("#saveBtn").removeAttr("disabled");
    $("#close").removeAttr("disabled");
    $("#ajaxModel").modal("show");
  });

  // Click to Edit Button

  $("body").on("click", "#editCustomerPayment", function () {
    var customer_payment_id = $(this).data("id");
    ajaxGetRequest(
      url_local + "/customer-payment/edit" + "/" + customer_payment_id
    )
      .then(function (data) {
        const form = document.getElementById("customerPaymentForm");
        for (let index = 0; index < form.length; index++) {
          const element = form[index];
          if (element && element.value != "Save" && element.name != "id")
            element.value = data[element.name];
          if (element.name == "id") element.value = data.id;
        }
        $("#saveBtn").show();
        $("#saveBtn").removeAttr("disabled");
        $("#close").removeAttr("disabled");
        $("#ajaxModel").modal("show");
      })
      .catch(function (err) {
        errorMessage(err.Message);
      });
  });

  // Click to View Button

  $("body").on("click", "#viewCustomerPayment", function () {
    var customer_payment_id = $(this).data("id");
    ajaxGetRequest(
      url_local + "/customer-payment/edit" + "/" + customer_payment_id
    )
      .then(function (data) {
        const form = document.getElementById("customerPaymentForm");
        for (let index = 0; index < form.length; index++) {
          const element = form[index];
          if (element && element.value != "Save" && element.name != "id")
            element.disabled = "disabled";
          element.value = data[element.name];
          if (element.name == "id") element.value = data.id;
        }
        $("#saveBtn").hide();
        $("#close").removeAttr("disabled");
        $("#ajaxModel").modal("show");
      })
      .catch(function (err) {
        errorMessage(err.Message);
      });
  });

  // Create Code

  $("#customerPaymentForm").submit(function (e) {
    e.preventDefault();

    ajaxPostRequest(
      url_local + "/customer-payment/store",
      $("#customerPaymentForm").serialize()
    )
      .then(function (data) {
        $("#customerPaymentForm").trigger("reset");
        $("#ajaxModel").modal("hide");
        initDataTablecustomer_payment_table();
      })
      .catch(function (err) {
        errorMessage(err.Message);
      });
  });

  // Delete Customer Payment Code

  $("body").on("click", "#DeleteCustomerPayment", function () {
    var customer_payment_id = $(this).data("id");
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!",
    }).then((result) => {
      if (result) {
        ajaxGetRequest(
          url_local + "/customer-payment/destroy" + "/" + customer_payment_id
        )
          .then(function (data) {
            successMessage(data.Message);
            initDataTablecustomer_payment_table();
          })
          .catch(function (err) {
            errorMessage(err.Message);
          });
      }
    });
  });
});
