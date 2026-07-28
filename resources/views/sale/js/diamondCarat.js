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
    if ($("#finish_product_id").val() == '') {
        error("Tag is not selected!");
        return false;
    }
    $("#diamondCaratForm").trigger("reset");
    $("#diamondCaratModel").modal("show");
});

$("#carat").on("keyup", function (event) {
    var carat = $("#carat").val();
    var carat_rate = $("#carat_rate").val();
    var cal = carat * carat_rate;
    var dollars= cal/dollar_rate;
    $("#diamond_total").val(cal.toFixed(3));
    $("#diamond_total_dollar").val(dollars.toFixed(3));
});

$("#carat_rate").on("keyup", function (event) {
    var carat = $("#carat").val();
    var carat_rate = $("#carat_rate").val();
    var cal = carat * carat_rate;
    var dollars= cal/dollar_rate;
    $("#diamond_total").val(cal.toFixed(3));
    $("#diamond_total_dollar").val(dollars.toFixed(3));
});
var diamondsData = [];
var diamond_sr = 0;

function addDiamond() {
    
    var finish_product_id = $("#finish_product_id").val();
    var type = $("#diamond_type option:selected").val();
    var diamonds = $("#diamonds").val();
    var carat = $("#carat").val();
    var cut = $("#cut").val();
    var color = $("#color").val();
    var clarity = $("#clarity").val();
    var carat_rate = $("#carat_rate").val();
    var diamond_total = $("#diamond_total").val();
    if (type == 0 || type == '') {
        error("type is not selected!");
        return false;
    }
    if (diamonds == 0 || diamonds == "") {
        error("Please enter diamonds!");
        return false;
    }
    if (carat == 0 || carat == "") {
        error("Please enter diamond carat");
        return false;
    }
    if (cut == 0 || cut == "") {
        error("Please enter diamond cut");
        return false;
    }
    if (color == 0 || color == "") {
        error("Please enter diamond color");
        return false;
    }
    if (clarity == 0 || clarity == "") {
        error("Please enter diamond clarity");
        return false;
    }
    if (carat_rate == 0 || carat_rate == "") {
        error("Please enter diamond carat rate");
        return false;
    }
    if (diamond_total == 0 || diamond_total == "") {
        error("Please enter total diamond amount");
        return false;
    }
    var check = true;
    $.each(diamondsData, function (e, val) {
        var valuetype=val.type;
        if (valuetype.replace(/\s+/g, '') + Math.floor(val.diamonds) + Math.floor(val.carat) == type.replace(/\s+/g, '') + Math.floor(diamonds) + Math.floor(carat)) {
            error("This Diamond is already added !");
            check = false;
            return false;
        }
    });
    if (check == false) {
        return;
    }
    diamond_sr = diamond_sr + 1;
    var tbody = $("#diamondsTable tbody");
    tbody.empty();
    var rows = "";
    var total = 0;
    var total_weight = 0;

    diamondsData.push({
        // sr: i,
        finish_product_id:finish_product_id,
        type: type,
        diamonds: diamonds,
        cut: cut,
        color: color,
        clarity: clarity,
        carat: carat,
        carat_rate: carat_rate,
        total_amount: diamond_total,
    });
    $.each(diamondsData, function (e, val) {
        diamond_sr = diamond_sr + 1;
        diamondsData.sr = diamond_sr;
        var type = val.type;
        
        rows += `<tr id=${type.replace(/\s+/g, '')+ finish_product_id + Math.floor(val.diamonds) + Math.floor(val.carat)}><td>${i}</td><td>${val.diamonds}</td><td>${val.type}</td>
                <td >${val.cut}</td><td >${val.color}</td><td >${val.clarity}</td><td style="text-align: right;" >${val.carat}</td>
          <td style="text-align: right;" >${val.carat_rate}</td><td style="text-align: right;" >${val.total_amount}</td>
          <td><a class="text-danger text-white diamondr${type.replace(/\s+/g, '') + finish_product_id + Math.floor(val.diamonds) + Math.floor(val.carat)}" onclick="DiamondRemove('${type.replace(/\s+/g, '') + finish_product_id + Math.floor(val.diamonds) + Math.floor(val.carat)}')"><i class="fa fa-trash"></i></a></td></tr>`;
        total += val.total_amount * 1;
        total_weight += val.carat * 1;
    });

    $("#total_diamond_price").val(total.toFixed(3));
    $("#diamond_weight").val(total_weight.toFixed(3));
    
    TotalAmount();
    netWeight();
    success("Diamond Added Successfully!");
    tbody.prepend(rows);
    diamondshort();
    $("#diamondCaratForm").trigger("reset");
}
function DiamondsByFinishDetail(finish_product_id) {
    $.ajax({
        type: "GET",
        url: url_local + "/finish-product/get-diamond-by-id" + "/" + finish_product_id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },

        success: function (data) {
            console.log(data);
            diamond_sr = 0;
            diamondsData = data.Data;
            var tbody = $("#diamondsTable tbody");
            tbody.empty();
            var rows = "";
            var total = 0;
            var total_weight = 0;
            $.each(diamondsData, function (e, val) {
                diamond_sr = diamond_sr + 1;
                diamondsData.sr = diamond_sr;
                
                var type = val.type;
                rows += `<tr id=${type.replace(/\s+/g, '') + Math.floor(val.diamonds) + Math.floor(val.carat)}><td>${i}</td><td>${val.diamonds}</td><td>${val.type}</td>
                <td >${val.cut}</td><td >${val.color}</td><td >${val.clarity}</td><td style="text-align: right;" >${val.carat}</td>
          <td style="text-align: right;" >${val.carat_rate}</td><td style="text-align: right;" >${val.total_amount}</td>
          <td><a class="text-danger text-white diamondr${type.replace(/\s+/g, '') + Math.floor(val.diamonds) + Math.floor(val.carat)}" onclick="DiamondRemove('${type.replace(/\s+/g, '') + Math.floor(val.diamonds) + Math.floor(val.carat)}')"><i class="fa fa-trash"></i></a></td></tr>`;
                total += val.total_amount * 1;
                total_weight += val.carat * 1;
            });

            $("#total_diamond_price").val(total.toFixed(3));
            $("#diamond_weight").val(total_weight.toFixed(3));
            TotalAmount();
            netWeight();
            tbody.prepend(rows);
        },
    });
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
        var total_weight = 0;
        $.each(diamondsData, function (diamond_sr, val) {
            
            var type = val.type;
            if (type.replace(/\s+/g, '') + Math.floor(val.diamonds) + Math.floor(val.carat) == id) {
                total = $("#total_diamond_price").val() * 1 - val.total_amount * 1;
                total_weight = $("#diamond_weight").val() * 1 - val.carat * 1;
                $("#total_diamond_price").val(total > 0 ? total : 0);
                $("#diamond_weight").val(total_weight > 0 ? total_weight : 0);
                $("#" + id).hide();
                item_index = diamond_sr;
                return false;
            }
        });

        diamondsData.splice(item_index, 1);
        var check = ".diamondr" + id;
        $(check).closest("tr").remove();
        success("Diamond Deleted Successfully!");
        TotalAmount();
        netWeight();
        diamondshort();
    });
}
// Short
function diamondshort() {
    var table, j, x, y;
    table = document.getElementById("diamondsTable");
    var switching = true;

    // Run loop until no switching is needed
    while (switching) {
        switching = false;
        var rows = table.rows;

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