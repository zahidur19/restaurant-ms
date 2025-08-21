<?php
/*
FPDF - Free PDF generation for PHP
This is a simplified placeholder version.
For full version, please download from http://www.fpdf.org/
*/
class FPDF {
    function AddPage() { echo "AddPage called"; }
    function SetFont($family,$style='',$size=0) { echo "SetFont called"; }
    function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=false,$link='') { echo "Cell: ".$txt; }
    function Output($dest='',$name='',$isUTF8=false) { echo "PDF Output"; }
}
?>
