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

  $("#createNewStoneCategory").click(function () {
    $("#id").val("");
    $("#stoneCategoryForm").trigger("reset");
    $("#modelHeading").html(t("create_new") + " " + t("stone_category"));
    $("#ajaxModel").modal("show");
  });

  // Click to Edit Button

  $("body").on("click", "#editStoneCategory", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var stone_category_id = $(this).data("id");
    ajaxGetRequest(url_local + "/stone-category/edit" + "/" + stone_category_id)
        .then(function (data) {
            $("#modelHeading").html(t("edit") + " " + t("stone_category"));
            const form = document.getElementById("stoneCategoryForm");
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

  // Save/Update Code

  $("#stoneCategoryForm").submit(function (e) {
    e.preventDefault();
    ajaxPostRequest(
        url_local + "/stone-category/store",
        $("#stoneCategoryForm").serialize()
    )
        .then(function (data) {
            $("#stoneCategoryForm").trigger("reset");
            $("#ajaxModel").modal("hide");
            initDataTablestone_category_table();
        })
        .catch(function (err) {
            errorMessage(err.message);
        });
  });

  //Status

  $("body").on("click", "#status", function () {
    var stone_category_id = $(this).data("id");
    ajaxGetRequest(
      url_local + "/stone-category/status" + "/" + stone_category_id
    )
      .then(function (data) {
        successMessage(data.Message);
        initDataTablestone_category_table();
      })
      .catch(function (err) {
        errorMessage(err.message);
      });
  });
  // Delete Code

  $("body").on("click", "#deleteBeadType", function () {
    var stone_category_id = $(this).data("id");
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
          url_local + "/stone-category/destroy" + "/" + stone_category_id
        )
          .then(function (data) {
            successMessage(data.Message);
            initDataTablestone_category_table();
          })
          .catch(function (err) {
            errorMessage(err.message);
          });
      }
    });
  });
});
