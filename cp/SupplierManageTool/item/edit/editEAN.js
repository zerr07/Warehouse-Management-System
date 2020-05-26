function getCodes(index) {

    let codes = $.ajax({
        type: "post",
        data: {'index': index},
        dataType: "text",
        async: false,
        url: "/controllers/products/getEAN.php"
    });
    codes = JSON.parse(codes.responseText);
    document.getElementById("linkEANTitle").innerHTML = "Edit EAN";
    let tbody = document.getElementById("linkEANModal");
    tbody.innerHTML = "";
    $("#linkEANModal").append("<div class='table-responsive'>" +
        "<table class='table table-sm table-responsive'>" +
        "<thead>" +
        "<tr>" +
        "<th>EAN</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody id='EANTableBody'>" +
        "</tbody>" +
        "</table>" +
        "</div>");
    for (let key in codes){
        $("#EANTableBody").append("<tr>" +
            "<td>"+codes[key]['ean']+"</td>" +
            "<td><button type='button' class='btn btn-link btn-cat' onclick=\"deleteEAN("+key+");getCodes("+index+");\"><i class='fas fa-trash'></i> Delete</button></td>" +
            "</tr>");
    }
    $("#linkEANModal").append("<form action='/cp/SupplierManageTool/item/edit/setEAN.php' method='post' id='EANform'><div class='form-group' id='formEANExport'></div></form>");
    $("#formEANExport").append("<input type='text' name='prodID' value='"+index+"' hidden>");
    $("#formEANExport").append("<input type='number' step='1' class='form-control doubleInput' id='exampleFormControlInput1' name='EAN' placeholder='EAN' required>" +
        "<button type='submit' style='margin-bottom: 2px;' class='btn btn-primary'>Submit</button>");

    $('#EANform').on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type     : "POST",
            cache    : false,
            url      : $(this).attr('action'),
            data     : $(this).serialize(),
            success  : function(data) {
                getCodes(index);
            }
        });

    });

}

function deleteEAN(index) {
    $.ajax({
        type: "post",
        data: {'index': index},
        dataType: "text",
        async: false,
        url: "/cp/SupplierManageTool/item/edit/deleteEAN.php"
    });
}
