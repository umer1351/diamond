function error(message) {
    toastr.error(message, "Error!", {
        showMethod: "slideDown",
        hideMethod: "slideUp",
        timeOut: 2e3,
    });
}
function success(message) {
    toastr.success(message, "Success!", {
        showMethod: "slideDown",
        hideMethod: "slideUp",
        timeOut: 2e3,
    });
}
var goldImpurityProductData = [];
var goldImpurity_product_sr = 0;
Clear();
function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
$("#CustomerButton").click(function () {
    $("#customerForm").trigger("reset");
    $("#customerModel").modal("show");
});
function getCustomer() {
    $("#preloader").show();
    $.ajax({
        url: url_local + "/customers/json",
        type: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            console.log(data);
            var data = data.Data;
            $("#customer_id").empty();
            $("#customer").empty();
            var customer =
                '<option value="0" selected disabled>--Select Customer--</option>';
            $.each(data, function (key, value) {
                customer +=
                    '<option value="' +
                    value.id +
                    '">' +
                    value.name +
                    " </option>";
            });
            $("#customer_id").append(customer);
            $("#preloader").hide();
        },
    });
}
$("#customer_id").on("change", function () {
    var customer_id = $("#customer_id").val();
    $("#preloader").show();
    $.ajax({
        url: url_local + "/customers/detail/" + customer_id,
        type: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            console.log(data);
            if (data.Success) {
                var data = data.Data;
                $("#customer").empty();
                var row =
                    '<table class="table table-bordered" style="margin-bottom: 0px;">';
                row += "<tbody>";
                row += "<tr>";
                row += '<td style="padding: 3px; width:75px;">Name:</td>';
                row += '<td style="padding: 3px;">' + data.name + "</td>";
                row += '<td style="padding: 3px; width:75px;">CNIC:</td>';
                row += '<td style="padding: 3px;">' + data.cnic + "</td>";
                row += "</tr>";
                row += "<tr>";
                row += '<td style="padding: 3px; width:75px;">Contact #:</td>';
                row += '<td style="padding: 3px;">' + data.contact + "</td>";
                row += '<td style="padding: 3px; width:75px;">Address:</td>';
                row += '<td style="padding: 3px;">' + data.address + "</td>";
                row += "</tr>";
                row += "</tbody>";
                row += "</table>";
                $("#customer").html(row);
                $("#preloader").hide();
            } else {
                error(data.Message);
                $("#preloader").hide();
            }
        },
    });
});
$("#customerForm").submit(function (e) {
    e.preventDefault();
    $("#preloader").show();
    if ($("#name").val() == "" && $("#name").val() == 0) {
        error("Please enter name!");
        $("#name").focus();
        $("#preloader").hide();
        return false;
    }
    if ($("#contact").val() == "" && $("#contact").val() == 0) {
        error("Please enter contact and unique!");
        $("#contact").focus();
        $("#preloader").hide();
        return false;
    }

    $.ajax({
        url: url_local + "/customers/json-store",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#customerForm").serialize(),
        dataType: "json",

        success: function (data) {
            if (data.Success) {
                success(data.Message);
                $("#customerForm").trigger("reset");
                $("#customerModel").modal("hide");
                getCustomer();
                $("#preloader").hide();
            } else {
                error(data.Message);
                $("#preloader").hide();
            }
        },
    });
});


$("#scale_weight,#bead_weight,#stone_weight").on("keyup", function (event) {
    var scale_weight = $("#scale_weight").val();
    var bead_weight = $("#bead_weight").val();
    var stone_weight = $("#stone_weight").val();
    var net_weight = (scale_weight * 1) - ((bead_weight * 1) + (stone_weight * 1));
    $("#net_weight").val(net_weight.toFixed(3));
});
$("#point").on("keyup", function (event) {
    var net_weight = $("#net_weight").val();
    var point = $("#point").val();
    var pure_weight = (net_weight * 1) * (point * 1);
    $("#pure_weight").val(pure_weight.toFixed(3));
});
$("#gold_rate").on("keyup", function (event) {
    $("#total_amount").val(0);
    var gold_rate = $("#gold_rate").val();
    var pure_weight = $("#pure_weight").val();
    var total_amount = (gold_rate * 1) * (pure_weight * 1);
    $("#total_amount").val(total_amount.toFixed(3));
});

