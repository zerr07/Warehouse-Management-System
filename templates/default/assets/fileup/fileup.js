$(document).ready(function() {
    var input = document.querySelector('#image_uploads');
    var preview = document.querySelector('.preview');
    input.style.opacity = 0;
    input.addEventListener('change', updateImageDisplay);

    function updateImageDisplay() {
        while (preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }

        var curFiles = input.files;
        if (curFiles.length === 0) {
            var para = document.createElement('p');
            para.textContent = 'No files currently selected for upload';
            preview.appendChild(para);
        } else {
            var list = document.createElement('ol');
            preview.appendChild(list);
            for (var i = 0; i < curFiles.length; i++) {
                var listItem = document.createElement('li');
                listItem.classList.add("img_li");
                var para = document.createElement('p');
                if (validFileType(curFiles[i])) {
                    para.classList.add("text-dark");
                    para.classList.add("img_p");
                    para.textContent = 'File name ' + curFiles[i].name + ', file size ' + returnFileSize(curFiles[i].size) + '.';
                    var image = document.createElement('img');
                    image.classList.add("prev_img");
                    image.src = window.URL.createObjectURL(curFiles[i]);
                    let itemID = Date.now()+i;
                    let ext = (curFiles[i].type).replace('image/', '');
                    let defaultCheck = $("<input type='text' name='imgName[]' value='"+itemID+"."+ext+"' hidden>" +
                        "<input class='form-check-input' type='radio' style='margin-top: 22px;' " +
                        "name='defaultImage' id='defaultImage' value='"+itemID+"."+ext+"'>" +
                        "<label class='form-check-label form-img-delete' style='margin-top: 20px;' for='defaultImage'>" +
                        "Default" +
                        "</label>");
                    console.log(defaultCheck);
                    listItem.append(defaultCheck[0]);
                    listItem.append(defaultCheck[1]);
                    listItem.append(defaultCheck[2]);
                    listItem.appendChild(image);
                    listItem.appendChild(para);

                } else {
                    para.textContent = 'File name ' + curFiles[i].name + ': Not a valid file type. Update your selection.';
                    listItem.appendChild(para);
                }

                list.appendChild(listItem);
            }
        }
    }
});
var fileTypes = [
    'image/jpeg',
    'image/pjpeg',
    'image/png'
];

function validFileType(file) {
    for(var i = 0; i < fileTypes.length; i++) {
        if(file.type === fileTypes[i]) {
            return true;
        }
    }

    return false;
}function returnFileSize(number) {
    if(number < 1024) {
        return number + 'bytes';
    } else if(number > 1024 && number < 1048576) {
        return (number/1024).toFixed(1) + 'KB';
    } else if(number > 1048576) {
        return (number/1048576).toFixed(1) + 'MB';
    }
}
function getQueryVariable(variable)
{
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
        var pair = vars[i].split("=");
        if(pair[0] == variable){return pair[1];}
    }
    return(false);
}