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
    $("#linkExportModal").append(
        "<div class='row'>" +
            "<div class='col-12'>" +
                "<p>Import Links</p>" +
            "</div>" +
            "<div class='col-12'>" +
                "<div id='linkImportTableBody'></div>" +
            "</div>" +
        "</div>");
    for (let key in linksImport){
        $("#linkImportTableBody").append("<div class='row'>" +
            "<div class='col-3 m-auto'>"+platformsImport[linksImport[key]['id_platform']]['name']+"</div>" +
            "<div class='col-4 m-auto'>"+linksImport[key]['id_category_platform']+"</div>" +
            "<div class='col-5'><button type='button' class='btn btn-link' " +
            "onclick=\"deleteLinkImport("+key+");getLinks("+index+",'"+name+"');\"><i class='fas fa-trash'></i> Delete</button></div></div>");
    }
    $("#linkExportModal").append("<form action='/controllers/categories/insertImportLink.php' method='post'>" +
        "<div class='form-row align-items-end' id='formLinkImport'></div></form>");
    $("#formLinkImport").append("<input type='text' name='catID' value='"+index+"' hidden>" +
        "<div class='col-12 col-sm-12 col-md-4 mt-1 mt-sm-1 mt-md-0'>" +
        "<label for='linkSelectImport' style='font-size: 0.8rem;'>Platform</label>" +
        "<select class='form-control' id='linkSelectImport' name='platformID'></select>" +
        "</div>");
    for (let key in platformsImport){
        $("#linkSelectImport").append("<option value='"+platformsImport[key]['id']+"'>"+platformsImport[key]['name']+"</option>");
    }
    $("#formLinkImport").append("<div class='col-12 col-sm-12 col-md-4 mt-1 mt-sm-1 mt-md-0'>" +
        "<label for='platformCategoryImport' style='font-size: 0.8rem;'>Platform category id</label>" +
        "<input type='text' class='form-control' id='platformCategoryImport' name='platformCategory' placeholder='Platform category id' required>" +
        "</div><div class='col-12 col-sm-12 col-md-4 mt-1 mt-sm-1 mt-md-0'>" +
        "<button type='submit' class='btn btn-primary w-100'>Submit</button>" +
        "</div>");


    /* DRAW EXPORT LINKS */
    $("#linkExportModal").append("<hr style='border-color: white;'>" +
        "<div class='row'>" +
            "<div class='col-12'>" +
                "<p>Import Links</p>" +
            "</div>" +
            "<div class='col-12'>" +
                "<div id='linkTableBody'></div>" +
            "</div>" +
        "</div>");
    for (let key in links){
        $("#linkTableBody").append("<div class='row'>" +
            "<div class='col-3 m-auto'>"+platforms[links[key]['id_platform']]['name']+"</div>" +
            "<div class='col-4 m-auto'>"+links[key]['id_category_platform']+"</div>" +
            "<div class='col-5'><button type='button' class='btn btn-link btn-cat' " +
            "onclick=\"deleteLink("+key+");getLinks("+index+",'"+name+"');\"><i class='fas fa-trash'></i> Delete</button></div></div>");

    }
    $("#linkExportModal").append("<form action='/controllers/categories/insertExportLink.php' method='post' id='form_export'>" +
        "<div class='form-row align-items-end' id='formLinkExport'>" +
        "</div>" +
        "</form>");
    $("#formLinkExport").append(" <input type='text' name='catID' value='"+index+"' hidden>" +
        "<div class='col-12 col-sm-12 col-md-3 mt-1 mt-sm-1 mt-md-0 p-0'>" +
        "<label for='linkSelect' style='font-size: 0.8rem;'>Platform</label>" +
        "<select class='form-control' id='linkSelect' name='platformID'></select>" +
        "</div>");
    for (let key in platforms){
        $("#linkSelect").append("<option value='"+platforms[key]['id']+"'>"+platforms[key]['name']+"</option>");
    }
    $("#formLinkExport").append("" +
        "<div class='col-12 col-sm-12 col-md-3 mt-1 mt-sm-1 mt-md-0 p-0'>" +
        "<label for='platformCategory' style='font-size: 0.8rem;'>Platform category id</label>" +
        "<input type='text' class='form-control' id='platformCategory' name='platformCategory' placeholder='Platform category id' required>" +
        "</div>" +
        "<div class='col-12 col-sm-12 col-md-3 mt-1 mt-sm-1 mt-md-0 p-0'>" +
        "<button type='submit' class='btn btn-primary w-100'>Submit</button>" +
        "</div>"+
        "<div class='col-12 col-sm-12 col-md-3 mt-1 mt-sm-1 mt-md-0 p-0'>" +
        "<input type='button' name='bulk' value='Bulk Submit' class='btn btn-info w-100' onclick=\"if (confirm('Are you sure?')){bulk_submit("+index+")}\"" +
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