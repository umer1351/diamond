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
$("#discount_amount").on("keyup", function () {
    var discount_amount = $("#discount_amount").val();
    var sub_total = $("#grand_total").val();
    var total = (sub_total * 1) - (discount_amount * 1);
    $("#grand_total_total").val(total.toFixed(3));
});
$("#select_tag_no").on("change", function () {
    var select_tag_no = $("#select_tag_no").val();
    getFinishProduct(select_tag_no);
});
$("#search_tag_no").on("input", function () {
    var inputVal = $(this).val();
    if (inputVal.length > 6) {
        getFinishProduct(select_tag_no);
    }
});
function getFinishProduct(tag_no) {
    $("#preloader").show();
    $.ajax({
        url: url_local + "/finish-product/get-by-tag-no/" + tag_no,
        type: "GET",
    }).done(function (data) {
        console.log(data);
        if (data.Success) {
            var data = data.Data;
            if (Array.isArray(data)) {
                ChildAdd(data);
            } else {
                $("#finish_product_id").val(data.id > 0 ? data.id : "");
                $("#ratti_kaat_id").val(
                    data.ratti_kaat_id > 0 ? data.ratti_kaat_id : ""
                );
                $("#ratti_kaat_detail_id").val(
                    data.ratti_kaat_detail_id > 0 ? data.ratti_kaat_detail_id : ""
                );
                $("#job_purchase_detail_id").val(
                    data.job_purchase_detail_id > 0 ? data.job_purchase_detail_id : ""
                );
                $("#product").val(data.product.name != "" ? data.product.name : "");
                $("#product_id").val(data.product_id > 0 ? data.product_id : 0);
                $("#tag_no").val(data.tag_no != "" ? data.tag_no : 0);
                $("#gold_carat").val(data.gold_carat > 0 ? data.gold_carat : 0).trigger("keyup");
                $("#scale_weight").val(
                    data.scale_weight > 0 ? data.scale_weight : 0
                );
                $("#net_weight").val(data.net_weight > 0 ? data.net_weight : 0);
                $("#net_weight").val(data.net_weight > 0 ? data.net_weight : 0);
                $("#gross_weight").val(
                    data.gross_weight > 0 ? data.gross_weight : 0
                );
                $("#bead_weight").val(data.bead_weight > 0 ? data.bead_weight : 0);
                $("#stones_weight").val(
                    data.stones_weight > 0 ? data.stones_weight : 0
                );
                $("#diamond_weight").val(
                    data.diamond_weight > 0 ? data.diamond_weight : 0
                );
                $("#making").val(data.making > 0 ? data.making : 0);
                $("#other_amount").val(
                    data.other_amount > 0 ? data.other_amount : 0
                );
                $("#waste").val(data.waste > 0 ? data.waste : 0);
                GoldRate();
                BeadByFinishDetail(data.id);
                StonesByFinishDetail(data.id);
                DiamondsByFinishDetail(data.id);
                TotalGoldAmount();
                TotalAmount();
            }
            $("#preloader").hide();
        } else {
            error(data.Message);
            $("#preloader").hide();
        }
    });
}

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

$("#waste").on("keyup", function (event) {
    var waste = $("#waste").val();
    var net_weight = $("#net_weight").val();
    var cal = (waste / net_weight) * 100;
    var gross_wt = net_weight * 1 + waste * 1;
    $("#waste_per").val(cal.toFixed(3));
    $("#gross_weight").val(gross_wt.toFixed(3));
    TotalGoldAmount();
    TotalAmount();
});

$("#making").on("keyup", function (event) {
    var making = $("#making").val();
    var gross_weight = $("#gross_weight").val();
    var making_gram = (making * 1) / (gross_weight * 1);
    $("#making_gram").val(making_gram.toFixed(3));
    TotalAmount();
});

$("#other_amount").on("keyup", function (event) {
    TotalAmount();
});
function GoldRate() {
    var gold_carat = $("#gold_carat").val();
    var gold_carat_rate = (gold_rate / 24) * (gold_carat * 1);
    var gold_rate_gram = gold_carat_rate / 11.664;
    $("#gold_rate").val(gold_rate_gram.toFixed(3));
    TotalGoldAmount();
    TotalAmount();
};
$("#gold_rate").on("keyup", function (event) {
    TotalGoldAmount();
    TotalAmount();
});
function TotalGoldAmount() {
    var gross_wt = $("#gross_weight").val();
    var gold_rate = $("#gold_rate").val();
    var total_gold_price = (gold_rate * 1) * (gross_wt * 1);
    $("#total_gold_price").val(total_gold_price.toFixed(3));
}
function TotalAmount() {
    var total_bead_price = $("#total_bead_price").val();
    var total_stones_price = $("#total_stones_price").val();
    var total_diamond_price = $("#total_diamond_price").val();
    var other_amount = $("#other_amount").val();
    var making = $("#making").val();
    var total_gold_price = $("#total_gold_price").val();
    var cal =
        total_bead_price * 1 +
        total_stones_price * 1 +
        total_diamond_price * 1 +
        other_amount * 1 +
        making * 1 +
        total_gold_price * 1;

    $("#total_amount").val(cal.toFixed(3));
}

