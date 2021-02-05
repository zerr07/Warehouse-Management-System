async function loadParams(el){
    return fetch("/controllers/products/properties.php?getProperties")
        .then(response => response.json())
        .then( await function (d) {
            el.innerHTML = "";
            let opt = document.createElement("option");
            opt.setAttribute("value", "None")
            opt.innerHTML = "Select param";
            el.append(opt)
            for (let id in d){
                let opt = document.createElement("option");
                opt.setAttribute("value", id)
                opt.innerHTML = d[id]['name'][Object.keys(d[id]['name'])[0]]['name'];
                el.append(opt)
            }
            let el_v = el.parentNode.parentNode.lastChild.firstChild;
            el_v.innerHTML = "";
            let opt_v = document.createElement("option");
            opt_v.setAttribute("value", "None")
            opt_v.innerHTML = "Select value";
            el_v.append(opt_v)
            //loadParamValues(el.parentNode.parentNode.lastChild.firstChild, d[Object.keys(d)[0]]['id'])
        });
}

async function loadParamValues(el, param){
    return fetch("/controllers/products/properties.php?getPropertyValues="+param).then(response => response.json())
        .then(await function (d) {
            el.innerHTML = "";
            let opt = document.createElement("option");
            opt.setAttribute("value", "None")
            opt.innerHTML = "Select value";
            el.append(opt)
            for (let id in d){
                let opt = document.createElement("option");
                opt.setAttribute("value", id)

                opt.innerHTML = d[id]['name'][Object.keys(d[id]['name'])[0]]['name'];
                el.append(opt)
            }

        }).finally(() => {
            disableUsedParams()
        })
}

function loadParamsEdit(block_id, id) {
    fetch("/controllers/products/properties.php?getProductProperties="+id).then(response => response.json())
        .then(async function (d){
            for (let id in d) {
                console.log("Params started")
                await loadParamsEditField(block_id);
                console.log("Params finished")
            }
            console.log("Assign started")
            await assignParamsValuesEdit(d)
            console.log("Assign finished")
        })/*.finally(() => {
        disableUsedParams();
    })*/
}

async function assignParamsValuesEdit(params){
    let c = 0;
    let selects_p = document.querySelectorAll("select[onchange*='loadParamValues(this.parentNode.parentNode.lastChild.firstChild, this.value)']");

    for (let id in params){
        let opts = selects_p[c].childNodes;
        for (const el of opts) {
           if (parseInt(el.getAttribute("value")) === params[id]['id_prop']){
               el.selected = true;
               await loadParamValues(el.parentNode.parentNode.parentNode.lastChild.firstChild, params[id]['id_prop']);
               let selects_v = document.querySelectorAll("select[name='param_val[]']");
               let opts_v = selects_v[c].childNodes;
               opts_v.forEach( el_v => {
                   if (parseInt(el_v.getAttribute("value")) === parseInt(params[id]['id_value'])){
                       el_v.selected = true;
                   }
               });
           }
        }
        c++;
    }

}

function disableUsedParams(){
    let arr = [];
    let selects_p = document.querySelectorAll("select[onchange*='loadParamValues(this.parentNode.parentNode.lastChild.firstChild, this.value)']");
    selects_p.forEach(select => {
        if (select.value !== "" && select.value !== "None"){
            arr.push(select.value);
        }


    });
    selects_p.forEach(select => {
        let opt_o = select.querySelectorAll("option")
        opt_o.forEach(el =>{
            el.disabled = false;
        })
        arr.forEach(value => {
            let opt = select.querySelector("option[value='"+value+"']")
            if (opt) {
                if (select.value !== value) {
                    opt.disabled = true;
                }
            }
        });
    });
}

async function loadParamsEditField(block_id){
    let div1 = document.createElement("div");
    div1.setAttribute("class", "col-12");
    let div2 = document.createElement("div");
    div2.setAttribute("class", "row");
    let div3 = document.createElement("div");
    div3.setAttribute("class", "col-6");
    let div4 = document.createElement("div");
    div4.setAttribute("class", "col-6");
    let input1 = document.createElement("select");
    input1.setAttribute("class", "form-control");
    input1.setAttribute("onchange", "loadParamValues(this.parentNode.parentNode.lastChild.firstChild, this.value);");
    div3.append(input1);
    let input2 = document.createElement("select");
    input2.setAttribute("class", "form-control");
    input2.setAttribute("name", "param_val[]");
    div4.append(input2);

    document.getElementById(block_id).append(div1)
    div1.append(div2)
    div2.append(div3)
    div2.append(div4)
    await loadParams(input1);
    console.log("created")
    await disableUsedParams();
    console.log("disabled")
}
