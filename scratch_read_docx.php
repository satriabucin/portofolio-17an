<?php
$zip = new ZipArchive;
$file = 'c:\\xampp\\htdocs\\portofolio-17an\\PRD_Website_17_Agustus (1).docx';
if ($zip->open($file) === TRUE) {
    $content = $zip->getFromName('word/document.xml');
    $zip->close();
    $content = str_replace('</w:p>', "\n", $content);
    $text = strip_tags($content);
    echo $text;
} else {
    echo "Failed to open docx";
}
?>
