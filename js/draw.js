class Drawer {
    
    constructor(canvasID, height, width) {
        this.canvas = null;
        this.height = height;
        this.width = width;
        this.rScale = (height < width) ? height : width;
        
        if(document.getElementById(canvasID)) {
            this.canvas = document.getElementById(canvasID);
        }
        
        if(this.canvas == null){
            throw "Error: Canvas failed to initialize";
        }
        
    }
    
    clear() {
        var ctx = this.canvas.getContext('2d');
        ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }
    
    //this is used to make the canvas operate in the north-east quadrant so y ascends upwards
    correctY(y) {
        if(y < 0.5){
            y = 0.5 + (0.5 - y);
        }
        else{
            y = 0.5 - (y - 0.5);
        }
        return y;
    }
    
    //arc(x, y, radius, startAngle, EndAngle)
    drawCircle(x, y, radius, thickness=1, color='black', fillColor='white') {
        if(x < 1 && x > 0 && y < 1 && y > 0){
            y = this.correctY(y);
            var ctx = this.canvas.getContext('2d');
            ctx.beginPath();
            ctx.arc(x * this.width, y * this.height, radius * this.rScale, 0, Math.PI * 2, false);
            ctx.lineWidth = thickness;
            ctx.strokeStyle = color;
            ctx.fillStyle = fillColor;
            ctx.fill();
            ctx.stroke();
        }
    }
    
    //arc(x, y, radius, startAngle, EndAngle)
    //angle is in radians clockwise from north (counter-clockwise from south due to reflection)
    drawLineFromAngle(x, y, angle, length, thickness=1, color='black', round=false) {
        if(x <= 1 && x >= 0 && y <= 1 && y >= 0 && angle < (Math.PI * 2) && angle >= 0 && length <= 1 && length > 0){
            var endX = 0;
            var endY = 0;
            
            if(angle < (Math.PI / 2) && angle >= 0){
                endX = x + (Math.sin(angle) * length);
                endY = y + (Math.cos(angle) * length);
            }
            else if(angle < Math.PI && angle >= (Math.PI / 2)){
                angle = angle - (Math.PI / 2);
                endX = x + (Math.cos(angle) * length);
                endY = y - (Math.sin(angle) * length);
            }
            else if(angle < (3 * (Math.PI / 2)) && angle >= Math.PI){
                angle = angle - Math.PI;
                endX = x - (Math.sin(angle) * length);
                endY = y - (Math.cos(angle) * length);
            }
            else{
                angle = angle - (3 * (Math.PI / 2));
                endX = x - (Math.cos(angle) * length);
                endY = y + (Math.sin(angle) * length);   
            }
            
            y = this.correctY(y);
            endY = this.correctY(endY);
            
            var ctx = this.canvas.getContext('2d');
            ctx.lineWidth = thickness;
            ctx.lineCap = round ? 'round' : 'butt';
            ctx.strokeStyle = color;
            ctx.beginPath();
            ctx.moveTo(x * this.width, y * this.height);
            ctx.lineTo(endX * this.width, endY * this.height);
            ctx.stroke();
        }
    }
    
    getPointAtEndOfLineFromAngle(x, y, angle, length) {
        var endX = 0;
        var endY = 0;
        
        if(x <= 1 && x >= 0 && y <= 1 && y >= 0 && angle < (Math.PI * 2) && angle >= 0 && length <= 1 && length > 0){
            if(angle < (Math.PI / 2) && angle >= 0){
                endX = x + (Math.sin(angle) * length);
                endY = y + (Math.cos(angle) * length);
            }
            else if(angle < Math.PI && angle >= (Math.PI / 2)){
                angle = angle - (Math.PI / 2);
                endX = x + (Math.cos(angle) * length);
                endY = y - (Math.sin(angle) * length);
            }
            else if(angle < (3 * (Math.PI / 2)) && angle >= Math.PI){
                angle = angle - Math.PI;
                endX = x - (Math.sin(angle) * length);
                endY = y - (Math.cos(angle) * length);
            }
            else{
                angle = angle - (3 * (Math.PI / 2));
                endX = x - (Math.cos(angle) * length);
                endY = y + (Math.sin(angle) * length);   
            }
        }
        
        //endY = this.correctY(endY);
        
        return {x : endX, y : endY};
    }
    
    drawImage(imgObj, x, y, width, height) {
        var ctx = this.canvas.getContext('2d');
        ctx.drawImage(imgObj, (x * this.width) - (width / 2) , y * this.height - (height / 2), width, height);
        //ctx.drawImage(img, height * this.height, width * this.width, img.width / 15, img.height / 15);
    }

}







