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
    if ($("#product_id").find(":selected").val() == 0) {
        error("Product is not selected!");
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
function stoneWeightData(ratti_kaat_id, product_id) {
    $.ajax({
        url: url_local + "/ratti-kaats/stones" + "/" + ratti_kaat_id + "/" + product_id,
        type: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            console.log(data);
            if (data.Success) {
                var rows = "";
                var total_weight = 0;
                var total_stones_amount = 0;
                var i = 1;
                $("#stoneTable").empty();
                if (data.Data.length > 0) {
                    $.each(data.Data, function (e, val) {
                        total_weight = total_weight * 1 + val.gram * 1;
                        total_stones_amount = total_stones_amount*1 + val.total_amount * 1;
                        rows += `<tr id=${val.id} ><td>${i}</td><td>${val.category}</td><td>${val.type}</td><td>${val.stones}</td><td>${val.gram}</td><td>${val.carat}</td><td>${val.gram_rate}</td><td>${val.carat_rate}</td><td>${val.total_amount}</td><td><a class="text-danger text-white"  id="deleteStone" href="javascript:void(0)" data-toggle="tooltip"  data-id="${val.id}" data-original-title="delete"><i class="fa fa-trash" style="font-size:18px;"></i></a></td></tr>`;
                    });

                    $("#stones_weight").val(total_weight.toFixed(3)).trigger("keyup");
                    $("#total_stones_amount").val(total_stones_amount.toFixed(3)).trigger("keyup");
                    var tbody = $("#stoneTable");
                    tbody.prepend(rows);
                    i = i + 1;
                } else {
                    $("#total_stones_amount").val(0);
                    $("#stoneTable").html('<tr><td colspan="10" style="text-align:center;">Record Not Found!</td></tr>');
                }
            } else {
                error(data.Message);
            }
        },
    });
}

