
function loadAuctionCharts(tag){
    $.ajax({
        //url: "/auctions_charts.html",
        url: "/cp/loadChart.php?tag="+tag,
        cache: false,
        dataType: "html",
        success: function(data) {
            $("#auction_charts").html(data);
            if (typeof(worker) != "undefined") {
                worker.terminate();
                delete worker;
            }
            if (typeof(worker) == "undefined") {
                worker = new Worker("/templates/default/assets/js/auction_charts.js?t=02102020T124553");
                worker.postMessage(`{
                    "type":"tag",
                    "data":"`+tag+`"
                    }`);
                    var handleChart2 = function (){
                        worker.postMessage(`{"type":"loadChart2Data"}`);
                        document.getElementById("chart2-tab").removeEventListener("click", handleChart2);
                    }
                    var handleChart3 = function (){
                        worker.postMessage(`{"type":"loadChart3Data"}`);
                        document.getElementById("chart3-tab").removeEventListener("click", handleChart3);
                    }
                        worker.onmessage = function(event) {
                            let msg = JSON.parse(event.data);
                            if (msg['type'] === 'toggleModal'){
                                $("#auction_charts_modal").modal("toggle");
                            }
                            if (msg['type'] === 'DrawChart1'){
                                setTimeout(() => chart1(tag, msg['data']), 1000);
                            }
                            if (msg['type'] === 'noChart1'){
                                document.getElementById("chart1").innerHTML = "No data ᕕ( ᐛ )ᕗ";
                                setPreloaderProgress("100");
                                turnOffProgressPreloader();
                            }
                            if (msg['type'] === 'DrawChart2'){
                                setTimeout(() => chart2(tag, msg['data']), 1000);
                            }
                            if (msg['type'] === 'noChart2'){
                                document.getElementById("chart2").innerHTML = "No data ᕕ( ᐛ )ᕗ";
                                setPreloaderProgress("100");
                                turnOffProgressPreloader();
                            }
                            if (msg['type'] === 'DrawChart3'){
                                setTimeout(() => chart3(tag, msg['data']), 1000);
                            }
                            if (msg['type'] === 'noChart3'){
                                document.getElementById("chart3").innerHTML = "No data ᕕ( ᐛ )ᕗ";
                                setPreloaderProgress("100");
                                turnOffProgressPreloader();
                            }
                            if (msg['type'] === 'turnOnPreloader'){
                                turnOnProgressPreloader();
                            }
                            if (msg['type'] === 'turnOffPreloader'){
                                turnOffProgressPreloader();
                            }
                            if (msg['type'] === 'setPreloaderProgress'){
                                setPreloaderProgress(msg['data']);
                            }
                            document.getElementById("chart2-tab").addEventListener("click", handleChart2);
                            document.getElementById("chart3-tab").addEventListener("click", handleChart3);

                            };
                        } else {
                        console.log("Web Workers are not supported in your browser");
                    }
                    }
                });


    }