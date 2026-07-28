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
var productData = [];
var product_sr = 0;
Clear();
function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
$("body").on("click", "#JobTaskDetail", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    $("td a.job_task_detail_active").removeClass("job_task_detail_active");
    $(this).addClass("job_task_detail_active");
    $("#purchase_order_detail_id").val($(this).data("id"));
    $("#product_id").val($(this).data("product_id"));
    $("#product").val($(this).data("product"));
    $("#product_category").val($(this).data("category"));
    $("#design_no").val($(this).data("design_no"));
    // $("#recieved_weight").val($(this).data("net_weight"));
    $("#purchase_order_id").val($(this).data("purchase_order_id"));
    $("#sale_order_id").val($(this).data("sale_order_id"));
    $("#warehouse_id").val($(this).data("warehouse_id"));
});
$("#CustomerButton").click(function () {
    $("#customerForm").trigger("reset");
    $("#customerModel").modal("show");
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
    var stone_waste = $("#stone_waste").val();
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
function totalRecievedWeight() {
    var recieved_weight = $("#recieved_weight").val();
    var stone_waste_weight = $("#stone_waste_weight").val();
    var with_stone_weight = $("#with_stone_weight").val();
    var total_recieved_weight = 0;
    var payable_weight = 0;
    total_recieved_weight = recieved_weight * 1 + stone_waste_weight * 1;
    payable_weight = with_stone_weight * 1 - total_recieved_weight * 1;
    $("#total_recieved_weight").val(total_recieved_weight.toFixed(3));
    $("#payable_weight").val(payable_weight.toFixed(3));
}
function finalPureWeight() {
    var payable_weight = $("#payable_weight").val();
    var mail_weight = $("#mail_weight").val();
    var stones_weight = $("#stones_weight").val();
    var stone_adjustement = 0;
    if (stones_weight > 0) {
        if ($("#mail").val() == 'Upper') {
            stone_adjustement = (payable_weight / (96 + (1 * mail_weight))) * 96;
        } else {
            stone_adjustement = (payable_weight / 96) * (96 - (1 * mail_weight));
        }
        $("#stone_adjustement").val(stone_adjustement.toFixed(3));
    }
    var pure_weight = $("#pure_weight").val();
    var final_pure_weight = ((pure_weight * 1) + (stone_adjustement * 1)) - payable_weight;
    $("#final_pure_weight").val(final_pure_weight.toFixed(3));
}
$("#polish_weight").on("keyup", function (event) {
    var waste_ratti = $("#waste_ratti").val();
    var polish_weight = $("#polish_weight").val();
    var waste = (polish_weight / 96) * waste_ratti;
    var total_weight = polish_weight * 1 + waste * 1;
    $("#waste").val(waste.toFixed(3));
    $("#total_weight").val(total_weight.toFixed(3));

    var stones_weight = $("#stones_weight").val();
    var with_stone_weight = (polish_weight*1)+(stones_weight*1);
    $("#with_stone_weight").val(with_stone_weight.toFixed(3));
    finalPureWeight();
});
$("#mail").on("change", function (event) {
    $("#mail_weight").val(0);
    $("#pure_weight").val(0);
    $("#stone_adjustement").val(0);
    $("#final_pure_weight").val(0);
});
$("#mail_weight").on("keyup", function (event) {
    var total_weight = $("#total_weight").val();
    var mail_weight = $("#mail_weight").val();
    var pure_weight = 0;
    $("#pure_weight").val(0);
    console.log($("#mail").val());
    if ($("#mail").val() == 'Upper') {
        let devid = 96 + (1 * mail_weight);
        console.log(devid);
        pure_weight = (total_weight/ devid) * 96;
    } else {
        
        console.log('else');
        pure_weight = ((1 * total_weight) / 96) * (96 - (1 * mail_weight));
    }
    $("#pure_weight").val(pure_weight.toFixed(3));
    totalRecievedWeight();
    finalPureWeight();
});
$("#recieved_weight").on("keyup", function (event) {

    totalRecievedWeight();
    finalPureWeight();
});
$("#other,#laker,#rp,#wax").on("keyup", function (event) {
    TotalAmount();
});
function TotalAmount() {
    var total_bead_price = $("#total_bead_price").val();
    var total_stones_price = $("#total_stones_price").val();
    var total_diamond_price = $("#total_diamond_price").val();
    var other_amount = $("#other").val();
    var laker = $("#laker").val();
    var rp = $("#rp").val();
    var wax = $("#wax").val();
    var cal = total_bead_price * 1 + total_stones_price * 1 + total_diamond_price * 1 + other_amount * 1 + laker * 1 + rp * 1 + wax * 1;
    $("#total_amount").val(cal.toFixed(3));
    // GrandTotal();
}
function GrandTotal() {
    var total = 0;
    var total_dollar = 0;
    var total_amount = $("#total_amount").val();
    var total_amount_dollar = $("#total_diamond_dollar").val();
    total = total * 1 + total_amount * 1;
    total_dollar = total_dollar * 1 + total_amount_dollar * 1;
    $("#total").val(cal.toFixed(3));
    $("#total_dollar").val(cal.toFixed(3));
}

function addProduct() {
    $("#preloader").show();
    var product = $("#product").val();
    var product_id = $("#product_id").val();
    var purchase_order_detail_id = $("#purchase_order_detail_id").val();
    var product_category = $("#product_category").val();
    var design_no = $("#design_no").val();
    var polish_weight = $("#polish_weight").val();
    var waste_ratti = $("#waste_ratti").val();
    var waste = $("#waste").val();
    var total_weight = $("#total_weight").val();
    var mail = $("#mail").val();
    var mail_weight = $("#mail_weight").val();
    var pure_weight = $("#pure_weight").val();
    var stone_waste = $("#stone_waste").val();
    var stone_adjustement = $("#stone_adjustement").val();
    var bead_weight = $("#bead_weight").val();
    var stones_weight = $("#stones_weight").val();
    var diamond_weight = $("#diamond_weight").val();
    var with_stone_weight = $("#with_stone_weight").val();
    var recieved_weight = $("#recieved_weight").val();
    var stone_waste_weight = $("#stone_waste_weight").val();
    var total_recieved_weight = $("#total_recieved_weight").val();
    var payable_weight = $("#payable_weight").val();
    var total_bead_price = $("#total_bead_price").val();
    var total_stones_price = $("#total_stones_price").val();
    var total_diamond_price = $("#total_diamond_price").val();
    var laker = $("#laker").val();
    var rp = $("#rp").val();
    var wax = $("#wax").val();
    var other = $("#other").val();
    var final_pure_weight = $("#final_pure_weight").val();
    var total_diamond_dollar = $("#total_diamond_dollar").val();
    var total_amount = $("#total_amount").val();

    if (
        product_id == "" ||
        purchase_order_detail_id == ""
    ) {
        error("Product is not selected!");
        $("#preloader").hide();
        return false;
    }
    if (product_id == 0 || product_id == "") {
        error("Please Enter product!");
        $("#preloader").hide();
        return false;
    }
    if (polish_weight == 0 || polish_weight == "") {
        error("Please enter with polish weight!");
        $("#preloader").hide();
        return false;
    }
    if (mail_weight == 0 || mail_weight == "") {
        error("Please Enter mail weight!");
        $("#preloader").hide();
        return false;
    }
    var check = true;
    $.each(productData, function (e, val) {
        if (val.product_id == $("#product_id").val()) {
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
    var total_dollar = 0;
    var total_au = 0;
    var total_recieved_au = 0;

    productData.push({
        // sr: i,
        product: product,
        product_id: product_id,
        purchase_order_detail_id: purchase_order_detail_id,
        category: product_category,
        design_no: design_no,
        polish_weight: polish_weight,
        waste_ratti: waste_ratti,
        waste: waste,
        total_weight: total_weight,
        mail: mail,
        mail_weight: mail_weight,
        pure_weight: pure_weight,
        stone_waste: stone_waste,
        stone_adjustement: stone_adjustement,
        bead_weight: bead_weight,
        stones_weight: stones_weight,
        diamond_carat: diamond_weight,
        with_stone_weight: with_stone_weight,
        recieved_weight: recieved_weight,
        stone_waste_weight: stone_waste_weight,
        total_recieved_weight: total_recieved_weight,
        payable_weight: payable_weight,
        total_bead_amount: total_bead_price,
        total_stones_amount: total_stones_price,
        total_diamond_amount: total_diamond_price,
        laker: laker,
        rp: rp,
        wax: wax,
        other: other,
        final_pure_weight: final_pure_weight,
        total_amount: total_amount,
        total_dollar: total_diamond_dollar,
        beadDetail: beadData,
        stonesDetail: stonesData,
        diamondDetail: diamondsData,
    });

    $("#total").val(total);
    $("#total_dollar").val(total_dollar);
    $("#total_au").val(total_au);
    $("#total_recieved_au").val(total_recieved_au);

    $.each(productData, function (e, val) {
        product_sr = product_sr + 1;
        productData.sr = product_sr;

        rows += `<tr id="${val.product_id}">
            <td>${product_sr}</td>
            <td>${val.product}</td>
            <td>${val.category}</td>
            <td>${val.design_no}</td>
            <td style="text-align: right;">${val.polish_weight}</td>
            <td style="text-align: right;">${val.waste_ratti}</td>
            <td style="text-align: right;">${val.waste}</td>
            <td style="text-align: right;">${val.total_weight}
            <td>${val.mail}</td>
            <td style="text-align: right;">${val.mail_weight}</td>
            </td><td style="text-align: right;">${val.pure_weight}</td>
            <td style="text-align: right;" >${val.stone_waste}</td>
          <td style="text-align: right;" >${val.stone_adjustement}</td>
          <td style="text-align: right;" >${val.bead_weight}</td>
          <td style="text-align: right;" >${val.stones_weight}</td>
          <td style="text-align: right;" >${val.diamond_carat}</td>
          <td style="text-align: right;" >${val.with_stone_weight}</td>
          <td style="text-align: right;" >${val.recieved_weight}</td>
          <td style="text-align: right;" >${val.stone_waste_weight}</td>
          <td style="text-align: right;" >${val.total_recieved_weight}</td>
          <td style="text-align: right;" >${val.payable_weight}</td>
          <td style="text-align: right;" >${val.total_bead_amount}</td>
          <td style="text-align: right;" >${val.total_stones_amount}</td>
          <td style="text-align: right;" >${val.total_diamond_amount}</td>
          <td style="text-align: right;" >${val.laker}</td>
          <td style="text-align: right;" >${val.rp}</td>
          <td style="text-align: right;" >${val.wax}</td>
          <td style="text-align: right;" >${val.other}</td>
          <td style="text-align: right;" >${val.final_pure_weight}</td>
          <td style="text-align: right;" >${val.total_dollar}</td>
          <td style="text-align: right;" >${val.total_amount}</td>
          <td><a class="text-danger text-white productr${val.product_id}" onclick="ProductRemove(${val.product_id})"><i class="fa fa-trash"></i></a></td>
          </tr>`;

        total += val.total_amount * 1;
        total_dollar += val.total_dollar * 1;
        total_au += val.final_pure_weight * 1;
        total_recieved_au += val.total_recieved_weight * 1;
    });
    $("#total").val(total.toFixed(3));
    $("#total_dollar").val(total_dollar.toFixed(3));
    $("#total_au").val(total_au.toFixed(3));
    $("#total_recieved_au").val(total_recieved_au.toFixed(3));
    success("Product Added Successfully!");
    $("#products").empty();
    console.log(rows);
    $("#products").html(rows);
    Clear();
    ProductShort();
    $("#preloader").hide();
}


$("body").on("click", "#submit", function (e) {
    e.preventDefault();

    $("#preloader").show();
    // Validation logic
    if ($("#job_purchase_date").val() == "") {
        error("Please select job purchase date!");
        $("#job_purchase_date").focus();
        $("#preloader").hide();
        return false;
    }
    if ($("#reference").val() == "" || $("#reference").val() == 0) {
        error("Reference field is required!");
        $("#reference").focus();
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
    formData.append("job_purchase_date", $("#job_purchase_date").val());
    formData.append("supplier_id", $("#supplier_id").val());
    formData.append("warehouse_id", $("#warehouse_id").val());
    formData.append("job_task_id", $("#job_task_id").val());
    formData.append("purchase_order_id", $("#purchase_order_id").val());
    formData.append("sale_order_id", $("#sale_order_id").val());
    formData.append("reference", $("#reference").val());
    formData.append("total", $("#total").val());
    formData.append("total_dollar", $("#total_dollar").val());
    formData.append("total_recieved_au", $("#total_recieved_au").val());
    formData.append("total_au", $("#total_au").val());

    formData.append("productDetail", JSON.stringify(productData));

    $.ajax({
        url: url_local + "/job-purchase/store", // Laravel route
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
                    window.location = url_local + "/job-task";
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

function ProductRemove(id) {
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
        var total_dollar = 0;
        var total_recieved_au = 0;
        $("#preloader").show();
        $.each(productData, function (i, val) {
            if (val.product_id == id) {
                total = $("#total").val() * 1 - val.total_amount * 1;
                total_dollar = $("#total_dollar").val() * 1 - val.total_dollar * 1;
                total_recieved_au = $("#total_recieved_au").val() * 1 - val.final_pure_weight * 1;
                $("#total").val(total > 0 ? total.toFixed(3) : 0);
                $("#total_dollar").val(total_dollar > 0 ? total_dollar.toFixed(3) : 0);
                $("#total_recieved_au").val(total_recieved_au > 0 ? total_recieved_au.toFixed(3) : 0);
                $("#" + id).hide();
                item_index = i;
                return false;
            }
        });

        productData.splice(item_index, 1);
        var check = ".productr" + id;
        $(check).closest("tr").remove();
        success("Product Deleted Successfully!");
        ProductShort();
        $("#preloader").hide();
    });
}

function Clear() {
    $("#product").val('');
    $("#product_id").val('');
    $("#purchase_order_detail_id").val('');
    $("#product_category").val('');
    $("#design_no").val('');
    $("#polish_weight").val(0);
    $("#waste").val(0);
    $("#total_weight").val(0);
    $("#mail").val(0);
    $("#mail_weight").val(0);
    $("#pure_weight").val(0);
    $("#stone_adjustement").val(0);
    $("#bead_weight").val(0);
    $("#stones_weight").val(0);
    $("#diamond_weight").val(0);
    $("#with_stone_weight").val(0);
    $("#recieved_weight").val(0);
    $("#stone_waste_weight").val(0);
    $("#total_recieved_weight").val(0);
    $("#payable_weight").val(0);
    $("#total_bead_price").val(0);
    $("#total_stones_price").val(0);
    $("#total_diamond_price").val(0);
    $("#laker").val(0);
    $("#rp").val(0);
    $("#wax").val(0);
    $("#other").val(0);
    $("#final_pure_weight").val(0);
    $("#total_diamond_dollar").val(0);
    $("#total_amount").val(0);
}
// Short
function ProductShort() {
    var tbody, j, x, y;
    tbody = document.getElementById("products");
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
