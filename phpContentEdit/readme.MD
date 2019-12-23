#  PHP Content Editable 

<img align="right" width="250" src="https://github.com/acbrandao/templates/blob/master/img/html5.PNG">
This is an example of using PHP to update a contentEditable  webpaget.
Basically this script allows the designated and tagged <Div> blocks to be edited and re-written
back into the HTML file.

## Installation

Simlply clone this folder, or click on the file you're interested in and just copy it to your working direcotry.

```bash
git clone git@github.com:acbrandao/PHP.git
``` 

### General Approach

  - The plain HTML file we want to edit , which holds our content, we’ll call this content.html
  - The PHP script named index.php which reads and write the content.html and saves the changes

  We first begin by just reading and displaying the content.html , and we also have a few {{placeholders}} inside content.html to replace things like title .

   We also add a little allowEdit javascript within the content.html to basically put it into edit mode. This examples uses a very simplistic password prompt, but its recommended you secure the page more carefully for production systems. The prompt can be initiated by a button (as in this example) or via a secret queryString parameter, use your best judgement how to enabled editing.

```javascirpt
<input type="hidden" name="pw" value="" id="pw"><button type="submit" class="btn btn-primary" onclick="return allowEdit()"> Edit  </button>
 <script>
     function allowEdit()
     {
     var result= prompt('Credentials for editing. Make more secure. This is for demo purposes.');
     document.getElementById('pw').value=result;
     return result;
     }
   </script>
```

After the password prompt, we send the request back to the same index.php which checks that the user supplied password against our “secret” hash and if they match, reloads the content.html replacing the   `<div class="editable" id="ce-1">` with   tags with    `<div class="editable" contentEditabe="true" id="ce-1">`  This then allows the user to make the edits inside the browser.

Next we have an editable onBlur event handler which sends the changes back to the **index.php** to overwrite them to the content.html .  The actual code that does this is below.

```php
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

```

## Usage

Create a plain Plain HTML page then place your content inside structure like this
 ```html
<div class="editable" id="ce-1">Your content inside here </div>
```  
 the **class="editable"**  will tell the PHP file to make the content located inside editable. 
 Thne assign an eventHanlder on Blur to write those changes back to the disk.

## Security Cosniderations
Please note  using this technique on a live PUBLIC INTERNET site, with minimal security considerations is just inviting trouble, since, we’re allowing folks to **edit and overwrite**  our html files system directly  we are potentially exposing our site to some potential mischeif. 

So in addition to the simplistic password prompt, we may want to sanitize the edited HTML prior to writing it to disk, escaping any offending or malicious user input. And generallly make editing these pages much more secure.

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## License

This code is licensed under the MIT license. See https://opensource.org/licenses/MIT for details.
