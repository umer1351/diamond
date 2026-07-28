import {
    ajaxPostRequest,
    ajaxGetRequest,
} from "../../../../../js/common-methods/http-requests.js";
import {
    errorMessage,
    successMessage,
} from "../../../../../js/common-methods/toasters.js";

$(document).ready(function () {
    $("#supplier").select2();
    $("#account_id").select2();
    $("#tax_account_id").select2();
    $("#supplier_id").select2();
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
    $("#supplier_id").removeAttr("disabled");
    $("#account_id").removeAttr("disabled");
    $("#payment_date").removeAttr("disabled");
    $("#cheque_ref").removeAttr("disabled");
    $("#sub_total").removeAttr("disabled");
    $("#tax").removeAttr("disabled");
    $("#tax_account_id").removeAttr("disabled");
    $("#SupplierPaymentForm").trigger("reset");
    $("#modelHeading").html(t("create_new") + " " + t("supplier_payment"));
    $("#saveBtn").removeAttr("disabled");
    $("#close").removeAttr("disabled");
    $("#ajaxModel").modal("show");
  });

  // Click to Edit Button

  $("body").on("click", "#editSupplierPayment", function () {
    var supplier_id = $(this).data("id");
    ajaxGetRequest(
      url_local + "/supplier-payment/edit" + "/" + supplier_id
    )
      .then(function (data) {
        const form = document.getElementById("SupplierPaymentForm");
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

  $("body").on("click", "#viewSupplierPayment", function () {
    var supplier_id = $(this).data("id");
    ajaxGetRequest(
      url_local + "/supplier-payment/edit" + "/" + supplier_id
    )
      .then(function (data) {
        const form = document.getElementById("SupplierPaymentForm");
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

  $("#supplierPaymentForm").submit(function (e) {
    e.preventDefault();

    ajaxPostRequest(
      url_local + "/supplier-payment/store",
      $("#supplierPaymentForm").serialize()
    )
      .then(function (data) {
        $("#supplierPaymentForm").trigger("reset");
        $("#ajaxModel").modal("hide");
        initDataTablesupplier_payment_table();
      })
      .catch(function (err) {
        errorMessage(err.Message);
      });
  });

  // Delete Supplier Payment Code

  $("body").on("click", "#DeleteSupplierPayment", function () {
    var supplier_payment_id = $(this).data("id");
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
          url_local + "/supplier-payment/destroy" + "/" + supplier_payment_id
        )
          .then(function (data) {
            successMessage(data.Message);
            initDataTablesupplier_payment_table();
          })
          .catch(function (err) {
            errorMessage(err.Message);
          });
      }
    });
  });
});
