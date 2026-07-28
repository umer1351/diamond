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

$("#BeadWeightButton").click(function () {
    if ($("#product_id").val() == '') {
        error("Product is not selected!");
        return false;
    }
    $("#beadWeightForm").trigger("reset");
    $("#beadWeightModel").modal("show");
});

$("#bead_gram").on("keyup", function (event) {
    var bead_gram = $("#bead_gram").val();
    var cal = bead_gram * 5;
    $("#bead_carat").val(cal.toFixed(3));
    bead_total();
});
$("#bead_carat").on("keyup", function (event) {
    var bead_carat = $("#bead_carat").val();
    var cal = bead_carat / 5;
    $("#bead_gram").val(cal.toFixed(3));
    bead_total();
});

$("#bead_carat_rate").on("keyup", function (event) {
    var bead_carat_rate = $("#bead_carat_rate").val();
    var cal = bead_carat_rate * 5;
    $("#bead_gram_rate").val(cal.toFixed(3));
    bead_total();
});
$("#bead_gram_rate").on("keyup", function (event) {
    var bead_gram_rate = $("#bead_gram_rate").val();
    var cal = bead_gram_rate / 5;
    $("#bead_carat_rate").val(cal.toFixed(3));
    bead_total();
});
function bead_total() {
    var bead_carat = $("#bead_carat").val();
    var bead_carat_rate = $("#bead_carat_rate").val();
    var cal = bead_carat * bead_carat_rate;
    $("#bead_total").val(cal.toFixed(3));
}


var beadData = [];
var i = 0;
function addBead() {
    var product_id = $("#product_id").val();
    var type = $("#type option:selected").val();
    var beads = $("#beads").val();
    var bead_gram = $("#bead_gram").val();
    var bead_carat = $("#bead_carat").val();
    var bead_gram_rate = $("#bead_gram_rate").val();
    var bead_carat_rate = $("#bead_carat_rate").val();
    var bead_total = $("#bead_total").val();
    if (product_id == 0 || product_id == '') {
        error("Product is not selected!");
        return false;
    }
    if (type == 0 || type == '') {
        error("Type is not selected!");
        return false;
    }
    if (beads == 0 || beads == "") {
        error("Please enter beads!");
        return false;
    }
    if (bead_gram == 0 || bead_gram == "") {
        error("Please enter bead in gram");
        return false;
    }
    if (bead_carat == 0 || bead_carat == "") {
        error("Please enter bead in carat");
        return false;
    }
    if (bead_gram_rate == 0 || bead_gram_rate == "") {
        error("Please enter bead gram rate");
        return false;
    }
    if (bead_carat_rate == 0 || bead_carat_rate == "") {
        error("Please enter bead carat rate");
        return false;
    }
    if (bead_total == 0 || bead_total == "") {
        error("Please enter total bead amount");
        return false;
    }
    var check = true;
    $.each(beadData, function (e, val) {
        if (val.beads + val.bead_gram == beads + bead_gram) {
            error("This Bead is already added !");
            check = false;
            return false;
        }
    });
    if (check == false) {
        return;
    }
    var tbody = $("#beadTable tbody");
    tbody.empty();
    var rows = "";
    var total = 0;
    var total_weight = 0;

    beadData.push({
        // sr: i,
        product_id: product_id,
        type: type,
        beads: beads,
        gram: bead_gram,
        carat: bead_carat,
        gram_rate: bead_gram_rate,
        carat_rate: bead_carat_rate,
        total_amount: bead_total,
    });
    $.each(beadData, function (e, val) {
        i = i + 1;
        beadData.sr = i;
        var type = val.type;
        rows += `<tr id=${type.replace(/\s+/g, '') + val.product_id + Math.floor(val.beads) + Math.floor(val.gram)}><td>${i}</td><td>${val.type}</td><td>${val.beads}</td><td style="text-align: right;">${val.gram}</td><td style="text-align: right;" >${val.carat}</td>
            <td style="text-align: right;" >${val.gram_rate}</td><td style="text-align: right;" >${val.carat_rate}</td><td style="text-align: right;" >${val.total_amount}</td>
            <td><a class="text-danger text-white r${type.replace(/\s+/g, '') + val.product_id + Math.floor(val.beads) + Math.floor(val.gram)}" onclick="Remove('${type.replace(/\s+/g, '') + val.product_id + Math.floor(val.beads) + Math.floor(val.gram)}')"><i class="fa fa-trash"></i></a></td></tr>`;
        total += val.total_amount * 1;
        total_weight += val.gram * 1;
    });

    $("#total_bead_price").val(total.toFixed(3));
    $("#bead_weight").val(total_weight.toFixed(3));
    TotalAmount();
    totalRecievedWeight();
    finalPureWeight();
    success("Bead Added Successfully!");
    tbody.prepend(rows);
    Short();
    $("#beadWeightForm").trigger("reset");
}

function Remove(id) {

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
        $.each(beadData, function (i, val) {
            var type = val.type;
            if (type.replace(/\s+/g, '') + val.product_id + Math.floor(val.beads) + Math.floor(val.gram) == id) {
                total = $("#total_bead_price").val() * 1 - val.total_amount * 1;
                total_weight = $("#bead_weight").val() * 1 - val.gram * 1;
                $("#total_bead_price").val(total > 0 ? total : 0);
                $("#bead_weight").val(total_weight > 0 ? total_weight : 0);
                $("#" + id).hide();
                item_index = i;
                return false;
            }
        });

        beadData.splice(item_index, 1);
        var check = ".r" + id;
        $(check).closest("tr").remove();
        success("Bead Deleted Successfully!");
        TotalAmount();
        totalRecievedWeight();
        finalPureWeight();
        Short();
    });
}
// Short
function Short() {
    var table, j, x, y;
    table = document.getElementById("beadTable");
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
