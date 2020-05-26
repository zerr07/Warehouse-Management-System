<?php
function applyRule($id, $round, $platform){

    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_price_rules*}"));
    $getPrice = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*supplier_data*}
                                                                                        WHERE id_item='$id' LIMIT 1"));
    $price = mysqli_fetch_assoc($getPrice)['priceVAT'];
    while ($row = mysqli_fetch_assoc($q)) {
        if ($price >= $row['startPrice'] && $price <= $row['endPrice']) {

            if ($price * ($row['percent'] / 100) < $row['minMargin'] && $row['minMargin'] != "") {
                return round($price + $row['minMargin'] * 1.2, $round);
            } else {
                return round(($price + $price * ($row['percent'] / 100)) * 1.2, $round);
            }
        }
    }
    $getPlatformPrice = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_platforms*}
                                                                WHERE id_platform='$platform' AND id_item='$id'"));
    $platformPrice = mysqli_fetch_assoc($getPlatformPrice);
    return $platformPrice['price'];
}