function ImageUploader_resize(image, multiply){
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

function ImageUploader_getBase64FileSizeInKB(string){
    var stringLength = string.length - 'data:image/png;base64,'.length;

    var sizeInBytes = 4 * Math.ceil((stringLength / 3))*0.5624896334383812;
    return sizeInBytes/1000;
}

function ImageUploader_previewImage(input, prefix) {
    if (input.files) {
        let count = input.files.length;
        for (let i = 0; i < count; i++) {

            let reader = new FileReader();
            reader.onload = function (e) {

                if (e.target.result.match(/^(data:image)/i)) {
                    if (ImageUploader_getBase64FileSizeInKB(e.target.result) < 1000){

                        if (prefix === "_live"){
                            ImageUploader_images_live.push(['new',e.target.result]);
                        } else {
                            ImageUploader_images.push(['new',e.target.result]);
                        }
                    } else {
                        var image = new Image();
                        image.onload = function(e) {
                            let multiply = 1;
                            let newimg = resize(image, multiply)
                            while (ImageUploader_getBase64FileSizeInKB(newimg) > 1000){
                                multiply++;
                                newimg = ImageUploader_resize(image, multiply);
                            }

                            if (prefix === "_live"){
                                ImageUploader_images_live.push(['new',newimg]);
                            } else {
                                ImageUploader_images.push(['new',newimg]);
                            }
                            ImageUploader_displayImagePreview(prefix);
                            return;
                        };
                        image.src = e.target.result;
                    }
                }
                ImageUploader_displayImagePreview(prefix);
            };
            reader.readAsDataURL(input.files[i]);
        }
    }
}
function ImageUploader_displayImagePreview(prefix) {
    if (prefix === "_live"){
        var img = ImageUploader_images_live;

    } else {
        var img = ImageUploader_images;
    }
    img = img.filter(function (el) {
        return el != null;
    });
    let imagesPreviewBlock = $("#ImageUploader_previewImages"+prefix);
    imagesPreviewBlock.html("");
    for (let id in img){
        imagesPreviewBlock.append("<div class='col-auto d-inline-flex ImageUploader_image' draggable='true'> " +
            "<img width='200px' height='200px' src='"+img[id][1]+"' id='image"+id+"_"+prefix+"' alt='' class='img-thumbnail' onclick='ImageUploader_displayPreviewFunc("+id+", \""+prefix+"\")' >" +
            "</div><div class='col-auto d-inline-flex ImageUploader_img_between'><div class='droppable'></div></div>");
    }
    if ( $( "#ImageUploader_imagesJSON" ).length ) {
        $("#ImageUploader_imagesJSON").val(JSON.stringify(ImageUploader_images));
    }
    if ( $( "#ImageUploader_imagesJSON_live" ).length ) {
        $("#ImageUploader_imagesJSON_live").val(JSON.stringify(ImageUploader_images_live));
    }
    ImageUploader_addListeners();
}
function ImageUploader_displayPreviewFunc(id,prefix) {
    var img = ImageUploader_images;
    ImageUploader_displayImagePreview(prefix);
    $("#image"+id+"_"+prefix).addClass("hover");
    let imagesPreviewBlock = $("#ImageUploader_previewImages"+prefix);
    imagesPreviewBlock.addClass("col-10");
    let previewFunc = $("#ImageUploader_previewImagesFunc"+prefix);
    previewFunc.addClass("border border-dark rounded");
    previewFunc.html("");
    previewFunc.append("<button type='button' class='btn btn-outline-primary' onclick='ImageUploader_deleteImg("+id+", \""+prefix+"\")'>Delete</button>");
}

function ImageUploader_deleteImg(id, prefix) {
    if (prefix === "_live"){
        delete ImageUploader_images_live[id];
    } else {
        delete ImageUploader_images[id];
    }
    ImageUploader_images = ImageUploader_images.filter(Boolean);
    ImageUploader_images_live = ImageUploader_images_live.filter(Boolean);

    ImageUploader_displayImagePreview(prefix);
    let previewFunc = $("#ImageUploader_previewImagesFunc"+prefix);
    previewFunc.removeClass("border border-dark rounded");
    previewFunc.html("");
    let imagesPreviewBlock = $("#ImageUploader_previewImages"+prefix);
    imagesPreviewBlock.removeClass("col-10");
}

Element.prototype.appendAfter = function (element) {
    element.parentNode.insertBefore(this, element.nextSibling);
},false;
Element.prototype.appendBefore = function (element) {
    element.parentNode.insertBefore(this, element);
},false;
var dragSrcEl = null;

function ImageUploader_handleDragStart(e) {
    this.style.opacity = '0.4';
    dragSrcEl = this;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);
}

