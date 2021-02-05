<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
/*

create table properties
(
	id int auto_increment,
	constraint properties_pk
		primary key (id)
);

create table property_value
(
	id int auto_increment,
	id_property int not null,
	constraint properties_pk
		primary key (id)
);

create table property_value_name
(
	id int auto_increment,
	id_value int not null,
	id_lang int not null,
	name longtext not null,
	constraint property_value_pk
		primary key (id)
);

create table property_name
(
	id int auto_increment,
	id_property int not null,
	id_lang int not null,
	name longtext not null,
	constraint property_name_pk
		primary key (id)
);

create table product_properties
(
	id int auto_increment,
	id_product int not null,
	id_value int not null,
	constraint product_properties_pk
		primary key (id)
);


 */

function get_properties($id_lang=0): array {
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM properties"));
    if ($q){
        while ($row = $q->fetch_assoc()){
            $arr[$row['id']] = $row;
            $arr[$row['id']]['name'] = get_property_name($row['id'],$id_lang);
            $arr[$row['id']]['value'] = get_property_value($row['id'],$id_lang);
        }
    }
    return $arr;
}

function get_property($id, $id_lang=0): array {
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM properties WHERE id='$id'"));
    if ($q){
        while ($row = $q->fetch_assoc()){
            $arr = $row;
            $arr['name'] = get_property_name($row['id'],$id_lang);
            $arr['value'] = get_property_value($row['id'],$id_lang);
        }
    }
    return $arr;
}

function get_property_by_value($id): ? array{
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_property FROM property_value WHERE id='$id'"));
    if ($q){
       return get_property($q->fetch_assoc()['id_property']);
    }
    return null;
}
function get_property_id_by_value($id): ? int{
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_property FROM property_value WHERE id='$id'"));
    if ($q){
        return $q->fetch_assoc()['id_property'];
    }
    return null;
}

function get_property_name_by_value($id, $id_lang=0): ? array{
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id_property FROM property_value WHERE id='$id'"));
    if ($q){
        return get_property_name($q->fetch_assoc()['id_property'], $id_lang);
    }
    return null;
}

function get_property_name($id, $id_lang=0): array {
    $arr = array();
    if ($id_lang == 0){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM property_name WHERE id_property='$id'"));
    } else {
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM property_name WHERE id_property='$id' AND id_lang='$id_lang'"));
    }
    if ($q){
        while ($row = $q->fetch_assoc()){
            $arr[$row['id_lang']] = $row;
        }
    }
    return $arr;
}

function get_property_value($id, $id_lang=0){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM property_value WHERE id_property='$id'"));
    if ($q){
        while ($row = $q->fetch_assoc()){
            $arr[$row['id']] = $row;
            $arr[$row['id']]['name'] = get_property_value_name($row['id'], $id_lang);

        }
    }
    return $arr;
}

function get_property_value_name($id, $id_lang=0){
    $arr = array();
    if ($id_lang == 0){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM property_value_name WHERE id_value='$id'"));
    } else {
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM property_value_name WHERE id_value='$id' AND id_lang='$id_lang'"));
    }
    if ($q){
        while ($row = $q->fetch_assoc()){
            $arr[$row['id_lang']] = $row;
        }
    }
    return $arr;
}

function create_property($names): int{
    /*
        {
            "1": "ruName",
            "2": "enName",
            "3": "etName"
        }
     */
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*properties*} () VALUES ()"));
    $last = $GLOBALS['DBCONN']->insert_id;
    foreach ($names as $key => $value){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*property_name*} 
        (id_property, id_lang, name) VALUES ('$last', '$key', '$value')"));
    }
    return $last;
}

function create_property_value($id, $names): int{
    /*
        {
            "1": "ruName",
            "2": "enName",
            "3": "etName"
        }
     */
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*property_value*} (id_property) VALUES ('$id')"));
    $last = $GLOBALS['DBCONN']->insert_id;
    foreach ($names as $key => $value){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*property_value_name*} 
        (id_value, id_lang, name) VALUES ('$last', '$key', '$value')"));
    }
    return $last;
}

function assign_property($id_prod, $id_value){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_properties*} WHERE id_product='$id_prod' AND id_value='$id_value'"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_properties*} (id_product, id_value) VALUES ('$id_prod', '$id_value')"));
}



function get_product_properties($id, $id_lang=0): array{
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM product_properties WHERE id_product='$id'"));
    if ($q){
        while ($row = $q->fetch_assoc()){
            $arr[$row['id']] = $row;
            $arr[$row['id']]['id_prop'] = get_property_id_by_value($row['id_value']);
            $arr[$row['id']]['value_name'] = get_property_value_name($row['id_value'], $id_lang);
            $arr[$row['id']]['prop_name'] = get_property_name_by_value($row['id_value'], $id_lang);
        }
    }
    return $arr;
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($_GET['getProperties'])){
    exit(json_encode(get_properties(2)));
}
if (isset($_GET['getProperty'])){
    exit(json_encode(get_property($_GET['getProperty'], 2)));
}
if (isset($_GET['getPropertyValues'])){
    if (isset($_GET['id_lang'])){
        exit(json_encode(get_property_value($_GET['getPropertyValues'], $_GET['id_lang'])));
    } else {
        exit(json_encode(get_property_value($_GET['getPropertyValues'], 2)));
    }
}
if (isset($_GET['getProductProperties'])){
    exit(json_encode(get_product_properties($_GET['getProductProperties'], 2)));
}
if (isset($data['ruName']) && isset($data['enName']) && isset($data['etName'])){
    exit(json_encode(array("id"=>create_property(array("1"=>$data['ruName'], "2"=>$data['enName'], "3"=>$data['etName'])))));

}
if (isset($data['ruNameVal']) && isset($data['enNameVal']) && isset($data['etNameVal']) && isset($data['id'])){
    create_property_value($data['id'], array("1"=>$data['ruNameVal'], "2"=>$data['enNameVal'], "3"=>$data['etNameVal']));
    exit(json_encode(array("result"=>"success")));
}