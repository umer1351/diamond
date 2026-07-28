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
var otherProductData = [];
var other_product_sr = 0;
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

$("#unit_price").on("keyup", function (event) {
    var qty = $("#qty").val();
    var unit_price = $("#unit_price").val();
    var total = qty * 1 * (unit_price * 1);
    $("#total_amount").val(total.toFixed(3));
});
$("#qty").on("keyup", function (event) {
    var qty = $("#qty").val();
    var unit_price = $("#unit_price").val();
    var total = qty * 1 * (unit_price * 1);
    $("#total_amount").val(total.toFixed(3));
});

function addProduct() {
    $("#preloader").show();
    var other_product_id = $("#other_product_id").find(":selected").val();
    var other_product = $("#other_product_id").find(":selected").text();
    var qty = $("#qty").val();
    var unit_price = $("#unit_price").val();
    var total_amount = $("#total_amount").val();

    if (other_product_id == 0 || other_product_id == "") {
        error("Please Enter product!");
        $("#preloader").hide();
        return false;
    }
    if (qty == 0 || qty == "") {
        error("Please Enter Quantity!");
        $("#preloader").hide();
        return false;
    }
    if (unit_price == 0 || unit_price == "") {
        error("Please Enter Unit Price!");
        $("#preloader").hide();
        return false;
    }
    var check = true;
    $.each(otherProductData, function (e, val) {
        if (val.other_product_id == $("#other_product_id").val()) {
            error("Product is already added !");
            $("#preloader").hide();
            check = false;
            return false;
        }
    });
    if (check == false) {
        return;
    }

    var rows = "";
    var total = 0;

    otherProductData.push({
        // sr: i,
        other_product_id: other_product_id,
        other_product: other_product,
        qty: qty,
        unit_price: unit_price,
        total_amount: total_amount,
    });

    $("#total").val(total);

    $.each(otherProductData, function (e, val) {
        other_product_sr = other_product_sr + 1;
        otherProductData.sr = other_product_sr;

        rows += `<tr id="${val.other_product_id}"><td>${other_product_sr}</td><td>${val.other_product}</td>
                <td style="text-align: right;">${val.unit_price}</td><td style="text-align: right;">${val.qty}</td>
                <td style="text-align: right;">${val.total_amount}</td>
                <td><a class="text-danger text-white otherproductr${val.other_product_id}" onclick="OtherProductRemove(${val.other_product_id})"><i class="fa fa-trash"></i></a></td></tr>`;

        total += val.total_amount * 1;
    });
    $("#grand_total").val(total.toFixed(3));
    success("Product Added Successfully!");
    $("#other_products").empty();
    console.log(rows);
    $("#other_products").html(rows);
    Clear();
    OtherProductShort();
    $("#preloader").hide();
}

$("body").on("click", "#submit", function (e) {
    e.preventDefault();

    $("#preloader").show();
    // Validation logic
    if ($("#other_purchase_date").val() == "") {
        error("Please select purchase date!");
        $("#purchase_date").focus();
        $("#preloader").hide();
        return false;
    }
    if ($("#bill_no").val() == "") {
        error("Please add bill no!");
        $("#bill_no").focus();
        $("#preloader").hide();
        return false;
    }
    if (
        $("#supplier_id").find(":selected").val() == "" ||
        $("#supplier_id").find(":selected").val() == 0
    ) {
        error("Please Select supplier!");
        $("#supplier_id").focus();
        $("#preloader").hide();
        return false;
    }

    if (
        $("#warehouse_id").find(":selected").val() == "" ||
        $("#warehouse_id").find(":selected").val() == 0
    ) {
        error("Please Select warehouse!");
        $("#warehouse_id").focus();
        $("#preloader").hide();
        return false;
    }
    if (
        $("#purchase_account_id").find(":selected").val() == "" ||
        $("#purchase_account_id").find(":selected").val() == 0
    ) {
        error("Please Select Purchase Account!");
        $("#purchase_account_id").focus();
        $("#preloader").hide();
        return false;
    }

    if ($("#grand_total").val() == "" || $("#grand_total").val() == 0) {
        error("Grand total is zero!");
        $("#grand_total").focus();
        $("#preloader").hide();
        return false;
    }

    // Create FormData object for Ajax
    var formData = new FormData();
    formData.append("id", $("#id").val());
    formData.append("other_purchase_date", $("#other_purchase_date").val());
    formData.append("supplier_id", $("#supplier_id").find(":selected").val());
    formData.append("warehouse_id", $("#warehouse_id").find(":selected").val());
    formData.append("purchase_account_id", $("#purchase_account_id").find(":selected").val());
    formData.append("total", $("#grand_total").val());
    formData.append("paid", $("#paid").val());
    formData.append("paid_account_id", $("#paid_account_id").find(":selected").val());

    formData.append("otherProductDetail", JSON.stringify(otherProductData));

    $.ajax({
        url: url_local + "/other-purchase/store", // Laravel route
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
                    window.location = url_local + "/other-purchase";
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

function OtherProductRemove(id) {
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
        $("#preloader").show();
        $.each(otherProductData, function (i, val) {
            if (val.other_product_id == id) {
                total = $("#grand_total").val() * 1 - val.total_amount * 1;
                $("#grand_total").val(total > 0 ? total : 0);
                $("#" + id).hide();
                item_index = i;
                return false;
            }
        });

        otherProductData.splice(item_index, 1);
        var check = ".productr" + id;
        $(check).closest("tr").remove();
        success("Product Deleted Successfully!");
        OtherProductShort();
        $("#preloader").hide();
    });
}

function Clear() {
    $("#other_product_id option[value='0']").remove();
    $("#other_product_id").append(
        '<option disabled selected value="0">--Select Product--</option>'
    );
    $("#qty").val("");
    $("#unit_price").val("");
    $("#total_amount").val(0);
    
}
// Short
function OtherProductShort() {
    var tbody, j, x, y;
    tbody = document.getElementById("other_products");
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
