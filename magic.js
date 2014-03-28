js = "\
\
<script type='text/javascript'> \
$('.clickable').click(function(){ \
	if($(this).css('color') == 'rgb(0, 0, 0)') { \
		$(this).css('color', 'red'); \
	} else { \
		$(this).css('color', 'black'); \
	} \
}); \
\
$(function() { \
	$( '.draggable' ).draggable(); \
}); \
 \
</script> \
\
";

document.writeln("<script type='text/javascript' src='http://code.jquery.com/jquery-1.11.0.min.js'></script>");
document.writeln("<link rel='stylesheet' href='http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css'>");
document.writeln("<script src='http://code.jquery.com/jquery-1.9.1.js'></script>");
document.writeln("<script src='http://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>");

document.write(js);
