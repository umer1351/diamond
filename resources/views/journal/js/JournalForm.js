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

  $("#createNewJournal").click(function () {
    $("#id").val("");
    $("#journalForm").trigger("reset");
    $("#modelHeading").html("Create New Journal");
    $("#ajaxModel").modal("show");
  });

  // Click to Edit Button

  $("body").on("click", "#editJournal", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var journal_id = $(this).data("id");
    ajaxGetRequest(url_local + "/journals/edit" + "/" + journal_id)
      .then(function (data) {
        $("#modelHeading").html("Edit Journal");
        const form = document.getElementById("journalForm");
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

  // Save/Update Journal Code

  $("#journalForm").submit(function (e) {
    e.preventDefault();
    ajaxPostRequest(
      url_local + "/journals/store",
      $("#journalForm").serialize()
    )
      .then(function (data) {
        $("#journalForm").trigger("reset");
        $("#ajaxModel").modal("hide");
        initDataTablejournal_table();
      })
      .catch(function (err) {
        errorMessage(err.message);
      });
  });

  //Status

  $("body").on("click", "#status", function () {
    var journal_id = $(this).data("id");
    ajaxGetRequest(
      url_local + "/journals/status" + "/" + journal_id
    )
      .then(function (data) {
        successMessage(data.Message);
        initDataTablejournal_table();
      })
      .catch(function (err) {
        errorMessage(err.message);
      });
  });
  // Delete Journal Code

  $("body").on("click", "#deleteJournal", function () {
    var journal_id = $(this).data("id");
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
          url_local + "/journals/destroy" + "/" + journal_id
        )
          .then(function (data) {
            successMessage(data.Message);
            initDataTablejournal_table();
          })
          .catch(function (err) {
            errorMessage(err.message);
          });
      }
    });
  });
});
