<style>
    .c3-tooltip td {
        color: #222;
    }
    .c3-region.regionBad {
        fill: red;
    }
    .c3-region.regionGood {
        fill: green;
    }
    #tooltip-span {
        background: rgb(2,0,36);
        background: radial-gradient(circle, rgba(24,19,122,0.6138830532212884) 0%, rgb(31 32 33) 0%, rgb(23 22 22) 100%);
        padding: 20px;
        border-radius: 5px;
        z-index: 9999;
    }
    .c3-line-profit{
        stroke-width: 1.6px;
    }

</style>
<div class="modal fade" id="auction_charts_modal" tabindex="-1" aria-labelledby="auction_charts_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="auction_charts_modalLabel"><i class="fas fa-cat"></i> {$tag} - {$name}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="tooltip-span" style="display: none; position: fixed"></span>

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="chart1-tab" data-toggle="tab" href="#chart1-box" role="tab" aria-controls="home" aria-selected="true">Profit</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="chart2-tab" data-toggle="tab" href="#chart2-box" role="tab" aria-controls="profile" aria-selected="false">Avg</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="chart3-tab" data-toggle="tab" href="#chart3-box" role="tab" aria-controls="contact" aria-selected="false">Avg (7 days)</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="chart1-box" role="tabpanel" aria-labelledby="chart1-tab">
                        <div id="chart1"></div>
                    </div>
                    <div class="tab-pane fade" id="chart2-box" role="tabpanel" aria-labelledby="chart2-tab">
                        <div id="chart2"></div>
                    </div>
                    <div class="tab-pane fade" id="chart3-box" role="tabpanel" aria-labelledby="chart3-tab">
                        <div id="chart3"></div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{literal}
