import {
  ajaxPostRequest,
  ajaxGetRequest,
} from "../../../../../js/common-methods/http-requests.js";
import {
  errorMessage,
  successMessage,
} from "../../../../../js/common-methods/toasters.js";

$(function () {
  //Click to Button

  $("#createNewOtherProduct").click(function () {
    $("#id").val("");
    $("#otherProductForm").trigger("reset");
    $("#modelHeading").html("Create New Other Product");
    $("#ajaxModel").modal("show");
  });

  // Click to Edit Button

  $("body").on("click", "#editOtherProduct", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var other_product_id = $(this).data("id");
    ajaxGetRequest(url_local + "/other-product/edit" + "/" + other_product_id)
      .then(function (data) {
        $("#modelHeading").html("Edit Other Product");
        const form = document.getElementById("otherProductForm");
        for (let index = 0; index < form.length; index++) {
          const element = form[index];
          if (element && element.value != "Save" && element.name != "id")
            element.value = data[element.name];
          if (element.name == "id") element.value = data.id;
        }
        $("#ajaxModel").modal("show");
      })
      .catch(function (err) {
        // Run this when promise was rejected via reject()
        errorMessage(err.message);
      });
  });

  // Save/Update OtherProduct Code

  $("#otherProductForm").submit(function (e) {
    e.preventDefault();
    ajaxPostRequest(
      url_local + "/other-product/store",
      $("#otherProductForm").serialize()
    )
      .then(function (data) {
        $("#otherProductForm").trigger("reset");
        $("#ajaxModel").modal("hide");
        initDataTableother_product_table();
      })
      .catch(function (err) {
        errorMessage(err.message);
      });
  });

  //Status

  $("body").on("click", "#status", function () {
    var other_product_id = $(this).data("id");
    ajaxGetRequest(
      url_local + "/other-product/status" + "/" + other_product_id
    )
      .then(function (data) {
        successMessage(data.Message);
        initDataTableother_product_table();
      })
      .catch(function (err) {
        errorMessage(err.message);
      });
  });
  // Delete OtherProduct Code

  $("body").on("click", "#deleteOtherProduct", function () {
    var other_product_id = $(this).data("id");
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
        ajaxGetRequest(
          url_local + "/other-product/destroy" + "/" + other_product_id
        )
          .then(function (data) {
            successMessage(data.Message);
            initDataTableother_product_table();
          })
          .catch(function (err) {
            errorMessage(err.message);
          });
      }
    });
  });
});
