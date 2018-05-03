<?php
require_once('./base.php');
echo $htmlGen->createPageBeforeContent('Messages');
?>

<h4>Messages</h4>
<p>This tool is for anybody to read and submit random messages. Submitted messages are then added to a pool of messages for other users to read at random.</p>
<p>The purpose of creation was to mess around with Javascript and AJAX.</p>

<button type='button' id='start-button'>Open Message Widget</button>

<script src='./js/widget.js'></script>
<script src='./mapi/messages.js'></script>
<script type='text/javascript'>
    window.onload = function(){
        var height = 460;
        var width = 400;
        var startButton = document.getElementById('start-button');
        var pageBody = document.getElementsByTagName('body')[0];
        
        var widget = new Widget(height, width, startButton, pageBody);
        var messages = new Messages(widget.getContainer());
    }
</script>
    

<?php
echo $htmlGen->createPageAfterContent();
?>