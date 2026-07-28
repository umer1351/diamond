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

  $("#createNewProduct").click(function () {
    $("#product_id").val("");
    $("#productForm").trigger("reset");
    $("#modelHeading").html("Create New Product");
    $("#ajaxModel").modal("show");
  });

  // Click to Edit Button

  $("body").on("click", "#editProduct", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var product_id = $(this).data("id");
    ajaxGetRequest(url_local + "/products/edit" + "/" + product_id)
      .then(function (data) {
        $("#modelHeading").html("Edit Product");
        const form = document.getElementById("productForm");
        for (let index = 0; index < form.length; index++) {
          const element = form[index];
          if (element && element.type === "file") continue;
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

  // Save/Update Product Code

  $("#productForm").submit(function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    ajaxPostRequest(url_local + "/products/store", formData, {
      processData: false,
      contentType: false,
    })
      .then(function (data) {
        $("#productForm").trigger("reset");
        $("#ajaxModel").modal("hide");
        initDataTableproduct_table();
      })
      .catch(function (err) {
        errorMessage(err.message);
      });
  });

  //Status

  $("body").on("click", "#status", function () {
    var product_id = $(this).data("id");
    ajaxGetRequest(
      url_local + "/products/status" + "/" + product_id
    )
      .then(function (data) {
        successMessage(data.Message);
        initDataTableproduct_table();
      })
      .catch(function (err) {
        errorMessage(err.message);
      });
  });
  // Delete product Code

  $("body").on("click", "#deleteProduct", function () {
    var product_id = $(this).data("id");
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
          url_local + "/products/destroy" + "/" + product_id
        )
          .then(function (data) {
            successMessage(data.Message);
            initDataTableproduct_table();
          })
          .catch(function (err) {
            errorMessage(err.message);
          });
      }
    });
  });
});
