function init_image_uploader(prefix){
    eval("ImageUploader_images"+prefix + " = []");
}


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
                        eval("ImageUploader_images"+prefix + ".push(['new',e.target.result])");
                    } else {
                        var image = new Image();
                        image.onload = function(e) {
                            let multiply = 1;
                            let newimg = ImageUploader_resize(image, multiply)
                            while (ImageUploader_getBase64FileSizeInKB(newimg) > 1000){
                                multiply++;
                                newimg = ImageUploader_resize(image, multiply);
                            }
                            eval("ImageUploader_images"+prefix + ".push(['new',newimg])");
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
    eval("var img = ImageUploader_images"+prefix);
    img = img.filter(function (el) {
        return el != null;
    });
    let imagesPreviewBlock = $("#ImageUploader_previewImages"+prefix);
    imagesPreviewBlock.html("");
    for (let id in img){
        let btn1 = document.createElement("button");
        btn1.setAttribute('onclick', "ImageUploader_deleteImg("+id+", \""+prefix+"\")");
        btn1.setAttribute('type', "button");
        btn1.setAttribute('class', "btn btn-outline-primary");
        btn1.innerText = "Delete";
        imagesPreviewBlock.append(" " +
            "<img src='"+img[id][1]+"' id='image"+id+"_"+prefix+"' alt='' class='img-thumbnail ImageUploader_image'" +
            " data-toggle='popover' onclick='ImageUploader_showPopover(this)' draggable='true'>" +
            "<div class='ImageUploader_img_between' draggable='true'></div>");

        $("#image"+id+"_"+prefix).popover({
            html : true,
            title: 'Controls',
            content: btn1,
            placement: 'top'
        })
    }
    eval("if ( $( \"#ImageUploader_imagesJSON"+prefix+"\" ).length ) {$(\"#ImageUploader_imagesJSON"+prefix+"\").val(JSON.stringify(ImageUploader_images"+prefix+"));}");
    ImageUploader_addListeners();
}

function ImageUploader_showPopover(el){
    $("[data-toggle='popover']").popover("hide");
    $(el).popover("show");
}

function ImageUploader_deleteImg(id, prefix) {
    $("[data-toggle='popover']").popover("hide");
    eval("delete ImageUploader_images"+prefix+"[id]");
    eval("ImageUploader_images"+prefix+" = ImageUploader_images"+prefix+".filter(Boolean)");
    ImageUploader_displayImagePreview(prefix);


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
    e.dataTransfer.setData('text/html', this);
}

function ImageUploader_handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    return false;
}
function ImageUploader_handleDragEnter(e) {
    if (this !== dragSrcEl){
        this.style.opacity = '0.4';
        ImageUploader_overin(this);
    }

}
function ImageUploader_handleDragLeave(e) {
    if (this !== dragSrcEl){
        this.style.opacity = '1';
        ImageUploader_overout(this);
    }

}
function ImageUploader_handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation(); // stops the browser from redirecting.
    }
    if (dragSrcEl !== this) {
        if (this.parentNode.id === dragSrcEl.closest("div").id){
            if (this.closest("div").className === "ImageUploader_img_between over" || this.closest("div").className === "ImageUploader_img_between"){
                let el = $(this).next()[0];
                let dragTemp = dragSrcEl.src;
                var NewElement = document.createElement('img');
                NewElement.src = dragTemp;
                NewElement.draggable = true;
                NewElement.id = dragSrcEl.id;
                NewElement.width = dragSrcEl.width;
                NewElement.height = dragSrcEl.height;
                NewElement.className = "img-thumbnail ImageUploader_image";
                NewElement.setAttribute("onclick", dragSrcEl.getAttribute("onclick"))
                if (el !== undefined){
                    NewElement.appendBefore(el);
                } else {
                    NewElement.appendBefore(this);
                }

                var NewElementBetween = document.createElement('div');
                NewElementBetween.draggable = true;
                NewElementBetween.className = "ImageUploader_img_between";
                NewElementBetween.appendAfter(NewElement);
                $(dragSrcEl).next().remove();
                dragSrcEl.remove();
                ImageUploader_addListeners();
            } else {
                if(dragSrcEl.tagName === "IMG"){
                    let temp = this.src;
                    let dragTemp = dragSrcEl.src;
                    dragSrcEl.src = temp;
                    this.src = dragTemp;
                }
            }
        }
    }
    let new_arr;
    let prefix = [];
    for (var key in window) {
        if (key.startsWith("ImageUploader_images")){
            prefix.push(key.replace("ImageUploader_images", ""));
        }
    }
    for (var c in prefix){
        new_arr = [];
        let els = document.querySelectorAll('#ImageUploader_previewImages'+prefix[c]+' > .ImageUploader_image');
        for (let i = 0; i< els.length; i++){
            if (ImageUploader_isBase64(els[i].src)){
                eval("new_arr.push(ImageUploader_search(els[i].src, ImageUploader_images"+prefix[c]+"))");
            } else {
                let src = (els[i].src).substr(els[i].src.indexOf('/', 8) + 1);
                eval("new_arr.push(ImageUploader_search(\"/\"+src, ImageUploader_images"+prefix[c]+"))");
            }
        }
        eval("ImageUploader_images"+prefix[c]+" = new_arr");
        eval("ImageUploader_displayImagePreview(\""+prefix[c]+"\")");

    }
    return false;
}

function ImageUploader_handleDragEnd(e) {
    items.forEach(function (item) {
        item.style.opacity = '1';
        ImageUploader_overout(item);
    });
}
function ImageUploader_addListeners(){
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
    return str.startsWith("data:image/");
}

function ImageUploader_overin(el){
    el.classList.add('over');
}
function ImageUploader_overout(el){
    el.classList.remove('over');
}