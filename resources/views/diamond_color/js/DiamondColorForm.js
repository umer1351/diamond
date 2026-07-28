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

  $("#createNewDiamondColor").click(function () {
    $("#id").val("");
    $("#diamondColorForm").trigger("reset");
    $("#modelHeading").html(t("create_new") + " " + t("diamond_color"));
    $("#ajaxModel").modal("show");
  });

  // Click to Edit Button

  $("body").on("click", "#editDiamondColor", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var diamond_color_id = $(this).data("id");
    ajaxGetRequest(url_local + "/diamond-color/edit" + "/" + diamond_color_id)
        .then(function (data) {
            $("#modelHeading").html(t("edit") + " " + t("diamond_color"));
            const form = document.getElementById("diamondColorForm");
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

  $("#diamondColorForm").submit(function (e) {
    e.preventDefault();
    ajaxPostRequest(
        url_local + "/diamond-color/store",
        $("#diamondColorForm").serialize()
    )
        .then(function (data) {
            $("#diamondColorForm").trigger("reset");
            $("#ajaxModel").modal("hide");
            initDataTablediamond_color_table();
        })
        .catch(function (err) {
            errorMessage(err.message);
        });
  });

  //Status

  $("body").on("click", "#status", function () {
    var diamond_color_id = $(this).data("id");
    ajaxGetRequest(
      url_local + "/diamond-color/status" + "/" + diamond_color_id
    )
      .then(function (data) {
        successMessage(data.Message);
        initDataTablediamond_color_table();
      })
      .catch(function (err) {
        errorMessage(err.message);
      });
  });
  // Delete Code

  $("body").on("click", "#deleteDiamondColor", function () {
    var diamond_color_id = $(this).data("id");
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
          url_local + "/diamond-color/destroy" + "/" + diamond_color_id
        )
          .then(function (data) {
            successMessage(data.Message);
            initDataTablediamond_color_table();
          })
          .catch(function (err) {
            errorMessage(err.message);
          });
      }
    });
  });
});
