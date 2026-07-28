function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
$("#product_id").on("change", function () {
    var product_id = $("#product_id").val();
    $.ajax({
        url: url_local + "/ratti-kaats/ratti-kaat-by-product-id/" + product_id,
        type: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            console.log(data);
            if (data.Success) {
                var data = data.Data;
                var row = "";
                $("#purchases").empty();
                if (data.ratti_kaat.length > 0) {
                    $.each(data.ratti_kaat, function (k, value) {
                        row += "<tr>";
                        row +=
                            '<td><a id="purchase_id" href="javascript:void(0)" data-toggle="tooltip"  data-id="' +
                            value.ratti_kaat_detail_id +
                            '" data-original-title="Purchase"><b>' +
                            value.ratti_kaat_no +
                            "</b></a></td>";
                        row += "</tr>";
                    });
                }

                if (data.job_purchase.length > 0) {
                    $.each(data.job_purchase, function (k, value) {
                        row += `<tr>
                                    <td>
                                        <a id="job_purchase_id" href="javascript:void(0)" data-toggle="tooltip"
                                         data-id="${value.job_purchase_detail_id}" data-original-title="Purchase">
                                            <b>${value.job_purchase_no}</b>
                                        </a>
                                    </td>
                                </tr>`;
                    });
                }

                if (
                    data.ratti_kaat.length === 0 &&
                    data.job_purchase.length === 0
                ) {
                    row += "<tr><td>No Purchase found</td></tr>";
                }
                $("#purchases").append(row);
                $("#tag_no_text").val(data.tag_no);
                $("#tag_no").val(data.tag_no);
            } else {
                error(data.Message);
            }
        },
    });
});

$("body").on("click", "#purchase_id", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var purchase_id = $(this).data("id");
    var product_id = $("#product_id").val();
    $("td a.purchase_active").removeClass("purchase_active");
    $(this).addClass("purchase_active");
    $.ajax({
        url: url_local + "/ratti-kaats/get-detail-by-id/" + purchase_id,
        type: "GET",
    }).done(function (data) {
        console.log(data);
        if (data.Success) {
            var data = data.Data;
            $("#ratti_kaat_id").val(
                data.ratti_kaat_id > 0 ? data.ratti_kaat_id : 0
            );
            $("#ratti_kaat_detail_id").val(data.id > 0 ? data.id : 0);
            $("#scale_weight").val(
                data.scale_weight > 0 ? data.scale_weight : 0
            );
            $("#net_weight").val(data.net_weight > 0 ? data.net_weight : 0);
            $("#bead_weight").val(data.bead_weight > 0 ? data.bead_weight : 0);
            $("#stones_weight").val(
                data.stones_weight > 0 ? data.stones_weight : 0
            );
            $("#diamond_weight").val(
                data.diamond_carat > 0 ? data.diamond_carat : 0
            );

            BeadByPurchaseDetail(data.id);
            StonesByPurchaseDetail(data.id);
            DiamondsByPurchaseDetail(data.id);
        } else {
            error(data.Message);
        }
    });
});

$("body").on("click", "#job_purchase_id", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var job_purchase_id = $(this).data("id");
    var product_id = $("#product_id").val();
    $("td a.purchase_active").removeClass("purchase_active");
    $(this).addClass("purchase_active");
    $.ajax({
        url: url_local + "/job-purchase/get-detail/" + job_purchase_id,
        type: "GET",
    }).done(function (data) {
        console.log(data);
        if (data.Success) {
            var data = data.Data;
            $("#job_purchase_id").val(
                data.job_purchase_id > 0 ? data.job_purchase_id : 0
            );
            $("#job_purchase_detail_id").val(data.id > 0 ? data.id : 0);
            $("#scale_weight").val(
                data.polish_weight > 0 ? data.polish_weight : 0
            );
            $("#net_weight").val(data.final_pure_weight > 0 ? data.final_pure_weight : 0);
            $("#bead_weight").val(data.bead_weight > 0 ? data.bead_weight : 0);
            $("#stones_weight").val(
                data.stones_weight > 0 ? data.stones_weight : 0
            );
            $("#diamond_weight").val(
                data.diamond_carat > 0 ? data.diamond_carat : 0
            );
            $("#waste").val(data.waste > 0 ? data.waste : 0);
            $("#laker").val(data.laker > 0 ? data.laker : 0);
            $("#other_amount").val(data.other > 0 ? data.other : 0);

            BeadByJobPurchaseDetail(data.id, product_id);
            StonesByJobPurchaseDetail(data.id, product_id);
            DiamondsByJobPurchaseDetail(data.id, product_id);
        } else {
            error(data.Message);
        }
    });
});

