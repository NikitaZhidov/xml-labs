<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // $xml_answer = $_POST['XML'];
  // $dtd_answer = $_POST['DTD'];
  // $xxx_answer = $_POST['XPath_XLink_XPointer'];
  // $xquery_answer = $_POST['XQuery'];
  // $xsl_answer = $_POST['XSL'];

  $correctAnswersXML = simplexml_load_file('_correct-answers.xml');

  $resultXML = new SimpleXMLElement('<result></result>');

  foreach ($correctAnswersXML->quiz as $quiz) {
    $quizName = (string) $quiz['name'];
    $correctAnswer = $quiz->{"correct-answer"};

    $quizResult = $resultXML->addChild('quiz');
    $quizResult->addAttribute('name', $quizName);

    $quizResult->addChild('correct-answer', $correctAnswer);

    $quizResult->addChild('user-answer', $_POST[$quizName]);
  }

  // Сохраняем результат в _user-answers.xml
  $resultXML->asXML('_user-answers.xml');

  echo "Результаты проверки ответов сохранены в _user-answers.xml.";
}
?>

<div>
  <a href="./index5.php">Посмотреть результаты</a>
</div>