<script>
    var tooltipSpan = document.getElementById('tooltip-span');

    function chart1(tag, data){

        var profit_table = data[0];
        var labels_table = data[1];
        var cData = data[2];
        var sum = data[3];
        var mouseover;
        var chart = c3.generate({
            bindto: "#chart1",
            data: {
                "onmouseover": customOver,
                "onmouseout": customOut,
                xs: {
                    'sum': 'x2',
                    'profit': 'x1'
                },
                columns: [
                    ['x2'].concat(labels_table),
                    ['x1'].concat(labels_table),
                    ['sum'].concat(sum),
                    ['profit'].concat(profit_table)

                ]
            },
            axis: {
                x: {
                    type: 'normal',
                    tick:{
                        culling: {
                            max: 12312312 // the number of tick texts will be adjusted to less than this value
                        },
                        rotate: 90,
                        multiline: false,
                        format: function (x) { return moment(x).format("DD.MM.YYYY"); }
                    }
                }
            },
            tooltip: {
                grouped: true,
                contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
                    mouseover = d
                }
            },
            legend: {
                show: true
            },
            size: {
                height: 600,
                width: 1000
            },
            zoom: {
                enabled: true
            },
            color: {
                pattern: ['#ff7f0e', '#1f77b4']
            },
            regions: [
                {axis: 'y', start: -999999999, end: 0, class: 'regionBad'},
                {axis: 'y', start: 0, class: 'regionGood'}
            ]
        });
        setPreloaderProgress("100");
        turnOffProgressPreloader();
        function customOver(d,i){
            $(window).on("mousemove", cords);
            if (mouseover.length === 2) {
                let roi = Math.round(((cData[d.index][1]['profit']/cData[d.index][1]['buyprice'])*100)* 100) / 100;
                let e = moment(cData[d.index][1]['enddate']);
                let s = moment(cData[d.index][1]['startdate']);
                let diff = e.diff(s, 'days');
                tooltipSpan.innerHTML =  '' +
                'Profit: ' + cData[d.index][1]['profit'] + "<br />" +
                'Duration: ' + diff + "<br />" +
                'Final Price: ' + cData[d.index][1]['finalprice'] + "<br />" +
                'Lisateenused: ' + cData[d.index][1]['lisateenused'] + "<br />" +
                'ROI: ' + roi + "%";
            } else {
                tooltipSpan.innerHTML =  '';
                if (d.id === "profit"){
                    let roi = Math.round(((cData[d.index][1]['profit']/cData[d.index][1]['buyprice'])*100)* 100) / 100;
                    let e = moment(cData[d.index][1]['enddate']);
                    let s = moment(cData[d.index][1]['startdate']);
                    let diff = e.diff(s, 'days');
                    tooltipSpan.innerHTML +=  '' +
                        'Profit: ' + cData[d.index][1]['profit'] + "<br />" +
                        'Duration: ' + diff + "<br />" +
                        'Final Price: ' + cData[d.index][1]['finalprice'] + "<br />" +
                        'Lisateenused: ' + cData[d.index][1]['lisateenused'] + "<br />" +
                        'ROI: ' + roi + "%" +
                        "<hr><br />";
                }
                tooltipSpan.innerHTML += 'SUM: ' + sum[d.index] + "<br />";
                let quantity = mouseover.filter(function(value){
                    return value.id === "profit";
                }).length;
                tooltipSpan.innerHTML += 'Quantity: ' + quantity + "<br />";
                tooltipSpan.innerHTML += 'Avg : ' + sum[d.index]/quantity + "<br />";
            }

        }
        function customOut(){
            $(window).off("mousemove", cords);
            tooltipSpan.style.display = "none";
            tooltipSpan.innerHTML =  'Could not load.';
        }
    }
    function cords (e) {
        var x = e.clientX,
            y = e.clientY;
        tooltipSpan.style.top = (y + 20) + 'px';
        tooltipSpan.style.left = (x + 20) + 'px';
        tooltipSpan.style.display = "block";
    }

    function chart2(tag, data){
        var profit_table = data[0];
        var labels_table = data[1];
        var cData = data[2];
        setPreloaderProgress("70");
        var chart = c3.generate({
            bindto: "#chart2",
            data: {
                xs: {
                    'profit': 'x1'
                },
                columns: [
                    ['x1'].concat(labels_table),
                    ['profit'].concat(profit_table)
                ]
            },
            axis: {
                x: {
                    type: 'normal',
                    tick:{
                        culling: {
                            max: 12312312 // the number of tick texts will be adjusted to less than this value
                        },
                        rotate: 90,
                        multiline: false,
                        format: function (x) { return moment(x).format("DD.MM.YYYY"); }
                    }
                }
            },
            tooltip: {
                grouped: false,
                contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
                    return '<div class="tooltipBox">' +
                        'Avg profit: ' + cData[d[0].index][1]['avg'] + "<br />" +
                        '</div>';

                }
            },
            legend: {
                show: false
            },
            size: {
                height: 600,
                width: 1000
            },
            zoom: {
                enabled: true
            },
            regions: [
                {axis: 'y', start: -999999999, end: 0, class: 'regionBad'},
                {axis: 'y', start: 0, class: 'regionGood'}
            ]
        });
        setPreloaderProgress("100");
        turnOffProgressPreloader();
    }
    function chart3(tag, data){
        var profit_table = data[0];
        var labels_table = data[1];
        var cData = data[2];
        setPreloaderProgress("70");
        var chart = c3.generate({
            bindto: "#chart3",
            data: {
                xs: {
                    'profit': 'x1'
                },
                columns: [
                    ['x1'].concat(labels_table),
                    ['profit'].concat(profit_table)
                ]
            },
            axis: {
                x: {
                    type: 'normal',
                    tick:{
                        culling: {
                            max: 12312312 // the number of tick texts will be adjusted to less than this value
                        },
                        rotate: 90,
                        multiline: false,
                        format: function (x) { return moment(x).format("DD.MM.YYYY"); }
                    }
                }
            },
            tooltip: {
                grouped: false,
                contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
                    return '<div class="tooltipBox">' +
                        'Avg profit: ' + cData[d[0].index][1]['avg'] + "<br />" +
                        '</div>';

                }
            },
            legend: {
                show: false
            },
            size: {
                height: 600,
                width: 1000
            },
            zoom: {
                enabled: true
            },
            regions: [
                {axis: 'y', start: -999999999, end: 0, class: 'regionBad'},
                {axis: 'y', start: 0, class: 'regionGood'}
            ]
        });
        setPreloaderProgress("100");
        turnOffProgressPreloader();
    }
</script>{/literal}