$("#gold_carat").on("keyup", function (event) {
    var gold_carat = $("#gold_carat").val();
    var gold_carat_rate = (gold_rate / 24) * (gold_carat * 1);
    var gold_rate_gram = gold_carat_rate / 11.664;
    $("#gold_rate").val(gold_rate_gram.toFixed(3));
    TotalGoldAmount();
    TotalAmount();
});

$("#bead_price").on("keyup", function (event) {
    var bead_price = $("#bead_price").val();
    var bead_weight = $("#bead_weight").val();
    var cal = bead_price * bead_weight;
    $("#total_bead_price").val(cal.toFixed(3));
    TotalAmount();
});

$("#stones_price").on("keyup", function (event) {
    var stones_price = $("#stones_price").val();
    var stones_weight = $("#stones_weight").val();
    var cal = stones_price * stones_weight;
    $("#total_stones_price").val(cal.toFixed(3));
    TotalAmount();
});

$("#diamond_price").on("keyup", function (event) {
    var diamond_price = $("#diamond_price").val();
    var diamond_weight = $("#diamond_weight").val();
    var cal = diamond_price * diamond_weight;
    $("#total_diamond_price").val(cal.toFixed(3));
    TotalAmount();
});
//Function
function netWeight() {
    var scale_weight = $("#scale_weight").val();
    var bead_weight = $("#bead_weight").val();
    var stones_weight = $("#stones_weight").val();
    var diamond_weight = $("#diamond_weight").val();
    var net_weight = 0;
    net_weight =
        scale_weight * 1 -
        (bead_weight * 1 + stones_weight * 1 + diamond_weight * 1);
    $("#net_weight").val(net_weight.toFixed(3));
}
$("#waste_per").on("keyup", function (event) {
    var waste_per = $("#waste_per").val();
    var net_weight = $("#net_weight").val();
    var cal = (net_weight * waste_per) / 100;
    var gross_wt = net_weight * 1 + cal * 1;
    $("#waste").val(cal.toFixed(3));
    $("#gross_weight").val(gross_wt.toFixed(3));
    TotalGoldAmount();
});

$("#waste").on("keyup", function (event) {
    var waste = $("#waste").val();
    var net_weight = $("#net_weight").val();
    var cal = (waste / net_weight) * 100;
    var gross_wt = net_weight * 1 + waste * 1;
    $("#waste_per").val(cal.toFixed(3));
    $("#gross_weight").val(gross_wt.toFixed(3));
    TotalGoldAmount();
});

$("#making_gram").on("keyup", function (event) {
    var making_gram = $("#making_gram").val();
    var gross_weight = $("#gross_weight").val();
    var making = making_gram * 1 * (gross_weight * 1);
    $("#making").val(making.toFixed(3));
    TotalAmount();
});

$("#making").on("keyup", function (event) {
    var making = $("#making").val();
    var gross_weight = $("#gross_weight").val();
    var making_gram = (making * 1) / (gross_weight * 1);
    $("#making_gram").val(making_gram.toFixed(3));
    TotalAmount();
});