StoneClear();
function addStoneProduct() {
    $("#preloader").show();
    var category = $("#category").find(":selected").val();
    var type = $("#stone_type").val();
    var stones = $("#stones").val();
    var stone_gram = $("#stone_gram").val();
    var stone_carat = $("#stone_carat").val();
    var stone_gram_rate = $("#stone_gram_rate").val();
    var stone_carat_rate = $("#stone_carat_rate").val();
    var stone_total = $("#stone_total").val();
    console.log(type);

    if (category == "" || category == 0) {
        error("Please select stone category!");
        $("#category").focus();
        return false;
    }

    if (type == "" || type == 0) {
        error("Please select stone type!");
        $("#type").focus();
        return false;
    }
    if (stones == "" && stones == 0) {
        error("Please enter stones!");
        $("#stones").focus();
        return false;
    }
    if (stone_gram == "" && stone_gram == 0) {
        error("Please enter stone in gram!");
        $("#stone_gram").focus();
        return false;
    }
    if (stone_carat == "" && stone_carat == 0) {
        error("Please enter stone in carat!");
        $("#stone_carat").focus();
        return false;
    }
    if (stone_gram_rate == "" && stone_gram_rate == 0) {
        error("Please enter gram rate!");
        $("#stone_gram_rate").focus();
        return false;
    }
    if (stone_carat_rate == "" && stone_carat_rate == 0) {
        error("Please enter carat rate!");
        $("#stone_carat_rate").focus();
        return false;
    }
    if (stone_total == "" && stone_total == 0) {
        error("Please enter stone total!");
        $("#stone_total").focus();
        return false;
    }
    var check = true;
    $.each(stoneData, function (e, val) {
        if (val.type.replace(/\s+/g, '')+val.category.replace(/\s+/g, '')+val.stones == type.replace(/\s+/g, '')+category.replace(/\s+/g, '')+stones) {
            error("Stone is already added !");
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
    var total_weight = 0;

    stoneData.push({
        // sr: i,
        category:category,
        type: type,
        stones: stones,
        gram: stone_gram,
        carat: stone_carat,
        gram_rate: stone_gram_rate,
        carat_rate: stone_carat_rate,
        total_amount: stone_total,
    });

    $("#total").val(total);

    $.each(stoneData, function (index, val) {

        rows += `<tr id="${removeSpaces(val.type)}${removeSpaces(val.category)}${val.stones}">
                <td>${val.category}</td>
                <td>${val.type}</td>
                <td style="text-align: right;">${val.stones}</td>
                <td style="text-align: right;">${val.gram}</td>
                <td style="text-align: right;">${val.carat}</td>
                <td style="text-align: right;">${val.gram_rate}</td>
                <td style="text-align: right;">${val.carat_rate}</td>
                <td style="text-align: right;">${val.total_amount}</td>
                <td><a class="text-danger text-white stoneproductr${removeSpaces(val.type)}${removeSpaces(val.category)}${val.stones}" onclick="StoneRemove('${removeSpaces(val.type)}${removeSpaces(val.category)}${val.stones}')"><i class="fa fa-trash"></i></a></td></tr>`;

        total += val.total_amount * 1;
        total_weight = total_weight * 1 + val.gram * 1;
    });
    $("#total_stones_amount").val(total.toFixed(3));
    $("#stones_weight").val(total_weight.toFixed(3)).trigger("keyup");
    success("Stone Added Successfully!");
    $("#stones_products").empty();
    netWeight();
    totalAmount();
    $("#stones_products").html(rows);
    StoneClear();
    StoneShort();
    $("#preloader").hide();
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
        $("#preloader").show();
        $.each(stoneData, function (index, val) {
            if (removeSpaces(val.type) + removeSpaces(val.category) + val.stones == id) {
                total = $("#total_stones_amount").val() * 1 - val.total_amount * 1;
                total_weight = total_weight * 1 - val.gram * 1;
                $("#total_stones_amount").val(total > 0 ? total : 0);
                $("#stones_weight").val(total_weight.toFixed(3)).trigger("keyup");
                $("#" + id).hide();
                item_index = index;
                return false;
            }
        });

        stoneData.splice(item_index, 1);
        var check = ".stoneproductr" + id;
        $(check).closest("tr").remove();
        success("Stone Deleted Successfully!");
        StoneShort();
        $("#preloader").hide();
    });
}

function StoneClear() {
    $("#category").val('');
    $("#stone_type").val('');
    $("#stones").val('');
    $("#stone_gram").val('');
    $("#stone_carat").val('');
    $("#stone_gram_rate").val('');
    $("#stone_carat_rate").val('');
    $("#stone_total").val('');

}

// Short
function StoneShort() {
    var tbody, j, x, y;
    tbody = document.getElementById("stones_products");
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

// $("#stoneWeightForm").submit(function (e) {
//     e.preventDefault();
//     if ($("#category").find(":selected").val() == "" || $("#category").find(":selected").val() == 0) {
//         error("Please select stone category!");
//         $("#category").focus();
//         return false;
//     }
//     if ($("#type").val() == "") {
//         error("Please select stone type!");
//         $("#type").focus();
//         return false;
//     }
//     if ($("#stones").val() == "" && $("#stones").val() == 0) {
//         error("Please enter stones qty!");
//         $("#stones").focus();
//         return false;
//     }
//     if ($("#stone_gram").val() == "" && $("#stone_gram").val() == 0) {
//         error("Please enter stone in gram!");
//         $("#stone_gram").focus();
//         return false;
//     }
//     if ($("#stone_carat").val() == "" && $("#stone_carat").val() == 0) {
//         error("Please enter stone in carat!");
//         $("#stone_carat").focus();
//         return false;
//     }
//     if ($("#stone_gram_rate").val() == "" && $("#stone_gram_rate").val() == 0) {
//         error("Please enter gram rate!");
//         $("#stone_gram_rate").focus();
//         return false;
//     }
//     if ($("#stone_carat_rate").val() == "" && $("#stone_carat_rate").val() == 0) {
//         error("Please enter carat rate!");
//         $("#stone_carat_rate").focus();
//         return false;
//     }
//     if ($("#stone_total").val() == "" && $("#stone_total").val() == 0) {
//         error("Please enter stone total!");
//         $("#stone_total").focus();
//         return false;
//     }
//     $.ajax({
//         url: url_local + "/ratti-kaats/stone-store",
//         type: "POST",
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//         data: $("#stoneWeightForm").serialize(),
//         dataType: "json",

//         success: function (data) {
//             if (data.Success) {
//                 success(data.Message);
//                 stoneWeightData(ratti_kaat_id, $("#product_id").find(":selected").val());
//                 $("#stoneWeightForm").trigger("reset");
//             } else {
//                 error(data.Message);
//             }
//         },
//     });
// });
// Delete Stone Weight

// $("body").on("click", "#deleteStone", function () {
//     var ratti_kaat_stone_id = $(this).data("id");
//     Swal.fire({
//         title: "Are you sure?",
//         text: "You won't be able to revert this!",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         confirmButtonText: "Yes, delete it!",
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 url: url_local + "/ratti-kaats/stone-destroy" + "/" + ratti_kaat_stone_id,
//                 type: "GET",
//                 headers: {
//                     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//                 },
//                 success: function (data) {
//                     if (data.Success) {
//                         success(data.Message);
//                         stoneWeightData(ratti_kaat_id, $("#product_id").find(":selected").val());
//                     } else {
//                         error(data.Message);
//                     }
//                 },
//             });
//         }
//     });
// });