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

$("#StonesWeightButton").click(function () {
    if ($("#ratti_kaat_detail_id").val() == '' && $("#job_purchase_detail_id").val()=='') {
        error("Purchase is not selected!");
        return false;
    }
    $("#stoneWeightForm").trigger("reset");
    $("#stoneWeightModel").modal("show");
});

$("#stone_gram").on("keyup", function (event) {
    var stone_gram = $("#stone_gram").val();
    var cal = stone_gram * 5;
    $("#stone_carat").val(cal.toFixed(3));
    stone_total();
});
$("#stone_carat").on("keyup", function (event) {
    var stone_carat = $("#stone_carat").val();
    var cal = stone_carat / 5;
    $("#stone_gram").val(cal.toFixed(3));
    stone_total();
});

$("#stone_carat_rate").on("keyup", function (event) {
    var stone_carat_rate = $("#stone_carat_rate").val();
    var cal = stone_carat_rate * 5;
    $("#stone_gram_rate").val(cal.toFixed(3));
    stone_total();
});
$("#stone_gram_rate").on("keyup", function (event) {
    var stone_gram_rate = $("#stone_gram_rate").val();
    var cal = stone_gram_rate / 5;
    $("#stone_carat_rate").val(cal.toFixed(3));
    stone_total();
});
function stone_total() {
    var stone_carat = $("#stone_carat").val();
    var stone_carat_rate = $("#stone_carat_rate").val();
    var cal = stone_carat * stone_carat_rate;
    $("#stone_total").val(cal.toFixed(3));
}

