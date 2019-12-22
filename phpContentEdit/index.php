<?php

// Change the following to suit your environment
$basename= pathinfo(basename($_SERVER['SCRIPT_NAME']), PATHINFO_FILENAME);
$html_filename="content.html";   //html file that holds content, and is to be updated
$server_write_hmtl=$_SERVER['PHP_SELF'];  // PHP server page that writes the content
$secret_pw="admin"  ;  //secrete password to allow editable content
// End of configurable code 

error_reporting(E_ALL);
ini_set('display_errors', 'On');

ob_start(); //start buffering the content
session_start(); // start this sesssion
date_default_timezone_set('America/New_York');  //Enable timezone

//Load in the corresponding HTML template file by the same name 
$page_html = file_get_contents($html_filename);  //load in our html content with proper placeholders

$isAuthorized= isset($_REQUEST['pw']) && ( MD5($_REQUEST['pw']))==MD5($secret_pw) ? true: false;

//Are we authorized to change this page, if so lets make teh content editable 
if ( isset($_REQUEST['action']) && $_REQUEST['action']=="edit" && !$isAuthorized )  // 
 {
     echo "<script> alert('Invalid Credentails supplied try again') </script>";
 }
 

 //Ok lets re-write the content for this page
if ( isset($_REQUEST['action']) && $_REQUEST['action']=="write" && isset($_REQUEST['id']) )  // 
 {
      //REQUIRES THAT  DOMDocument is available  
      //https://www.php.net/manual/en/class.domdocument.php  
      //in order to properly parse HTML file and append to it.
      $doc = new DOMDocument();  //instantiate the HTML 
      $doc->loadHTML($page_html);  //load the HTML into the DomDocument Parser
      $id=$_REQUEST['id'];

      $replacement_html = htmlspecialchars(  trim($_REQUEST['content']) );
      echo "Editing content //*[@id='".$id."']";  //debug messages can be remove
      echo "\n Replacing with contnet \n";   //debug messages can be remove
      echo $replacement_html  ;

      //Now walk the matching Nodes and REPLACE with  the new fragment
      $xpath = new DOMXPath($doc);  //setup an Xpath Query object

      $nodes =  $xpath->query("//*[@id='".$id."']");  //search for our id
      foreach($nodes as $node) {
          $newnode = $doc->createDocumentFragment();

      if ( isset($replacement_html) )
        {     
            $newnode->appendXML($replacement_html);  //new content
        //    $node->appendChild($newnode);  //replace the existing content
            $node->parentNode->replaceChild($newnode, $node);
         
        }
      }
   
  //write the update page (Entire Page back to disk)
        if (is_writable($html_filename))
        {
         $bytes= $doc->saveHTMLFile($html_filename);
          echo "\n Success wrote $html_filename with $bytes bytes \n";
        }
        else
        die("File  $html_filename cannot not be written to check permissions");

        //return status and exit here.
        die("\n Success  AJAX Function write Content");
    }


// Now let's update the page with various placeholders
$page_html_placeholders = [
   'title' => "Content Editable Page Sample", // title show in the page title
    'action' => isset($_REQUEST['action'])? $_REQUEST['action'] : " Edit",  //action mode
   'javascript_content_edit'=>( isset($_REQUEST['action']) &&  $_REQUEST['action']=='edit'  && $isAuthorized )? contentJSEvent_handler() : ' '
  ];
 
  //save the original page html incase changes aborted
  $disk_page_html=$page_html;
  
   //Now replace the actual patterns/placeholders with actual values
  while($i = current($page_html_placeholders)) {
    $page_html = str_replace('{{'.key($page_html_placeholders).'}}', $i, $page_html );
    next($page_html_placeholders);
    }
  
    //Look for any class="editable" and if authorized replace with contentEditable=true tag
    if ( isset($_REQUEST['action']) && ( $_REQUEST['action']=='edit' ) && $isAuthorized)
    {
      $page_html = str_replace("class=\"editable\"",  "class=\"editable\" contenteditable='true' ", $page_html );
    }
    

  echo $page_html ;
  ob_end_flush();


  //This funtion is injexted into the web page when valid credentials are supplies
  function contentJSEvent_handler()
  {
    global $server_write_hmtl;

    $html = <<<HTML
    <script >
    $('.editable').blur(function(){
      var myTxt = $(this).html();
      var myid = $(this).attr('id');
      
      console.log("Updating content: "+myTxt.trim() );
      console.log("content ATTR: "+myid);
  
      $.ajax({
          type: 'post',
          url:  '$server_write_hmtl',
          data: 'content=' +myTxt+"&id="+myid+"&action=write"
      });
  });
  </script>
HTML;

return $html;
  }
?>