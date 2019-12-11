<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

ob_start(); //start buffering the content
session_start(); // start this sesssion
date_default_timezone_set('America/New_York');  //Enable sesssions and timezone

//Get the script name and find the corresponding 
$basename= pathinfo(basename($_SERVER['SCRIPT_NAME']), PATHINFO_FILENAME);
$html_filename="content.html";

//Load in the corresponding HTML template file by the same name 
$page_html = file_get_contents($html_filename);  //load in our html content with proper placeholders


//Are we authorized to change this page, if so lets make teh content editable 
if ( isset($_REQUEST['action']) )  // 
 {
 //     print_r($_REQUEST);
    if ($_REQUEST['action']=="write")
    {

       //$doc = new DOMDocument();

        if (isset($_REQUEST['id']))
            $id= $_REQUEST['id'];

        //Find the ID in the page that was just loaded 
        $matches = array();
        preg_match('#(<div[^>]*id=[\'|"]'.$id.'[\'|"][^>]*>)(.*)</div>#isU', $page_html, $matches);
       
        //extract the contain block of that id
        //var_dump($matches);
        $matched_text =$matches[2];

        $replacement ="<div class='editable' id=\"".$id."\" {{contentedit}} >".$_REQUEST['content']."</div>";
        //echo  $replacement;  

        //replace the block with the new content
        $new_html= preg_replace('#(<div[^>]*id=[\'|"]'.$id.'[\'|"][^>]*>)(.*)</div>#isU', $replacement , $page_html);

        //write the update page (Entire Page back to disk)
        if (is_writable($html_filename))
        {
        $bytes=file_put_contents($html_filename, $new_html);
        echo "\n Successfully wrote $bytes bytes \n";
        }
        else
        die("File cannot not be written to check permissions");

        //return status and exit here.
        die("End of AJAX Function  write  Content");
    }

 }

// Associate the various general and page 
$page_html_placeholders = [
    'title' => "Content Editable Page Sample", // title show in the page title
   'server_page' =>$_SERVER['PHP_SELF']."?action=edit",
   'action' => isset($_REQUEST['action'])? $_REQUEST['action'] : " Edit",
   'contentedit' => isset($_REQUEST['action']) &&  ( $_REQUEST['action']=='edit' ) ? "contenteditable='true'" : null
  ];
 
  //save the original page html incase changes aborted
  $disk_page_html=$page_html;
  
   //Now replace the actual patterns/placeholders with actual values
  while($i = current($page_html_placeholders)) {
    $page_html = str_replace('{{'.key($page_html_placeholders).'}}', $i, $page_html );
    next($page_html_placeholders);
    }
   
  echo $page_html ;
  ob_end_flush();
?>