<?php
$zip = new ZipArchive;
$res = $zip->open('INSTRUMEN EVALUASI KINERJA GURU PENDIDIKAN VOKASI.docx');
if ($res === TRUE) {
    $xml = $zip->getFromName('word/document.xml');
    $zip->close();
    $dom = new DOMDocument;
    $dom->loadXML($xml);
    
    $paragraphs = $dom->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'p');
    foreach ($paragraphs as $p) {
        $texts = $p->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 't');
        $line = '';
        foreach ($texts as $t) {
            $line .= $t->nodeValue;
        }
        if (trim($line) !== '') {
            echo $line . "\n";
        }
    }
} else {
    echo "Failed to open docx\n";
}
