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
var saleOrderData = [];
var sale_order_sr = 0;
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
                row += "</tr>";
                row += "<tr>";
                row += '<td style="padding: 3px; width:75px;">CNIC:</td>';
                row += '<td style="padding: 3px;">' + data.cnic + "</td>";
                row += "</tr>";
                row += "<tr>";
                row += '<td style="padding: 3px; width:75px;">Contact #:</td>';
                row += '<td style="padding: 3px;">' + data.contact + "</td>";
                row += "</tr>";
                row += "<tr>";
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

$("#net_weight,#waste").on("keyup", function (event) {
    var net_weight = $("#net_weight").val();
    var waste = $("#waste").val();
    var gross_weight = (net_weight * 1) + (waste * 1);
    $("#gross_weight").val(gross_weight.toFixed(3));
});

function addProduct() {
    $("#preloader").show();
    var product_id = $("#product_id").find(":selected").val();
    var product = $("#product_id").find(":selected").text();
    var category = $("#category").val();
    var design_no = $("#design_no").val();
    var net_weight = $("#net_weight").val();
    var waste = $("#waste").val();
    var gross_weight = $("#gross_weight").val();
    var description = $("#description").val();

    if (product_id == 0 || product_id == "") {
        error("Please Select Product!");
        $("#preloader").hide();
        return false;
    }
    if (category == 0 || category == "") {
        error("Please Enter Category!");
        $("#preloader").hide();
        return false;
    }
    if (design_no == 0 || design_no == "") {
        error("Please Enter Design No!");
        $("#preloader").hide();
        return false;
    }
    if (net_weight == 0 || net_weight == "") {
        error("Please Enter Net Weight!");
        $("#preloader").hide();
        return false;
    }
    if (waste == 0 || waste == "") {
        error("Please Enter Waste!");
        $("#preloader").hide();
        return false;
    }
    if (gross_weight == 0 || gross_weight == "") {
        error("Please Enter Gross Weight!");
        $("#preloader").hide();
        return false;
    }
    if (description == 0 || description == "") {
        error("Please Enter Description!");
        $("#preloader").hide();
        return false;
    }
    var check = true;
    $.each(saleOrderData, function (e, val) {
        if (val.product_id == $("#product_id").find(":selected").val()) {
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

    saleOrderData.push({
        // sr: i,
        product_id: product_id,
        product: product,
        category: category,
        design_no: design_no,
        net_weight: net_weight,
        waste: waste,
        gross_weight: gross_weight,
        description: description,
    });

    $.each(saleOrderData, function (e, val) {
        sale_order_sr = sale_order_sr + 1;
        saleOrderData.sr = sale_order_sr;

        rows += `<tr id="${val.product_id}"><td>${sale_order_sr}</td><td>${val.product}</td><td>${val.category}</td>
                <td>${val.design_no}</td><td style="text-align: right;">${val.net_weight}</td>
                <td style="text-align: right;">${val.waste}</td>
                <td style="text-align: right;">${val.gross_weight}</td>
                <td>${val.description}</td>
                <td><a class="text-danger text-white saleorderr${val.product_id}" onclick="SaleOrderRemove(${val.product_id})"><i class="fa fa-trash"></i></a></td></tr>`;

    });
    success("Product Added Successfully!");
    $("#sale_order_products").empty();
    console.log(rows);
    $("#sale_order_products").html(rows);
    Clear();
    SaleOrderShort();
    $("#preloader").hide();
}


$("body").on("click", "#submit", function (e) {
    e.preventDefault();

    $("#preloader").show();
    // Validation logic
    if ($("#sale_order_date").val() == "") {
        error("Please select sale date!");
        $("#sale_order_date").focus();
        $("#preloader").hide();
        return false;
    }

    if ($("#delivery_date").val() == "") {
        error("Please select delivery date!");
        $("#delivery_date").focus();
        $("#preloader").hide();
        return false;
    }

    if (
        $("#customer_id").find(":selected").val() == "" ||
        $("#customer_id").find(":selected").val() == 0
    ) {
        error("Please Select customer!");
        $("#customer_id").focus();
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

    if ($("#gold_rate").val() == "" || $("#gold_rate").val() == 0) {
        error("Gold Rate is zero!");
        $("#gold_rate").focus();
        $("#preloader").hide();
        return false;
    }
    if (
        $("#gold_rate_type_id").find(":selected").val() == "" ||
        $("#gold_rate_type_id").find(":selected").val() == 0
    ) {
        error("Please Select Gold Rate Type!");
        $("#gold_rate_type_id").focus();
        $("#preloader").hide();
        return false;
    }
    

    // Create FormData object for Ajax
    var formData = new FormData();
    formData.append("id", $("#id").val());
    formData.append("sale_order_date", $("#sale_order_date").val());
    formData.append("delivery_date", $("#delivery_date").val());
    formData.append("customer_id", $("#customer_id").find(":selected").val());
    formData.append("warehouse_id", $("#warehouse_id").find(":selected").val());
    formData.append("gold_rate_type_id", $("#gold_rate_type_id").find(":selected").val());
    formData.append("gold_rate", $("#gold_rate").val());

    formData.append("saleOrderDetail", JSON.stringify(saleOrderData));

    $.ajax({
        url: url_local + "/sale-order/store", // Laravel route
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
                    window.location = url_local + "/sale-order";
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

function SaleOrderRemove(id) {
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
        $.each(saleOrderData, function (i, val) {
            if (val.product_id == id) {
                $("#" + id).hide();
                item_index = i;
                return false;
            }
        });

        saleOrderData.splice(item_index, 1);
        var check = ".saleorderr" + id;
        $(check).closest("tr").remove();
        success("Product Deleted Successfully!");
        SaleOrderShort();
        $("#preloader").hide();
    });
}

function Clear() {
    $("#product_id option[value='0']").remove();
    $("#product_id").append(
        '<option disabled selected value="0">--Select Product--</option>'
    );
    $("#category").val("");
    $("#design_no").val("");
    $("#net_weight").val(0);
    $("#waste").val(0);
    $("#gross_weight").val(0);
    $("#description").val("");
}
// Short
function SaleOrderShort() {
    var tbody, j, x, y;
    tbody = document.getElementById("sale_order_products");
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
