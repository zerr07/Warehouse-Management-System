function displayCart(index) {

    let cart = getCartArray();
    cart = JSON.parse(cart.responseText);

    console.log(cart);
    let tbody = document.getElementById(index);
    tbody.innerHTML = "";
    let counter = 0;
    for (var key in cart){
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
            tag = "<span class='d-inline-block text-truncate' style='max-width: 50px;color: white;text-overflow: ellipsis;'>" + cart[key]['tag'] + "</span>";
        } else {
            available = cart[key]['Available'];
            name = "<span class='d-inline-block text-truncate' style='max-width: 250px;'>" +
                "<a title='"+cart[key]['name']+"' style='color: white;text-overflow: ellipsis;' " +
                "href='/cp/WMS/view?view="+cart[key]['id']+"'>"+cart[key]['name']+"</a></span>";
            tag = "<span class='d-inline-block text-truncate' style='max-width: 50px;color: white;text-overflow: ellipsis;'>"
                + "<a title='"+cart[key]['tag']+"' href='/cp/WMS/view?view="+cart[key]['id']+"'>"
                + cart[key]['tag'] +
                "</a></span>";
        }

        let loc = "";
        if (cart[key]['loc'] !== null || cart[key]['loc'] !== ""){
            if (cart[key]['tag'] !== "Buffertoode") {
                console.log(key);
                let def_loc = cart[key]['loc']['selected'].toString();
                loc += "<select class=\"custom-select\" name='loc_select["+key+"]'>";
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
                        loc += cart[key]['loc']['locationList'][place]['type_name'].toString() + " : "
                        +cart[key]['loc']['locationList'][place]['location'].toString() + " : "
                        +cart[key]['loc']['locationList'][place]['quantity'].toString()
                        +"</option>"
                }
                loc += "</select>";
            }
        }
        let quantityInput;
        console.log(cart[key]['tag']);
        if (cart[key]['tag'] !== "Buffertoode"){
            quantityInput = "<input type='text' class='form-control' id='quantity"+counter+"' " +
                "onchange='sum();checkQuantity("+cart[key]['Available']+", "+counter+")' " +
                "name='quantity[]' value='"+cart[key]['quantity']+"' placeholder='Quantity'>";
        } else {
            quantityInput = "<input type='text' class='form-control' id='quantity"+counter+"' " +
                "name='quantity[]' value='"+cart[key]['quantity']+"' placeholder='Quantity'>";
        }

        $("#"+index).append("<tr>"
            + "<td class='POStalble'>" + tag + "</td>" +
            "<td>" + "<input type='text' name='id[]' value='"+cart[key]['id']+"' hidden>" +
            "<img class='img-fluid d-block itemSMimg' src='"+imgURL+"' width='70px' >" +
            "</td>" +
            "<td class='POStalble'>"+name+"</td>" +
            "<td class='POStalble'>" +
            quantityInput +
            "</td>" +
            "<td class='POStalble'>"+available+"</td>" +
            "<td class='POStalble'>"+loc+"</td>" +
            "<td class='POStalble'>" +
            "<input type='text' class='form-control' id='price'"+counter+" onchange='sum()' name='price[]' value='"+cart[key]['basePrice']+"' placeholder='Price'>" +
            "</td>" +
            "<td class='POStalble' id='totalPrice"+counter+"'>"+cart[key]['price']+"</td>" +
            "<td>" +
            "<button type='submit' formaction='/cp/POS/update.php' name='delete' value='"+cart[key]['id']+"'" +
            " class='btn btn-danger'><i class='fas fa-trash'></i></button>" +
            "</td>" +
            "</tr>");
        counter++;
    }
}
function getCartArray() {
    return $.ajax({
        dataType: "text",
        async: false,
        url: "/cp/POS/getCartJson.php"
    });
}
