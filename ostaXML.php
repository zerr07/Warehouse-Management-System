<?php
function is_valid_xml ( $xml ) {
    libxml_use_internal_errors( true );

    $doc = new DOMDocument('1.0', 'utf-8');

    $doc->loadXML( $xml );

    $errors = libxml_get_errors();

    return empty( $errors );
}

if (is_valid_xml(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/XML/ProductList_Osta.xml')) && filesize($_SERVER['DOCUMENT_ROOT'].'/XML/ProductList_Osta.xml')){
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="ProductList.xml"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($_SERVER['DOCUMENT_ROOT'].'/XML/ProductList_Osta.xml'));
    flush(); // Flush system output buffer
    readfile($_SERVER['DOCUMENT_ROOT'].'/XML/ProductList_Osta.xml');
} else {
    echo "XML is updating please try again later";
}


exit;