$("#laker").on("keyup", function (event) {
    TotalAmount();
});
$("#other_amount").on("keyup", function (event) {
    TotalAmount();
});
$("#gold_rate").on("keyup", function (event) {
    TotalGoldAmount();
});
function TotalGoldAmount() {
    var gross_wt = $("#gross_weight").val();
    var gold_rate = $("#gold_rate").val();
    var total_gold_price = gold_rate * 1 * (gross_wt * 1);
    $("#total_gold_price").val(total_gold_price.toFixed(3));
}
function TotalAmount() {
    var total_bead_price = $("#total_bead_price").val();
    var total_stones_price = $("#total_stones_price").val();
    var total_diamond_price = $("#total_diamond_price").val();
    var other_amount = $("#other_amount").val();
    var making = $("#making").val();
    var laker = $("#laker").val();
    var total_gold_price = $("#total_gold_price").val();
    var cal =
        total_bead_price * 1 +
        total_stones_price * 1 +
        total_diamond_price * 1 +
        other_amount * 1 +
        making * 1 +
        laker * 1 +
        total_gold_price * 1;

    $("#total_amount").val(cal.toFixed(3));
}

$("body").on("click", "#submit", function (e) {
    e.preventDefault();
    var is_parent=1;
    if (!$("#is_parent").is(":checked")) {
        is_parent=0;
        // Validation logic
        if ($("#tag_no").val() == "") {
            error("Please generat tag no!");
            $("#tag_no").focus();
            return false;
        }
        if (
            $("#ratti_kaat_id").val() == "" &&
            $("#ratti_kaat_detail_id").val() == "" &&
            $("#job_purchase_id").val() == "" &&
            $("#job_purchase_detail_id").val() == ""
        ) {
            error("Please select purchase!");
            return false;
        }
        if (
            $("#product_id").find(":selected").val() == "" ||
            $("#product_id").find(":selected").val() == 0
        ) {
            error("Please Select product!");
            $("#product_id").focus();
            return false;
        }
    }
    if (
        $("#warehouse_id").find(":selected").val() == 0 ||
        $("#warehouse_id").find(":selected").val() == ""
    ) {
        error("Please Select warehouse!");
        $("#warehouse_id").focus();
        return false;
    }
    if ($("#picture").val() == "") {
        error("Please add picture!");
        $("#picture").focus();
        return false;
    }
    if (!$("#is_parent").is(":checked")) {
        if ($("#gold_carat").val() == "" || $("#gold_carat").val() == 0) {
            error("Please enter gold carat!");
            $("#gold_carat").focus();
            return false;
        }
        if ($("#scale_weight").val() == "" || $("#scale_weight").val() == 0) {
            error("Please enter scale weight!");
            $("#scale_weight").focus();
            return false;
        }
        if ($("#net_weight").val() == "" || $("#net_weight").val() == 0) {
            error("Please enter net weight!");
            $("#net_weight").focus();
            return false;
        }
        if ($("#bead_weight").val() == "") {
            error("Please enter bead weight minimum 0!");
            $("#bead_weight").focus();
            return false;
        }
        if ($("#stones_weight").val() == "") {
            error("Please enter stone weight minimum 0!");
            $("#stones_weight").focus();
            return false;
        }
        if ($("#diamond_weight").val() == "") {
            error("Please enter diamond weight minimum 0!");
            $("#diamond_weight").focus();
            return false;
        }
        if ($("#waste_per").val() == "" || $("#waste_per").val() < 10) {
            error("Please enter waste (%) minimum 10!");
            $("#waste_per").focus();
            return false;
        }
        if ($("#waste").val() == "" || $("#waste").val() == 0) {
            error("Please enter waste!");
            $("#waste").focus();
            return false;
        }
        if ($("#gross_weight").val() == "" || $("#gross_weight").val() == 0) {
            error("Please enter gross weight!");
            $("#gross_weight").focus();
            return false;
        }
        if ($("#laker").val() == "" || $("#laker").val() < 0) {
            error("Please enter laker!");
            $("#laker").focus();
            return false;
        }
        if ($("#making_gram").val() == "" || $("#making_gram").val() == 0) {
            error("Please enter making/gram!");
            $("#making_gram").focus();
            return false;
        }
        if ($("#making").val() == "" || $("#making").val() == 0) {
            error("Please enter making!");
            $("#making").focus();
            return false;
        }
        if ($("#total_bead_price").val() == "") {
            error("Please enter total bead amount, minimum 0!");
            $("#total_bead_price").focus();
            return false;
        }
        if ($("#total_stones_price").val() == "") {
            error("Please enter total stones amount, minimum 0!");
            $("#total_stones_price").focus();
            return false;
        }
        if ($("#total_diamond_price").val() == "") {
            error("Please enter total diamond amount, minimum 0!");
            $("#total_diamond_price").focus();
            return false;
        }
        if ($("#other_amount").val() == "") {
            error("Please enter other amount, minimum 0!");
            $("#other_amount").focus();
            return false;
        }
        if ($("#gold_rate").val() == "" || $("#gold_rate").val() == 0) {
            error("Please enter gold rate!");
            $("#gold_rate").focus();
            return false;
        }
        if (
            $("#total_gold_price").val() == "" ||
            $("#total_gold_price").val() == 0
        ) {
            error("Please enter total gold amount!");
            $("#total_gold_price").focus();
            return false;
        }
        if ($("#total_amount").val() == "" || $("#total_amount").val() == 0) {
            error("Please enter total amount!");
            $("#total_amount").focus();
            return false;
        }
    }

    // Create FormData object for Ajax
    var formData = new FormData();
    formData.append("is_parent", is_parent);
    formData.append("parent_id", $("#parent_id").find(":selected").val());
    formData.append("tag_no", $("#tag_no").val());
    formData.append("job_purchase_id", $("#job_purchase_id").val());
    formData.append(
        "job_purchase_detail_id",
        $("#job_purchase_detail_id").val()
    );
    formData.append("ratti_kaat_id", $("#ratti_kaat_id").val());
    formData.append("ratti_kaat_detail_id", $("#ratti_kaat_detail_id").val());
    formData.append("product_id", $("#product_id").find(":selected").val());
    formData.append("warehouse_id", $("#warehouse_id").find(":selected").val());
    formData.append("gold_carat", $("#gold_carat").val());
    formData.append("scale_weight", $("#scale_weight").val());
    formData.append("net_weight", $("#net_weight").val());
    formData.append("bead_weight", $("#bead_weight").val());
    formData.append("stones_weight", $("#stones_weight").val());
    formData.append("diamond_weight", $("#diamond_weight").val());
    formData.append("waste_per", $("#waste_per").val());
    formData.append("waste", $("#waste").val());
    formData.append("gross_weight", $("#gross_weight").val());
    formData.append("laker", $("#laker").val());
    formData.append("making_gram", $("#making_gram").val());
    formData.append("making", $("#making").val());
    formData.append("total_bead_price", $("#total_bead_price").val());
    formData.append("total_stones_price", $("#total_stones_price").val());
    formData.append("total_diamond_price", $("#total_diamond_price").val());
    formData.append("other_amount", $("#other_amount").val());
    formData.append("gold_rate", $("#gold_rate").val());
    formData.append("total_gold_price", $("#total_gold_price").val());
    formData.append("total_amount", $("#total_amount").val());
    // Append files (multiple images)
    var picture = $("#picture")[0].files;
    for (var i = 0; i < picture.length; i++) {
        formData.append("picture", picture[i]); // Add files to the form data
    }

    // Append product data (assuming productData is already a JSON string)
    formData.append("beadDetail", JSON.stringify(beadData));
    formData.append("stonesDetail", JSON.stringify(stonesData));
    formData.append("diamondDetail", JSON.stringify(diamondsData));

    $.ajax({
        url: url_local + "/finish-product/store", // Laravel route
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: formData,
        processData: false, // Important for file uploads
        contentType: false, // Important for file uploads
        dataType: "json",
        success: function (data) {
            if (data.Success) {
                success(data.Message);
                $("#submit").prop("disabled", true);

                setTimeout(function () {
                    $("#submit").prop("disabled", false);
                    window.location = url_local + "/finish-product";
                }, 1000); // Disable button for 1 second
            } else {
                error(data.Message);
            }
        },
        error: function (xhr, status, e) {
            error("An error occurred:");
        },
    });
});
