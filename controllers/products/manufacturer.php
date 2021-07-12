<?php
function create_manufacturer($name){
    $name = preg_replace("/^\p{Z}+|\p{Z}+$/u", "", $name);

    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*manufacturer*} WHERE `name`='$name' LIMIT 1"));
    if ($q->num_rows == 0){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*manufacturer*} (`name`) VALUES ('$name')"));
        return $q->insert_id;
    } else
        return $q->fetch_assoc()['id'];
}
function set_manufacturer($id_prod, $name=null, $id=null){
    if ($id_prod == "")
        return false;
    if (is_null($name) && is_null($id))
        return false;
    if (!is_null($id)){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*products*} SET id_manufacturer='$id' WHERE id='$id_prod'"));
        return true;
    }
    if (!is_null($name)){
        $id = create_manufacturer(preg_replace("/^\p{Z}+|\p{Z}+$/u", "", trim($name)));
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*products*} SET id_manufacturer='$id' WHERE id='$id_prod'"));
        return true;
    } else {
        return false;
    }
}