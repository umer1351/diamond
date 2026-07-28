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

$("#DiamondCaratButton").click(function () {
    if ($("#product_id").find(":selected").val() == 0) {
        error("Product is not selected!");
        return false;
    }
    $("#diamondCaratForm").trigger("reset");
    $("#diamondCaratModel").modal("show");
});

$("#carat").on("keyup", function (event) {
    var carat = $("#carat").val();
    var carat_rate = $("#carat_rate").val();
    var cal = carat * carat_rate;
    var dollars = cal / dollar_rate;
    $("#diamond_total").val(cal.toFixed(3));
    $("#diamond_total_dollar").val(dollars.toFixed(3));
});

$("#carat_rate").on("keyup", function (event) {
    var carat = $("#carat").val();
    var carat_rate = $("#carat_rate").val();
    var cal = carat * carat_rate;
    var dollars = cal / dollar_rate;
    $("#diamond_total").val(cal.toFixed(3));
    $("#diamond_total_dollar").val(dollars.toFixed(3));
});

DiamondClear();
function addDiamondProduct() {
    $("#preloader").show();
    var diamonds = $("#diamonds").val();
    var type = $("#diamond_type").find(":selected").val();
    var cut = $("#cut").find(":selected").val();
    var color = $("#color").find(":selected").val();
    var clarity = $("#clarity").find(":selected").val();
    var carat = $("#carat").val();
    var carat_rate = $("#carat_rate").val();
    var diamond_total = $("#diamond_total").val();
    var diamond_total_dollar = $("#diamond_total_dollar").val();

    if (diamonds == "" && diamonds == 0) {
        error("Please enter diamonds!");
        $("#diamonds").focus();
        return false;
    }
    console.log(type);
    if (type == "" || type == 0) {
        error("Please select diamond type!");
        $("#type").focus();
        return false;
    }
    if (cut == "" || cut == 0) {
        error("Please select diamond cut!");
        $("#cut").focus();
        return false;
    }
    if (color == "" && color == 0) {
        error("Please select diamond color!");
        $("#color").focus();
        return false;
    }
    if (clarity == "" && clarity == 0) {
        error("Please select clarity!");
        $("#clarity").focus();
        return false;
    }
    if (carat == "" && carat == 0) {
        error("Please enter diamond in carat!");
        $("#carat").focus();
        return false;
    }

    if (carat_rate == "" && carat_rate == 0) {
        error("Please enter carat rate!");
        $("#carat_rate").focus();
        return false;
    }
    if (diamond_total == "" && diamond_total == 0) {
        error("Please enter diamond total!");
        $("#diamond_total").focus();
        return false;
    }
    var check = true;
    $.each(diamondData, function (e, val) {
        if (removeSpaces(val.type) + removeSpaces(val.cut) + removeSpaces(val.color) == removeSpaces(type) + removeSpaces(cut) + removeSpaces(color)) {
            error("Diamond is already added !");
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
    var diamond_carat = 0;

    diamondData.push({
        // sr: i,
        diamonds: diamonds,
        type: type,
        cut: cut,
        color: color,
        clarity: clarity,
        carat: carat,
        carat_rate: carat_rate,
        total_amount: diamond_total,
        total_dollar: diamond_total_dollar,
    });

    $("#total").val(total);

    $.each(diamondData, function (index, val) {

        rows += `<tr id="${removeSpaces(val.type)}${removeSpaces(val.cut)}${removeSpaces(val.color)}">
                <td>${val.diamonds}</td>
                <td>${val.type}</td>
                <td>${val.cut}</td>
                <td>${val.color}</td>
                <td>${val.clarity}</td>
                <td style="text-align: right;">${val.carat}</td>
                <td style="text-align: right;">${val.carat_rate}</td>
                <td style="text-align: right;">${val.total_amount}</td>
                <td style="text-align: right;">${val.total_dollar}</td>
                <td><a class="text-danger text-white diamondproductr${removeSpaces(val.type)}${removeSpaces(val.cut)}${removeSpaces(val.color)}" onclick="DiamondRemove('${removeSpaces(val.type)}${removeSpaces(val.cut)}${removeSpaces(val.color)}')"><i class="fa fa-trash"></i></a></td></tr>`;

        total += val.total_amount * 1;
        diamond_carat = diamond_carat * 1 + val.carat * 1;
        total_dollar = total_dollar * 1 + val.total_dollar * 1;
    });
    $("#total_diamond_amount").val(total.toFixed(3));
    $("#diamond_carat").val(diamond_carat.toFixed(3)).trigger("keyup");
    $("#total_dollar").val(total_dollar.toFixed(3));
    success("Diamond Added Successfully!");
    $("#diamonds_products").empty();
    netWeight();
    totalAmount();
    $("#diamonds_products").html(rows);
    DiamondClear();
    DiamondShort();
    $("#preloader").hide();
}

function DiamondRemove(id) {
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
        var diamond_carat = 0;
        $("#preloader").show();
        $.each(diamondData, function (index, val) {
            if (removeSpaces(val.type) + removeSpaces(val.cut) + removeSpaces(val.color) == id) {
                total = $("#total_diamond_amount").val() * 1 - val.total_amount * 1;
                total_dollar = $("#total_dollar").val() * 1 - val.total_dollar * 1;
                diamond_carat = diamond_carat * 1 - val.carat * 1;
                $("#total_diamond_amount").val(total > 0 ? total : 0);
                $("#total_dollar").val(total_dollar > 0 ? total_dollar : 0);
                $("#diamond_carat").val(diamond_carat > 0 ? diamond_carat : 0);
                $("#" + id).hide();
                item_index = index;
                return false;
            }
        });

        diamondData.splice(item_index, 1);
        var check = ".diamondproductr" + id;
        $(check).closest("tr").remove();
        success("Diamond Deleted Successfully!");
        DiamondShort();
        $("#preloader").hide();
    });
}

function DiamondClear() {
    $("#diamond_type").val('');
    $("#diamonds").val('');
    $("#cut").val('');
    $("#carat").val('');
    $("#color").val('');
    $("#clarity").val('');
    $("#carat_rate").val('');
    $("#diamond_total").val('');
    $("#diamond_total_dollar").val('');

}

// Short
function DiamondShort() {
    var tbody, j, x, y;
    tbody = document.getElementById("diamonds_products");
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
