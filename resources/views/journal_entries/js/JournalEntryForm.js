import {
    ajaxPostRequest,
    ajaxGetRequest,
} from "../../../../../js/common-methods/http-requests.js";
import {
    errorMessage,
    successMessage,
} from "../../../../../js/common-methods/toasters.js";

$(function () {
    
    $("body").on("click", "#deleteJournalEntry", function () {
        var journal_entry_id = $(this).data("id");
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
                    url_local +
                        "/journal-entries/destroy" +
                        "/" +
                        journal_entry_id
                )
                    .then(function (data) {
                        successMessage(data.Message);
                        initDataTablejournal_entry_table();
                    })
                    .catch(function (err) {
                        errorMessage(err.Message);
                    });
            }
        });
    });
    
    
});
