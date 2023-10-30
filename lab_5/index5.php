<?php

// Загружаем XML файл
$xml = new DOMDocument;
$xml->load('./_user-answers.xml');

// Загружаем XSL файл
$xsl = new DOMDocument;
$xsl->load('./_user-answers-xslt.xml');

// Настраиваем преобразователь
$proc = new XSLTProcessor;

// Присоединяем xsl правила
$proc->importStyleSheet($xsl);

echo $proc->transformToXML($xml);
?>
