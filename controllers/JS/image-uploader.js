
function previewImage(input) {
    if (input.files) {
        let count = input.files.length;
        for (let i = 0; i < count; i++) {
            let reader = new FileReader();
            reader.onload = function (e) {
                if (e.target.result.match(/^(data:image)/i)) {
                    images.push(['new',e.target.result, "0"]);
                }
                displayImagePreview();
            };
            reader.readAsDataURL(input.files[i]);
        }
    }
}
function displayImagePreview() {
    let imagesPreviewBlock = $("#previewImages");
    imagesPreviewBlock.html("");
    for (let id in images){
        if (images[id][2] === 1){
            imagesPreviewBlock.append("<div class='col-auto d-inline-block'> " +
                "<img width='200px' height='200px' src='"+images[id][1]+"' id='image"+id+"' alt='' class='img-thumbnail' onclick='displayPreviewFunc("+id+")' >" +
                "<span class='image-primary-preview'>Primary</span></div>");
        } else {
            imagesPreviewBlock.append("<div class='col-auto d-inline-block'> " +
                "<img width='200px' height='200px' src='"+images[id][1]+"' id='image"+id+"' alt='' class='img-thumbnail' onclick='displayPreviewFunc("+id+")' >" +
                "</div>");
        }


    }
    $("#imagesJSON").val(JSON.stringify(images));
}
function displayPreviewFunc(id) {
    displayImagePreview();
    $("#image"+id).addClass("hover");
    let imagesPreviewBlock = $("#previewImages");
    imagesPreviewBlock.addClass("col-10");
    let previewFunc = $("#previewImagesFunc");
    previewFunc.addClass("border border-dark rounded");
    previewFunc.html("");
    previewFunc.append("<button type='button' class='btn btn-outline-primary' onclick='deleteImg("+id+")'>Delete</button>");
    previewFunc.append("<button type='button' class='btn btn-outline-primary mt-3' onclick='setPrimaryImage("+id+")'>Set primary</button>");
}

function deleteImg(id) {
    delete images[id];
    images = images.filter(Boolean);
    displayImagePreview();
    let previewFunc = $("#previewImagesFunc");
    previewFunc.removeClass("border border-dark rounded");
    previewFunc.html("");
    let imagesPreviewBlock = $("#previewImages");
    imagesPreviewBlock.removeClass("col-10");
}
function setPrimaryImage(id) {
    for (index in images){
        images[index][2] = 0;
    }
    images[id][2] = 1;
    displayImagePreview();
    displayPreviewFunc(id);
}