function updateCart(tag) {
    $.ajax({
        url:"/cp/POS/search.php", //the page containing php script
        type: "post", //request type,
        dataType: 'json',
        data: {searchTagID: tag},
        success:function(result){

        }
    });

}
function searchByName(name) {
     return $.ajax({
        url:"/cp/POS/searchProducts.php", //the page containing php script
        type: "post", //request type,
        dataType: 'json',
        async: false,
        data: {searchName: name}
    });
}
function updateByID(index) {
    return $.ajax({
        url:"/cp/POS/searchProducts.php", //the page containing php script
        type: "GET", //request type,
        dataType: 'json',
        async: false,
        data: {addID: index}
    });
}

function searchModal(name) {

    let cart = searchByName(index, name);
    cart = cart.responseJSON;


    let tbody = document.getElementById(index);
    tbody.innerHTML = "";
    let counter = 0;
    for (var key in cart){
        let imgURL = "";
        if (cart[key]['IMG'] === null || cart[key]['IMG'] === ""){
            imgURL = "https://static.pingendo.com/img-placeholder-1.svg";
        } else{
            imgURL = "/uploads/images/products/"+cart[key]['IMG'];
        }
        let name = "";
        if (cart[key]['tag'] === "Buffertoode"){
            name = "<input type='text' class='form-control' name='buffer[]' value='"+cart[key]['name']+"'" +
                " placeholder='Buffer name' id='buffer' data-placement='top' title='Try not to use numbers!'>" +
                "<input type='text' name='bufferID[]' value='"+cart[key]['id']+"' hidden>"
        } else {
            name = "<span class='d-inline-block text-truncate' style='max-width: 250px;'>" +
                "<a title='"+cart[key]['name']+"' style='color: white;text-overflow: ellipsis;' " +
                "href='/cp/WMS/view?view="+cart[key]['id']+"'>"+cart[key]['name']+"</a></span>"

        }
        let loc = "";
        if (cart[key]['loc'] !== null || cart[key]['loc'] !== ""){
            for(var place in cart[key]['loc']){
                loc = cart[key]['loc'][place].toString();
                break;
            }
        }
        $("#"+index).append("<tr>" +
            "<td>" +
            "<img class='img-fluid d-block itemSMimg' src='"+imgURL+"' width='70px' >" +
            "</td>" +
            "<td class='POStalble'>"+name+"</td>" +
            "<td class='POStalble'>" +
            "<input type='text' class='form-control' id='quantity"+counter+"' " +
            "onchange='sum();checkQuantity("+cart[key]['Available']+", "+counter+")' " +
            "name='quantity[]' value='"+cart[key]['quantity']+"' placeholder='Quantity'>" +
            "</td>" +
            "<td class='POStalble'>"+cart[key]['Available']+"</td>" +
            "<td class='POStalble'>"+loc+"</td>" +
            "<td class='POStalble'>" +
            "<input type='text' class='form-control' id='price'"+counter+" onchange='sum()' name='price[]' value='"+cart[key]['basePrice']+"' placeholder='Price'>" +
            "</td>" +
            "<td class='POStalble' id='totalPrice"+counter+"'>"+cart[key]['price']+"</td>" +
            "<td>" +
            "<button type='submit' formaction='/cp/POS/update.php' name='delete' value='"+cart[key]['id']+"'" +
            " class='btn btn-danger'><i class='fas fa-trash'></i></button>" +
            "</td>" +
            "<input type='text' name='id[]' value='"+cart[key]['id']+"' hidden>" +
            "</tr>");
        counter++;
    }
}