var stonesData = [];
function addStones() {
    var category = $("#category option:selected").val();
    var type = $("#stone_type").val();
    var stones = $("#stones").val();
    var stone_gram = $("#stone_gram").val();
    var stone_carat = $("#stone_carat").val();
    var stone_gram_rate = $("#stone_gram_rate").val();
    var stone_carat_rate = $("#stone_carat_rate").val();
    var stone_total = $("#stone_total").val();
    if (category == 0 || category == '') {
        error("Category is not selected!");
        return false;
    }
    if (type == 0 || type == '') {
        error("Please enter type!");
        return false;
    }
    if (stones == 0 || stones == "") {
        error("Please enter stones!");
        return false;
    }
    if (stone_gram == 0 || stone_gram == "") {
        error("Please enter stones in gram");
        return false;
    }
    if (stone_carat == 0 || stone_carat == "") {
        error("Please enter stones in carat");
        return false;
    }
    if (stone_gram_rate == 0 || stone_gram_rate == "") {
        error("Please enter stone gram rate");
        return false;
    }
    if (stone_carat_rate == 0 || stone_carat_rate == "") {
        error("Please enter stone carat rate");
        return false;
    }
    if (stone_total == 0 || stone_total == "") {
        error("Please enter total stone amount");
        return false;
    }
    var check = true;
    $.each(stonesData, function (e, val) {
        if (val.stones + val.bead_gram == stones + bead_gram) {
            error("This Stone is already added !");
            check = false;
            return false;
        }
    });
    if (check == false) {
        return;
    }
    var tbody = $("#stonesTable tbody");
    tbody.empty();
    var rows = "";
    var total = 0;
    var total_weight = 0;

    stonesData.push({
        // sr: i,
        category: category,
        type: type,
        stones: stones,
        gram: stone_gram,
        carat: stone_carat,
        gram_rate: stone_gram_rate,
        carat_rate: stone_carat_rate,
        total_amount: stone_total,
    });
    $.each(stonesData, function (e, val) {
        var category = val.category;
        
        rows += `<tr id=${category.replace(/\s+/g, '') + Math.floor(val.stones) + Math.floor(val.gram)}><td>${val.category}</td><td>${val.type}</td><td>${val.stones}</td><td style="text-align: right;">${val.gram}</td><td style="text-align: right;" >${val.carat}</td>
            <td style="text-align: right;" >${val.gram_rate}</td><td style="text-align: right;" >${val.carat_rate}</td><td style="text-align: right;" >${val.total_amount}</td>
            <td><a class="text-danger text-white stoner${category.replace(/\s+/g, '') + Math.floor(val.stones) + Math.floor(val.gram)}" onclick="StoneRemove('${category.replace(/\s+/g, '') + Math.floor(val.stones) + Math.floor(val.gram)}')"><i class="fa fa-trash"></i></a></td></tr>`;
        total += val.total_amount * 1;
        total_weight += val.gram * 1;
    });

    $("#total_stones_price").val(total.toFixed(3));
    $("#stones_weight").val(total_weight.toFixed(3));
    
    TotalAmount();
    netWeight();
    success("Stones Added Successfully!");
    tbody.prepend(rows);
    StoneShort();
    $("#stoneWeightForm").trigger("reset");
}
function StonesByPurchaseDetail(ratti_kaat_detail_id) {
    $.ajax({
        type: "GET",
        url: url_local + "/ratti-kaats/stones" + "/" + ratti_kaat_detail_id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },

        success: function (data) {
            console.log(data);
            stonesData = data.Data;
            var tbody = $("#stonesTable tbody");
            tbody.empty();
            var rows = "";
            var total = 0;
            var total_weight = 0;
            $.each(stonesData, function (e, val) {
                
                var category = val.category;
                rows += `<tr id=${category.replace(/\s+/g, '') + Math.floor(val.stones) + Math.floor(val.gram)}><td>${val.category}</td><td>${val.type}</td><td>${val.stones}</td><td style="text-align: right;">${val.gram}</td><td style="text-align: right;" >${val.carat}</td>
          <td style="text-align: right;" >${val.gram_rate}</td><td style="text-align: right;" >${val.carat_rate}</td><td style="text-align: right;" >${val.total_amount}</td>
          <td><a class="text-danger text-white stoner${category.replace(/\s+/g, '') + Math.floor(val.stones) + Math.floor(val.gram)}" onclick="StoneRemove('${category.replace(/\s+/g, '') + Math.floor(val.stones) + Math.floor(val.gram)}')"><i class="fa fa-trash"></i></a></td></tr>`;
                total += val.total_amount * 1;
                total_weight += val.gram * 1;
            });

            $("#total_stones_price").val(total.toFixed(3));
            $("#stones_weight").val(total_weight.toFixed(3));
            TotalAmount();
            tbody.prepend(rows);
        },
    });
}
function StonesByJobPurchaseDetail(job_purchase_detail_id, product_id) {
    $.ajax({
        type: "GET",
        url: url_local + "/job-purchase/stones" + "/" + job_purchase_detail_id + "/" + product_id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },

        success: function (data) {
            console.log(data);
            stonesData = data.Data;
            var tbody = $("#stonesTable tbody");
            tbody.empty();
            var rows = "";
            var total = 0;
            var total_weight = 0;
            $.each(stonesData, function (e, val) {
                
                var category = val.category;
                rows += `<tr id=${category.replace(/\s+/g, '') + Math.floor(val.stones) + Math.floor(val.gram)}><td>${val.category}</td><td>${val.type}</td><td>${val.stones}</td><td style="text-align: right;">${val.gram}</td><td style="text-align: right;" >${val.carat}</td>
          <td style="text-align: right;" >${val.gram_rate}</td><td style="text-align: right;" >${val.carat_rate}</td><td style="text-align: right;" >${val.total_amount}</td>
          <td><a class="text-danger text-white stoner${category.replace(/\s+/g, '') + Math.floor(val.stones) + Math.floor(val.gram)}" onclick="StoneRemove('${category.replace(/\s+/g, '') + Math.floor(val.stones) + Math.floor(val.gram)}')"><i class="fa fa-trash"></i></a></td></tr>`;
                total += val.total_amount * 1;
                total_weight += val.gram * 1;
            });

            $("#total_stones_price").val(total.toFixed(3));
            $("#stones_weight").val(total_weight.toFixed(3));
            TotalAmount();
            tbody.prepend(rows);
        },
    });
}
function StoneRemove(id) {
    
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
        $.each(stonesData, function (stone_sr, val) {
            
            var category = val.category;
            if (category.replace(/\s+/g, '') + Math.floor(val.stones) + Math.floor(val.gram) == id) {
                total = $("#total_stones_price").val() * 1 - val.total_amount * 1;
                total_weight = $("#stones_weight").val() * 1 - val.gram * 1;
                $("#total_stones_price").val(total > 0 ? total : 0);
                $("#stones_weight").val(total_weight > 0 ? total_weight : 0);
                $("#" + id).hide();
                item_index = stone_sr;
                return false;
            }
        });

        stonesData.splice(item_index, 1);
        var check = ".stoner" + id;
        $(check).closest("tr").remove();
        success("Stones Deleted Successfully!");
        TotalAmount();
        netWeight();
        StoneShort();
    });
}
// Short
function StoneShort() {
    var table, j, x, y;
    table = document.getElementById("stonesTable");
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