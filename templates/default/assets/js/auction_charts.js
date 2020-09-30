importScripts("/templates/default/assets/js/fakeDom.js");
importScripts("/templates/default/assets/js/jquery.js");
importScripts("/templates/default/assets/js/moment.js");
var chart1Loaded = false;
var chart2Loaded = false;
var chart3Loaded = false;
var chart_data;
var chart_avg_data;
var chart_avgLast7_data;
var dataChart;
var dataAvg;
var dataAvg7;
console.log("JQuery version:", $.fn.jquery);
var tag;
self.addEventListener("message", function(e) {
    console.log(JSON.parse(e.data));
    let msg = JSON.parse(e.data);
    if (msg['type'] === "tag"){
        tag = msg['data'];
    }
    if (msg['type'] === "loadChart2Data"){
        get_chart2_data()
    }
    if (msg['type'] === "loadChart3Data"){
        get_chart3_data()
    }

}, false);
/*$loaderProgress[0].style.display = "block";
$preloaderProgress[0].style.display = "block";
$($loaderProgressBar[0]).css("width", "0%");
$($loaderProgressBar[0]).attr("aria-valuenow", "0");*/
setTimeout(() => load_chart_data(tag), 1000);

function get_chart2_data(){
    if (dataAvg.length !== 0){
        if (!chart2Loaded){
            /*$loaderProgress[0].style.display = "block";
            $preloaderProgress[0].style.display = "block";
            $($loaderProgressBar[0]).css("width", "0%");
            $($loaderProgressBar[0]).attr("aria-valuenow", "0");*/
            let msg = `{
                    "type": "DrawChart2",
                    "data": `+computeData2(dataAvg)+`
                    }`;
            postMessage(msg)
            chart2Loaded = true;
        }
    } else {
        let msg = `{
                "type": "noChart2"
                }`;
        postMessage(msg)
    }
}
function get_chart3_data(){
    if (dataAvg7.length !== 0){
        if (!chart3Loaded){
            /*$loaderProgress[0].style.display = "block";
            $preloaderProgress[0].style.display = "block";
            $($loaderProgressBar[0]).css("width", "0%");
            $($loaderProgressBar[0]).attr("aria-valuenow", "0%");*/
            let msg = `{
                    "type": "DrawChart3",
                    "data": `+computeData3(dataAvg7)+`
                    }`;
            postMessage(msg)
        }
    } else {
        let msg = `{
                "type": "noChart3"
                }`;
        postMessage(msg)
    }
}
function load_chart_data(tag){

    chart_data = $.ajax({
        type: "GET",
        dataType: "text",
        async: false,
        url: "/controllers/getDrundelChartData.php?tag="+tag,
        success: function () {

            let msg = `{
            "type": "toggleModal"
            }`;
            postMessage(msg)
            /*$($loaderProgressBar[0]).css("width", "25%");
            $($loaderProgressBar[0]).attr("aria-valuenow", "25");*/
        }
    });

    chart_avg_data = $.ajax({
        type: "GET",
        dataType: "text",
        async: false,
        url: "/controllers/getDrundelChartData.php?tagAVG="+tag,
        success: function () {
            /*$($loaderProgressBar[0]).css("width", "50%");
            $($loaderProgressBar[0]).attr("aria-valuenow", "50");*/
        }
    });
    chart_avgLast7_data = $.ajax({
        type: "GET",
        dataType: "text",
        async: false,
        url: "/controllers/getDrundelChartData.php?tagAVG7="+tag,
        success: function () {
            /*$($loaderProgressBar[0]).css("width", "75%");
            $($loaderProgressBar[0]).attr("aria-valuenow", "75");*/
        }
    });
    dataChart = JSON.parse(chart_data.responseText);
    dataAvg = JSON.parse(chart_avg_data.responseText);
    dataAvg7 = JSON.parse(chart_avgLast7_data.responseText);
    dataChart = Object.entries(dataChart);
    dataAvg = Object.entries(dataAvg);
    dataAvg7 = Object.entries(dataAvg7);
    if (dataChart.length !== 0){
        let msg = `{
         "type": "DrawChart1",
         "data": `+computeData1(dataChart)+`
         }`;
        postMessage(msg)
        chart1Loaded = true;
    } else {
        let msg = `{
        "type": "noChart1"
        }`;
        postMessage(msg)
    }
}


function computeData1(cData){
    var profit_table = [];
    var labels_table = [];
    profit_table.push('profit');
    labels_table.push('x1');
    for (let i = 0; i < cData.length; i++){
        cData[i][1]['profit'] = Math.round(cData[i][1]['profit'] * 100) / 100;
        cData[i][1]['finalprice'] = Math.round(cData[i][1]['finalprice'] * 100) / 100;
        cData[i][1]['lisateenused'] = Math.round(cData[i][1]['lisateenused'] * 100) / 100;
        cData[i][1]['buyprice'] = Math.round(cData[i][1]['buyprice'] * 100) / 100;
        cData[i][1]['enddate'] = moment(cData[i][1]['enddate'], "DD.MM.YYYY");
        cData[i][1]['startdate'] = moment(cData[i][1]['startdate'], "DD.MM.YYYY");
        profit_table.push(cData[i][1]['profit']);
        labels_table.push(cData[i][1]['enddate'].valueOf());
    }
    let arr = [];
    arr.push(profit_table);
    arr.push(labels_table);
    arr.push(cData);
    return JSON.stringify(arr);
}
function computeData2(cData){
    var profit_table = [];
    var labels_table = [];
    profit_table.push('profit');
    labels_table.push('x1');
    for (let i = 0; i < cData.length; i++){
        cData[i][1]['enddate'] = moment(cData[i][1]['enddate'], "YYYY-MM-DD");
        cData[i][1]['avg'] = Math.round(cData[i][1]['avg'] * 100) / 100;
        profit_table.push(Math.round(cData[i][1]['avg'] * 100) / 100);
        labels_table.push(cData[i][1]['enddate'].valueOf());
    }
    let arr = [];
    arr.push(profit_table);
    arr.push(labels_table);
    arr.push(cData);
    return JSON.stringify(arr);
}
function computeData3(cData){
    var profit_table = [];
    var labels_table = [];
    profit_table.push('profit');
    labels_table.push('x1');
    for (let i = 0; i < cData.length; i++){
        cData[i][1]['enddate'] = moment(cData[i][1]['enddate'], "YYYY-MM-DD");
        cData[i][1]['avg'] = Math.round(cData[i][1]['avg'] * 100) / 100;
        profit_table.push(Math.round(cData[i][1]['avg'] * 100) / 100);
        labels_table.push(cData[i][1]['enddate'].valueOf());
    }
    let arr = [];
    arr.push(profit_table);
    arr.push(labels_table);
    arr.push(cData);
    return JSON.stringify(arr);
}