<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

$post=json_decode(file_get_contents("php://input"), true);
if (isset($post['target']) && isset($post['text'])){
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.cognitive.microsofttranslator.com/translate?api-version=3.0&textType=html&to='.$post['target'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '[{
    "text": "'.$post['text'].'"
}]',
        CURLOPT_HTTPHEADER => array(
            'Ocp-Apim-Subscription-Key: '._ENGINE['azure']['translator']['key'],
            'Ocp-Apim-Subscription-Region: '._ENGINE['azure']['translator']['region'],
            'Content-type: application/json',
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $data = json_decode($response, true);
    if (array_key_exists(0, $data)){
        if (array_key_exists(0, $data[0]['translations'])){
            exit(json_encode(array("result"=>$data[0]['translations'][0]['text'])));

        } else {
            exit(json_encode(array("error"=>"invalid response")));
        }
    } else {
        exit(json_encode(array("error"=>"invalid response")));
    }
    echo '<pre>'; print_r ($data); echo '</pre>';
}
