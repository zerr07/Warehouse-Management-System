window.forms = [];
function editor_bold() {
    document.execCommand('bold');
}
function editor_italic() {
    document.execCommand('italic');
}
function editor_underline() {
    document.execCommand('underline');
}
function editor_removeTextFormat() {
    document.execCommand('removeFormat');
}
function editor_strikeThrough() {
    document.execCommand('strikeThrough');
}
function editor_subscript() {
    document.execCommand('subscript');
}
function editor_superscript() {
    document.execCommand('superscript');
}
function editor_insertOrderedList() {
    document.execCommand('insertOrderedList');
}
function editor_insertUnorderedList() {
    document.execCommand('insertUnorderedList');
}
function editor_outdent() {
    document.execCommand('outdent');
}
function editor_indent() {
    document.execCommand('indent');
}
function editor_justifyLeft() {
    document.execCommand('justifyLeft');
}
function editor_justifyCenter() {
    document.execCommand('justifyCenter');
}
function editor_justifyRight() {
    document.execCommand('justifyRight');
}
function editor_justifyFull() {
    document.execCommand('justifyFull');
}
function editor_Translate(block, lang){
    let editor = document.getElementById(block);
    let req = {
        method: "POST",
        headers: new Headers({"Content-Type": "application/json"}),
        body: JSON.stringify({
            target: lang,
            text: editor.innerHTML

        })
    }
    fetch("/controllers/translateAzure.php",req).then(response => response.json()).then(d => {
        if (d.hasOwnProperty("result")){
            editor.innerHTML = d.result
        } else if (d.hasOwnProperty("error")){
			
            alert(d.error)
        } else {
            alert("Unknown error")
        }
    })
}
function clickCheck(ele) {
    console.log(ele)
    let checkbox = ele.parentNode.querySelector("[id*='HTMLCheckbox']");
    console.log(ele.parentNode.querySelector("[id*='HTMLCheckbox']").checked)
    if (checkbox.checked === true){
        ele.classList.remove("active")
        //$(ele).removeClass('active');
        checkbox.checked = false;
    } else {
        console.log(checkbox.checked)
        ele.classList.add("active")
        //$(ele).addClass('active');
        checkbox.checked = true;
    }

}

function editor_fontsize(fontsize) {
    document.execCommand("FontSize", false, "7");
    let fontElements = document.getElementsByTagName("font");
    for (let i = 0, len = fontElements.length; i < len; ++i) {
        if (fontElements[i].size === "7") {
            fontElements[i].removeAttribute("size");
            fontElements[i].style.fontSize = fontsize.toString()+"px";
        }
    }
}

