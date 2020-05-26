<?php
$arr = array(array());
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_price_rules*} 
                                                                                        ORDER BY CAST(startPrice as SIGNED INTEGER) ASC"));
for ($i = 0; $row = mysqli_fetch_assoc($q); $i++){
    $arr[$i]['id'] = $row['id'];
    $arr[$i]['start'] = $row['startPrice'];
    $arr[$i]['end'] = $row['endPrice'];
    $arr[$i]['percent'] = $row['percent'];
    $arr[$i]['margin'] = $row['minMargin'];
}
$smarty->assign("rules", array_filter($arr));