<?php
require_once('./base.php');
echo $htmlGen->createPageBeforeContent('Clock');
?>

<h4>Clock</h4>
<p>This is an analog clock created so I could try out drawing with Javascript on a canvas element.</p>

<button type='button' id='start-button'>Open Clock</button>

<script src='./js/widget.js'></script>
<script src='./js/draw.js'></script>
<script type='text/javascript'>
    
    
    window.onload = function(){
        var height = 210;
        var width = 210; 
        var startButton = document.getElementById('start-button');
        var pageBody = document.getElementsByTagName('body')[0];
        var widget = new Widget(height, width, startButton, pageBody);
        
        var container = widget.getContainer();
        container.innerHTML = "<canvas id='cnvs' width='200px' height='200px' style='padding: 0; margin: auto; display: block;'></canvas>";
        
        drawer = new Drawer('cnvs', 200, 200);
        
        var img = document.createElement('img');
        img.src = './images/spartanHead.png';
        img.alt = 'Michigan State University Spartan head logo';
        img.height = 1160;
        img.width = 1000;
        
        function makeClock() {
            drawer.clear();
            
            var date = new Date;
            
            var twoPI = (Math.PI * 2);
            var seconds = date.getSeconds();
            var minutes = date.getMinutes();
            var hours = date.getHours();
            hours = hours > 11 ? hours - 12 : hours;
            
            var secondAngle = (seconds / 60) * twoPI;
            var minuteAngle = ((minutes / 60) * twoPI) + ((seconds / (60 * 60)) * twoPI);
            var hourAngle = ((hours / 12) * twoPI) + ((minutes / (60 * 12)) * twoPI) + ((seconds / (60 * 60 * 12)) * twoPI);
            
            drawer.drawCircle(.5, .5, .4, 5, 'black', 'silver');
            
            //img created once outside function
            drawer.drawImage(img, .5, .5, 75, 87);
        
            for(var i=0; i<=11; i++){
                var angle = (((Math.PI * 2) / 12) * i);
                var start = drawer.getPointAtEndOfLineFromAngle(.5, .5, angle, .33);
                drawer.drawLineFromAngle(start.x, start.y, angle, .05, 7, 'black');
            }
            
            drawer.drawLineFromAngle(.5, .5, hourAngle, .15, 8, 'white', true);
            drawer.drawLineFromAngle(.5, .5, minuteAngle, .34, 6, 'white', true);
            drawer.drawLineFromAngle(.5, .5, secondAngle, .355, 3, 'white', true);
            
            drawer.drawLineFromAngle(.5, .5, hourAngle, .15, 6, 'black', true);
            drawer.drawLineFromAngle(.5, .5, minuteAngle, .34, 4, 'black', true);
            drawer.drawLineFromAngle(.5, .5, secondAngle, .355, 2, 'red', true);
            
            window.requestAnimationFrame(makeClock);
        }
        
        window.requestAnimationFrame(makeClock);
    }
    
    
</script>
    

<?php
echo $htmlGen->createPageAfterContent();
?>