import { successMessage } from "./toasters.js";

// Generic Ajax Request for All APIs
export function ajaxPostRequest(url, data, options = {}) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            processData: options.processData,
            contentType: options.contentType,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            // dataType: "json",
            beforeSend: function () {
                $("#preloader").show();
            },
            success: function (response) {
                if (response.Success) {
                    successMessage(response.Message);
                    resolve(response.Data);
                } else reject(response);
            },
            complete: function (data) {
                $("#preloader").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                reject(thrownError);
            },
        });
    });
}

// Generic Ajax Request for All APIs
export function ajaxGetRequest(url) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: url,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            // dataType: "json",
            beforeSend: function () {
                $("#preloader").show();
            },
            success: function (response) {
                if (response.Success) {
                    if (response.display == true) {
                        successMessage(response.Message);
                    }
                    resolve(response.Data);
                } else reject(response);
            },
            complete: function (data) {
                $("#preloader").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                reject(thrownError);
            },
        });
    });
}
