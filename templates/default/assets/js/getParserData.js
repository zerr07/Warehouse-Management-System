function getParsedImages(id_product){
    if (document.getElementById("by-images-block").children.length === 0) {

        fetch("/controllers/parser/getData.php?images=" + id_product).then(response => response.json())
            .then(d => {
                let block = document.getElementById("by-images-block");
                if (d.length === 0) {
                    block.innerText = "Nothing found"
                }
                for (let c = 0; c < d.length; c++) {
                    fetch("/controllers/parser/getData.php?url=" + encodeURIComponent(d[c].url)).then(response => response.json()).then(r => {
                        if (parser_matches.includes(encodeURIComponent(d[c].url))) {
                            block.append(ParsedBox(id_product, r, d[c].url, true))
                        } else {
                            block.append(ParsedBox(id_product, r, d[c].url))
                        }
                    });
                }

            });
    }
}

function getParsedBySku(id, sku, platform){
    console.log("/controllers/parser/getData.php?sku="+sku+"&platform="+encodeURIComponent(platform))
    fetch("/controllers/parser/getData.php?sku="+sku+"&platform="+encodeURIComponent(platform)).then(response => response.json())
        .then(async d => {
            let block = document.getElementById("by-sku-block");
            block.innerHTML = "";
            if (d.length === 0){
                block.innerText = "Nothing found"
            }
            console.log(d)
            for (let c = 0; c < d.length; c++){
                console.log("/controllers/parser/getData.php?url="+encodeURIComponent(d[c].url))

                await fetch("/controllers/parser/getData.php?url="+encodeURIComponent(d[c].url)).then(response => response.json()).then(r => {
                    if (parser_matches.includes(d[c].url)){
                        console.log("includes")
                        block.append(ParsedBox(id, r, d[c].url, true, false, false))
                    } else {
                        block.append(ParsedBox(id, r, d[c].url, false, false, false))
                    }
                });
            }
        })
}

function getParsedByText(id, title, offset, platform=null){
    let btns = document.querySelectorAll("[onclick*='loadMoreTextParser(']")
    let els = document.querySelectorAll("[onchange*='getParsedByText(']")

    btns.forEach(btn => {
        btn.disabled = true
    })
    els.forEach(el => {
        el.disabled = true
    })
    console.log("/controllers/parser/getData.php?offset="+offset+"&title="+title+"&platform="+encodeURIComponent(platform))
    fetch("/controllers/parser/getData.php?offset="+offset+"&title="+title+"&platform="+encodeURIComponent(platform)).then(response => response.json())
        .then(async d => {
            let block = document.getElementById("by-text-block");
            if (d.length === 0){
                block.innerText = "Nothing found"
            }
            console.log("YES")
            for (let c = 0; c < d.length; c++){
                console.log("/controllers/parser/getData.php?url="+encodeURIComponent(d[c].url))

                await fetch("/controllers/parser/getData.php?url="+encodeURIComponent(d[c].url)).then(response => response.json()).then(r => {
                    if (r.hasOwnProperty("error")){
                        displayAlert(r.error, 5000, "error");
                    } else {
                        if (parser_matches.includes(d[c].url)){
                            console.log("includes")
                            block.append(ParsedBox(id, r, d[c].url, true, false, false,  d[c].score))
                        } else {
                            block.append(ParsedBox(id, r, d[c].url, false, false, false,  d[c].score))
                        }
                    }

                });
            }
        })
        .finally(() => {
            btns.forEach(btn => {
                btn.disabled = false
            })
            els.forEach(el => {
                el.disabled = false
            })
        });

}

function getParseBlock(id){
    let block = document.getElementById("parse-block");
    block.innerHTML = ""
    fetch("/controllers/parser/getData.php?inserted&id_product="+id).then(response => response.json())
        .then(async d => {
            for (let c = 0; c < d.length; c++){
                console.log(d)
                await fetch("/controllers/parser/getData.php?url="+encodeURIComponent(d[c])).then(response => response.json()).then(r => {
                    block.append(ParsedBox(id, r, d[c], false, true))
                })
                    .catch(() => {
                        block.append(ParsedBox(id, null, d[c], false, true, true))
                    });
            }
        })
}

