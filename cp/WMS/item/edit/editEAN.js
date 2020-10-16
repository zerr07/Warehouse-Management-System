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
    for (let key in codes){
        $("#linkEANModal").append("<div class='row'>" +
            "<div class='col-6'>"+codes[key]['ean']+"</div> " +
            "<div class='col-6'><button type='button' class='btn btn-link btn-cat' onclick=\"deleteEAN("+key+");getCodes("+index+");\"><i class='fas fa-trash'></i> Delete</button></div> " +
            "</div>");
    }
    $("#linkEANModal").append("<form action='/cp/WMS/item/edit/setEAN.php' method='post' id='EANform'><div class='form-group' id='formEANExport'></div></form>");
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
        url: "/cp/WMS/item/edit/deleteEAN.php"
    });
}
