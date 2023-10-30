<?php

// Загружаем XML файл
$xml = new DOMDocument;
$xml->load('./data/notes.xml');

// Загружаем XSL файл
$xsl = new DOMDocument;
$xsl->load('./data/notes-xslt.xml');

// Настраиваем преобразователь
$proc = new XSLTProcessor;

// Присоединяем xsl правила
$proc->importStyleSheet($xsl);

echo $proc->transformToXML($xml);
?>
