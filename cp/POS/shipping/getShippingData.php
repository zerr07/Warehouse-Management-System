<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

function getShippingTypes(){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*shipment_types*}"));
    if ($q->num_rows == 0){
        return json_encode(array("status"=>"empty"));
    } else {
        $arr = array();
        while ($row = $q->fetch_assoc()){
            $arr[$row['id']] = array("name" => $row['name'], "id"=>$row['id']);
        }
        return json_encode($arr);
    }
}

function getSmartpostTerminals(){
    $response = file_get_contents('http://iseteenindus.smartpost.ee/api/?request=destinations&country=EE&type=APT');
    return json_encode(new SimpleXMLElement($response));
}

function saveData($id ,$data, $status, $type){
    $data = rawurlencode($data);
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*shipment_data*} WHERE id_shipment='$id' LIMIT 1"));
    if ($q->num_rows !== 0){
        $id_ship_data = $q->fetch_assoc()['id'];
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"UPDATE {*shipment_data*} SET `data`='$data' WHERE id='$id_ship_data'"));
    } else {
        // Insert data
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"INSERT INTO {*shipment_data*} (id_shipment, id_type, `data`) VALUES ('$id', '$type', '$data')"));
    }
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*shipment_status*} WHERE id_shipment='$id' LIMIT 1"));
    if ($q->num_rows !== 0){
        updateStatus($status, $id);
    } else {
        setStatus($status, $id);
    }
}

function savePDFFromBase64($id ,$data){
    $base = explode(",", $data);
    $pdf_decoded = base64_decode ($base[1]);
    $name = $id*time();
    file_put_contents($_SERVER['DOCUMENT_ROOT']."/uploads/files/pdf/$name.pdf", $pdf_decoded);

    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*shipment_data*} WHERE id_shipment='$id' LIMIT 1"));
    if ($q->num_rows !== 0){
        $row = $q->fetch_assoc();
        $id_ship_data = $row['id'];
        if ($row['data_file'] != "" || !is_null($row['data_file'])){
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/files/pdf/".$row['data_file'])){
                unlink($_SERVER['DOCUMENT_ROOT']."/uploads/files/pdf/".$row['data_file']);
            }
        }
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"UPDATE {*shipment_data*} SET `data_file`='$name.pdf' WHERE id='$id_ship_data'"));
    }
}

function getData($id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT data FROM {*shipment_data*} WHERE id_shipment='$id' LIMIT 1"));
    return json_decode(rawurldecode($q->fetch_assoc()['data']), true);

}
function getData_full($id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT data, barcode, data_file FROM {*shipment_data*} WHERE id_shipment='$id' LIMIT 1"));

    if ($q->num_rows != 0){
        $arr = array();
        $q1 = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT id_status FROM {*shipment_status*} WHERE id_shipment='$id' LIMIT 1"));
        if ($q1->num_rows != 0){
            $arr['id_status'] = $q1->fetch_assoc()['id_status'];
        } else {
            $arr['id_status'] = null;
        }
        $row = $q->fetch_assoc();
        $arr['data'] = json_decode(rawurldecode($row['data']), true);
        $arr['barcode'] = $row['barcode'];
        $arr['data_file'] = $row['data_file'];
        return $arr;
    } else {
        return null;
    }

}
function setStatus($status, $id){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"INSERT INTO {*shipment_status*} (id_shipment, id_status) VALUES ('$id', '$status')"));
}
function updateStatus($status, $id){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"UPDATE {*shipment_status*} SET id_status='$status' WHERE id_shipment='$id'"));

}

function getProductTagsFromShippment($id){
    $q = $GLOBALS['DBCONN']->query( prefixQuery(/** @lang */"SELECT tag FROM {*products*} WHERE id=(SELECT id_product FROM {*reserved_products*} WHERE id_reserved='$id' AND id_product={*products*}.id)"));
    $arr = array();
    while ($row = $q->fetch_assoc()){
        array_push($arr, $row['tag']);
    }
    return $arr;
}
function getBarSmartpost($id){
    $data = getData($id);
    $tags_str = implode("/", array_filter(getProductTagsFromShippment($id)));
    $xml = new XMLWriter();
    $xml->openURI("test.xml");
    $xml->openMemory();
    $xml->setIndent(true);
    $xml->startDocument('1.0','UTF-8');
    $xml->startElement('orders');

    $xml->startElement('authentication');
    if ($data['checked'] == "clientPaysTheDelivery"){
        $xml->startElement('user');
        $xml->text("levilux");
        $xml->endElement();

        $xml->startElement('password');
        $xml->text("gaxGGhBP1B0m_w");
        $xml->endElement();
    } else {
        $xml->startElement('user');
        $xml->text("trade8");
        $xml->endElement();

        $xml->startElement('password');
        $xml->text("g3giec6");
        $xml->endElement();
    }


    $xml->endElement(); // End of authentication

    $xml->startElement('item');
    $xml->startElement('reference');
    $xml->text($data['deliveryNr']);
    $xml->endElement();
    $xml->startElement('content');
    $xml->text($tags_str);
    $xml->endElement();
    $xml->startElement('recipient');
    if($data['checked'] == "cashOnDelivery" || $data['checked'] == "clientPaysTheDelivery") {
        $xml->startElement('cash');
        if ($data['checked'] == "clientPaysTheDelivery") {
            $xml->text("2.39");
        } elseif ($data['checked'] == "cashOnDelivery") {
            $xml->text($data['COD_Sum']);
        } else {
            $xml->text("2.59");
        }
        $xml->endElement();
    }
    $xml->startElement('name');
    $xml->text($data['name']);
    $xml->endElement();
    $xml->startElement('phone');
    $xml->text($data['phone']);
    $xml->endElement();
    $xml->startElement('email');
    $xml->text($data['email']);
    $xml->endElement();
    $xml->endElement(); // End of recipient
    $xml->startElement('destination');
    $xml->startElement('place_id');
    $xml->text($data['terminal']);
    $xml->endElement();
    $xml->endElement(); // End of destination
    $xml->endElement(); // End of item

    $xml->endElement(); // End of orders
    $url = 'http://iseteenindus.smartpost.ee/api/?request=shipment';
    $curl = curl_init($url);
    curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml->outputMemory(true));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    if(curl_errno($curl)){
        throw new Exception(curl_error($curl));
    }
    curl_close($curl);
    $new = simplexml_load_string($result);
    $con = json_encode($new);
    $newArr = json_decode($con, true);
    if ($newArr !== false){
        $data["barcode"] = $newArr['item']['barcode'];
        $data["reference"] = $newArr['item']['reference'];
        saveData($id ,json_encode($data), 3, 1);
        updateBarcode($id, $data['barcode']);
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*shipment_status*} WHERE id_shipment='$id' LIMIT 1"));
        if ($q->num_rows !== 0){
            updateStatus(3, $id);
        } else {
            setStatus(3, $id);
        }
    }
}
function saveAndGetBarSmartpost($id, $data){
    saveData($id ,$data, 2, 1);
    try {
        getBarSmartpost($id);
    } catch (Exception $e) {
    }
}

