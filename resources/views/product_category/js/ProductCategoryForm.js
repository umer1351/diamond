import {
  ajaxPostRequest,
  ajaxGetRequest,
} from "../../../../../js/common-methods/http-requests.js";
import {
  errorMessage,
  successMessage,
} from "../../../../../js/common-methods/toasters.js";

$(function () {
  $("#createNewProductCategory").click(function () {
    $("#id").val("");
    $("#productCategoryForm").trigger("reset");
    $("#modelHeading").html("Create New Category");
    $("#imagePreview").attr("src", "").hide();
    $("#removeImageWrap").hide();
    $("#remove_image").prop("checked", false);
    $("#ajaxModel").modal("show");
  });

  // Live preview when a new thumbnail is chosen (add / update).
  $("body").on("change", "#image_file", function () {
    const file = this.files && this.files[0];
    if (file) {
      $("#imagePreview").attr("src", URL.createObjectURL(file)).show();
      $("#remove_image").prop("checked", false);
    }
  });

  $("body").on("click", "#editProductCategory", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var product_category_id = $(this).data("id");
    ajaxGetRequest(url_local + "/product-categories/edit" + "/" + product_category_id)
      .then(function (data) {
        $("#modelHeading").html("Edit Category");
        const form = document.getElementById("productCategoryForm");
        for (let index = 0; index < form.length; index++) {
          const element = form[index];
          if (! element || element.name === "image_file") {
            continue;
          }

          if (element.value !== "Save" && element.name !== "id") {
            element.value = data[element.name] ?? "";
          }

          if (element.name === "id") {
            element.value = data.id;
          }
        }

        $("#remove_image").prop("checked", false);

        if (data.image_url) {
          $("#imagePreview").attr("src", data.image_url).show();
          $("#removeImageWrap").show();
        } else {
          $("#imagePreview").attr("src", "").hide();
          $("#removeImageWrap").hide();
        }

        $("#ajaxModel").modal("show");
      })
      .catch(function (err) {
        errorMessage(err.message || err.Message || "Request failed");
      });
  });

  $("#productCategoryForm").submit(function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    ajaxPostRequest(url_local + "/product-categories/store", formData, {
      processData: false,
      contentType: false,
    })
      .then(function () {
        $("#productCategoryForm").trigger("reset");
        $("#imagePreview").attr("src", "").hide();
        $("#removeImageWrap").hide();
        $("#remove_image").prop("checked", false);
        $("#ajaxModel").modal("hide");
        successMessage("Category saved successfully.");
        initDataTableproduct_category_table();
      })
      .catch(function (err) {
        errorMessage(err.message || err.Message || "Request failed");
      });
  });

  $("body").on("click", "#status", function () {
    var product_category_id = $(this).data("id");
    ajaxGetRequest(url_local + "/product-categories/status" + "/" + product_category_id)
      .then(function (data) {
        successMessage(data.Message);
        initDataTableproduct_category_table();
      })
      .catch(function (err) {
        errorMessage(err.message || err.Message || "Request failed");
      });
  });
});
