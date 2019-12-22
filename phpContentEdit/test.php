
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

echo "<h1> DOmDocument </h1>";
$html="content.html";

$doc = new DOMDocument();
$doc->loadHTMLFile($html);

$xpath = new DOMXPath($doc);

$nodes =  $xpath->query("//*[@id='ce-01']");

foreach($nodes as $node) {
  $newnode = $doc->createDocumentFragment();
  $newnode->appendXML("<strong>NEw HTML CODED INSERTED </strong>");
  $node->appendChild($newnode);
}

echo "<h3> Print out HTML (Escaped) </h3> <hr>";
echo $doc->saveHTML();

?>


