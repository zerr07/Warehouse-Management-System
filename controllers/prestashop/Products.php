<?php

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/prestashop/API.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/generateURL.php');

$api_key = _DB_EXPORT['auth_key'];
$domain = _DB_EXPORT['domain'];


function PR_GET_Products(){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/products?output_format=JSON";
    return CallGETAPI($url);
}
function PR_GET_ProductsSyncData(){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/products?output_format=JSON&display=[id,reference,active]";
    return CallGETAPI($url);
}
function PR_GET_Product_By_Tag($tag){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/products?output_format=JSON&filter[reference]=$tag";
    return CallGETAPI($url);
}

function PR_GET_Product_Stock_Available($id){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/stock_availables?output_format=JSON&display=full&filter[id_product]=$id";
    return CallGETAPI($url);
}

function PR_CreateBody_PUT_Product_Stock_Available($id, $id_product, $id_product_attribute, $id_shop, $id_shop_group, $quantity, $depends_on_stock, $out_of_stock, $location){
    return "<prestashop xmlns:xlink=\"http://www.w3.org/1999/xlink\">
                <stock_available>
                    <id>$id</id>
                    <id_product>$id_product</id_product>
                    <id_product_attribute>$id_product_attribute</id_product_attribute>
                    <id_shop>$id_shop</id_shop>
                    <id_shop_group>$id_shop_group</id_shop_group>
                    <quantity>$quantity</quantity>
                    <depends_on_stock>$depends_on_stock</depends_on_stock>
                    <out_of_stock>$out_of_stock</out_of_stock>
                    <location>$location</location>
                </stock_available>
            </prestashop>";
}
function PR_PUT_Product_Stock_Available($id, $quantity){
    global $domain, $api_key;

    $data = PR_GET_Product_Stock_Available($id)['stock_availables'];
    if (sizeof($data) == 1){
        $data = $data[0];
        $body = PR_CreateBody_PUT_Product_Stock_Available($data['id'], $data['id_product'], $data['id_product_attribute'],
            $data['id_shop'], $data['id_shop_group'], $quantity, $data['depends_on_stock'], $data['out_of_stock'],
            $data['location']);
        $url = "https://$api_key@$domain/api/stock_availables?output_format=JSON";

        return CallPUTAPI($url, $body);
    } else {
        return null;
    }

}
function PR_GET_Product_Images($id){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/images/products/$id?output_format=JSON";
    $res = CallGETAPI($url);
    if (!is_null($res)){
        $data = CallGETAPI($url)[""];
        unset($data[0]);
        return $data;
    } else {
        return null;
    }
}