function loadEditor(index, lang, mode =1) {
    let txtAreaObj = $("#"+index);                                        // hidden textarea object
    let txtArea = "#"+index;

    let editorAreaID = index+"editor";                                    // id of actual editor

    let btnAreaID = index+"button";
    let btnRowAreaID = index+"row";
    let btnArea = "#"+btnAreaID;
    let btnRowArea = "#"+btnRowAreaID;

    let parentBlockID = index+"parent";
    let parentBlock = "#"+parentBlockID;

    txtAreaObj.attr("hidden", true);
    let parent_div = "<div class='editor-parent-block' id='"+parentBlockID+"'></div>";
    let editor_div = "<div class='editor-block' id='"+editorAreaID+"' lang='"+lang+"' contenteditable='true' spellcheck='true'></div>"; // editor block
    let button_div = "<div class='btn-group editor-btn-block' id='"+btnAreaID+"' role='group'>" +
        "<div class='row m-0 w-100' id='"+btnRowAreaID+"'></div>" +
        "</div>";

    $(parent_div).insertAfter(txtArea);
    $(parentBlock).append(button_div);

    $(parentBlock).append(editor_div);
    document.getElementById(editorAreaID).addEventListener("paste", function(e) {
        // cancel paste
        e.preventDefault();



        if ($("#HTMLCheckbox"+index).prop('checked') === true){
            let text = (e.originalEvent || e).clipboardData.getData('text/html');
            document.execCommand("insertHTML", false, text)
        } else {
            let text = (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand("insertText", false, text);
        }

    });
    if (mode === 1){
        let boldButton          = "<button class='btn btn-primary w-100' type='button' onclick='editor_bold()'><b>B</b></button>";
        let italicButton        = "<button class='btn btn-primary w-100' type='button' onclick='editor_italic()'><em>I</em></button>";
        let underlineButton     = "<button class='btn btn-primary w-100' type='button' onclick='editor_underline()'><u>U</u></button>";
        let removeFormatButton  = "<button class='btn btn-primary w-100' type='button' onclick='editor_removeTextFormat()'><i class='fas fa-eraser'></i></button>";
        let strikeThroughButton = "<button class='btn btn-primary w-100' type='button' onclick='editor_strikeThrough()'><s>S</s></button>";
        let subscriptButton     = "<button class='btn btn-primary w-100' type='button' onclick='editor_subscript()'>X<sub>2</sub></button>";
        let superscriptButton   = "<button class='btn btn-primary w-100' type='button' onclick='editor_superscript()'>X<sup>2</sup></button>";
        let orderedButton       = "<button class='btn btn-primary w-100' type='button' onclick='editor_insertOrderedList()'><i class='fas fa-list-ol'></i></button>";
        let unorderedButton     = "<button class='btn btn-primary w-100' type='button' onclick='editor_insertUnorderedList()'><i class='fas fa-list'></i></button>";
        let outdent             = "<button class='btn btn-primary w-100' type='button' onclick='editor_outdent()'><i class='fas fa-outdent'></i></button>";
        let indent              = "<button class='btn btn-primary w-100' type='button' onclick='editor_indent()'><i class='fas fa-indent'></i></button>";
        let justifyLeft         = "<button class='btn btn-primary w-100' type='button' onclick='editor_justifyLeft()'><i class='fas fa-align-left'></i></button>";
        let justifyCenter       = "<button class='btn btn-primary w-100' type='button' onclick='editor_justifyCenter()'><i class='fas fa-align-center'></i></button>";
        let justifyRight        = "<button class='btn btn-primary w-100' type='button' onclick='editor_justifyRight()'><i class='fas fa-align-right'></i></button>";
        let justifyFull         = "<button class='btn btn-primary w-100' type='button' onclick='editor_justifyFull()'><i class='fas fa-align-justify'></i></button>";

        let HTMLtoggle = "<div class='btn-group-toggle' data-toggle='buttons' >" +
            "<input type='checkbox' id='HTMLCheckbox"+index+"' autocomplete='off' style='visibility: hidden;\n" +
            "    position: absolute;'>" +
            "   <label class='btn btn-primary html-editor-btn w-100' onclick='clickCheck(this)'>" +
            "       HTML" +
            "   </label>" +
            "</div>";
        let Translate           = "<button class='btn btn-primary w-100' type='button' onclick='editor_Translate(\""+editorAreaID+"\",\""+lang+"\")'>Translate</button>";

        let fontSizeDropdown = "" +
            "<div class='dropdown'>" +
            "  <button class='btn btn-primary dropdown-toggle dropdown-btn-editor w-100' type='button' id='dropdownMenuButton"+index+"'" +
            "    data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
            "   Font" +
            "  </button>" +
            "   <div class='dropdown-menu dropdown-menu-editor' aria-labelledby='dropdownMenuButton"+index+"'>" +
            "    <button type='button' class='dropdown-item dropdown-item-editor' onclick='editor_fontsize(15)'>15px</button>" +
            "    <button type='button' class='dropdown-item dropdown-item-editor' onclick='editor_fontsize(22)'>22px</button>" +
            "  </div>" +
            "</div>";

        $(btnRowArea).append("<div class='col p-0 m-0'>"+boldButton+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+italicButton+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+underlineButton+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+removeFormatButton+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+strikeThroughButton+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+subscriptButton+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+superscriptButton+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+fontSizeDropdown+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+orderedButton+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+unorderedButton+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+justifyLeft+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+justifyCenter+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+justifyRight+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+justifyFull+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+outdent+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+indent+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+HTMLtoggle+"</div>");
        $(btnRowArea).append("<div class='col p-0 m-0'>"+Translate+"</div>");
    }

    window.forms[index] = index+"editor";
    document.getElementById(editorAreaID).innerHTML = txtAreaObj.val();

}

$(window).on('load', function() {
    let $form = $('form');
    $form.submit(function (e) {
        for (let key in window.forms) {
            document.getElementById(key).value = document.getElementById(window.forms[key]).innerHTML;
        }
        return true;
    });
});
