Grade School Coding Tools
By Julian Egelstaff

To learn about what this package is for and why I made it, see this Prezi: 
http://prezi.com/bgjzpgdqmh-w/teaching-coding-to-grade-school-kids/

This package consists of the following files:

* this readme

* Kimberley Computer Club - Refernece.pdf
-- a two page reference sheet and introduction to HTML and CSS.

* Kimberley Computer Club - Reference.docx
-- the source file for the Reference PDF.

* Kimberley Computer Club - Cheat Sheet.pdf
-- a one page cheat sheet with simple code examples of common things kids want to do.  It is laid out in two identical columns so you can cut it in half after printing.

* Kimberley Computer Club - Cheat Sheet.docx
-- the source file for the Cheat Sheet PDF.

* edit.php
-- a simple file manager and editor.

* magic.js
-- a javascript file that can be included in any page, to make elements classed 'clickable' alternate between red and black colours when clicked, and to make elements classed 'draggable' into draggable items courtesy of jQuery UI.  The intention is that kids can easily include one script file, without having to understand all of what it does, and then have some simple interactivity in their pages.  magic.js could be easily extended to support other actions.  Note that since Javascript does not support multiple line strings, the main code has slashes in it to escape the line breaks!

* /user/edit.php
-- a file that refers back to the main edit.php, intended to let multiple subfolders all work off the same edit.php file, while managing only the files in their own subfolder.

* /user/mac_and_cheese.jpg
-- a photo used in the sample page described in the reference file.  Thanks to Slice of Chic on flickr for use of the photo.

* /codemirror-3.18/
-- a copy of the codemirror library that provides the syntax highlighing, line numbering, etc, and which includes a custom .js file (referenced in the main edit.php) that supports HTML, javascript, CSS and PHP in the same file

===============
INSTALLATION
===============

There are two things in this package that you might want to use. One is the reference sheet. The original .docx file is included so you can edit it to suit your purposes.  The minimal change you might want to make is to alter the title and the URL in the upper right.

The other is the edit.php file. This is a simple editor and file manager. You can drop it into any directory on your webserver, browse to it, and it will then give you a list of the files in the directory. You can click them to edit them. The editor provides syntax highlighting for HTML, javascript, css and PHP.

The edit.php file expects the codemirror library to be present at the same level in the directory structure.

I recommend that you make multiple folders, one for each user (student?). Then everyone can have their own space to manage their own files. Furthermore, I recommend you password protect those folders through your webserver's control panel, or by other means. 

You can put the /user/edit.php file in each subfolder. That file will refer back to the main edit.php file. This way, you can maintain one copy of the code, but it will be used by each subfolder. If there are changes to the main edit.php, it's easier to manage them this way.

The complete recommended folder structure is like this:

/www/
|_edit.php
|_/codemirror-3.18/
|_/tom/
  |_edit.php
|_/dick/
  |_edit.php
|_/harry/
  |_edit.php

Lastly, there is an image file included, mac_and_cheese.jpg, and this is intended to let people follow the entire HTML example on the reference sheet, and actually have an image appear in their pages. It can be placed in each user's folder as required.

I hope you find this useful. Let me know how it goes. Happy coding!

--Julian
julian@yourturn.ca