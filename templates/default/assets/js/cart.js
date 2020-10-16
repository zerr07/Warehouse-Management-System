sum();
function sum(){
    var quantity = [];
    var price = [];
    var totalPrice = [];
    $('input[id^="quantity"]').each(function () {
        if (isNaN(parseFloat($(this).val()))){
            quantity.push(0);
        } else {
            quantity.push($(this).val().replace(',','.'));
        }

    });
    $('input[id^="price"]').each(function () {
        if (isNaN(parseFloat($(this).val()))){
            price.push(0);
        } else {
            price.push($(this).val().replace(',','.'));
        }
    });
    var sum = 0;
    for (var i = 0; i<quantity.length; i++){
        sum = sum+(parseFloat(quantity[i])*parseFloat(price[i]));
        totalPrice.push((parseFloat(quantity[i])*parseFloat(price[i])).toFixed(2));
    }
    var counter = 0;
    $('div[id^="totalPrice"]').each(function () {
        counter++;
    });
    for (var i = 0; i < counter; i++){
        if(document.getElementById('totalPrice'+i)){
            document.getElementById('totalPrice'+i).innerHTML = totalPrice[i];
        }

    }
    sum = sum.toFixed(2);
    if (document.getElementById('sum')){
        document.getElementById('sum').innerHTML = 'Sum: '+sum;

    }
    document.getElementById('modalSUM').innerHTML = 'Sum: '+sum;
    return sum;
}
$('#cashBtn').click(function () {
    var cash = $('#cash');
    var card = $('#card');
    cash.val(sum());
    card.val("0.00");
    card.trigger("change");
    cash.trigger("change");
});
$('#cardBtn').click(function () {
    var cash = $('#cash');
    var card = $('#card');
    card.val(sum());
    cash.val("0.00");
    card.trigger("change");
    cash.trigger("change");
});
$('#cash').change(function () {
    var cash = $('#cash');
    var cashVal = parseFloat(cash.val());
    var card = $('#card');
    var total = sum();
    var cardVal = parseFloat(card.val());
    var checkSum = cardVal+cashVal;
    if (checkSum.toFixed(2) >= total){
        document.getElementById('sale').disabled = false;
    } else {
        document.getElementById('sale').disabled = true;
    }
    document.getElementById('modalTagasi').innerHTML = "Tagasi: " + (checkSum.toFixed(2)-total).toFixed(2).toString();
});
$('#card').change(function () {
    var cash = $('#cash');
    var card = $('#card');
    var cardVal = parseFloat(card.val());
    var total = sum();
    var cashVal = parseFloat(cash.val());
    var checkSum = cardVal+cashVal;
    if (checkSum.toFixed(2) >= total){
        document.getElementById('sale').disabled = false;
    } else {
        document.getElementById('sale').disabled = true;
    }
    document.getElementById('modalTagasi').innerHTML = "Tagasi: " + (checkSum.toFixed(2)-total).toFixed(2).toString();
});
