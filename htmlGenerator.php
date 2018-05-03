<?php
class htmlGenerator{

    //var $placeHolder;
    
    function __construct(){
        //$this->placeHolder = 1;
    }
    
    function createPageBeforeContent($tabTitle){
        $HTML = "
        <!DOCTYPE html>
        <html>
        <head>
            <link rel='stylesheet' type='text/css' href='./css/style.css'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <title>" . $tabTitle . "</title>
        </head>
        <body>
            <div id='full-window'>
                <div id='center-column'>
                    <div id='hdr'>
                        <img src='./images/4banner.jpg' alt='4runner in desert' height='630' width='3261'>
                    </div>
                    <div id='side-nav'>
                        <ul>
                            <a href='./'><li>Home</li></a>
                            <a href='./resume.php'><li>Resume</li></a>
                            <a href='./termsUnknown.php'><li>Terms Unknown</li></a>
                            <a href='./messages.php'><li>Messages</li></a>
                            <a href='./clock.php'><li>Clock</li></a>
                            <div id='side-nav-last-li'></div>
                        </ul>
                    </div>
                    <div id='content'>";
        
        return $HTML;
    }
    
    function createPageAfterContent(){
        $HTML = "
                    </div>
                </div>
            </div>      
        </body>
        </html>";
        
        return $HTML;
    }
    
}
  
?>