function ChildAdd(data) {
    productData = [];
    product_sr = 0;
    var rows = "";
    var total = 0;
    $.each(data, function (e, val) {
        productData.push({
            // sr: i,
            tag_no: val.tag_no,
            finish_product_id: val.finish_product_id,
            ratti_kaat_id: val.ratti_kaat_id,
            ratti_kaat_detail_id: val.ratti_kaat_detail_id,
            job_purchase_detail_id: val.job_purchase_detail_id,
            product: val.product,
            product_id: val.product_id,
            gold_carat: val.gold_carat,
            scale_weight: val.scale_weight,
            bead_weight: val.bead_weight,
            stones_weight: val.stones_weight,
            diamond_weight: val.diamond_weight,
            net_weight: val.net_weight,
            gross_weight: val.gross_weight,
            waste: val.waste,
            making: val.making,
            gold_rate: val.gold_rate,
            total_gold_price: val.total_gold_price,
            other_amount: val.other_amount,
            total_bead_price: val.total_bead_price,
            total_stones_price: val.total_stones_price,
            total_diamond_price: val.total_diamond_price,
            total_amount: val.total_amount,
            beadDetail: val.beadDetail,
            stonesDetail: val.stonesDetail,
            diamondDetail: val.diamondDetail,
        });

    });

    $("#total").val(total);

    $.each(productData, function (e, val) {
        product_sr = product_sr + 1;
        productData.sr = product_sr;

        rows += `<tr id="${val.finish_product_id}"><td>${product_sr}</td><td>${val.tag_no}</td><td>${val.product}</td>
                <td>${val.gold_carat}</td><td style="text-align: right;">${val.scale_weight}</td><td style="text-align: right;">${val.bead_weight}</td>
                <td style="text-align: right;">${val.stones_weight}</td><td style="text-align: right;">${val.diamond_weight}
                <td style="text-align: right;">${val.net_weight}</td><td style="text-align: right;">${val.waste}</td>
                </td><td style="text-align: right;">${val.gross_weight}</td><td style="text-align: right;" >${val.gold_rate}</td>
          <td style="text-align: right;" >${val.total_gold_price}</td><td style="text-align: right;" >${val.making}</td>
          <td style="text-align: right;" >${val.other_amount}</td><td style="text-align: right;" >${val.total_amount}</td>
          <td><a class="text-warning text-white" id="Detail" href="javascript:void(0)" data-toggle="tooltip"  data-id="${val.finish_product_id}" data-original-title="Detail"><i title="Detail" class="mr-2 fa fa-eye"></i></a>
          <a class="text-danger text-white productr${val.finish_product_id}" onclick="ProductRemove(${val.finish_product_id})"><i class="fa fa-trash"></i></a></td></tr>`;

        total += val.total_amount * 1;
    });
    $("#total").val(total);
    $("#grand_total").val(total.toFixed(3));
    success("Product Added Successfully!");
    $("#products").empty();
    console.log(rows);
    $("#products").html(rows);
    Clear();
    ProductShort();
    $("#preloader").hide();
}

