function displayCart(index) {

    let cart = getCartArray();
    cart = JSON.parse(cart.responseText);

    console.log(cart);
    let tbody = document.getElementById(index);
    tbody.innerHTML = "";
    let counter = 0;
    for (let key in cart){
        let tag = "";
        let imgURL = "";
        if (cart[key]['IMG'] === null || cart[key]['IMG'] === ""){
            imgURL = "https://static.pingendo.com/img-placeholder-1.svg";
        } else{
            imgURL = "/uploads/images/products/"+cart[key]['IMG'];
        }
        let name = "";
        let available = "";

        if (cart[key]['tag'] === "Buffertoode"){
            name = "<input type='text' class='form-control' name='buffer[]' value='"+cart[key]['name']+"'" +
                " placeholder='Buffer name' id='buffer' data-placement='top' title='Try not to use numbers!'>" +
                "<input type='text' name='bufferID[]' value='"+cart[key]['id']+"' hidden>"
            tag = "<span class='text-truncate' style='text-overflow: ellipsis;'>" + cart[key]['tag'] + "</span>";
        } else {
            available = cart[key]['Available'];
            if (available == null){
                available = 0;
            }
            name = "<span class='text-truncate' >" +
                "<a title='"+cart[key]['name']+"' style='color: white;text-overflow: ellipsis;' " +
                "href='/cp/WMS/view?view="+cart[key]['id']+"'>"+cart[key]['name']+"</a></span>";
            tag = "<span class='text-truncate' style='text-overflow: ellipsis;'>"
                + "<a title='"+cart[key]['tag']+"' href='/cp/WMS/view?view="+cart[key]['id']+"'>"
                + cart[key]['tag'] +
                "</a></span>";
        }

        let loc = "";
        if (cart[key]['loc'] !== null || cart[key]['loc'] !== ""){
            if (cart[key]['tag'] !== "Buffertoode") {
                if (cart[key]['loc']['selected']){
                    let def_loc = cart[key]['loc']['selected'].toString();

                    loc += "<select class=\"custom-select\" id='loc_selectPOP"+key+"' name='loc_selectPOP["+key+"]' onChange='setLoc(this)'>";
                    for (var place in cart[key]['loc']['locationList']) {
                        if (!isNaN(def_loc) || def_loc !== null){
                            if (place === def_loc){
                                loc += "<option value='"+place+"' selected>";
                            } else {
                                loc += "<option value='"+place+"'>";
                            }

                        } else {
                            loc += "<option value='"+place+"'>";
                        }
                        loc +=  cart[key]['loc']['locationList'][place]['type_name'].toString() + " : "
                            +   cart[key]['loc']['locationList'][place]['location'].toString() + " : "
                            +   cart[key]['loc']['locationList'][place]['quantity'].toString()
                            +"</option>"
                    }
                    loc += "</select>";
                }

            }
        }

        let locHidden = "";
        if (cart[key]['loc'] !== null || cart[key]['loc'] !== ""){
            if (cart[key]['tag'] !== "Buffertoode") {
                let def_loc = cart[key]['loc']['selected'].toString();
                locHidden += "<select class=\"custom-select\" id='loc_select"+key+"' name='loc_select["+key+"]' hidden>";
                for (var place in cart[key]['loc']['locationList']) {
                    if (!isNaN(def_loc) || def_loc !== null){
                        if (place === def_loc){
                            locHidden += "<option value='"+place+"' selected>";
                        } else {
                            locHidden += "<option value='"+place+"'>";
                        }

                    } else {
                        locHidden += "<option value='"+place+"'>";
                    }
                    locHidden += cart[key]['loc']['locationList'][place]['type_name'].toString() + " : "
                        +cart[key]['loc']['locationList'][place]['location'].toString() + " : "
                        +cart[key]['loc']['locationList'][place]['quantity'].toString()
                        +"</option>"
                }
                locHidden += "</select>";
            }
        }

        let quantityInput;
        let a;
        console.log(cart[key]['tag']);
        if (cart[key]['tag'] !== "Buffertoode"){
            quantityInput = "<input type='text' class='form-control' id='quantity"+counter+"' " +
                "onchange='sum();checkQuantity("+cart[key]['Available']+", "+counter+")' " +
                "name='quantity[]' value='"+cart[key]['quantity']+"' placeholder='Quantity' hidden>";
            a = "/ "+cart[key]['Available'];
        } else {
            quantityInput = "<input type='text' class='form-control' id='quantity"+counter+"' " +
                "name='quantity[]' value='"+cart[key]['quantity']+"' placeholder='Quantity' hidden>";
            a = "";
        }

        $("#"+index).append("<div class='row mt-3 border border-secondary p-1' data-toggle='popover' id='"+key+"'>" +
            "<input type='text' name='id[]' value='"+cart[key]['id']+"' hidden>" +
            locHidden +
            "<div class='p-0 col-3 col-sm-3 col-md-2 col-xl-2 m-auto d-flex justify-content-start'>" + tag + "</div>" +
            "<div class='p-0 col-3 col-sm-3 col-md-5 col-xl-4 m-auto d-flex justify-content-center'>"+name + quantityInput+"<input type='text' class='form-control' id='price"+counter+"' onchange='sum()' name='price[]' value='"+cart[key]['basePrice']+"' placeholder='Price' hidden></div>" +
            "<div class='p-0 d-none d-xl-flex col-xl-1 m-auto justify-content-center'><span id='quantityXL"+counter+"'>"+cart[key]['quantity']+"</span>" + "<span> "+a +" pcs</span></div>" +
            "<div class='p-0 d-none d-xl-flex col-xl-1 m-auto justify-content-center'><span id='basePriceXL"+counter+"'>"+cart[key]['basePrice']+"</span></div>" +
            "<div class='p-0 d-none d-xl-flex col-xl-2 m-auto justify-content-center'><span id='locXL"+counter+"'></span></div>" +
            "<div class='p-0 col-3 col-sm-3 col-md-2 col-xl-1 m-auto d-flex justify-content-center'>Total : <div id='totalPrice"+counter+"'>"+cart[key]['price']+"</div></div>" +
            "<div class='p-0 col-1 col-sm-1 col-md-1 col-xl-1 m-auto d-flex justify-content-end'>" +
            "<button type='submit' formaction='/cp/POS/update.php' name='delete' value='"+cart[key]['id']+"'" +
            " class='btn btn-link' style='color: #cd6464'><i class='fas fa-trash'></i></button>" +
            "</div>" +
            "</div>");
        let div;
        let input1 = document.createElement("input");
        let label1 = document.createElement("label");
        label1.setAttribute('for', "#popPrice"+counter);
        label1.innerText = "Base price";
        input1.setAttribute('onchange', "setValuePrice('"+counter+"')");
        input1.setAttribute('type', "text");
        input1.setAttribute('id', "popPrice"+counter);
        input1.setAttribute('class', "form-control dont-hide");
        let input2 = document.createElement("input");
        let label2 = document.createElement("label");
        let child = document.createElement('div');
        label2.setAttribute('for', "#popQuantity"+counter);
        label2.innerText = "Quantity";
        input2.setAttribute('onchange', "setValueQuantity('" + cart[key]['Available'] + "','" + counter + "')");

        if (cart[key]['tag'] !== "Buffertoode") {
            child.innerHTML = loc;
            child = child.firstChild;
            place = cart[key]['loc']['selected'];
            document.getElementById("locXL"+counter).innerText = cart[key]['loc']['locationList'][place]['type_name'].toString() + " : "+
                cart[key]['loc']['locationList'][place]['location'].toString() + " : "+
                cart[key]['loc']['locationList'][place]['quantity'].toString();
        }
        input2.setAttribute('type', "text");
        input2.setAttribute('id', "popQuantity"+counter);
        input2.setAttribute('class', "form-control dont-hide");
        div = document.createElement("div");
        div.appendChild(label1);
        div.appendChild(input1);
        div.appendChild(label2);
        div.appendChild(input2);
        input1.value = cart[key]['basePrice'];
        input2.value = cart[key]['quantity'];


        let label3 = document.createElement("label");
        label3.setAttribute('for', "#loc_selectPOP"+key);
        label3.innerText = "Location";
        div.appendChild(label3);
        div.appendChild(child);

        $("#"+key).popover({
            html : true,
            title: 'Controls',
            content: div,
            placement: 'left'
        })

        counter++;
    }
}
function setLoc(selectObject){
    $("#"+(selectObject.id).replace("POP", "")).val($("#"+selectObject.id).val())

}


function setValuePrice(counter){
    let pop = $('#popPrice'+counter);
    let target = $('#price'+counter);
    target.val(pop.val());
    sum()
    document.getElementById("basePriceXL"+counter).innerText = pop.val();
}

function setValueQuantity(ava ,counter){
    let pop = $('#popQuantity'+counter);
    let target = $('#quantity'+counter);
    target.val(pop.val());
    sum();
    checkQuantity(ava, counter)
    document.getElementById("quantityXL"+counter).innerText = pop.val();
}

function getCartArray() {
    return $.ajax({
        dataType: "text",
        async: false,
        url: "/cp/POS/getCartJson.php"
    });
}