async function MarkAsMatch(id, url, el=null){
    console.log(id, url)
    if (el !== null){
        if (el.checked){
            fetch("/controllers/parser/getData.php?insert&id_product="+id+"&url="+encodeURIComponent(url)).then(response => response.json())
                .then(d => {
                    if (d.hasOwnProperty("error")){
                        alert("Error: " + d.error)
                        el.checked = false
                    }

                }).finally(()=>{
                loadParserMatches();
            })
        } else {
            fetch("/controllers/parser/getData.php?delete&id_product="+id+"&url="+encodeURIComponent(url)).then(response => response.json())
                .then(d => {
                    if (d.hasOwnProperty("error")){
                        alert("Error: " + d.error)
                        displayAlert("Error: " + d.error, 10000, "error")
                        el.checked = true
                    }
                }).finally(()=>{
                loadParserMatches();
            })
        }
    } else {
        fetch("/controllers/parser/getData.php?insert&id_product="+id+"&url="+encodeURIComponent(url)).then(response => response.json())
            .then(d => {
                if (d.hasOwnProperty("error")){
                    displayAlert("Error: " + d.error, 10000, "error")

                }
            }).finally(()=>{
            loadParserMatches();
        })
    }


}

function RemoveMatch(id, url){
    fetch("/controllers/parser/getData.php?delete&id_product="+id+"&url="+encodeURIComponent(url)).then(response => response.json())
        .then(async d => {
            if (d.hasOwnProperty("error")){
                displayAlert("Error: " + d.error, 10000, "error")
            }

        }).finally(()=>{
        loadParserMatches();
    })
}

function ParseProductData(el, preview=false){
    let row = el.parentNode.parentNode.parentNode.parentNode
    let title_input = row.querySelector("input[id*='checkbox_title']")
    let desc_input = row.querySelector("input[id*='checkbox_desc']")
    let lang_select = row.querySelector("select[id*='select_lang']")
    let url = row.parentNode.parentNode.querySelector("a").href
    if (title_input.checked || desc_input.checked){
        let req = {
            method: "POST",
            headers: new Headers({"Content-Type": "application/json"}),
            body: JSON.stringify({
                parse: {
                    url: url,
                    lang: lang_select.value,
                    title: title_input.checked,
                    desc: desc_input.checked
                }
            })
        }
        fetch("/controllers/parser/getData.php", req).then(response => response.json())
            .then(d => {
                document.getElementById("PreviewParserModalDataTitle").innerHTML = "";
                document.getElementById("PreviewParserModalDataDesc").innerHTML = "";
                if (d.hasOwnProperty("title")) {
                    if (d.title !== null) {
                        if (preview) {
                            document.getElementById("PreviewParserModalDataTitle").innerHTML = d.title;
                        } else {
                            document.querySelector("input[id='itemName" + lang_select.value.toUpperCase() + "']").value = d.title;
                        }
                    }
                }
                if (d.hasOwnProperty("desc")){
                    if (d.desc !== null){
                        if (preview){
                            document.getElementById("PreviewParserModalDataDesc").innerHTML = d.desc;
                        } else {
                            document.querySelector("div[id='"+lang_select.value+"Texteditor']").innerHTML = d.desc;
                        }
                    }
                }
                if (preview){
                    $('#PreviewParserModal').modal('toggle')
                } else {
                    displayAlert("Parsing successful", 2000, "success")
                }
            })
    }
}