function PR_DELETE_Product_Image($id, $id_image){
    global $domain, $api_key;
    $url = "https://$api_key@$domain/api/images/products/$id/$id_image?output_format=JSON";
    CallDELETEAPI($url);
}
function PR_DELETE_Product($id_prod){
    global $domain, $api_key;
    $data = get_product($id_prod);
    $id = PR_GET_Product_By_Tag($data['tag'])['products'][0]['id'];
    $url = "https://$api_key@$domain/api/products/$id?output_format=JSON";
    CallDELETEAPI($url);
}
function PR_DELETE_Product_By_Tag($tag){
    global $domain, $api_key;
    $id = PR_GET_Product_By_Tag($tag)['products'][0]['id'];
    $url = "https://$api_key@$domain/api/products/$id?output_format=JSON";
    CallDELETEAPI($url);
}
function PR_POST_Product_Image($id, $image){
    global $domain, $api_key;
    $data = array('image'=> new CURLFILE($image));
    $url = "https://$api_key@$domain/api/images/products/$id?output_format=JSON";
    return CallPOSTAPI( $url, $data);

}
function PR_POST_Product($id){
    global $domain, $api_key;
    $data = get_product($id);
    $PriceTaxExcluded = round($data['platforms'][_ENGINE['ps_platform_id']]['price']/1.2, 5);
    $active = 0;
    if (isset($data['platforms'][_ENGINE['ps_platform_id']]['export'])){
        $active = $data['platforms'][_ENGINE['ps_platform_id']]['export'];
    }
    $categories = "";
    foreach ($data['categories'] as $value){
        $categories .= "<category><id>$value</id></category>";
    }
    $xml = '
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <product>
    <name>
        <language id="'._LANG['ps_en'].'"><![CDATA['.trim($data['name']['en']).']]></language>
        <language id="'._LANG['ps_et'].'"><![CDATA['.trim($data['name']['et']).']]></language>
        <language id="'._LANG['ps_ru'].'"><![CDATA['.trim($data['name']['ru']).']]></language>
    </name>
    <description>
        <language id="'._LANG['ps_en'].'"><![CDATA['.trim($data['descriptions']['en']).']]></language>
        <language id="'._LANG['ps_et'].'"><![CDATA['.trim($data['descriptions']['et']).']]></language>
        <language id="'._LANG['ps_ru'].'"><![CDATA['.trim($data['descriptions']['ru']).']]></language>
    </description>
    <link_rewrite>
        <language id="'._LANG['ps_en'].'"><![CDATA['.get_EN_URL(trim($data['name']['en'])).']]></language>
        <language id="'._LANG['ps_et'].'"><![CDATA['.get_ET_URL(trim($data['name']['et'])).']]></language>
        <language id="'._LANG['ps_ru'].'"><![CDATA['.get_RU_URL(trim($data['name']['ru'])).']]></language>
    </link_rewrite>
    <available_for_order>'.$active.'</available_for_order>
    
    <price>'.$PriceTaxExcluded.'</price>
    <reference>'.$data['tag'].'</reference>
    <active>'.$active.'</active>
    <show_price>1</show_price>
    <pack_stock_type>3</pack_stock_type>
    <state>1</state>
    <indexed>1</indexed>
    <id_category_default>'.$data['main_category'].'</id_category_default>
    <id_tax_rules_group>1</id_tax_rules_group>
    <associations>
      <categories>
        '.$categories.'
      </categories>
     </associations>
  </product>
</prestashop>';
    $url = "https://$api_key@$domain/api/products?output_format=JSON";
    $res = CallPOSTAPI($url, $xml);
    if (isset($res['product'])){
        $id = $res['product']['id'];
        $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang */ "DELETE FROM {*ps_product_carrier*} WHERE id_product='$id'"));
        foreach ($data['carrier'] as $val){
            $carr_ref = $val['shop_id'];
            $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*ps_product_carrier*} (id_product, id_carrier_reference, id_shop) VALUES ('$id', '$carr_ref', '1')"));
        }
        foreach ($data['images'] as $key => $value){
            PR_POST_Product_Image($id, $_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$value['image']);
        }
        PR_PUT_Product_Stock_Available($id, $data['quantity']);
    }
}

