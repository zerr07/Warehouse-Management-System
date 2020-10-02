importScripts("/templates/default/assets/js/fakeDom.js");
importScripts("/templates/default/assets/js/jquery.js");
importScripts("/templates/default/assets/js/moment.js");
var chart1Loaded = false;
var chart2Loaded = false;
var chart3Loaded = false;
var chart_data;
var chart_sum_data;
var chart_avg_data;
var chart_avgLast7_data;
var dataChart;
var dataChartSUM;
var dataAvg;
var dataAvg7;
console.log("JQuery version:", $.fn.jquery);
var tag;
self.addEventListener("message", function(e) {
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
postMessage(`{
            "type": "turnOnPreloader"
            }`)

postMessage(`{
            "type": "setPreloaderProgress",
            "data": "0"
            }`)
setTimeout(() => load_chart_data(tag), 1000);

function get_chart2_data(){
    if (dataAvg.length !== 0){
        if (!chart2Loaded){
            postMessage(`{
            "type": "turnOnPreloader"
            }`)

            postMessage(`{
            "type": "setPreloaderProgress",
            "data": "0"
            }`)
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
            postMessage(`{
            "type": "turnOnPreloader"
            }`)

            postMessage(`{
            "type": "setPreloaderProgress",
            "data": "0"
            }`)
            let msg = `{
                    "type": "DrawChart3",
                    "data": `+computeData3(dataAvg7)+`
                    }`;
            postMessage(msg)
            chart3Loaded = true;
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
            postMessage(`{
            "type": "setPreloaderProgress",
            "data": "15"
            }`)
        }
    });
    chart_sum_data = $.ajax({
        type: "GET",
        dataType: "text",
        async: false,
        url: "/controllers/getDrundelChartData.php?tagSUM="+tag,
        success: function () {
            postMessage(`{
            "type": "setPreloaderProgress",
            "data": "35"
            }`)
        }
    });
    chart_avg_data = $.ajax({
        type: "GET",
        dataType: "text",
        async: false,
        url: "/controllers/getDrundelChartData.php?tagAVG="+tag,
        success: function () {
            postMessage(`{
            "type": "setPreloaderProgress",
            "data": "50"
            }`)
        }
    });
    chart_avgLast7_data = $.ajax({
        type: "GET",
        dataType: "text",
        async: false,
        url: "/controllers/getDrundelChartData.php?tagAVG7="+tag,
        success: function () {
            postMessage(`{
            "type": "setPreloaderProgress",
            "data": "75"
            }`)
        }
    });
    dataChart = JSON.parse(chart_data.responseText);
    dataChartSUM = JSON.parse(chart_sum_data.responseText);
    dataAvg = JSON.parse(chart_avg_data.responseText);
    dataAvg7 = JSON.parse(chart_avgLast7_data.responseText);
    dataChart = Object.entries(dataChart);
    dataAvg = Object.entries(dataAvg);
    dataAvg7 = Object.entries(dataAvg7);
    console.log(dataChart);
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
    var sum = [];
    for (let i = 0; i < cData.length; i++){
        sum.push(Math.round(dataChartSUM[cData[i][1]['enddate']]['sum'] * 100) / 100);
        cData[i][1]['profit'] = Math.round(cData[i][1]['profit'] * 100) / 100;
        cData[i][1]['finalprice'] = Math.round(cData[i][1]['finalprice'] * 100) / 100;
        cData[i][1]['lisateenused'] = Math.round(cData[i][1]['lisateenused'] * 100) / 100;
        cData[i][1]['buyprice'] = Math.round(cData[i][1]['buyprice'] * 100) / 100;
        cData[i][1]['enddate'] = moment(cData[i][1]['enddate'], "YYYY-MM-DD");
        cData[i][1]['startdate'] = moment(cData[i][1]['startdate'], "DD.MM.YYYY");
        profit_table.push(cData[i][1]['profit']);
        labels_table.push(cData[i][1]['enddate'].valueOf());
    }
    let arr = [];
    arr.push(profit_table);
    arr.push(labels_table);
    arr.push(cData);
    arr.push(sum);
    postMessage(`{
            "type": "setPreloaderProgress",
            "data": "85"
            }`)
    return JSON.stringify(arr);
}
function computeData2(cData){
    postMessage(`{
            "type": "setPreloaderProgress",
            "data": "50"
            }`)
    var profit_table = [];
    var labels_table = [];
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