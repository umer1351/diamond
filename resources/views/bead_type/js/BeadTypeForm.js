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

  $("#createNewBeadType").click(function () {
    $("#id").val("");
    $("#beadTypeForm").trigger("reset");
    $("#modelHeading").html(t("create_new") + " " + t("bead_types"));
    $("#ajaxModel").modal("show");
  });

  // Click to Edit Button

  $("body").on("click", "#editBeadType", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var bead_type_id = $(this).data("id");
    ajaxGetRequest(url_local + "/bead-type/edit" + "/" + bead_type_id)
        .then(function (data) {
            $("#modelHeading").html(t("edit") + " " + t("bead_types"));
            const form = document.getElementById("beadTypeForm");
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

  $("#beadTypeForm").submit(function (e) {
    e.preventDefault();
    ajaxPostRequest(
        url_local + "/bead-type/store",
        $("#beadTypeForm").serialize()
    )
        .then(function (data) {
            $("#beadTypeForm").trigger("reset");
            $("#ajaxModel").modal("hide");
            initDataTablebead_type_table();
        })
        .catch(function (err) {
            errorMessage(err.message);
        });
  });

  //Status

  $("body").on("click", "#status", function () {
    var bead_type_id = $(this).data("id");
    ajaxGetRequest(
      url_local + "/bead-type/status" + "/" + bead_type_id
    )
      .then(function (data) {
        successMessage(data.Message);
        initDataTablebead_type_table();
      })
      .catch(function (err) {
        errorMessage(err.message);
      });
  });
  // Delete Code

  $("body").on("click", "#deleteBeadType", function () {
    var bead_type_id = $(this).data("id");
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
          url_local + "/bead-type/destroy" + "/" + bead_type_id
        )
          .then(function (data) {
            successMessage(data.Message);
            initDataTablebead_type_table();
          })
          .catch(function (err) {
            errorMessage(err.message);
          });
      }
    });
  });
});