function addProduct() {
    $("#preloader").show();
    var tag_no = $("#tag_no").val();
    var finish_product_id = $("#finish_product_id").val();
    var ratti_kaat_id = $("#ratti_kaat_id").val();
    var ratti_kaat_detail_id = $("#ratti_kaat_detail_id").val();
    var job_purchase_detail_id = $("#job_purchase_detail_id").val();
    var product = $("#product").val();
    var product_id = $("#product_id").val();
    var gold_carat = $("#gold_carat").val();
    var scale_weight = $("#scale_weight").val();
    var bead_weight = $("#bead_weight").val();
    var stones_weight = $("#stones_weight").val();
    var diamond_weight = $("#diamond_weight").val();
    var net_weight = $("#net_weight").val();
    var gross_weight = $("#gross_weight").val();
    var waste = $("#waste").val();
    var making = $("#making").val();
    var gold_rate = $("#gold_rate").val();
    var total_gold_price = $("#total_gold_price").val();
    var total_bead_price = $("#total_bead_price").val();
    var total_stones_price = $("#total_stones_price").val();
    var total_diamond_price = $("#total_diamond_price").val();
    var other_amount = $("#other_amount").val();
    var total_amount = $("#total_amount").val();

    if (
        tag_no == "" ||
        finish_product_id == ""
    ) {
        error("tag is not selected!");
        $("#preloader").hide();
        return false;
    }
    if (product == 0 || product == "") {
        error("Please Enter product!");
        $("#preloader").hide();
        return false;
    }
    if (gold_carat == 0 || gold_carat == "") {
        error("Please Enter karat!");
        $("#preloader").hide();
        return false;
    }
    if (scale_weight == 0 || scale_weight == "") {
        error("Please Enter scale weight!");
        $("#preloader").hide();
        return false;
    }
    if (net_weight == 0 || net_weight == "") {
        error("Please Enter net weight!");
        $("#preloader").hide();
        return false;
    }
    if (gross_weight == 0 || gross_weight == "") {
        error("Please Enter gross weight!");
        $("#preloader").hide();
        return false;
    }
    if (waste == 0 || waste == "") {
        error("Please Enter waste!");
        $("#preloader").hide();
        return false;
    }
    if (gold_rate == 0 || gold_rate == "") {
        error("Please Enter gold rate!");
        $("#preloader").hide();
        return false;
    }
    var check = true;
    $.each(productData, function (e, val) {
        if (val.finish_product_id == $("#finish_product_id").val()) {
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

    productData.push({
        // sr: i,
        tag_no: tag_no,
        finish_product_id: finish_product_id,
        ratti_kaat_id: ratti_kaat_id,
        ratti_kaat_detail_id: ratti_kaat_detail_id,
        job_purchase_detail_id: job_purchase_detail_id,
        product: product,
        product_id: product_id,
        gold_carat: gold_carat,
        scale_weight: scale_weight,
        bead_weight: bead_weight,
        stones_weight: stones_weight,
        diamond_weight: diamond_weight,
        net_weight: net_weight,
        gross_weight: gross_weight,
        waste: waste,
        making: making,
        gold_rate: gold_rate,
        total_gold_price: total_gold_price,
        other_amount: other_amount,
        total_bead_price: total_bead_price,
        total_stones_price: total_stones_price,
        total_diamond_price: total_diamond_price,
        total_amount: total_amount,
        beadDetail: beadData,
        stonesDetail: stonesData,
        diamondDetail: diamondsData,
    });

    $("#total").val(total);

    $.each(productData, function (e, val) {
        product_sr = product_sr + 1;
        productData.sr = product_sr;

        rows += `<tr id="${val.finish_product_id}"><td>${product_sr}</td><td>${val.tag_no}</td><td>${val.product}</td>
                <td>${val.gold_carat}</td><td style="text-align: right;">${val.scale_weight}</td><td style="text-align: right;">${val.bead_weight}</td>
                <td style="text-align: right;">${val.stones_weight}</td><td style="text-align: right;">${val.diamond_weight}
                <td style="text-align: right;">${val.net_weight}</td><td style="text-align: right;">${val.waste}</td>
                </td><td style="text-align: right;">${val.gross_weight}</td><td style="text-align: right;" >${val.gold_rate}</td>
          <td style="text-align: right;" >${val.total_gold_price}</td><td style="text-align: right;" >${val.making}</td>
          <td style="text-align: right;" >${val.other_amount}</td><td style="text-align: right;" >${val.total_amount}</td>
          <td><a class="text-warning text-white" id="Detail" href="javascript:void(0)" data-toggle="tooltip"  data-id="${val.finish_product_id}" data-original-title="Detail"><i title="Detail" class="mr-2 fa fa-eye"></i></a>
          <a class="text-danger text-white productr${val.finish_product_id}" onclick="ProductRemove(${val.finish_product_id})"><i class="fa fa-trash"></i></a></td></tr>`;

        total += val.total_amount * 1;
    });
    $("#total").val(total);
    $("#grand_total").val(total.toFixed(3));
    success("Product Added Successfully!");
    $("#products").empty();
    console.log(rows);
    $("#products").html(rows);
    Clear();
    ProductShort();
    $("#preloader").hide();
}

$("body").on("click", "#Detail", function (event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    let data = productData.filter(
        (product) => product.finish_product_id === String($(this).data("id"))
    );
    var bead_sr = 0;
    var bead_row = "";
    var stone_sr = 0;
    var stone_row = "";
    var diamond_sr = 0;
    var diamond_row = "";
    $("#preloader").show();
    $.each(data, function (e, val) {
        console.log(val.beadDetail);

        $.each(val.beadDetail, function (e, val) {
            bead_sr = bead_sr + 1;
            beadData.sr = bead_sr;
            bead_row += `<tr><td>${bead_sr}</td><td>${val.type}</td><td>${val.beads}</td><td style="text-align: right;">${val.gram}</td><td style="text-align: right;" >${val.carat}</td>
                    <td style="text-align: right;" >${val.gram_rate}</td><td style="text-align: right;" >${val.carat_rate}</td><td style="text-align: right;" >${val.total_amount}</td>
                    </tr>`;
        });
        $("#beadDetail").html(bead_row);

        $.each(val.stonesDetail, function (e, val) {
            stone_sr = stone_sr + 1;
            stonesData.sr = stone_sr;
            stone_row += `<tr><td>${stone_sr}</td><td>${val.category}</td><td>${val.type}</td><td>${val.stones}</td><td style="text-align: right;">${val.gram}</td><td style="text-align: right;" >${val.carat}</td>
                <td style="text-align: right;" >${val.gram_rate}</td><td style="text-align: right;" >${val.carat_rate}</td><td style="text-align: right;" >${val.total_amount}</td>
                </tr>`;
        });
        $("#stoneDetail").html(stone_row);

        $.each(val.diamondDetail, function (e, val) {
            diamond_sr = diamond_sr + 1;
            diamondsData.sr = diamond_sr;
            diamond_row += `<tr><td>${diamond_sr}</td><td>${val.diamonds}</td><td>${val.type}</td>
                <td >${val.cut}</td><td >${val.color}</td><td >${val.clarity}</td><td style="text-align: right;" >${val.carat}</td>
                <td style="text-align: right;" >${val.carat_rate}</td><td style="text-align: right;" >${val.total_amount}</td>
                </tr>`;
        });
        $("#diamondDetail").html(diamond_row);
    });

    $("#detailModel").modal("show");
    $("#preloader").hide();
});

$("body").on("click", "#submit", function (e) {
    e.preventDefault();

    $("#preloader").show();
    // Validation logic
    if ($("#sale_date").val() == "") {
        error("Please select sale date!");
        $("#sale_date").focus();
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

    if ($("#grand_total").val() == "" || $("#grand_total").val() == 0) {
        error("Subtotal is zero!");
        $("#grand_total").focus();
        $("#preloader").hide();
        return false;
    }
    if ($("#grand_total_total").val() == "" || $("#grand_total_total").val() == 0) {
        error("Total is zero!");
        $("#grand_total_total").focus();
        $("#preloader").hide();
        return false;
    }

    // Create FormData object for Ajax
    var formData = new FormData();
    formData.append("id", $("#id").val());
    formData.append("sale_date", $("#sale_date").val());
    formData.append("customer_id", $("#customer_id").find(":selected").val());
    formData.append("sub_total", $("#grand_total").val());
    formData.append("total", $("#grand_total_total").val());
    formData.append("discount_amount", $("#discount_amount").val());

    formData.append("productDetail", JSON.stringify(productData));

    $.ajax({
        url: url_local + "/sale/store", // Laravel route
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
                    window.location = url_local + "/sale";
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
        $("#preloader").show();
        $.each(productData, function (i, val) {
            if (val.finish_product_id == id) {
                total = $("#grand_total").val() * 1 - val.total_amount * 1;
                $("#grand_total").val(total > 0 ? total : 0);
                $("#" + id).hide();
                item_index = i;
                return false;
            }
        });

        productData.splice(item_index, 1);
        var check = ".productr" + id;
        $(check).closest("tr").remove();
        success("Product Deleted Successfully!");
        // GrandTotalAmount();
        ProductShort();
        $("#preloader").hide();
    });
}

function Clear() {
    $("#select_tag_no option[value='0']").remove();
    $("#select_tag_no").append(
        '<option disabled selected value="0">--Select Tag No--</option>'
    );
    $("#search_tag_no").val("");
    $("#finish_product_id").val("");
    $("#ratti_kaat_id").val("");
    $("#ratti_kaat_detail_id").val("");
    $("#tag_no").val("");
    $("#product").val("");
    $("#product_id").val("");
    $("#gold_carat").val("");
    $("#scale_weight").val("");
    $("#bead_weight").val(0);
    $("#stones_weight").val(0);
    $("#diamond_weight").val(0);
    $("#net_weight").val(0);
    $("#gross_weight").val(0);
    $("#waste").val(0);
    $("#making").val(0);
    $("#total_bead_price").val(0);
    $("#total_stones_price").val(0);
    $("#total_diamond_price").val(0);
    $("#other_amount").val(0);
    $("#total_gold_price").val(0);
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
