
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

echo "<h1> DOmDocument </h1>";
$html="content.html";

$doc = new DOMDocument();
$doc->loadHTML($html);

// $elements = $doc->getElementsByTagName('h1');
// $elements = $doc->getElementsByTagName('*');

$elements = $doc->getElementsByTagName('h1');
// https://www.php.net/manual/en/domdocument.loadhtmlfile.php

foreach ($elements as $element) {
    echo "<br/>". $element->nodeName. ": ";

    $nodes = $element->childNodes;
    foreach ($nodes as $node) {
      echo $node->nodeValue. "\n";
    }
  }

?>