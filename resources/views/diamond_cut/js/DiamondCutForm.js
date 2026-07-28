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

  $("#createNewDiamondCut").click(function () {
    $("#id").val("");
    $("#diamondCutForm").trigger("reset");
    $("#modelHeading").html(t("create_new") + " " + t("diamond_cut"));
    $("#ajaxModel").modal("show");
  });

  // Click to Edit Button

  $("body").on("click", "#editDiamondCut", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var diamond_cut_id = $(this).data("id");
    ajaxGetRequest(url_local + "/diamond-cut/edit" + "/" + diamond_cut_id)
        .then(function (data) {
            $("#modelHeading").html(t("edit") + " " + t("diamond_cut"));
            const form = document.getElementById("diamondCutForm");
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

  $("#diamondCutForm").submit(function (e) {
    e.preventDefault();
    ajaxPostRequest(
        url_local + "/diamond-cut/store",
        $("#diamondCutForm").serialize()
    )
        .then(function (data) {
            $("#diamondCutForm").trigger("reset");
            $("#ajaxModel").modal("hide");
            initDataTablediamond_cut_table();
        })
        .catch(function (err) {
            errorMessage(err.message);
        });
  });

  //Status

  $("body").on("click", "#status", function () {
    var diamond_cut_id = $(this).data("id");
    ajaxGetRequest(
      url_local + "/diamond-cut/status" + "/" + diamond_cut_id
    )
      .then(function (data) {
        successMessage(data.Message);
        initDataTablediamond_cut_table();
      })
      .catch(function (err) {
        errorMessage(err.message);
      });
  });
  // Delete Code

  $("body").on("click", "#deleteDiamondCut", function () {
    var diamond_cut_id = $(this).data("id");
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
          url_local + "/diamond-cut/destroy" + "/" + diamond_cut_id
        )
          .then(function (data) {
            successMessage(data.Message);
            initDataTablediamond_cut_table();
          })
          .catch(function (err) {
            errorMessage(err.message);
          });
      }
    });
  });
});
