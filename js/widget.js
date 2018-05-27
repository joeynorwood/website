class Widget {
    
    constructor(height, width, startButton, pageBody, id='') {
        this.pageBody = pageBody;
        this.startButton = startButton;
        this.height = height;
        this.width = width;
        this.id = '-' + id;
        
        var screenWidth = (pageBody.getBoundingClientRect().width == 0) ? width : pageBody.getBoundingClientRect().width;
        var screenHeight = (pageBody.getBoundingClientRect().height == 0) ? height : pageBody.getBoundingClientRect().height;
        
        if(screenWidth <= 400){
            this.width = Math.floor(screenWidth * 0.8);
        }
        if(screenHeight <= 450){
            this.height = Math.floor(screenHeight * 0.8);
        }
        
        this.grayArea = this.createGrayArea();
        this.widgetArea = this.createWidgetArea();
        
        this.pageBody.appendChild(this.grayArea);
        this.pageBody.appendChild(this.widgetArea);
        
        //these 2 methods will select elements by ID because they are click responses
        //this.startButton.onclick = this.showWidget;
        //this.grayArea.onclick = this.hideWidget;
        
        var postfix = this.id;
        
        this.startButton.addEventListener('click', function(){
            document.getElementById('gray-area' + postfix).style.visibility = 'visible';
            document.getElementById('widget-area' + postfix).style.visibility = 'visible';
        });
        
        this.grayArea.addEventListener('click', function(){
            document.getElementById('gray-area' + postfix).style.visibility = 'hidden';
            document.getElementById('widget-area' + postfix).style.visibility = 'hidden';
        });
    }
    
    /*these 2 methods will select elements by ID because they are click responses
    showWidget() {
        document.getElementById('gray-area').style.visibility = 'visible';
        document.getElementById('widget-area').style.visibility = 'visible';
    }
    hideWidget() {
        document.getElementById('gray-area').style.visibility = 'hidden';
        document.getElementById('widget-area').style.visibility = 'hidden';
    }
    */

    createGrayArea() {
        var grayArea = document.createElement('DIV');
        grayArea.style.width = '10000px';
        grayArea.style.height = '10000px';
        grayArea.style.position = 'fixed';
        grayArea.style.top = '0';
        grayArea.style.left = '0';
        grayArea.style.background = 'gray';
        grayArea.style.opacity = '0.5';
        
        grayArea.id = 'gray-area' + this.id;
        grayArea.style.visibility = 'hidden';
        return grayArea;
    }

    createWidgetArea() {
        var widgetArea = document.createElement('DIV');
        widgetArea.style.height = this.height.toString() + 'px';
        widgetArea.style.width = this.width.toString() + 'px';
        widgetArea.style.overflowX = 'scroll';
        widgetArea.style.overflowY = 'scroll';
        widgetArea.style.border = '1px solid black';
        widgetArea.style.position = 'fixed';
        widgetArea.style.top = '50%';
        widgetArea.style.left = '50%';
        widgetArea.style.marginTop = (0 - (this.height / 2.0)).toString() + 'px';
        widgetArea.style.marginLeft = (0 - (this.width / 2.0)).toString() + 'px';
        widgetArea.style.background = 'white';
        
        widgetArea.id = 'widget-area' + this.id;
        widgetArea.style.visibility = 'hidden';
        return widgetArea;
    }
    
    getContainer() {
        return this.widgetArea;
    }
};