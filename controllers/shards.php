<?php
function getShards()
{
    $arr = array(array());
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*shards*}"));
    while ($row = mysqli_fetch_assoc($query)) {
        $arr[$row['id']] = $row['name'];
    }
    return $arr;
}

function getShardName($id)
{
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*shards*} WHERE id='$id'"));
    while ($row = mysqli_fetch_assoc($query)) {
        return $row['name'];
    }
    return null;
}