<?php

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.elkogroup.com/v3.0/api/Catalog/Categories",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array("accept: text/plain",
            "Authorization: Bearer  eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1lIjoiNDAyMjE2MUAxIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQXBpIiwiZXhwIjoxNjA5Njg5OTQ1LCJpc3MiOiJodHRwczovL2Vjb20uZWxrb2dyb3VwLmNvbSIsImF1ZCI6Imh0dHBzOi8vZWNvbS5lbGtvZ3JvdXAuY29tIn0.ow6Gvw0BT2XNtKZsCN6aaHCNDWxI5Ftmo0R9pMftWYQ"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $arr = json_decode($response, true);
        foreach ($arr as $item){
            echo $item['name']." | ".$item['code']."<br>";
        }

    }
