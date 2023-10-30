<?php

function wrap_with_xpointer_link($query) {
  return "./xml-2-task.xml#xpointer($query)";
}

function get_title_from_xpointer($xpointer_link) {
  $filename = strtok($xpointer_link, '#');
  $xml_content = file_get_contents($filename);
  $xml = new SimpleXMLElement($xml_content);

  $xpointerParts = explode('#xpointer(', $xpointer_link);
  $xpointerPart = rtrim($xpointerParts[1], ')');

  $query = $xpointerPart;
  $result = $xml->xpath("$query");

  return $result[0];
}

// Обработка примечаний
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $author = $_POST['author'];
  $content = $_POST['content'];
  $topic = $_POST['topic'];

  $notes_xml = simplexml_load_file('notes.xml');
  $note = $notes_xml->addChild('note');

  $note->addChild('date', date('Y-m-d'));
  $note->addChild('author', $author);
  $note->addChild('content', $content);

  $reference = $note->addChild('reference', 'Quiz reference');
  $reference->addAttribute('xlink:type', 'simple', 'http://www.w3.org/1999/xlink');
  $reference->addAttribute('xlink:href', "$topic", 'http://www.w3.org/1999/xlink');

  $reference_title = $note->addChild('reference_title', 'Quiz title reference');
  $reference_title->addAttribute('xlink:type', 'simple', 'http://www.w3.org/1999/xlink');
  $reference_title->addAttribute('xlink:href', wrap_with_xpointer_link("//quiz[@name='$topic']/quiz-title"), 'http://www.w3.org/1999/xlink');

  $notes_xml->asXML('notes.xml');
}

// Чтение и вывод
$xml_path = './xml-2-task.xml';
$xml_content = file_get_contents($xml_path);

$xml = new SimpleXMLElement($xml_content);

$topics = $xml->xpath("//quiz/@name");

function get_questions($topic_name) {
  global $xml;
  $questions = $xml->xpath("//quiz[@name='$topic_name']/question/question-text");
  return $questions;
}

function get_notes($topic) {
  $xml_notes_raw = file_get_contents('./notes.xml');
  $xml_notes = new SimpleXMLElement($xml_notes_raw);

  $notes = $xml_notes->xpath("//note[reference/@xlink:href='$topic']");

  return $notes;
}

function get_title_by_note($note) {
  global $xml;
  $xpointer = $note->reference_title->attributes('xlink', true)->href;
  return get_title_from_xpointer($xpointer);
}

?>

<h3>Списки тем</h3>

<ul>
  <?php foreach ($topics as $topic): ?>
    <li><?php echo $topic; ?></li>

    <ol>
      <?php foreach(get_questions($topic) as $question): ?>
        <li><?php echo $question?></li>
      <?php endforeach; ?>
    </ol>

    <br />

    <form method="post">
      <input type="text" name="author" placeholder="Автор" />
      <input type="text" name="content" placeholder="Примечание" />
      <input type="hidden" name="topic" value="<?php echo $topic; ?>" />
      <button type="submit">Добавить</button>
    </form>

    <?php foreach (get_notes($topic) as $note): ?>
        <li>
          <div>
            <span>Автор: <?php echo $note->author; ?></span>,
            <span>Примечание: <?php echo $note->content; ?></span>
          </div>
          <span>Дата: <?php echo $note->date; ?></span>
          <span>Заголовок темы: <?php echo get_title_by_note($note) ?></span>

          <br />
          <br />

        </li>
    <?php endforeach; ?>
    <hr />
  <?php endforeach; ?>
</ul>