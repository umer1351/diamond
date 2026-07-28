$(document).ready(function () {
    $("#printPdf").hide();
    $("#submit").hide();
});
function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
$("body").on("keyup", ".unit_price, .actual_quantity", function (event) {
  var id = $(this).attr("data-id");
  console.log(id);
  var quantity = $("#actual_quantity" + id).val();
  var unit_price = $("#unit_price" + id).val();
  var total = quantity * unit_price;
  total_amount = (total_amount*1) + (total*1);
    total_qty = (total_qty *1) + (quantity*1);
  $("#total_amount" + id).val(total.toFixed(2));
});


$("#n_submit").click(function () {
    if ($("#stock_warehouse").find(":selected").val() == 0) {
        error("Please Select Warehouse!");

        return false;
    } else {
        $("#stock_n_warehouse").val(
            $("#stock_warehouse").find(":selected").val()
        );
    }
    if ($("#date").val() == '') {
        error("Please Select Date!");

        return false;
    } else {
        $("#stock_n_date").val(
            $("#date").val()
        );
    }
    var data = {
            date: $("#date").val(),
            warehouse_id: $("#stock_warehouse").find(":selected").val()
        };
    $.ajax({
        url:url_local+"/stock/detail",
        type: "GET",
        data:data,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: function () {
            $("#preloader").show();
            $("#aqual_total").val(0);
        },
        complete: function () {
            $("#preloader").hide();
            $("#printPdf").show();
        },
        success: function (data) {
            if (data == 1) {
                var tbody = $("#exampleStock tbody");
                tbody.empty();
            $("#preloader").hide();
            } else {
                console.log(data);
            $("#preloader").hide();
                if (data.length != []) {
                    $("#submit").show();
                } else {
                    rows +=
                        "<tr><td colspan='6' style='text-align:center;'>Record not found!</td></tr>";
                }
                //     $("#printPdf").attr(
                //         "href",
                //         "getStockDetailPdf?id=" +
                //             $("#stock_warehouse").find(":selected").val()
                //     );
                var rows = "";
                var total = 0;
                for (var i = 0; i < data.length; i++) {
                    // if (data[i].stock > 0) {
                        rows +=
                            "<tr><td><input type='checkbox' class='sub_chk"+ data[i].product_id +"'/></td><td style='display:none;'><input type='hidden' value=" +
                            data[i].product_id +
                            " name='productId'></td><td class='col-md-2' style='text-align:center'>" +
                            data[i].code +
                            " - " +
                            data[i].product_name +
                            "</td><td class='col-md-2' style='text-align:center'>" +
                            data[i].stock +
                            "</td>"+
                            "<td class='col-md-2' style='text-align:center'>"+
                            "<input type='text' value='0' id='actual_quantity"+ data[i].product_id +"'  data-id=" +
                            data[i].product_id +
                            " class='form-control actual_quantity' onkeypress='return isNumberKey(event)' min='0' placeholder='New Quantity'/>"+
                            "</td>"+
                            "<td class='col-md-2' style='text-align:center'>"+
                            "<input type='number' id='unit_price"+ data[i].product_id +"'  data-id=" +
                            data[i].product_id +
                            " value='"+data[i].unit_price+"' min='0' class='form-control unit_price' placeholder='Unit Price'/>"+
                            "</td>"+
                            "<td class='col-md-2' style='text-align:center'>"+
                            "<input type='number' value='0' min='0' disabled id='total_amount"+ data[i].product_id +"' class='form-control total_amount' placeholder='Total Amount'/>"+
                            "</td></tr>";
                        total = total + data[i].stock * 1;
                    // }
                }
                var tbody = $("#exampleStock tbody");
                tbody.empty();
                tbody.html(rows);
                $("#stock_total").val(total);

                $("#exampleStock").DataTable().destroy();
                $("#exampleStock tbody").empty();
                $("#exampleStock tbody").html(rows);
            }
        },
    });
});

$("body").on("click", "#aqual_button", function (e) {
    e.preventDefault();

    var table = $("#exampleStock tbody");
    var total_qty = 0;
    var total_amount = 0;
    table.find("tr").each(function (i, el) {
        var $tds = $(this).find("td");
        var values = $tds
            .find(":input")
            .map(function () {
                return $(this).val();
            })
            .get();
        if (values[2] != "" && values[2] > 0) {
            total_qty = total_qty + values[2] * 1;
            total_amount = total_amount + values[4] * 1;
        }
    });
    $("#total_qty").val(total_qty.toFixed(2));
    $("#total_amount").val(total_amount.toFixed(2));
});

$("body").on("click", "#submit", function (e) {
    e.preventDefault();
var tableDataStock = [];
    if ($("#stock_warehouse").find(":selected").val() == 0) {
        error("Please select warehouse!");
        return false;
    }
    if ($("#date").val() == '') {
        error("Please select Date!");
        return false;
    }
    var table = $("#exampleStock tbody");
    var actual_total = 0;
    table.find("tr").each(function (i, el) {
        var $tds = $(this).find("td");
        var values = $tds
            .find(":input")
            .map(function () {
                return $(this).val();
            })
            .get();
        if ($(".sub_chk"+values[1]).is(':checked')) {
            actual_total = actual_total + values[2] * 1;

            tableDataStock.push({
                productId: values[1],
                productName: $tds.eq(2).text(),
                quantityInStock: $tds.eq(3).text().replace(/\,/g,''),
                actualQuantity: values[2].replace(/\,/g,''),
                unit_price: values[3].replace(/\,/g,''),
                total_amount: values[4].replace(/\,/g,'')
            });
        }
    });
    console.log(tableDataStock);
    if (tableDataStock.length == 0) {
        error("Please check row first!");
        return false;
    }
    var submitEntry = {};
    submitEntry.stockDetail = tableDataStock;
    submitEntry.warehouse = $("#stock_warehouse").find(":selected").val();
    submitEntry.date = $("#stock_n_date").val();
    $.ajax({
        url: url_local + "/stock-taking/store",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: submitEntry,
        //crossDomain: true,
        dataType: "json",
        beforeSend: function () {
            $("#preloader").show();
        },
        complete: function () {
            $("#preloader").hide();
        },
        success: function (data) {
            if (data.Success == true) {
                tableDataStock = [];
                success("Stock Updated!");
                window.location = url_local + "/stock-taking";
            }else{
                error(data.Message);
                $("#preloader").hide();
            }
        },
    });
});

function sortTable(n) {
    var table,
        rows,
        switching,
        i,
        x,
        y,
        shouldSwitch,
        dir,
        switchcount = 0;
    table = document.getElementById("exampleStock");
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc";
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /*Loop through all table rows (except the
        first, which contains table headers):*/
        for (i = 1; i < rows.length - 1; i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
            one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /*check if the two rows should switch place,
            based on the direction, asc or desc:*/
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch
            and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            //Each time a switch is done, increase this count by 1:
            switchcount++;
        } else {
            /*If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again.*/
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