function ImageUploader_handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    return false;
}
function ImageUploader_handleDragEnter(e) {
    this.classList.add('over');
}
function ImageUploader_handleDragLeave(e) {
    this.classList.remove('over');
}
function ImageUploader_handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation(); // stops the browser from redirecting.
    }
    if (dragSrcEl !== this) {
        if (this.closest("div").parentNode.id === dragSrcEl.closest("div").parentNode.id){


            if (this.closest("div").className === "col-auto d-inline-flex ImageUploader_img_between over" || this.closest("div").className === "col-auto d-inline-flex ImageUploader_img_between"){
                let el = $(this).next()[0];
                var NewElement = document.createElement('div');
                NewElement.innerHTML = e.dataTransfer.getData('text/html');
                NewElement.draggable = true;
                NewElement.className = "ImageUploader_image";
                if (el !== undefined){
                    NewElement.appendBefore(el);
                } else {
                    NewElement.appendBefore(this);
                }
                var NewElementBetween = document.createElement('div');
                NewElementBetween.draggable = true;
                NewElementBetween.className = "ImageUploader_img_between";
                NewElementBetween.appendAfter(NewElement);
                $(dragSrcEl).closest("div").next().remove();
                dragSrcEl.closest("div").remove();
                ImageUploader_addListeners();
            } else {
                if(dragSrcEl.firstElementChild.tagName === "IMG"){
                    dragSrcEl.innerHTML = this.innerHTML;
                    this.innerHTML = e.dataTransfer.getData('text/html');
                }
            }
        }
    }
    let new_arr = [];
    let els = document.querySelectorAll('#ImageUploader_previewImages > .ImageUploader_image img');

    for (let i = 0; i< els.length; i++){
        if (ImageUploader_isBase64(els[i].src)){
            new_arr.push(ImageUploader_search(els[i].src, ImageUploader_images));
        } else {
            let src = (els[i].src).substr(els[i].src.indexOf('/', 8) + 1);
            new_arr.push(ImageUploader_search("/"+src, ImageUploader_images));
        }
    }

    ImageUploader_images = new_arr;
    let new_arr_live = [];
    let els_live = document.querySelectorAll('#ImageUploader_previewImages_live > .ImageUploader_image img');
    for (let i = 0; i< els_live.length; i++){
        if (ImageUploader_isBase64(els_live[i].src)){
            new_arr_live.push(ImageUploader_search(els_live[i].src, ImageUploader_images_live));
        } else {
            let src = (els_live[i].src).substr(els_live[i].src.indexOf('/', 8) + 1);
            new_arr_live.push(ImageUploader_search("/"+src, ImageUploader_images_live));
        }
    }

    ImageUploader_images_live = new_arr_live;
    ImageUploader_displayImagePreview("");
    ImageUploader_displayImagePreview("_live");
    return false;
}

function ImageUploader_handleDragEnd(e) {
    this.style.opacity = '1';
    items.forEach(function (item) {
        item.classList.remove('over');
    });
}
function ImageUploader_addListeners(){
    items = document.querySelectorAll('.ImageUploader_image');
    items.forEach(function(item) {

    });
    items = document.querySelectorAll('.ImageUploader_image, .ImageUploader_img_between');
    items.forEach(function(item) {
        item.addEventListener('dragstart', ImageUploader_handleDragStart, false);
        item.addEventListener('dragenter', ImageUploader_handleDragEnter, false);
        item.addEventListener('dragover', ImageUploader_handleDragOver, false);
        item.addEventListener('dragleave', ImageUploader_handleDragLeave, false);

        item.addEventListener('drop', ImageUploader_handleDrop, false);
        item.addEventListener('dragend', ImageUploader_handleDragEnd, false);
    });
}

function ImageUploader_search(nameKey, myArray){
    myArray = myArray.filter(function (el) {
        return el != null;
    });
    for (var i=0; i < myArray.length; i++) {
        if (myArray[i][1] === nameKey) {
            return myArray[i];
        }
    }
}
window.onload = function(){
    let items = document.querySelectorAll('.ImageUploader_image, .ImageUploader_img_between');
    ImageUploader_addListeners();
}

function ImageUploader_isBase64(str) {
    try {
        return btoa(atob(str)) === str;
    } catch (err) {
        return false;
    }
}