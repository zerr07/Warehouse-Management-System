function changePercent() {
    document.getElementById("itemMarginPercent").value =
        (document.getElementById("itemMarginNumber").value/document.getElementById("actPrice").value*100).toFixed(2);
}
function changeNumber() {
    document.getElementById("itemMarginNumber").value =
        (document.getElementById("actPrice").value*document.getElementById("itemMarginPercent").value/100).toFixed(2);
}

/*function checkCustomMargin() {
    if (document.getElementById("override").checked) {
        document.getElementById("override").checked = false;
    } else {
        document.getElementById("override").checked = true;
    }
}*/
function exportAll() {
    let platforms = $.ajax({
        dataType: "text",
        async: false,
        url: "/controllers/getPlatformMargin.php"
    });
    platforms = JSON.parse(platforms.responseText);
    for (let key in platforms){
        document.getElementById("export"+platforms[key]['id']).checked = true;
    }
}
function unsetCustomAll() {
    let platforms = $.ajax({
        dataType: "text",
        async: false,
        url: "/controllers/getPlatformMargin.php"
    });
    platforms = JSON.parse(platforms.responseText);
    for (let key in platforms){
        document.getElementById("platformCustom"+platforms[key]['id']).checked = false;
    }
    applyPrices();
}
function applyPrices() {
    let platforms = $.ajax({
        dataType: "text",
        async: false,
        url: "/controllers/getPlatformMargin.php"
    });
    let rules = $.ajax({
        dataType: "text",
        async: false,
        url: "/controllers/getPriceRule.php"
    });
    rules = JSON.parse(rules.responseText);

    platforms = JSON.parse(platforms.responseText);
    let price = parseFloat(document.getElementById("actPrice").value);
    for (var key in platforms) {
        if (document.getElementById("platform" + platforms[key]['id'].toString())) {
            if (document.getElementById("override").checked) {
                if (!document.getElementById("platformCustom" + platforms[key]['id'].toString()).checked) {
                    applyMargin(key, price, platforms);
                }
            } else {
                if (!document.getElementById("platformCustom" + platforms[key]['id'].toString()).checked) {
                    applyPriceRule(key, rules, price, platforms);
                }
            }
            calcProfit(key, price, platforms);
        }
    }
}

function applyPriceRule(key, rules, price, platforms) {
    let margin;
    for (var ruleKey in rules){
        if (price >= parseFloat(rules[ruleKey]['startPrice']) && price <= parseFloat(rules[ruleKey]['endPrice'])){
            margin = price * (parseFloat(rules[ruleKey]['percent'])/100);
            if (margin < parseFloat(rules[ruleKey]['minMargin']) && rules[ruleKey]['minMargin'] !== ""){

                document.getElementById("platform"+platforms[key]['id'].toString()).value =
                    ((price + parseFloat(rules[ruleKey]['minMargin']))
                        * parseFloat(platforms[key]['margin']) * 1.2).toFixed(2);


            } else {

                document.getElementById("platform"+platforms[key]['id'].toString()).value =
                    ((price + margin) * parseFloat(platforms[key]['margin']) * 1.2).toFixed(2);

            }
        }
    }
}
function applyMargin(key, price, platforms) {
    let margin = parseFloat(document.getElementById("itemMarginNumber").value);
    if (isNaN(margin)){
        margin = 0.00;
    }
    document.getElementById("platform"+platforms[key]['id'].toString()).value =
        ((price + margin) * parseFloat(platforms[key]['margin']) * 1.2).toFixed(2);
}

function calcProfit(key, price, platforms) {  // key = platform id, price = actual price, platforms = list of platforms
    let final = document.getElementById("platform"+platforms[key]['id'].toString()).value;
    if(key === "1" || key === "2"){
        document.getElementById("profit"+platforms[key]['id'].toString()).innerHTML =
            (( ( parseFloat(final) / parseFloat(platforms[key]['margin']) )/1.2 ) - price).toFixed(2).toString();
    } else {
        document.getElementById("profit"+platforms[key]['id'].toString()).innerHTML =
            (( ( parseFloat(final) / parseFloat(platforms[key]['margin']) ) * parseFloat(platforms[key]['profitMargin'])  ) - price).toFixed(2).toString();
    }
}