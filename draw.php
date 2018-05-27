<?php
require_once('./base.php');
echo $htmlGen->createPageBeforeContent('Canvas Experiments');
?>

<h4>Canvas Experiments</h4>

<button type='button' id='clock-button'>Open Clock</button>
<button type='button' id='swarm-button'>Open Swarm</button>

<script src='./js/widget.js'></script>
<script src='./js/draw.js'></script>
<script type='text/javascript'>
    
    
    window.onload = function(){
        //this is code for the clock---------------------------------------------------------------------------------
        var height = 210;
        var width = 210; 
        var startButton = document.getElementById('clock-button');
        var pageBody = document.getElementsByTagName('body')[0];
        var widget = new Widget(height, width, startButton, pageBody, 'clk');
        
        var container = widget.getContainer();
        container.innerHTML = "<canvas id='clk-cnvs' width='200px' height='200px' style='padding:0; margin:auto; display:block;'></canvas>";
        
        drawer = new Drawer('clk-cnvs', 200, 200);
        
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
        }
        
        //the following is code for the swarm--------------------------------------------------------------------------
        var SWheight = 350;
        var SWwidth = 350; 
        var SWstartButton = document.getElementById('swarm-button');
        var SWpageBody = document.getElementsByTagName('body')[0];
        var SWwidget = new Widget(SWheight, SWwidth, SWstartButton, SWpageBody, 'sw');
        
        var SWcontainer = SWwidget.getContainer();
        SWcontainer.innerHTML = "<canvas id='sw-cnvs' width='350px' height='350px' style='padding:0; margin:auto; display:block;'></canvas>";
        
        var SWdrawer = new Drawer('sw-cnvs', 350, 350);
        
        var circles = [];
        var dMin = .0005;
        var dMax = .005;
        for(var i=0; i<=99; i++){
            circles[i] = {x : Math.random(), 
                          y : Math.random(), 
                          dx : (Math.random() * (dMax - dMin)) + dMin, 
                          dy : (Math.random() * (dMax - dMin)) + dMin,
                          outline : 'black',
                          fill : ''
                         };
            
            var blueInt = parseInt('0000ff', 16);
            var redInt = parseInt('ff0000', 16);
            
            var speed = Math.sqrt((circles[i].dx*circles[i].dx) + (circles[i].dy*circles[i].dy));
            var maxSpeed = Math.sqrt((dMax*dMax) + (dMax*dMax));
            var minSpeed = Math.sqrt((dMin*dMin) + (dMin*dMin));
            
            var speedPercent = (speed - minSpeed) / (maxSpeed - minSpeed);
            
            var colorNum = Math.floor(((redInt - blueInt) * speedPercent) + blueInt);
            
            //circles[i].fill = '#' + colorNum.toString(16);
            circles[i].fill = '#' + colorNum.toString(16).substring(0, 2) + '00' + colorNum.toString(16).substring(4, 6);
        }
        
        function swarm() {
            SWdrawer.clear();
            
            for(var i = 0; i<circles.length; i++){
                if(circles[i].x + circles[i].dx > 1 || circles[i].x + circles[i].dx < 0){
                    circles[i].dx = 0 - circles[i].dx;
                }
                if(circles[i].y + circles[i].dy > 1 || circles[i].y + circles[i].dy < 0){
                    circles[i].dy = 0 - circles[i].dy;
                }
                
                circles[i].x = circles[i].x + circles[i].dx;
                circles[i].y = circles[i].y + circles[i].dy;
                
                SWdrawer.drawCircle(circles[i].x, circles[i].y, .02, 1, circles[i].outline, circles[i].fill);
            }
        }
        
        //shared code to have all examples active--------------------------------------------------------------
        function draw() {
            makeClock();
            swarm();
            window.requestAnimationFrame(draw);
        }
        
        window.requestAnimationFrame(draw);
    }
    
    
</script>
    

<?php
echo $htmlGen->createPageAfterContent();
?>