function PR_PUT_Product($id_product){
    global $domain, $api_key;
    $data = get_product($id_product);
    $id = PR_GET_Product_By_Tag($data['tag'])['products'][0]['id'];
    $PriceTaxExcluded = round($data['platforms'][_ENGINE['ps_platform_id']]['price']/1.2, 5);
    $active = 0;
    if (isset($data['platforms'][_ENGINE['ps_platform_id']]['export'])){
        $active = $data['platforms'][_ENGINE['ps_platform_id']]['export'];
    }
    $categories = "";
    foreach ($data['categories'] as $value){
        $categories .= "<category><id>$value</id></category>";
    }
    $xml = '
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <product>
    <id>'.$id.'</id>
    <name>
        <language id="'._LANG['ps_en'].'"><![CDATA['.trim($data['name']['en']).']]></language>
        <language id="'._LANG['ps_et'].'"><![CDATA['.trim($data['name']['et']).']]></language>
        <language id="'._LANG['ps_ru'].'"><![CDATA['.trim($data['name']['ru']).']]></language>
    </name>
    <description>
        <language id="'._LANG['ps_en'].'"><![CDATA['.trim($data['descriptions']['en']).']]></language>
        <language id="'._LANG['ps_et'].'"><![CDATA['.trim($data['descriptions']['et']).']]></language>
        <language id="'._LANG['ps_ru'].'"><![CDATA['.trim($data['descriptions']['ru']).']]></language>
    </description>
    <link_rewrite>
        <language id="'._LANG['ps_en'].'"><![CDATA['.get_EN_URL(trim($data['name']['en'])).']]></language>
        <language id="'._LANG['ps_et'].'"><![CDATA['.get_ET_URL(trim($data['name']['et'])).']]></language>
        <language id="'._LANG['ps_ru'].'"><![CDATA['.get_RU_URL(trim($data['name']['ru'])).']]></language>
    </link_rewrite>
    <available_for_order>'.$active.'</available_for_order>
    <price>'.$PriceTaxExcluded.'</price>
    <reference>'.$data['tag'].'</reference>
    <active>'.$active.'</active>
    <show_price>1</show_price>
    <pack_stock_type>3</pack_stock_type>
    <state>1</state>
    <indexed>1</indexed>
    <id_category_default>'.$data['main_category'].'</id_category_default>
    <id_tax_rules_group>1</id_tax_rules_group>
    <associations>
      <categories>
        '.$categories.'
      </categories>
     </associations>
  </product>
</prestashop>';
    $url = "https://$api_key@$domain/api/products?output_format=JSON";
    $res = CallPUTAPI($url, $xml);
    if (isset($res['product'])){
        $id = $res['product']['id'];
        $images = PR_GET_Product_Images($id);
        if (!is_null($images)){
            foreach ($images as $val){
                PR_DELETE_Product_Image($id, $val['id']);
            }
        }

        $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang */ "DELETE FROM {*ps_product_carrier*} WHERE id_product='$id'"));
        foreach ($data['carrier'] as $val){
            $carr_ref = $val['shop_id'];
            $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*ps_product_carrier*} (id_product, id_carrier_reference, id_shop) VALUES ('$id', '$carr_ref', '1')"));
        }
        foreach ($data['images'] as $key => $value){
            PR_POST_Product_Image($id, $_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$value['image']);
        }
        PR_PUT_Product_Stock_Available($id, $data['quantity']);
    }
}
function PR_PUT_Product_Without_IMG($id_product){
    global $domain, $api_key;
    $data = get_product($id_product);
    $id = PR_GET_Product_By_Tag($data['tag'])['products'][0]['id'];
    $PriceTaxExcluded = round($data['platforms'][_ENGINE['ps_platform_id']]['price']/1.2, 5);
    $active = 0;
    if (isset($data['platforms'][_ENGINE['ps_platform_id']]['export'])){
        $active = $data['platforms'][_ENGINE['ps_platform_id']]['export'];
    }
    $categories = "";
    foreach ($data['categories'] as $value){
        $categories .= "<category><id>$value</id></category>";
    }
    $xml = '
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <product>
    <id>'.$id.'</id>
    <name>
        <language id="'._LANG['ps_en'].'"><![CDATA['.trim($data['name']['en']).']]></language>
        <language id="'._LANG['ps_et'].'"><![CDATA['.trim($data['name']['et']).']]></language>
        <language id="'._LANG['ps_ru'].'"><![CDATA['.trim($data['name']['ru']).']]></language>
    </name>
    <description>
        <language id="'._LANG['ps_en'].'"><![CDATA['.trim($data['descriptions']['en']).']]></language>
        <language id="'._LANG['ps_et'].'"><![CDATA['.trim($data['descriptions']['et']).']]></language>
        <language id="'._LANG['ps_ru'].'"><![CDATA['.trim($data['descriptions']['ru']).']]></language>
    </description>
    <link_rewrite>
        <language id="'._LANG['ps_en'].'"><![CDATA['.get_EN_URL(trim($data['name']['en'])).']]></language>
        <language id="'._LANG['ps_et'].'"><![CDATA['.get_ET_URL(trim($data['name']['et'])).']]></language>
        <language id="'._LANG['ps_ru'].'"><![CDATA['.get_RU_URL(trim($data['name']['ru'])).']]></language>
    </link_rewrite>
    <available_for_order>'.$active.'</available_for_order>
    <price>'.$PriceTaxExcluded.'</price>
    <reference>'.$data['tag'].'</reference>
    <active>'.$active.'</active>
    <show_price>1</show_price>
    <pack_stock_type>3</pack_stock_type>
    <state>1</state>
    <indexed>1</indexed>
    <id_category_default>'.$data['main_category'].'</id_category_default>
    <id_tax_rules_group>1</id_tax_rules_group>
    <associations>
      <categories>
        '.$categories.'
      </categories>
     </associations>
  </product>
</prestashop>';
    $url = "https://$api_key@$domain/api/products?output_format=JSON";
    $res = CallPUTAPI($url, $xml);
    if (isset($res['product'])){
        $id = $res['product']['id'];
        $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang */ "DELETE FROM {*ps_product_carrier*} WHERE id_product='$id'"));
        foreach ($data['carrier'] as $val){
            $carr_ref = $val['shop_id'];
            $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*ps_product_carrier*} (id_product, id_carrier_reference, id_shop) VALUES ('$id', '$carr_ref', '1')"));
        }
        PR_PUT_Product_Stock_Available($id, $data['quantity']);
    }
}

