function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
$("#product_id").on("change", function () {
    var product_id = $("#product_id").val();
    $.ajax({
        url: url_local + '/ratti-kaats/ratti-kaat-by-product-id/' + product_id,
        type: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            console.log(data);
            if (data.Success) {
                var data = data.Data;
                var row = "";
                $("#purchases").empty();
                if (data.ratti_kaat.length > 0) {
                    $.each(data.ratti_kaat, function (k, value) {
                        row += '<tr>';
                        row +=
                            '<td><a id="purchase_id" href="javascript:void(0)" data-toggle="tooltip"  data-id="' +
                            value.ratti_kaat_detail_id +
                            '" data-original-title="Purchase"><b>' +
                            value.ratti_kaat_no + '</b></a></td>';
                        row += '</tr>';
                    });

                } else {
                    row += '<tr>';
                    row +=
                        '<td>No Purchase found</td>';
                    row += '</tr>';
                }
                $("#purchases").append(row);
                $("#tag_no_text").val(data.tag_no);
                $("#tag_no").val(data.tag_no);
            } else {
                error(data.Message);
            }
        },
    });
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
    net_weight = scale_weight * 1 - (bead_weight * 1 + stones_weight * 1 + diamond_weight * 1);
    $("#net_weight").val(net_weight.toFixed(3));
}
$("#waste_per").on("keyup", function (event) {
    var waste_per = $("#waste_per").val();
    var net_weight = $("#net_weight").val();
    var cal = net_weight * waste_per / 100;
    var gross_wt = (net_weight * 1) + (cal * 1);
    $("#waste").val(cal.toFixed(3));
    $("#gross_weight").val(gross_wt.toFixed(3));
    TotalGoldAmount();
});

$("#waste").on("keyup", function (event) {
    var waste = $("#waste").val();
    var net_weight = $("#net_weight").val();
    var cal = waste / net_weight * 100;
    var gross_wt = (net_weight * 1) + (waste * 1);
    $("#waste_per").val(cal.toFixed(3));
    $("#gross_weight").val(gross_wt.toFixed(3));
    TotalGoldAmount();
});

$("#making_gram").on("keyup", function (event) {
    var making_gram = $("#making_gram").val();
    var gross_weight = $("#gross_weight").val();
    var making = (making_gram * 1) * (gross_weight * 1);
    $("#making").val(making.toFixed(3));
    TotalAmount();
});


