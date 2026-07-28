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
    if ($("#product_id").find(":selected").val() == 0) {
        error("Product is not selected!");
        return false;
    }
    netWeight();
    totalAmount();
    $("#beadWeightForm").trigger("reset");
    $("#bead_weight_product_id").val($("#product_id").find(":selected").val());
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
BeadClear();
function addBeadProduct() {
    $("#preloader").show();
    var type = $("#type").find(":selected").val();
    var beads = $("#beads").val();
    var bead_gram = $("#bead_gram").val();
    var bead_carat = $("#bead_carat").val();
    var bead_gram_rate = $("#bead_gram_rate").val();
    var bead_carat_rate = $("#bead_carat_rate").val();
    var bead_total = $("#bead_total").val();

    if (type == "" || type == 0) {
        error("Please select bead type!");
        $("#type").focus();
        return false;
    }
    if (beads == "" && beads == 0) {
        error("Please enter beads!");
        $("#beads").focus();
        return false;
    }
    if (bead_gram == "" && bead_gram == 0) {
        error("Please enter bead in gram!");
        $("#bead_gram").focus();
        return false;
    }
    if (bead_carat == "" && bead_carat == 0) {
        error("Please enter bead in carat!");
        $("#bead_carat").focus();
        return false;
    }
    if (bead_gram_rate == "" && bead_gram_rate == 0) {
        error("Please enter gram rate!");
        $("#bead_gram_rate").focus();
        return false;
    }
    if (bead_carat_rate == "" && bead_carat_rate == 0) {
        error("Please enter carat rate!");
        $("#bead_carat_rate").focus();
        return false;
    }
    if (bead_total == "" && bead_total == 0) {
        error("Please enter bead total!");
        $("#bead_total").focus();
        return false;
    }
    var check = true;
    $.each(beadData, function (e, val) {
        if (val.type.replace(/\s+/g, '')+val.beads == type.replace(/\s+/g, '')+beads) {
            error("Bead is already added !");
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

    beadData.push({
        // sr: i,
        type: type,
        beads: beads,
        gram: bead_gram,
        carat: bead_carat,
        gram_rate: bead_gram_rate,
        carat_rate: bead_carat_rate,
        total_amount: bead_total,
    });

    $("#total").val(total);

    $.each(beadData, function (index, val) {

        rows += `<tr id="${removeSpaces(val.type)}${val.beads}">
                <td>${val.type}</td>
                <td style="text-align: right;">${val.beads}</td>
                <td style="text-align: right;">${val.gram}</td>
                <td style="text-align: right;">${val.carat}</td>
                <td style="text-align: right;">${val.gram_rate}</td>
                <td style="text-align: right;">${val.carat_rate}</td>
                <td style="text-align: right;">${val.total_amount}</td>
                <td><a class="text-danger text-white beadproductr${removeSpaces(val.type)}${val.beads}" onclick="BeadRemove('${removeSpaces(val.type)}${val.beads}')"><i class="fa fa-trash"></i></a></td></tr>`;

        total += val.total_amount * 1;
        total_weight = total_weight * 1 + val.gram * 1;
    });
    $("#total_bead_amount").val(total.toFixed(3));
    $("#bead_weight").val(total_weight.toFixed(3)).trigger("keyup");
    success("Bead Added Successfully!");
    $("#beads_products").empty();
    netWeight();
    totalAmount();
    $("#beads_products").html(rows);
    BeadClear();
    BeadShort();
    $("#preloader").hide();
}

function BeadRemove(id) {
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
        var total_weight=0;
        $("#preloader").show();
        $.each(beadData, function (index, val) {
            if (removeSpaces(val.type) + val.beads == id) {
                total = $("#total_bead_amount").val() * 1 - val.total_amount * 1;
                total_weight = total_weight * 1 - val.gram * 1;
                $("#total_bead_amount").val(total > 0 ? total : 0);
                $("#bead_weight").val(total_weight.toFixed(3)).trigger("keyup");
                $("#" + id).hide();
                item_index = index;
                return false;
            }
        });

        beadData.splice(item_index, 1);
        var check = ".beadproductr" + id;
        $(check).closest("tr").remove();
        success("Bead Deleted Successfully!");
        BeadShort();
        $("#preloader").hide();
    });
}

function BeadClear() {
    $("#type").val('');
    $("#beads").val('');
    $("#bead_gram").val('');
    $("#bead_carat").val('');
    $("#bead_gram_rate").val('');
    $("#bead_carat_rate").val('');
    $("#bead_total").val('');

}

// Short
function BeadShort() {
    var tbody, j, x, y;
    tbody = document.getElementById("beads_products");
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

// $("#beadWeightForm").submit(function (e) {
//     e.preventDefault();
//     if ($("#type").find(":selected").val() == "" || $("#type").find(":selected").val() == 0) {
//         error("Please select bead type!");
//         $("#type").focus();
//         return false;
//     }
//     if ($("#beads").val() == "" && $("#beads").val() == 0) {
//         error("Please enter beads!");
//         $("#beads").focus();
//         return false;
//     }
//     if ($("#bead_gram").val() == "" && $("#bead_gram").val() == 0) {
//         error("Please enter bead in gram!");
//         $("#bead_gram").focus();
//         return false;
//     }
//     if ($("#bead_carat").val() == "" && $("#bead_carat").val() == 0) {
//         error("Please enter bead in carat!");
//         $("#bead_carat").focus();
//         return false;
//     }
//     if ($("#bead_gram_rate").val() == "" && $("#bead_gram_rate").val() == 0) {
//         error("Please enter gram rate!");
//         $("#bead_gram_rate").focus();
//         return false;
//     }
//     if ($("#bead_carat_rate").val() == "" && $("#bead_carat_rate").val() == 0) {
//         error("Please enter carat rate!");
//         $("#bead_carat_rate").focus();
//         return false;
//     }
//     if ($("#bead_total").val() == "" && $("#bead_total").val() == 0) {
//         error("Please enter bead total!");
//         $("#bead_total").focus();
//         return false;
//     }
//     $.ajax({
//         url: url_local + "/ratti-kaats/bead-store",
//         type: "POST",
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//         data: $("#beadWeightForm").serialize(),
//         dataType: "json",

//         success: function (data) {
//             if (data.Success) {
//                 success(data.Message);
//                 $("#beadWeightForm").trigger("reset");
//                 beadWeightData(
//                     ratti_kaat_id,
//                     $("#product_id").find(":selected").val()
//                 );
//                 netWeight();
//                 totalAmount();
//             } else {
//                 error(data.Message);
//             }
//         },
//     });
// });

// Delete Bead Weight

// $("body").on("click", "#deleteBead", function () {
//     var ratti_kaat_bead_id = $(this).data("id");
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
//                 url:
//                     url_local +
//                     "/ratti-kaats/bead-destroy" +
//                     "/" +
//                     ratti_kaat_bead_id,
//                 type: "GET",
//                 headers: {
//                     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
//                         "content"
//                     ),
//                 },
//                 success: function (data) {
//                     if (data.Success) {
//                         success(data.Message);
//                         beadWeightData(
//                             ratti_kaat_id,
//                             $("#product_id").find(":selected").val()
//                         );
//                         netWeight();
//                         totalAmount();
//                     } else {
//                         error(data.Message);
//                     }
//                 },
//             });
//         }
//     });
// });
