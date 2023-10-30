<?php

// Загружаем XML файл
$xml = new DOMDocument;
$xml->load('./data/xml-questions.xml');

// Загружаем XSL файл
$xsl = new DOMDocument;
$xsl->load('./data/answers-xslt.xml');

// Настраиваем преобразователь
$proc = new XSLTProcessor;

// Присоединяем xsl правила
$proc->importStyleSheet($xsl);

file_put_contents('./_correct-answers.xml', $proc->transformToXML($xml));

?>

Ответы сформированы