function ParsedBox(id, data, url, checked=false, parser=false, err=false, score=null){
    console.log(data)
    let div = document.createElement("div");
    div.setAttribute("class", "col-12");

    let div_row = document.createElement("div");
    div_row.setAttribute("class", "row mt-3");

    let div_img = document.createElement("div");
    div_img.setAttribute("class", "col-2 my-auto");

    let div_data = document.createElement("div");
    div_data.setAttribute("class", "col-3 my-auto");



    /* ------------------------------ */

        let img = document.createElement("img");
    if (!err){
        img.setAttribute("class", "thumbnail-img")
        img.setAttribute("src", data.image)
    }


    /* ------------------------------ */

    let title = document.createElement("span");
    title.setAttribute("class", "d-block")
    if (!err){
        title.innerText = "Title: " + data.title;
    } else {
        title.innerText = "Could not parse";
    }
    let url_anchor = document.createElement("a");
    url_anchor.setAttribute("class", "d-block")
    url_anchor.setAttribute("href", url);
    url_anchor.setAttribute("target", "_blank");
    if (!err){
        url_anchor.innerText = "Go to " + new URL(url).hostname.replace('www.','');
    } else {
        url_anchor.innerText = "Go to ";
    }

    let price = document.createElement("span");
    if (!err){
        price.innerText = "Price: " + data.price;
    }



    div_img.append(img);
    div_data.append(title);
    div_data.append(price);
    div_data.append(url_anchor);


    if (score !== null){
        let score_span = document.createElement("span");
        score_span.setAttribute("class", "d-block")
        score_span.innerText = "Score: " + score;
        div_data.append(score_span);
    }

    div_row.append(div_img);
    div_row.append(div_data);


    let uid;
    if (!parser) {
        let div_match = document.createElement("div");
        div_match.setAttribute("class", "col-7 my-auto");
        uid = "checkbox" + Date.now()

        let checkbox = document.createElement("input")
        checkbox.setAttribute("class", "form-check-input")
        checkbox.setAttribute("type", "checkbox")
        checkbox.setAttribute("id", uid)
        checkbox.setAttribute("onclick", "MarkAsMatch('" + id + "', '" + url + "', this)")
        if (checked) {
            checkbox.checked = true;
        }
        let checkbox_label = document.createElement("label")
        checkbox_label.setAttribute("class", "form-check-label")
        checkbox_label.setAttribute("for", uid)
        checkbox_label.innerText = "Match";
        div_match.append(checkbox);
        div_match.append(checkbox_label);
        div_row.append(div_match);
    } else {
        let div_parser = document.createElement("div");
        div_parser.setAttribute("class", "col-6  my-auto");

        let div_parser_row = document.createElement("div");
        div_parser_row.setAttribute("class", "row");

        let div_parser_lang_block = document.createElement("div");
        div_parser_lang_block.setAttribute("class", "col-4 my-auto");

        let div_parser_val_block = document.createElement("div");
        div_parser_val_block.setAttribute("class", "col-4 my-auto");

        let div_parser_btn_block = document.createElement("div");
        div_parser_btn_block.setAttribute("class", "col-4 my-auto");

        /* ------------------------------ */
        uid = "select_lang" + Date.now()
        let parser_lang_select = document.createElement("select")
        parser_lang_select.setAttribute("class", "form-control")
        parser_lang_select.setAttribute("id", uid)
        
        /* ------------------------------ */

        uid = "checkbox_title" + Date.now()
        let parser_checkbox_title_div = document.createElement("div")
        parser_checkbox_title_div.setAttribute("class", "form-check")
        let parser_checkbox_title = document.createElement("input")
        parser_checkbox_title.setAttribute("class", "form-check-input")
        parser_checkbox_title.setAttribute("type", "checkbox")
        parser_checkbox_title.setAttribute("name", "title")
        parser_checkbox_title.setAttribute("value", "yes")
        parser_checkbox_title.setAttribute("id", uid)

        let parser_checkbox_title_label = document.createElement("label")
        parser_checkbox_title_label.setAttribute("class", "form-check-label")
        parser_checkbox_title_label.setAttribute("for", uid)
        parser_checkbox_title_label.innerText = "Title"

        /* ------------------------------ */


        let parser_checkbox_desc_div = document.createElement("div")
        parser_checkbox_desc_div.setAttribute("class", "form-check")
        uid = "checkbox_desc" + Date.now()
        let parser_checkbox_desc = document.createElement("input")
        parser_checkbox_desc.setAttribute("class", "form-check-input")
        parser_checkbox_desc.setAttribute("type", "checkbox")
        parser_checkbox_desc.setAttribute("name", "desc")
        parser_checkbox_desc.setAttribute("value", "yes")
        parser_checkbox_desc.setAttribute("id", uid)

        let parser_checkbox_desc_label = document.createElement("label")
        parser_checkbox_desc_label.setAttribute("class", "form-check-label")
        parser_checkbox_desc_label.setAttribute("for", uid)
        parser_checkbox_desc_label.innerText = "Description"

        /* ------------------------------ */

        parser_checkbox_title_div.append(parser_checkbox_title)
        parser_checkbox_title_div.append(parser_checkbox_title_label)
        parser_checkbox_desc_div.append(parser_checkbox_desc)
        parser_checkbox_desc_div.append(parser_checkbox_desc_label)

        /* ------------------------------ */
        let parser_btn_row = document.createElement("div")
        parser_btn_row.setAttribute("class", "row")

        let parser_btn_col1 = document.createElement("div")
        parser_btn_col1.setAttribute("class", "col-12")

        let parser_btn_col2 = document.createElement("div")
        parser_btn_col2.setAttribute("class", "col-6")

        let parser_btn_col3 = document.createElement("div")
        parser_btn_col3.setAttribute("class", "col-6")

        let parser_btn = document.createElement("button")
        parser_btn.setAttribute("type", "button")
        parser_btn.setAttribute("class", "btn btn-info w-100")
        parser_btn.setAttribute("onclick", "ParseProductData(this)")
        parser_btn.innerText = "Parse"

        let parser_remove_btn = document.createElement("button")
        parser_remove_btn.setAttribute("type", "button")
        parser_remove_btn.setAttribute("class", "btn btn-danger w-100")
        parser_remove_btn.setAttribute("onclick", "RemoveMatch('"+id+"', '"+url+"')")
        parser_remove_btn.innerText = "Remove"

        let parser_preview_btn = document.createElement("button")
        parser_preview_btn.setAttribute("type", "button")
        parser_preview_btn.setAttribute("class", "btn btn-warning w-100")
        parser_preview_btn.setAttribute("onclick", "ParseProductData(this, true)")
        parser_preview_btn.innerText = "Preview"

        parser_btn_col1.append(parser_btn)
        parser_btn_col2.append(parser_preview_btn)
        parser_btn_col3.append(parser_remove_btn)
        parser_btn_row.append(parser_btn_col2)
        parser_btn_row.append(parser_btn_col3)
        parser_btn_row.append(parser_btn_col1)
        /* ------------------------------ */

        fetch("/controllers/parser/getData.php?getLanguages&url=" + url).then(response => response.json())
            .then(d => {
                for (let lang in d) {
                    let opt = document.createElement("option")
                    opt.setAttribute("value", lang)
                    opt.innerText = lang
                    parser_lang_select.append(opt)
                }
            }).finally(() => {
            div_parser_lang_block.append(parser_lang_select)
            div_parser_val_block.append(parser_checkbox_title_div)
            div_parser_val_block.append(parser_checkbox_desc_div)
            div_parser_btn_block.append(parser_btn_row)
            div_parser_row.append(div_parser_lang_block)
            div_parser_row.append(div_parser_val_block)
            div_parser_row.append(div_parser_btn_block)
            div_parser.append(div_parser_row)
            div_row.append(div_parser)
        })
    }

    div.append(div_row);

    return div;
}

