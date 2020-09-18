function getLinks(index, name) {
    let platforms = $.ajax({
        dataType: "text",
        async: false,
        url: "/controllers/getPlatformMargin.php"
    });
    let platformsImport = $.ajax({
        dataType: "text",
        async: false,
        url: "/controllers/getImportPlatforms.php"
    });
    let links = $.ajax({
        type: "post",
        data: {'index': index},
        dataType: "text",
        async: false,
        url: "/controllers/categories/get_platform_categories.php"
    });
    let linksImport = $.ajax({
        type: "post",
        data: {'index': index},
        dataType: "text",
        async: false,
        url: "/controllers/categories/get_platform_import_categories.php"
    });
    platforms = JSON.parse(platforms.responseText);
    links = JSON.parse(links.responseText);
    platformsImport = JSON.parse(platformsImport.responseText);
    linksImport = JSON.parse(linksImport.responseText);
    document.getElementById("linkExportTitle").innerHTML = "Edit Export/Import "+name;
    let tbody = document.getElementById("linkExportModal");
    tbody.innerHTML = "";

    /* DRAW Import LINKS */
    $("#linkExportModal").append("<div class='table-responsive'>" +
        "<p>Import Links</p>" +
        "<table class='table table-sm table-responsive'>" +
        "<thead>" +
        "<tr>" +
        "<th>Platform</th>" +
        "<th>Linked ID</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody id='linkImportTableBody'>" +
        "</tbody>" +
        "</table>" +
        "</div>");
    for (let key in linksImport){
        $("#linkImportTableBody").append("<tr>" +
            "<td>"+platformsImport[linksImport[key]['id_platform']]['name']+"</td>" +
            "<td>"+linksImport[key]['id_category_platform']+"</td>" +
            "<td><button type='button' class='btn btn-link btn-cat' onclick=\"deleteLinkImport("+key+");getLinks("+index+",'"+name+"');\"><i class='fas fa-trash'></i> Delete</button></td>" +
            "</tr>");
    }
    $("#linkExportModal").append("<form action='/controllers/categories/insertImportLink.php' method='post'><div class='form-group' id='formLinkImport'></div></form>");
    $("#formLinkImport").append("<input type='text' name='catID' value='"+index+"' hidden>" +
        "<select class='form-control doubleInput' id='linkSelectImport' name='platformID'></select>");
    for (let key in platformsImport){
        $("#linkSelectImport").append("<option value='"+platformsImport[key]['id']+"'>"+platformsImport[key]['name']+"</option>");
    }
    $("#formLinkImport").append("<input type='text' class='form-control doubleInput' id='exampleFormControlInput1' name='platformCategory' placeholder='Platform category id' required>" +
        "<button type='submit' style='margin-bottom: 2px;' class='btn btn-primary'>Submit</button>");


    /* DRAW EXPORT LINKS */
    $("#linkExportModal").append("<hr style='border-color: white;'><div class='table-responsive'>" +
        "<p>Export Links</p>" +
        "<table class='table table-sm table-responsive'>" +
        "<thead>" +
        "<tr>" +
        "<th>Platform</th>" +
        "<th>Linked ID</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody id='linkTableBody'>" +
        "</tbody>" +
        "</table>" +
        "</div>");
    for (let key in links){
        $("#linkTableBody").append("<tr>" +
            "<td>"+platforms[links[key]['id_platform']]['name']+"</td>" +
            "<td>"+links[key]['id_category_platform']+"</td>" +
            "<td><button type='button' class='btn btn-link btn-cat' onclick=\"deleteLink("+key+");getLinks("+index+",'"+name+"');\"><i class='fas fa-trash'></i> Delete</button></td>" +
            "</tr>");
    }
    $("#linkExportModal").append("<form action='/controllers/categories/insertExportLink.php' method='post' id='form_export'>" +
        "<div class='row' id='formLinkExport'>" +
        "</div>" +
        "</form>");
    $("#formLinkExport").append(" <input type='text' name='catID' value='"+index+"' hidden>" +
        "<div class='col-3 p-0'><select class='form-control' id='linkSelect' name='platformID'></select></div>");
    for (let key in platforms){
        $("#linkSelect").append("<option value='"+platforms[key]['id']+"'>"+platforms[key]['name']+"</option>");
    }
    $("#formLinkExport").append("<div class='col-4 p-0'>" +
        "<input type='text' class='form-control' id='exampleFormControlInput1' name='platformCategory' placeholder='Platform category id' required>" +
        "</div>" +
        "<div class='col-2 p-0'>" +
        "<button type='submit' style='margin-bottom: 1px;' class='btn btn-primary'>Submit</button>" +
        "</div>"+
        "<div class='col-3 p-0'>" +
        "<input type='button' name='bulk' value='Bulk Submit' style='margin-bottom: 1px;' class='btn btn-primary' onclick=\"if (confirm('Are you sure?')){bulk_submit("+index+")}\"" +
        "</div>");


    $('form').on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type     : "POST",
            cache    : false,
            url      : $(this).attr('action'),
            data     : $(this).serialize(),
            success  : function(data) {
                console.log(data);
                getLinks(index, name);
            }
        });

    });

}

function deleteLink(index) {
    $.ajax({
        type: "post",
        data: {'index': index},
        dataType: "text",
        async: false,
        url: "/controllers/categories/deleteExportLink.php"
    });
}
function deleteLinkImport(index) {
    $.ajax({
        type: "post",
        data: {'index': index},
        dataType: "text",
        async: false,
        url: "/controllers/categories/deleteImportLink.php"
    });
}

function bulk_submit(index){
    $.ajax({
        type     : "POST",
        cache    : false,
        url      : $("#form_export").attr('action'),
        data     : $("#form_export").serialize()+ '&bulk=',
        success  : function(data) {
            console.log(data);
            getLinks(index, name);
        }
    });
}