function PR_PUT_Product_category_only($id_product){
    global $domain, $api_key;
    $data = get_product($id_product);
    $id = PR_GET_Product_By_Tag($data['tag'])['products'][0]['id'];
    $PriceTaxExcluded = round($data['platforms'][_ENGINE['ps_platform_id']]['price']/1.2, 5);
    $categories = "";
    foreach ($data['categories'] as $value){
        $categories .= "<category><id>$value</id></category>";
    }
    $xml = '
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <product>
    <id>'.$id.'</id>
    <name>
        <language id="'._LANG['ps_en'].'"><![CDATA['.trim($data['name']['en']).']]></language>
        <language id="'._LANG['ps_et'].'"><![CDATA['.trim($data['name']['et']).']]></language>
        <language id="'._LANG['ps_ru'].'"><![CDATA['.trim($data['name']['ru']).']]></language>
    </name>
    <description>
        <language id="'._LANG['ps_en'].'"><![CDATA['.trim($data['descriptions']['en']).']]></language>
        <language id="'._LANG['ps_et'].'"><![CDATA['.trim($data['descriptions']['et']).']]></language>
        <language id="'._LANG['ps_ru'].'"><![CDATA['.trim($data['descriptions']['ru']).']]></language>
    </description>
    <price>'.$PriceTaxExcluded.'</price>
    <reference>'.$data['tag'].'</reference>
    <active>1</active>
    <pack_stock_type>3</pack_stock_type>
    <state>1</state>
    <indexed>1</indexed>
    <id_category_default>'.$data['main_category'].'</id_category_default>
    <id_tax_rules_group>1</id_tax_rules_group>
    <associations>
      <categories>
        '.$categories.'
      </categories>
     </associations>
  </product>
</prestashop>';
    $url = "https://$api_key@$domain/api/products?output_format=JSON";
    $res = CallPUTAPI($url, $xml);
    if (isset($res['product'])){
        $id = $res['product']['id'];
        $images = PR_GET_Product_Images($id);
        foreach ($images as $val){
            PR_DELETE_Product_Image($id, $val['id']);
        }
        $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang */ "DELETE FROM {*ps_product_carrier*} WHERE id_product='$id'"));
        foreach ($data['carrier'] as $val){
            $carr_ref = $val['shop_id'];
            $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*ps_product_carrier*} (id_product, id_carrier_reference, id_shop) VALUES ('$id', '$carr_ref', '1')"));
        }
        foreach ($data['images'] as $key => $value){
            PR_POST_Product_Image($id, $_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$value['image']);
        }
        PR_PUT_Product_Stock_Available($id, $data['quantity']);
    }
}

//PR_PUT_Product("AZ1636");
//echo '<pre>'; print_r(PR_GET_Product_Images(2440)); echo '</pre>';
//echo '<pre>'; print_r(PR_POST_Product_Image(2440, "/uploads/images/products/170943582363619483.jpeg")); echo '</pre>';

//echo '<pre>'; print_r(PR_DELETE_Product_Image(2440, "3501650")); echo '</pre>';

//echo '<pre>'; print_r(PR_GET_Product_Images(2440)); echo '</pre>';
//echo '<pre>'; print_r(PR_GET_Product_Stock_Available(2440)); echo '</pre>'; ;  // GET Stock data of product
//echo '<pre>'; print_r(PR_PUT_Product_Stock_Available(2440, 20)); echo '</pre>'; ; // SET Stock data for product