function updateBarcode($id, $barcode){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"UPDATE {*shipment_data*} SET `barcode`='$barcode' WHERE id_shipment='$id'"));
}

function getSmartpostLabel($id){
    $data = getData_full($id);


    $xml = new XMLWriter();
    $xml->openURI("test.xml");
    $xml->openMemory();
    $xml->setIndent(true);
    $xml->startDocument('1.0','UTF-8');
    $xml->startElement('labels');

    $xml->startElement('authentication');



    if ($data['data']['checked'] == "clientPaysTheDelivery"){
        $xml->startElement('user');
        $xml->text(_ENGINE['smartpost']['clientPaysTheDelivery']['login']);
        $xml->endElement();

        $xml->startElement('password');
        $xml->text(_ENGINE['smartpost']['clientPaysTheDelivery']['password']);
        $xml->endElement();
    } else {
        $xml->startElement('user');
        $xml->text(_ENGINE['smartpost']['other']['login']);
        $xml->endElement();

        $xml->startElement('password');
        $xml->text(_ENGINE['smartpost']['other']['password']);
        $xml->endElement();
    }

    $xml->endElement(); // end of authentication

    $xml->startElement('format');
    $xml->text("A6");
    $xml->endElement();

    $xml->startElement('barcode');
    $xml->text($data["barcode"]);
    $xml->endElement();

    $xml->endElement(); // end of labels

    $url = 'http://iseteenindus.smartpost.ee/api/?request=labels';
    $curl = curl_init($url);
    curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, utf8_encode($xml->outputMemory(true)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);

    if(curl_errno($curl)){
        throw new Exception(curl_error($curl));
    }

    curl_close($curl);
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*shipment_status*} WHERE id_shipment='$id' LIMIT 1"));
    if ($q->num_rows !== 0){
        updateStatus(4, $id);
    } else {
        setStatus(4, $id);
    }
    header('Content-Type: application/pdf');
    echo base64_encode($result);

}

$post=json_decode(file_get_contents("php://input"));
if (isset($post->saveVenipakFile) && isset($post->file)){
    savePDFFromBase64($post->saveVenipakFile,$post->file);
}
if (isset($post->saveDefaultFile) && isset($post->file)){
    savePDFFromBase64($post->saveDefaultFile,$post->file);
}

if (isset($_GET['getTypes'])){
    echo getShippingTypes();
}
if (isset($_GET['getSmartpost'])){
    echo getSmartpostTerminals();
}

if (isset($_GET['saveSmartPost']) && isset($_GET['saveSmartPostData'])){
    saveData($_GET['saveSmartPost'], $_GET['saveSmartPostData'], 2, 1);
}
if (isset($_GET['saveVenipak']) && isset($_GET['saveVenipakData'])){
    saveData($_GET['saveVenipak'], $_GET['saveVenipakData'], 7, 2);
}
if (isset($_GET['saveDefault']) && isset($_GET['saveDefaultData'])){
    saveData($_GET['saveDefault'], $_GET['saveDefaultData'], 7, 3);
}
if (isset($_GET['savePickup']) && isset($_GET['savePickupData'])){
    saveData($_GET['savePickup'], $_GET['savePickupData'], 8, 4);
}
if (isset($_GET['saveAndBarSmartPost']) && isset($_GET['saveAndBarSmartPostData'])){
    saveAndGetBarSmartpost($_GET['saveAndBarSmartPost'], $_GET['saveAndBarSmartPostData']);
}
if (isset($_GET['getSmartPostLabel'])){
    getSmartpostLabel($_GET['getSmartPostLabel']);
}
if(isset($_GET['setStatus']) && isset($_GET['setStatusID'])){
    $id = $_GET['setStatusID'];
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*shipment_status*} WHERE id_shipment='$id' LIMIT 1"));
    if ($q->num_rows !== 0){
        updateStatus($_GET['setStatus'], $id);
    } else {
        setStatus($_GET['setStatus'], $id);
    }
}
if (isset($_GET['setSmartpostPosted'])){
    $id = $_GET['setSmartpostPosted'];
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*shipment_status*} WHERE id_shipment='$id' LIMIT 1"));
    if ($q->num_rows !== 0){
        updateStatus(5, $id);
    } else {
        setStatus(5, $id);
    }
}
if (isset($_GET['setPickupReady'])){
    updateStatus(9, $_GET['setPickupReady']);
}