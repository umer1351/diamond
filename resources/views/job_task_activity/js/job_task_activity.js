function error(message) {
    toastr.error(message, "Error", {
        showMethod: "slideDown",
        hideMethod: "slideUp",
        timeOut: 2e3,
    });
}

function success(message) {
    toastr.success(message, "Success", {
        showMethod: "slideDown",
        hideMethod: "slideUp",
        timeOut: 2e3,
    });
}
function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

$("body").on("click", "#submit", function (e) {
    e.preventDefault();

    $("#submit").hide();
    // Validation logic
    if ($("#category").val() == "") {
        error("Please enter category!");
        $("#category").focus();
        return false;
    }
    if ($("#weight").val() == "") {
        error("Please enter weight!");
        $("#weight").focus();
        return false;
    }

    // if ($("#picture").val() == '') {
    //     error("Please add picture!");
    //     $("#picture").focus();
    //     return false;
    // }
    if ($("#description").val() == "") {
        error("Please enter description!");
        $("#description").focus();
        return false;
    }

    var formData = new FormData();
    formData.append("job_task_id", $("#job_task_id").val());
    formData.append("category", $("#category").val());
    formData.append("design_no", $("#design_no").val());
    formData.append("weight", $("#weight").val());
    formData.append("description", $("#description").val());
    formData.append('picture', $("#picture")[0].files[0]);

    $.ajax({
        url: url_local + "/job-task-activity/store",  // Laravel route
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: formData,
        processData: false,  // Important for file uploads
        contentType: false,  // Important for file uploads
        dataType: "json",
        success: function (data) {
            if (data.Success) {
                success(data.Message);
                $("#submit").show();
                initDataTablejob_task_activity_table();
                Clear();
            } else {
                error(data.Message);
                $("#submit").show();
            }
        },
        error: function (xhr, status, e) {
            error("An error occurred:");

            $("#submit").show();
        }
    });
});

function Clear() {
    $("#category").val("");
    $("#design_no").val("");
    $("#weight").val(0);
    $("#picture").val("");
    $("#description").val("");
}
