import {
    ajaxPostRequest,
    ajaxGetRequest,
} from "../../../../../js/common-methods/http-requests.js";
import {
    errorMessage,
    successMessage,
} from "../../../../../js/common-methods/toasters.js";
$(document).ready(function () {
    if (journal_entry_id == null) {
        Clear();
    }
    $("#debit").on("change", function () {
        $("#credit").val("0.000"); //Credit will be 0 keypress on Debit;
    });
    $("#credit").on("change", function () {
        $("#debit").val("0.000"); //Debit will be 0 keypress on Credit;
    });
    $("#checkno").on("keypress", function () {
        $(".billdate").css("display", "block");
    });
    $("#account_id").change(function () {
        accountInfo = $("#account_id  :selected").text(); //Account Code Rendering
        accountInfo = accountInfo.replace(/^\s+|\s+$/g, "");
        accounts = accountInfo.split(" -- ");
        $("#accountCode").val(accounts[0]);
    });
    $(document).on("keypress", function (e) {
        if (e.which == 13) {
            $("#add").trigger("click");
        }
    });
    $("#jsGrid").show("2000");

    var id = "";
    var account_id = "";
    var accountInfo = "";
    var accounts = "";
    var accountCode = "";
    var accountName = "";
    var debit = 0.000;
    var debitAmt = "";
    var credit = 0.000;
    var creditAmt = "";
    var explanation = "";
    var description = "";
    var checkno = "";
    var check_date = "";
    var tbl_index = "";
    var billno = "";
    var tbl_id = "";
    var reference = "";
    var date = "";
    var obj = "";
    var debitSort = 0.000;
    var creditSort = 0.000;
    var items = [];
    var totalCredit = 0.000;
    var totalDebit = 0.000;
    var total_debit = 0.000;
    var total_credit = 0.000;

    $("#add").click(function (item) {
        date = $("#pdate").val();
        account_id = $("#account_id").val();
        accountInfo = $("#account_id :selected").text();
        accountInfo = accountInfo.replace(/^\s+|\s+$/g, "");
        accounts = accountInfo.split(" -- ");
        accountName = accounts[1];
        accountCode = accounts[0];
        debit = $("#debit").val();
        debit = debit.replace(/[\, ]/g, "");
        debitAmt = parseFloat(debit)
            .toFixed(3)
            .replace(/\d(?=(\d{3})+\.)/g, "$&,");
        credit = $("#credit").val();
        credit = credit.replace(/[\, ]/g, "");
        creditAmt = parseFloat(credit)
            .toFixed(3)
            .replace(/\d(?=(\d{3})+\.)/g, "$&,");
        description = $("#description").val();
        explanation = $("#explanation").val();
        check_date = $("#doc_date").val();
        checkno = $("#checkno").val();
        billno = $("#billno").val();
        reference = $("#reference").val();
        tbl_id = $("#tbl_id").val();
        tbl_index = $("#tbl_index").val();

        if (date == "") {
            errorMessage("Please Fill Date!");
            $("#pdate").focus();
            return;
        }
        if (account_id == "" || account_id == null) {
            errorMessage("Please Select Account!");
            $("#account_id").focus();
            return;
        }

        if (debit == "") {
            $("#debit").val("0.000");
        }

        if (credit == "") {
            $("#credit").val("0.000");
        }

        if (debit == null || credit == null || debit == "" || credit == "") {
            errorMessage("Credit Debit Can't be null");
            $("#debit").focus();
            return;
        }
        if (tbl_id) {
            obj = editedItem;
            editedItems.item = obj;
            obj.Date = date;
            obj.acc_head_id = account_id;
            obj.Code = accountCode;
            obj.Account = accountName;
            obj.Debit = debitAmt;
            obj.Credit = creditAmt;
            obj.reference = description;
            obj.Explanation = explanation;
            obj.check_date = check_date;
            obj.CheckNo = checkno;
            obj.BillNo = billno;
            obj.reference = reference;
            obj.tbl_id = tbl_id;
            obj.tbl_index = tbl_index;

            $("#jsGrid")
                .jsGrid("updateItem", editedItems)
                .done(function () {
                    $("#jsGrid").jsGrid("updateItem", obj);
                });

            Clear();
        } else {
            obj = {};
            obj.Date = date;
            obj.acc_head_id = account_id;
            obj.Code = accountCode;
            obj.Account = accountName;
            obj.Debit = debitAmt;
            obj.Credit = creditAmt;
            obj.description = description;
            obj.Explanation = explanation;
            obj.check_date = check_date;
            obj.CheckNo = checkno;
            obj.BillNo = billno;
            obj.reference = reference;
            obj.tbl_id = tbl_id;
            Clear();
            $("#jsGrid").jsGrid("insertItem", obj);
        }

        items = $("#jsGrid").jsGrid("option", "data");
        total_debit = $("#total_debit").val();
        if (total_debit == "") {
            debit = debit.replace(/[\, ]/g, "");
            credit = credit.replace(/[\, ]/g, "");
            total_debit = parseFloat(debit);
            total_credit = parseFloat(credit);
            var worrds = parseInt(total_debit);
            obj.amount_in_words = toWords(worrds);

            debitSort = parseFloat(total_debit)
                .toFixed(3)
                .replace(/\d(?=(\d{3})+\.)/g, "$&,");
            creditSort = parseFloat(total_credit)
                .toFixed(3)
                .replace(/\d(?=(\d{3})+\.)/g, "$&,");
            $("#total_debit").val(debitSort);
            $("#total_credit").val(creditSort);

            var bal = total_debit * 1 - total_credit * 1;
            $("#balance").empty();
            var bal_value = "";
            if (bal > 0) {
                bal_value = bal + " Dr";
                $("#balance").val(bal_value);
            } else if (bal < 0) {
                bal_value = bal * -1 + " Cr";
                $("#balance").val(bal_value);
            } else {
                $("#balance").val(0);
            }
        } else {
            var result = [];
            $(".jsgrid-table tr").each(function () {
                $("td", this).each(function (index, val) {
                    if (!result[index]) result[index] = 0;
                    result[index] += parseFloat(
                        $(val).text().replace(/[\, ]/g, "")
                    );
                });
            });

            total_debit = result[3];
            total_credit = result[4];
            debitSort = parseFloat(result[3])
                .toFixed(3)
                .replace(/\d(?=(\d{3})+\.)/g, "$&,");
            creditSort = parseFloat(result[4])
                .toFixed(3)
                .replace(/\d(?=(\d{3})+\.)/g, "$&,");
            $("#total_debit").val(debitSort);
            $("#total_credit").val(creditSort);

            var bal = total_debit * 1 - total_credit * 1;
            $("#balance").empty();
            var bal_value = "";
            if (bal > 0) {
                bal_value = bal + " Dr";
                $("#balance").val(bal_value);
            } else if (bal < 0) {
                bal_value = bal * -1 + " Cr";
                $("#balance").val(bal_value);
            } else {
                $("#balance").val(0);
            }
            var worrds = parseInt(total_debit);
            obj.amount_in_words = toWords(worrds);
        }
    });
    $(function () {
        $("#jsGrid").jsGrid({
            height: "300px",
            width: "100%",
            editing: false,
            autoload: true,
            pageSize: 15,
            pageButtonCount: 5,
            insertRowLocation: "top",
            confirmDeleting: false,
            rowDoubleClick: function (items) {
                var getData = items.item;
                editedItems = items;
                (index = items.itemIndex), (editedItem = getData);
                editDetails(getData, index);
                var edit = $("#tbl_id").val();
            },
            onItemDeleting: function (args) {
                var debitVal = $("#total_debit").val();
                debitVal = debitVal.replace(/[\, ]/g, "");
                debitVal = parseInt(debitVal);
                var creditVal = $("#total_credit").val();
                creditVal = creditVal.replace(/[\, ]/g, "");
                creditVal = parseInt(creditVal);
                var credit = args.item.Credit;
                credit = credit.replace(/[\, ]/g, "");
                credit = parseInt(credit);
                var debit = args.item.Debit;
                console.log(debit);
                debit = debit.replace(/[\, ]/g, "");
                debit = parseInt(debit);
                totalCredit = creditVal - credit;
                totalDebit = debitVal - debit;

                totalDebit = totalDebit != null ? totalDebit : 0;
                totalCredit = totalCredit != null ? totalCredit : 0;
                $("#total_debit").val(totalDebit);
                $("#total_credit").val(totalCredit);
                var bal = totalDebit * 1 - totalCredit * 1;
                $("#balance").empty();
                var bal_value = "";
                if (bal > 0) {
                    bal_value = bal + " Dr";
                    $("#balance").val(bal_value);
                } else if (bal < 0) {
                    bal_value = bal * -1 + " Cr";
                    $("#balance").val(bal_value);
                } else {
                    $("#balance").val(0);
                }

                totalCredit = parseFloat(totalCredit)
                    .toFixed(3)
                    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
                totalDebit = parseFloat(totalDebit)
                    .toFixed(3)
                    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
            },
            controller: {
                loadData: function (filter) {
                    return /* result = */ $.ajax({
                        type: "GET",
                        url:
                            url_local +
                            "/journal-entries/grid-edit/" +
                            journal_entry_id,
                        data: filter,
                        dataType: "JSON",
                        success: function (response) {
                            items = response;
                            var total_debit = 0;
                            var total_credit = 0;
                            var i = 0;
                            for (i = 0; i < response.length; i++) {
                                var Debit = response[i].debit;
                                var Credit = response[i].credit;
                                if (!isNaN(debit)) {
                                    total_debit += parseFloat(Debit);
                                }
                                if (!isNaN(credit)) {
                                    total_credit += parseFloat(Credit);
                                }
                            }
                            totalCredit = parseFloat(total_credit)
                                .toFixed(3)
                                .replace(/\d(?=(\d{3})+\.)/g, "$&,");
                            totalDebit = parseFloat(total_debit)
                                .toFixed(3)
                                .replace(/\d(?=(\d{3})+\.)/g, "$&,");
                            $("#total_debit").val(totalDebit);
                            $("#total_credit").val(totalCredit);

                            var bal = total_debit * 1 - total_credit * 1;
                            $("#balance").empty();
                            var bal_value = "";
                            if (bal > 0) {
                                bal_value = bal + " Dr";
                                $("#balance").val(bal_value);
                            } else if (bal < 0) {
                                bal_value = bal * -1 + " Cr";
                                $("#balance").val(bal_value);
                            } else {
                                $("#balance").val(0);
                            }
                        },
                    });
                },
            },
            fields: [
                {
                    name: "Date",
                    type: "text",
                    width: 30,
                    editing: false,
                },
                {
                    name: "Code",
                    type: "text",
                    width: 30,
                    editing: false,
                },
                {
                    name: "Account",
                    type: "text",
                    width: 40,
                    editing: false,
                },
                {
                    name: "Debit",
                    type: "number",
                    width: 20,
                    editing: false,
                    align: "right",
                    itemTemplate: function (value) {
                        if (value)
                            return value.replace(/\d(?=(\d{3})+\.)/g, "$&,");
                    },
                },
                {
                    name: "Credit",
                    type: "number",
                    width: 20,
                    editing: false,
                    align: "right",
                    itemTemplate: function (value) {
                        if (value)
                            return value.replace(/\d(?=(\d{3})+\.)/g, "$&,");
                    },
                },
                {
                    name: "Explanation",
                    type: "text",
                    width: 45,
                    editing: false,
                },
                {
                    name: "CheckNo",
                    type: "text",
                    width: 30,
                    editing: false,
                },
                {
                    name: "BillNo",
                    type: "text",
                    width: 20,
                    editing: false,
                },
                {
                    name: "account_id",
                    type: "text",
                    width: 0,
                    visible: false,
                },
                {
                    type: "control",
                    width: 30,
                    modeSwitchButton: false,
                    editButton: false,
                },
            ],
        });
        $("#submitajax").click(function () {
            var total_credit = $("#total_credit").val();

            var total_amount = total_credit.replace(/[\, ]/g, "");
            var total = parseInt(total_amount);
            var abc = toWords(total);
            obj = {};
            obj.item = [];
            obj.item = items;
            obj.amount_in_words = abc.toUpperCase();
            var result = [];
            $(".jsgrid-table tr").each(function () {
                $("td", this).each(function (index, val) {
                    if (!result[index]) result[index] = 0;
                    result[index] += parseFloat(
                        $(val).text().replace(/[\, ]/g, "")
                    );
                });
            });
            total_debit = parseInt(result[3]);
            total_credit = parseInt(result[4]);
            debitSort = parseFloat(result[3])
                .toFixed(3)
                .replace(/\d(?=(\d{3})+\.)/g, "$&,");
            creditSort = parseFloat(result[4])
                .toFixed(3)
                .replace(/\d(?=(\d{3})+\.)/g, "$&,");
            $("#total_debit").val(debitSort);
            $("#total_credit").val(creditSort);
            if (
                $("#journal_id").val() == "" ||
                $("#journal_id").val() == null
            ) {
                errorMessage("Please Select Journal!");
                $("#journal_id").focus();
                return;
            }
            if (total_credit == "NaN" || total_debit == "NaN") {
                errorMessage("Please Add a Journal Entries!");
                return;
            }
            if ($("#currency").find(':selected').val() == "") {
                errorMessage("Currency filed required!");
                $("#currency").focus();
                return;
            }
            if ($("#reference").val() == "") {
                errorMessage("Reference filed required!");
                $("#reference").focus();
                return;
            }
            if (total_credit != total_debit) {
                errorMessage("Unbalance Debit & Credit");
                return;
            }
            id = $("#id").val();
            var journal_id = $("#journal_id").find(':selected').val();
            var supplier_id = $("#supplier_id").find(':selected').val();
            var reference = $("#reference").val();
            var currency = $("#currency").find(':selected').val();
            if (obj.item.length > 0) {
                var load_func = function () {
                    $("#preloader").show();
                };
                obj = JSON.stringify(obj);
                $.ajax({
                    data: {
                        id: id,
                        journal_id: journal_id,
                        supplier_id: supplier_id,
                        reference: reference,
                        currency: currency,
                        items: obj,
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    type: "post",
                    url: url_local + "/journal-entries/store",
                    beforeSend: function () {
                        $("#preloader").show();
                    },
                    complete: function () {
                        $("#preloader").hide();
                    },
                    success: function (data) {
                        if (data != null) {
                            successMessage("Journal Entry Save Successfully!");
                            window.location.href =
                                url_local + "/journal-entries";
                            $("#preloader").hide();
                            setTimeout(function () {
                                $("#deleted_div").hide("fade");
                            }, 1000);
                        }
                    },
                });
            }
        });

        var editDetails = function (item, index) {
            $("#explanation").val(item.Explanation);
            var debit = item.Debit.replace(/[\, ]/g, "");
            var credit = item.Credit.replace(/[\, ]/g, "");
            debit = parseInt(debit, 10);
            credit = parseInt(credit, 10);

            $("#debit").val(debit);
            $("#credit").val(credit);
            var check_no = item.CheckNo;
            if (check_no) {
                $(".billdate").css("display", "block");
            }
            $("#checkno").val(item.CheckNo);
            $("#billno").val(item.BillNo);
            $("#doc_date").val(item.check_date);
            $("#accountCode").val(item.code);
            $("#account_id").val(item.account_id);
            $("#account_id").change();
            $("#tbl_id").val(item.tbl_id);
        };
    });

    function Clear() {
        $("#account_id").val("").trigger("change");
        $("#accountCode").val("");
        $("#checkno").val("");
        $("#billno").val("");
        $("#explanation").val("");
        $("#debit").val(0.000);
        $("#credit").val(0.000);
    }

    function editDetails(item, index) {
        // $("#jsGrid").jsGrid("deleteItem", item);

        obj = editedItem;
        editedItems.item = obj;

        $("#explanation").val(item.Explanation);

        var debit = item.Debit.replace(/[\, ]/g, "");
        var credit = item.Credit.replace(/[\, ]/g, "");
        debit = parseInt(debit, 10);
        credit = parseInt(credit, 10);

        $("#debit").val(debit);
        $("#credit").val(credit);

        var check_no = item.CheckNo;
        if (check_no) {
            $(".billdate").css("display", "block");
        }
        $("#checkno").val(item.CheckNo);
        $("#billno").val(item.BillNo);
        $("#doc_date").val(item.bill_date);
        // $("#accountCode").val(item.code);
        $("#account_id").val(item.account_id);
        $("#account_id").change();
    }

    function calculate2() {
        var debitAmt = 0;
        var creditAmt = 0;
        var table = $("table tbody");
        var rowCount = $("#example tbody tr").length;
        table.find("tr").each(function (i) {
            if (i > 0) {
                var $tds = $(this).find("td");
                debit = $tds.eq(3).find("input").val();
                debitAmt = parseFloat(debit) + debitAmt;
                credit = $tds.eq(4).find("input").val();
                creditAmt = parseFloat(credit) + creditAmt;
            }
        });
        $("#total_debit").val(debitAmt);
        $("#total_credit").val(creditAmt);
        $("#total").closest("tr").remove();
        var tbody = $("#example tfoot");
        var row =
            "<tr id='total'><th colspan='2' style='text-align:center'>    Total</th><th style='text-align:center' id='debitAmt'>" +
            debitAmt +
            "</th><th style='text-align:center' id='creditAmt'>" +
            creditAmt +
            "</th><th><input type='hidden' name='debitAmt' class='form-control ' value='" +
            debitAmt +
            "' ><input type='hidden' name='creditAmt' class='form-control ' value='" +
            creditAmt +
            "' ></th></tr>";
        tbody.append(row);
        //debitTotal=$('#debitAmt').text();
        //creditTotal=$('#creditAmt').text();
    }
});
function afterAdd() {
    $("#journal_id").val("").trigger("change");
    $("#vendor_id").val("").trigger("change");
    $("#project_id").val("").trigger("change");
    $("#account_id").val("").trigger("change");
}
$("#credit").inputmask({
    alias: "decimal",
    groupSeparator: ",",
    autoGroup: true,
    digits: 3,
    digitsOptional: false,
});
$("#debit").inputmask({
    alias: "decimal",
    groupSeparator: ",",
    autoGroup: true,
    digits: 3,
    digitsOptional: false,
});

var th = ["", "thousand", "million", "billion", "trillion"];
// uncomment this line for English Number System
// var th = ['','thousand','million', 'milliard','billion'];

var dg = [
    "zero",
    "one",
    "two",
    "three",
    "four",
    "five",
    "six",
    "seven",
    "eight",
    "nine",
];
var tn = [
    "ten",
    "eleven",
    "twelve",
    "thirteen",
    "fourteen",
    "fifteen",
    "sixteen",
    "seventeen",
    "eighteen",
    "nineteen",
];
var tw = [
    "twenty",
    "thirty",
    "forty",
    "fifty",
    "sixty",
    "seventy",
    "eighty",
    "ninety",
];

function toWords(s) {
    s = s.toString();
    s = s.replace(/[\, ]/g, "");
    if (s != parseFloat(s)) return "not a number";
    var x = s.indexOf(".");
    if (x == -1) x = s.length;
    if (x > 15) return "too big";
    var n = s.split("");
    var str = "";
    var sk = 0;
    for (var i = 0; i < x; i++) {
        if ((x - i) % 3 == 2) {
            if (n[i] == "1") {
                str += tn[Number(n[i + 1])] + " ";
                i++;
                sk = 1;
            } else if (n[i] != 0) {
                str += tw[n[i] - 2] + " ";
                sk = 1;
            }
        } else if (n[i] != 0) {
            str += dg[n[i]] + " ";
            if ((x - i) % 3 == 0) str += "hundred ";
            sk = 1;
        }
        if ((x - i) % 3 == 1) {
            if (sk) str += th[(x - i - 1) / 3] + " ";
            sk = 0;
        }
    }
    if (x != s.length) {
        var y = s.length;
        str += "point ";
        for (var i = x + 1; i < y; i++) str += dg[n[i]] + " ";
    }
    return str.replace(/\s+/g, " ");
}
