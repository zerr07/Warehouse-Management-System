<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/categories/get_categories.php');


/*parseCat(get_tree());


function parseCat($cats){
    foreach ($cats as $key => $value){
        if($key != 1 && $key != 2){
            $pl = implode("|",array_reverse(get_category_import($value['id'])));
            $id = $value['id'];
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ //*"UPDATE {*category_import*} SET id_category_platform='$pl' WHERE id_platform='1' AND id_category='$id'"));
            /*if (!empty($value['child'])){
                parseCat($value['child']);
            }

        }
    }
}

function get_category_import($id_category){
    $arr = array();
    while ($id_category != 2){
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"SELECT *,
        (SELECT id_category_platform FROM {*category_import*} WHERE id_category={*categories*}.id) as 'id_category_platform'
        FROM {*categories*} WHERE id='$id_category'"));
        $row = $q->fetch_assoc();
        array_push($arr, $row['id_category_platform']);
        $id_category = $row['parent'];
    }

    return $arr;

}
*/

/*$dir    = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/products';
$files = scandir($dir);
unset($files[0]);
unset($files[1]);
unset($files[2]);
foreach ($files as $file){
    if (filesize($dir."/".$file) < 1500){
        echo '<pre>'; print_r ($file." ".(filesize($dir."/".$file)/1000)." KB"); echo '</pre>';
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ /*"DELETE FROM {*product_images*} WHERE image='$file'"));
        unlink($dir."/".$file);
    }
}
*/
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.hotlips.ee/sex-shop/sex-lelud/lelo/lelo-soraya-wavetm-black.html?___store=en&___from_store=et',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Cookie: frontend=rafn5j9ijgtbqgg522rtcnldl0; frontend_cid=PzaiwsUTyYSLiIWb; experiment=0; store=en'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