$("#cash_payment,#bank_payment").on("keyup", function (event) {
    $("#balance").val(0);
    var cash_payment = $("#cash_payment").val();
    var bank_payment = $("#bank_payment").val();
    var total = $("#total").val();
    var balance = (total*1) - ((cash_payment * 1) + (bank_payment * 1));
    $("#balance").val(balance.toFixed(2));
});

function addProduct() {
    $("#preloader").show();
    var scale_weight = $("#scale_weight").val();
    var bead_weight = $("#bead_weight").val();
    var stone_weight = $("#bead_weight").val();
    var net_weight = $("#net_weight").val();
    var pure_weight = $("#pure_weight").val();
    var point = $("#point").val();
    var gold_rate = $("#gold_rate").val();
    var total_amount = $("#total_amount").val();

    if (scale_weight == 0 || scale_weight == "") {
        error("Please enter scale weight!");
        $("#preloader").hide();
        return false;
    }
    if (bead_weight == "") {
        error("Please enter minimum 0 bead weight!");
        $("#preloader").hide();
        return false;
    }
    if (stone_weight == "") {
        error("Please enter minimum 0 stone weight!");
        $("#preloader").hide();
        return false;
    }
    if (net_weight == 0 || net_weight == "") {
        error("Please enter net weight!");
        $("#preloader").hide();
        return false;
    }
    if (point == 0 || point == "") {
        error("Please enter points!");
        $("#preloader").hide();
        return false;
    }
    if (pure_weight == 0 || pure_weight == "") {
        error("Please enter pure weight!");
        $("#preloader").hide();
        return false;
    }
    if (gold_rate == 0 || gold_rate == "") {
        error("Please enter gold rate/gram!");
        $("#preloader").hide();
        return false;
    }
    if (total_amount == 0 || total_amount == "") {
        error("Please enter total amount!");
        $("#preloader").hide();
        return false;
    }

    var rows = "";
    var total = 0;
    var total_weight = 0;
    var total_qty = 0;

    goldImpurityProductData.push({
        // sr: i,
        scale_weight: scale_weight,
        bead_weight: bead_weight,
        stone_weight: stone_weight,
        net_weight: net_weight,
        point: point,
        pure_weight: pure_weight,
        gold_rate: gold_rate,
        total_amount: total_amount
    });

    $("#total").val(total);
    $("#total_weight").val(total_weight);
    $("#total_qty").val(total_qty);

    $.each(goldImpurityProductData, function (index, val) {
        goldImpurityProductData.sr = index + 1;

        rows += `<tr id="${val.scale_weight}"><td>${index + 1}</td><td style="text-align: right;">${val.scale_weight}</td>
                <td style="text-align: right;">${val.bead_weight}</td><td style="text-align: right;">${val.stone_weight}</td>
                <td style="text-align: right;">${val.net_weight}</td><td style="text-align: right;">${val.point}</td>
                <td style="text-align: right;">${val.pure_weight}</td><td style="text-align: right;">${val.gold_rate}</td>
                <td style="text-align: right;">${val.total_amount}</td>
                <td><a class="text-danger text-white goldimpurityr${val.scale_weight}" onclick="GoldImpurityRemove(${val.scale_weight})"><i class="fa fa-trash"></i></a></td></tr>`;

        total += val.total_amount * 1;
        total_weight += val.scale_weight * 1;
        total_qty = index + 1;
    });
    $("#total").val(total.toFixed(3));
    $("#total_weight").val(total_weight.toFixed(3));
    $("#total_qty").val(total_qty);
    success("Product Added Successfully!");
    $("#gold_impurity_products").empty();
    console.log(rows);
    $("#gold_impurity_products").html(rows);
    Clear();
    GoldImpurityShort();
    $("#preloader").hide();
}


