function resize(image, multiply){
    let canvas = document.createElement("canvas");
    let ctx = canvas.getContext("2d");
    canvas.width = image.width/multiply; // target width
    canvas.height = image.height/multiply; // target height
    ctx.drawImage(image,
        0, 0, image.width, image.height,
        0, 0, canvas.width, canvas.height
    );
    // create a new base64 encodin
    return canvas.toDataURL();
}

function getBase64FileSizeInKB(string){
    var stringLength = string.length - 'data:image/png;base64,'.length;

    var sizeInBytes = 4 * Math.ceil((stringLength / 3))*0.5624896334383812;
    return sizeInBytes/1000;
}

function previewImage(input) {
    if (input.id === "imageInput_live"){
        var prefix = "_live";
    } else {
        var prefix = "";
    }
    if (input.files) {
        let count = input.files.length;
        for (let i = 0; i < count; i++) {

            let reader = new FileReader();
            reader.onload = function (e) {

                if (e.target.result.match(/^(data:image)/i)) {
                    if (getBase64FileSizeInKB(e.target.result) < 1000){
                        if (prefix === "_live"){
                            images_live.push(['new',e.target.result, "0"]);
                        } else {
                            images.push(['new',e.target.result, "0"]);
                        }
                    } else {
                        var image = new Image();
                        image.onload = function(e) {
                            let multiply = 1;
                            let newimg = resize(image, multiply)
                            while (getBase64FileSizeInKB(newimg) > 1000){
                                multiply++;
                                newimg = resize(image, multiply);
                            }
                            if (prefix === "_live"){
                                images_live.push(['new',newimg, "0"]);
                            } else {
                                images.push(['new',newimg, "0"]);
                            }
                            displayImagePreview(prefix);
                            return;
                        };
                        image.src = e.target.result;
                    }
                }
                displayImagePreview(prefix);
            };
            reader.readAsDataURL(input.files[i]);
        }
    }
}
function displayImagePreview(prefix) {
    if (prefix === "_live"){
        var img = images_live;
    } else {
        var img = images;
    }
    let imagesPreviewBlock = $("#previewImages"+prefix);
    imagesPreviewBlock.html("");
    for (let id in img){
        if (img[id][2] === 1){

            imagesPreviewBlock.append("<div class='col-auto d-inline-block'> " +
                "<img width='200px' height='200px' src='"+img[id][1]+"' id='image"+id+"_"+prefix+"' alt='' class='img-thumbnail' onclick='displayPreviewFunc("+id+", \""+prefix+"\")' >" +
                "<span class='image-primary-preview'>Primary</span></div>");
        } else {
            imagesPreviewBlock.append("<div class='col-auto d-inline-block'> " +
                "<img width='200px' height='200px' src='"+img[id][1]+"' id='image"+id+"_"+prefix+"' alt='' class='img-thumbnail' onclick='displayPreviewFunc("+id+", \""+prefix+"\")' >" +
                "</div>");
        }


    }
    $("#imagesJSON").val(JSON.stringify(images));
    $("#imagesJSON_live").val(JSON.stringify(images_live));
}
function displayPreviewFunc(id, prefix) {
    displayImagePreview(prefix);
    $("#image"+id+"_"+prefix).addClass("hover");
    let imagesPreviewBlock = $("#previewImages"+prefix);
    imagesPreviewBlock.addClass("col-10");
    let previewFunc = $("#previewImagesFunc"+prefix);
    previewFunc.addClass("border border-dark rounded");
    previewFunc.html("");
    previewFunc.append("<button type='button' class='btn btn-outline-primary' onclick='deleteImg("+id+", \""+prefix+"\")'>Delete</button>");
    previewFunc.append("<button type='button' class='btn btn-outline-primary mt-3' onclick='setPrimaryImage("+id+", \""+prefix+"\")'>Set primary</button>");
}

function deleteImg(id, prefix) {
    if (prefix === "_live"){
        delete images_live[id];
    } else {
        delete images[id];
    }
    images = images.filter(Boolean);
    images_live = images_live.filter(Boolean);
    displayImagePreview(prefix);
    let previewFunc = $("#previewImagesFunc"+prefix);
    previewFunc.removeClass("border border-dark rounded");
    previewFunc.html("");
    let imagesPreviewBlock = $("#previewImages"+prefix);
    imagesPreviewBlock.removeClass("col-10");
}
function setPrimaryImage(id, prefix) {
    if (prefix === "_live"){
        for (index in images_live){
            images_live[index][2] = 0;
        }
        images_live[id][2] = 1;
    } else {
        for (index in images){
            images[index][2] = 0;
        }
        images[id][2] = 1;
    }
    displayImagePreview(prefix);
    displayPreviewFunc(id, prefix);
}