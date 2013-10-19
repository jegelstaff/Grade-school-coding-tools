<html>
<head>
  <style>
  body {
    margin-top: 1px;
    margin-left: 20px;
    margin-right: 20px;
    margin-bottom: 20px;
  }
  h1, ul, ol, li, p {
    font-family: vedana, arial, helvetica, sans-serif;
  }
  textarea {
    font-family: courier new, monospace;
  }
  .small {
    font-size: 8pt;
  }

  </style>
  
  <script src="http://code.jquery.com/jquery-1.7.2.min.js" language="javascript" type="text/javascript"></script>
  <script src="../codemirror-3.18/lib/codemirror-compressed.js"></script>
  <link rel="stylesheet" href="../codemirror-3.18/lib/codemirror.css">
    
</head>
<body>
<?php

// kill magic quotes!
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}


// HIGH LEVEL PLAN:
// 1. provide list of all the files in the folder (only this folder)
// 2. files can be clicked and if so, then page reloads with editing window
// 3. editing window has three buttons at the top: save, close (save first)
// 4. architecture must prevent writing of files to other directories
// ----------

if(!isset($currentDir)) {
  // get the current folder
  $currentDir = str_replace("edit.php", "", __FILE__);
}

// -0.5 check if a file needs to be deleted, and if it exists, delete it
if(isset($_GET['delete'])) {
  if(file_exists($currentDir.str_replace("/","",str_replace("\\","",$_GET['delete'])))) {
    unlink($currentDir.$_GET['delete']);
  }
}

// get all the files in the current folder
$files = scandir($currentDir);

// 0. determine if a file has been requested for editing
$fileToRead = "";
if(isset($_GET['file'])) {
  // ignore slashes since we're limiting ourselves to this directory
  if(file_exists($currentDir.str_replace(array("/","\\"),"",$_GET['file']))) {
    $fileToRead = str_replace(array("/","\\"),"",$_GET['file']);
  }
}

// 0.25 determine if a file was saved and if so, then save it
if(isset($_POST['contents']) AND $fileToRead AND strtolower($fileToRead) != 'edit.php') {
  $fileToSave = fopen($currentDir.$fileToRead, "w");
  $i = 0;
  while($i <= strlen($_POST['flippedContents'])) { // flip the string back to normal since we reverse it before submitting
    $character = substr($_POST['flippedContents'],$i, 1);
    if($character != "\r") { // the reverse process in javascript seems to add \r chars to the string??!!
      $string = $character . $string;
    }
    $i++;
  }
  fwrite($fileToSave, $string); // use $_POST['contents'] and don't neuter it below if you want to avoid the messy flipping stuff, and the removal of the double lines that seem to creep in.
  fclose($fileToSave);
}

// 0.5 check if a new file needs to be created, as long as we're not editing something else, and as long as it doesn't exist already
if(isset($_POST['newname']) AND !$fileToRead) {
  $fileToRead = str_replace(array("/","\\"),"",$_POST['newname']);
  // if the file does not exist then create it
  if(!in_array($fileToRead, $files) AND strtolower($fileToRead) != 'edit.php') {
    if(!strstr($fileToRead,'.')) { $fileToRead .= '.html'; }
    fopen($currentDir.$fileToRead, "w");
  }
}

// 1./2. list the files...
if(!$fileToRead) {

  // setup the "create a new file box"
  ?>
  <div style="float: right;">
  <h1>Type a filename to create a new file</h1>
  <form name="newfile" action="edit.php" method="post">
    <input type="text" name="newname">
    <input type="submit" name="submit" value="Create!">
  </form>
  <p>Don't forget the extension at the end of the name, ie: .html or .php</p>
  </div>
  <?php

  // draw in the list of existing files
  $fileLinks = array();
  $unlistedFiles = array('.jpg', '.gif', '.jpg', '.png');
  natsort($files);
  foreach($files as $file) {
    if($file == "." OR $file == ".." OR $file == "edit.php" OR substr($file, 0, 1) == "." OR !strstr($file, ".")) { continue; }
    foreach($unlistedFiles as $thisExtension) {
      if(strtolower(substr($file, strlen($thisExtension)*-1)) == $thisExtension) {
        continue 2; // continue the higher foreach loop
      }
    }
    $safeFileName = strip_tags(htmlspecialchars($file));
    $fileLinks[] = "<li><a href=\"edit.php?file=".urlencode($safeFileName)."\">$safeFileName</a>&nbsp;&nbsp;&nbsp;<span class=\"small\"><a href=\"edit.php?delete=".urlencode($safeFileName)."\" onclick=\"javascript:return confirm('Are you sure you want to delete $safeFileName');\">[delete]</a></span></li>\n";
  }
  print "<div style=\"float: left;\">\n";
  print "<h1>Click a filename to edit that file</h1>\n";
  if(count($fileLinks)==0) {
    $fileLinks[] = "<p>No files have been created yet</p>";
  }
  print "<ul>\n";
  print implode($fileLinks,"");
  print "</ul></div>\n";

// 2./3. edit the files...  
} else {
  
  $safeFileName = strip_tags(htmlspecialchars($fileToRead));
  
  print "<div style=\"float: left;\">\n";
  print "<div style=\"float: right;\"><p><a href=\"$safeFileName\" target=\"_blank\">View this file in a new tab</a></p></div>\n";
  print "<h1>Edit the contents of: $safeFileName</h1>\n";
  print "<div style=\"float: right;\"><form name=\"closeform\" action=\"edit.php\" method=\"post\">\n";
  print "<input type=\"submit\" name=\"close\" value=\"Go back to the file list (don't save)\">\n"; // close button
  print "</form></div>\n"; 
  print "<form name=\"editform\" id=\"editform\" action=\"edit.php?file=".urlencode($safeFileName)."\" method=\"post\">\n";
  print "<input type=\"hidden\" name=\"flippedContents\" id=\"flippedContents\" value=\"\" />\n";
  print "<input type=\"submit\" name=\"save\" value=\"Save your changes\">\n<br>\n"; // save button
  $contents = file_get_contents($currentDir.$fileToRead);
  print "<textarea id=\"contents\" name=\"contents\">".htmlspecialchars($contents)."</textarea>\n";
  print "</form>\n";
  print "</div>\n";

?>
 
<script type="text/javascript">
 
$(window).load(function() {
  setContentsHeightWidth();
});

$(window).resize(function() {
  setContentsHeightWidth();
}); 

$('#editform').submit(function() {
  cm.save(); // Call this to update the textarea value for posting
  $('#flippedContents').val(reverseString($('#contents').val())); // reverse the string so that we won't trip up any validation/security parsers
  $('#contents').val('');
});
 
function setContentsHeightWidth() {
  $("#contents").css('height', ($(window).height()-140));
  $("#contents").css('width', ($(window).width()-35));
  cm.setSize($(window).width()-35, $(window).height()-140);
}

function reverseString(str) {
  var result = '';
  for(i=0;i<=str.length;i++) {
    result = str.substring(i, i+1) + result;
  }
  return result;
}

var cm;
$(document).ready(
  cm = CodeMirror.fromTextArea( document.getElementById('contents') , {
    indentUnit: 4,
    indentWithTabs: true,
    enterMode: "keep",
    tabMode: "shift",
    lineNumbers: true,
    matchBrackets: true,
    mode: 'application/x-httpd-php'
  })
);

</script>  
  
<?php
}
?>
</body>
</html>