$("body").on("click", "#submit", function (e) {
    e.preventDefault();

    $("#preloader").show();
    // Validation logic

    if (
        $("#customer_id").find(":selected").val() == "" ||
        $("#customer_id").find(":selected").val() == 0
    ) {
        error("Please Select customer!");
        $("#customer_id").focus();
        $("#preloader").hide();
        return false;
    }
    if ($("#total_weight").val() == "" || $("#total_weight").val() == 0) {
        error("total weight is zero!");
        $("#total_weight").focus();
        $("#preloader").hide();
        return false;
    }
    if ($("#total_qty").val() == "" || $("#total_qty").val() == 0) {
        error("total qty is zero!");
        $("#total_qty").focus();
        $("#preloader").hide();
        return false;
    }
    if ($("#total").val() == "" || $("#total").val() == 0) {
        error("Grand total is zero!");
        $("#total").focus();
        $("#preloader").hide();
        return false;
    }

    // Create FormData object for Ajax
    var formData = new FormData();
    formData.append("id", $("#id").val());
    formData.append("customer_id", $("#customer_id").find(":selected").val());
    formData.append("total_weight", $("#total_weight").val());
    formData.append("total_qty", $("#total_qty").val());
    formData.append("total", $("#total").val());
    formData.append("cash_payment", $("#cash_payment").val());
    formData.append("bank_payment", $("#bank_payment").val());
    formData.append("balance", $("#balance").val());

    formData.append("goldImpurityDetail", JSON.stringify(goldImpurityProductData));

    $.ajax({
        url: url_local + "/gold-impurity/store", // Laravel route
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
                $("#preloader").hide();
                $("#submit").prop("disabled", true);

                setTimeout(function () {
                    $("#submit").prop("disabled", false);
                    window.location = url_local + "/gold-impurity";
                }, 1000); // Disable button for 1 second
            } else {
                error(data.Message);
                $("#preloader").hide();
            }
        },
        error: function (xhr, status, e) {
            error("An error occurred:");
            $("#preloader").hide();
        },
    });
});

function GoldImpurityRemove(id) {
    console.log(id);
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        var item_index = "";
        var total = 0;
        var total_weight = 0;
        var total_qty = 0;
        $("#preloader").show();
        $.each(goldImpurityProductData, function (i, val) {
            if (val.scale_weight == id) {
                total = $("#total").val() * 1 - val.total_amount * 1;
                total_weight = $("#total_weight").val() * 1 - val.scale_weight * 1;
                total_qty = $("#total_qty").val() * 1 - 1;
                $("#total").val(total > 0 ? total : 0);
                $("#total_weight").val(total_weight > 0 ? total_weight : 0);
                $("#total_qty").val(total_qty > 0 ? total_qty : 0);
                $("#" + id).hide();
                item_index = i;
                return false;
            }
        });

        goldImpurityProductData.splice(item_index, 1);
        var check = ".goldimpurityr" + id;
        $(check).closest("tr").remove();
        success("Product Deleted Successfully!");
        GoldImpurityShort();
        $("#preloader").hide();
    });
}

function Clear() {
    $("#scale_weight").val(0);
    $("#bead_weight").val(0);
    $("#stone_weight").val(0);
    $("#net_weight").val(0);
    $("#point").val(0);
    $("#pure_weight").val(0);
    $("#gold_rate").val(0);
    $("#total_amount").val(0);
}
// Short
function GoldImpurityShort() {
    var tbody, j, x, y;
    tbody = document.getElementById("gold_impurity_products");
    var switching = true;

    // Run loop until no switching is needed
    while (switching) {
        switching = false;
        var rows = tbody.rows;

        // Loop to go through all rows
        for (j = 1; j < rows.length - 1; j++) {
            var Switch = false;

            // Fetch 2 elements that need to be compared
            x = rows[j].getElementsByTagName("TD")[0];
            y = rows[j + 1].getElementsByTagName("TD")[0];

            // Check if 2 rows need to be switched
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                // If yes, mark Switch as needed and break loop
                Switch = true;
                break;
            }
        }
        if (Switch) {
            // Function to switch rows and mark switch as completed
            rows[j].parentNode.insertBefore(rows[j + 1], rows[j]);
            switching = true;
        }
    }
}
