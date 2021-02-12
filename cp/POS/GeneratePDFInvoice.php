<?php


// Include the main TCPDF library (search for installation path).
require_once($_SERVER['DOCUMENT_ROOT'].'/libs/tcpdf/tcpdf_import.php');

function generatePDF($type){
    global $InvoiceNum, $Company, $Client, $RegNr, $PaymentMethod, $Address, $KMKR, $data, $DueDate, $Interest, $Sum, $SumNoTax, $TaxFromSum, $Currency, $fileName;
    $style = '
<style type="text/css">
      table{width:100%;}
</style>
';
// create new PDF document
    $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
    $pdf->SetCreator('TCPDF');
    $pdf->SetTitle('Invoice');
    $pdf->SetSubject('Invoice');


// set header and footer fonts
    $pdf->setHeaderFont(Array('helvetica', '', 10));
    $pdf->setFooterFont(Array('helvetica', '', 8));

// set default monospaced font
    $pdf->SetDefaultMonospacedFont('courier');

// set margins
    $pdf->SetMargins(15, 50, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(15);
    $pdf->SetAutoPageBreak(TRUE, 35);

// set image scale factor
    $pdf->setImageScale(1.25);


// ---------------------------------------------------------

// set default font subsetting mode
    $pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
    $pdf->SetFont('dejavusans', '', 8, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
    $pdf->AddPage();

// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

    $tbl1 = '
<table cellpadding="2" cellspacing="2">
    <tr>
        <td width="10%"><b>Müüja:</b></td>
        <td width="40%">'.$Company.'</td>
        <td width="10%"><b>Ostja:</b></td>
        <td width="40%">'.$Client.'</td>
    </tr>
    <tr>
        <td width="10%">Reg Nr:</td>
        <td width="40%">'.$RegNr.'</td>
        <td width="10%">Makseviis:</td>
        <td width="40%">'.$PaymentMethod.'</td>
    </tr>
    <tr>
        <td width="10%">Aadress:</td>
        <td width="40%">'.$Address.'</td>
    </tr>
    <tr>
        <td width="10%">KMKR:</td>
        <td width="40%">'.$KMKR.'</td>
    </tr>
</table>
';
    $tbl2 = $style.'
<table border="1" cellpadding="2" width="100%">
<thead>
 <tr>
  <td width="10%"    align="center">Kood</td>
  <td width="25%"    align="center">Teenuste/kaupade nimetus</td>
  <td width="10%"    align="center">Ühik</td>
  <td width="10%"    align="center">Maht</td>
  <td width="15%"    align="center">Hind KM-ga</td>
  <td width="15%"    align="center">Hind KM-ta</td>
  <td width="15%"    align="center">Kokku KM-ta</td>
 </tr>
</thead>
 '.processHTML($data).'
</table>';
    $pdf->SetY(40);
    $tblDueDate = '
<table cellpadding="2">
    <tr><td width="50%"></td><td width="25%"><b>Maksetähtaeg:</b></td><td width="25%"><b>'.$DueDate.'</b></td></tr>
    <tr><td width="50%"></td><td width="25%">Viivis:</td><td width="25%">'.$Interest.'</td></tr>
</table>';
    $tblSum = '
<table cellpadding="2">
    <tr><td width="50%"></td><td width="25%">Kokku KM-ta:</td>        <td width="25%">'.$SumNoTax.'</td></tr>
    <tr><td width="50%"></td><td width="25%">Käibemaks 20%:</td><td width="25%">'.$TaxFromSum.'</td></tr>
    <tr><td width="50%"></td><td width="25%"><b>Kokku KM-ga:</b></td> <td width="25%"><b>'.$Sum.'</b></td></tr>
    <tr><td width="50%"></td><td width="25%">Valuuta:</td>      <td width="25%">'.$Currency.'</td></tr>
</table>';


    $pdf->writeHTML($tbl1, true, false, false, false, '');
    $pdf->writeHTML($tblDueDate, true, false, false, false, 'right');

    $pdf->writeHTML($tbl2, true, false, false, false, '');
    $pdf->writeHTML($tblSum, true, false, false, false, 'right');
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
    if ($type == "base64") {
        return $pdf->Output($fileName, 'E');
    } else {
        echo "Invalid type";
        return null;
    }

}

class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        global $CurrentDate, $InvoiceNum;
        $tblHeader = '
<table cellpadding="2" cellspacing="1">
    <tr><td><b>Arve Nr: '.$InvoiceNum.'</b></td></tr>
    <tr><td>Kuupäev: '.$CurrentDate.'</td></tr>
</table>
';
        $image_file = K_PATH_IMAGES.'aaaaaaa.png';
        $this->Image($image_file, 10, 10, 80, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->setX(135);
        $this->writeHTML($tblHeader, true, false, false, false, '');

    }

    // Page footer
    public function Footer() {
        global $style;
        global $PhoneNum, $Email, $KMKR, $SWIFT, $Address, $Company, $SWEDBANK, $RegNr;
        $tblFooter = '
<table cellpadding="2" cellspacing="1">
    <tr>
        <td width="50%">'.$Company.', '.$Address.'</td>
        <td width="50%" align="right">SWEDBANK '.$SWEDBANK.'</td>
    </tr>
    <tr>
        <td width="50%">Reg nr: '.$RegNr.' , KMKR: '.$KMKR.'</td>
        <td width="50%" align="right">SWIFT: '.$SWIFT.'</td>
    </tr>
    <tr><td width="50%">E-post: '.$Email.'</td></tr>
    <tr><td width="50%">Telefon: '.$PhoneNum.'</td></tr>
</table>
';
        // Position at 15 mm from bottom
        $this->SetY(-35);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->writeHTML($style.'<hr>'.$tblFooter.'<br><p align="center"> Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages().'</p>', true, false, false, false, '');
        $this->SetMargins(15, 50, 15);

    }
}

function processHTML($data){
    $html = '';
    foreach ($data as $val){
        $html .= '<tr>
        <td width="10%"  align="center">'.$val[0].'</td>
        <td width="25%"  align="center">'.$val[1].'</td>
        <td width="10%"  align="center">'.$val[2].'</td>
        <td width="10%"  align="center">'.$val[3].'</td>
        <td width="15%"  align="center">'.$val[4].'</td>
        <td width="15%"  align="center">'.round($val[4]/1.2,2).'</td>
        <td width="15%"  align="center">'.round($val[4]/1.2*$val[3],2).'</td>
        </tr>';
    }
    return $html;
}

$post=json_decode(file_get_contents("php://input"));


if (isset($post->save) || isset($post->base64)){
    $data = $post->Data;
    $PhoneNum = "+37258834435";
    $Email = "info@bigshop.ee";
    $KMKR = "EE101681917";
    $SWIFT = "HABAEE2X";
    $Company = "AZ TRADE OÜ";
    $Address = "J. Koorti tn 2-122, 13623, Tallinn";
    $RegNr = "12474341";
    $Interest = "0.05%";
    $Currency = "EUR";
    $CurrentDate = date("d/m/Y H:i:s");
    if (isset($post->bank) && $post->bank == "FB"){
        $SWEDBANK = "EE232200221075720262";
    } else {
        $SWEDBANK = "EE132200221058780944";
    }
    $InvoiceNum = $post->InvoiceNum; //"5827";
    $Client = $post->Client; //"Eraisik";
    $PaymentMethod = $post->PaymentMethod; //"Kaart";
    $DueDate = $post->DueDate; //"14/12/2020";
    $Sum = $post->Sum; //33.39;
    $SumNoTax = round($Sum/1.2, 2);
    $TaxFromSum = $Sum-$SumNoTax;
    $fileName = $InvoiceNum . '_Invoice.pdf';
    if (isset($post->base64)){
        echo str_replace("filename=\"".$fileName."\"", "", explode(" ",generatePDF("base64"))[5]);
    }
}
