class Messages {
    constructor(contentContainer) {
        this.container = contentContainer;
        this.message = {
            box : null,
            txt : null,
            dateCreated : null
        };
        this.randomMessageButton = null;
        this.newMessageButton = null;
        this.newMessageEntry = null;
        this.newMessageSubmit = null;
        this.newMessageHide = null;
        this.newMessageCounter = null;
        
        this.createRandomMessageButton();
        this.createNewMessageButton();
        this.createMessageArea();
        this.createNewMessageEntry();
        this.createNewMessageSubmit();
        this.createNewMessageHideButton();
        this.createNewMessageCounter();
        //this.getRandomMessageClick();
    }
    
    createMessageArea() {
        this.message.txt = document.createElement('div');
        this.message.txt.style.width = '90%';
        this.message.txt.style.minHeight = '80px';
        this.message.txt.style.border = '1px solid black';
        this.message.txt.style.margin = 'auto';
        this.message.txt.style.marginTop = '8px';
        this.message.txt.style.padding = '8px';
        this.message.txt.style.display = 'block';
        this.message.txt.style.wordWrap = 'break-word';
        
        this.message.txt.style.background = '#e1f7dc';
        this.message.txt.id = 'msg-txt';
        
        this.message.dateCreated = document.createElement('div');
        this.message.dateCreated.style.textAlign = 'right';
        this.message.dateCreated.style.marginRight = '5%';
        this.message.dateCreated.style.padding = '4px';
        this.message.dateCreated.id = 'msg-date';
        
        this.message.box = document.createElement('div');
        this.message.box.style.width = '90%';
        this.message.box.style.border = '1px solid black';
        this.message.box.style.margin = 'auto';
        this.message.box.style.marginTop = '8px';
        this.message.box.style.display = 'block';
        this.message.box.style.background = '#ccdfff';
        this.message.box.id = 'msg-box';
        
        this.message.box.appendChild(this.message.txt);
        this.message.box.appendChild(this.message.dateCreated);
        this.container.appendChild(this.message.box);
    }
    
    createRandomMessageButton() {
        this.randomMessageButton = document.createElement('button');
        this.randomMessageButton.style.height = '3em';
        this.randomMessageButton.style.width = '40%';
        this.randomMessageButton.style.borderRadius = '0';
        this.randomMessageButton.style.border = '1px solid black';
        this.randomMessageButton.style.fontSize = '.8em';
        this.randomMessageButton.style.margin = '0';
        this.randomMessageButton.style.marginTop = '15px';
        this.randomMessageButton.style.marginLeft = '5%';
        this.randomMessageButton.style.marginRight = '10%';
        this.randomMessageButton.style.color = 'white';
        this.randomMessageButton.style.backgroundColor = '#7ccaf9';
        this.randomMessageButton.innerHTML = 'Get Random Message';
        this.randomMessageButton.id = 'rand-msg-btn';
        this.randomMessageButton.onclick = this.getRandomMessageClick;
        
        this.container.appendChild(this.randomMessageButton);
    }
    
    //this method will select elements by ID because it is a click response
    getRandomMessageClick() {
        var txt = document.getElementById('msg-txt');
        var dt = document.getElementById('msg-date');
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', './mapi/getRandomMessage.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var json_obj = JSON.parse(xhr.responseText);
                
                txt.innerHTML = json_obj.message;
                dt.innerHTML = 'Posted: ' + json_obj.date_created.substring(0, 10);
            }
            else {
                txt.innerHTML = '~~~SOMETHING WENT WRONG~~~';
                dt.innerHTML = 'BAD';
            }
        };
        xhr.send('');
    }
    
    createNewMessageButton() {
        this.newMessageButton = document.createElement('button');
        this.newMessageButton.style.height = '3em';
        this.newMessageButton.style.width = '40%';
        this.newMessageButton.style.borderRadius = '0';
        this.newMessageButton.style.border = '1px solid black';
        this.newMessageButton.style.fontSize = '.8em';
        this.newMessageButton.style.margin = '0';
        this.newMessageButton.style.marginTop = '15px';
        this.newMessageButton.style.marginRight = '5%';
        this.newMessageButton.style.color = 'white';
        this.newMessageButton.style.backgroundColor = '#7ccaf9';
        this.newMessageButton.innerHTML = 'Add New Message';
        this.newMessageButton.id = 'new-msg-btn';
        this.newMessageButton.onclick = this.newMessageCreateClick;
        
        this.container.appendChild(this.newMessageButton);
    }
    
    newMessageCreateClick() {
        document.getElementById('new-msg-entry').style.visibility = 'inherit';
        document.getElementById('new-msg-submit').style.visibility = 'inherit';
        document.getElementById('new-msg-hide').style.visibility = 'inherit';
        document.getElementById('new-msg-cnt').style.visibility = 'inherit';
    }
    
    createNewMessageEntry() {
        this.newMessageEntry = document.createElement('textarea');
        this.newMessageEntry.style.width = '90%';
        this.newMessageEntry.style.margin = 'auto';
        this.newMessageEntry.style.marginTop = '8px';
        this.newMessageEntry.style.display = 'block';
        this.newMessageEntry.style.minHeight = '7em';
        this.newMessageEntry.style.visibility = 'hidden';
        this.newMessageEntry.style.border = '1px solid black';
        this.newMessageEntry.id = 'new-msg-entry';
        this.newMessageEntry.onkeyup = this.newMessageKeyStroke;
        
        this.container.appendChild(this.newMessageEntry);
    }
    
    newMessageKeyStroke() {
        var txtArea = document.getElementById('new-msg-entry');
        var numChars = txtArea.value.length;
        
        if(txtArea.value.length < 10) {
            document.getElementById('new-msg-cnt').style.color = 'red';
        }
        else {
            document.getElementById('new-msg-cnt').style.color = 'black';
        }
        
        if(txtArea.value.length > 400){
            txtArea.value = txtArea.value.substring(0, 400);
        }
        
        document.getElementById('new-msg-cnt').innerHTML = txtArea.value.length.toString() + ' / 400';
    }
    
    createNewMessageSubmit() {
        this.newMessageSubmit = document.createElement('button');
        this.newMessageSubmit.style.height = '3em';
        this.newMessageSubmit.style.width = '40%';
        this.newMessageSubmit.style.borderRadius = '0';
        this.newMessageSubmit.style.border = '1px solid black';
        this.newMessageSubmit.style.fontSize = '.8em';
        this.newMessageSubmit.style.margin = '0';
        this.newMessageSubmit.style.marginTop = '8px';
        this.newMessageSubmit.style.marginLeft = '5%';
        this.newMessageSubmit.style.visibility = 'hidden';
        this.newMessageSubmit.style.color = 'white';
        this.newMessageSubmit.style.backgroundColor = '#7ccaf9';
        this.newMessageSubmit.innerHTML = 'Submit Message';
        this.newMessageSubmit.id = 'new-msg-submit';
        this.newMessageSubmit.onclick = this.newMessageSubmitClick;
        
        this.container.appendChild(this.newMessageSubmit);
    }
    
    newMessageSubmitClick() {
        var txtEntry = document.getElementById('new-msg-entry');
        var msg = document.getElementById('msg-txt');
        document.getElementById('msg-date').innerHTML = '';
        
        if(txtEntry.value.length < 10){
            msg.innerHTML = 'Messages must be at least 10 characters long';
            return;
        }
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', './mapi/createMessage.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                msg.innerHTML = 'Message Submitted Successfully';
                txtEntry.value = '';
                txtEntry.style.visibility = 'hidden';
                document.getElementById('new-msg-submit').style.visibility = 'hidden';
                document.getElementById('new-msg-hide').style.visibility = 'hidden';
                document.getElementById('new-msg-cnt').style.visibility = 'hidden';
                document.getElementById('new-msg-cnt').innerHTML = '0 / 400';
                document.getElementById('new-msg-cnt').style.color = 'red';
            }
            else {
                msg.innerHTML = 'Message Submission Failed';
            }
        };
        xhr.send('message=' + encodeURIComponent(txtEntry.value));
    }
    
    createNewMessageHideButton() {
        this.newMessageHide = document.createElement('button');
        this.newMessageHide.style.height = '3em';
        this.newMessageHide.style.width = '40%';
        this.newMessageHide.style.borderRadius = '0';
        this.newMessageHide.style.border = '1px solid black';
        this.newMessageHide.style.fontSize = '.8em';
        this.newMessageHide.style.margin = '0';
        this.newMessageHide.style.marginTop = '8px';
        this.newMessageHide.style.marginLeft = '10%';
        this.newMessageHide.style.visibility = 'hidden';
        this.newMessageHide.style.color = 'white';
        this.newMessageHide.style.backgroundColor = '#7ccaf9';
        this.newMessageHide.innerHTML = 'Hide Message Entry';
        this.newMessageHide.id = 'new-msg-hide';
        this.newMessageHide.onclick = this.newMessageHideClick;
        
        this.container.appendChild(this.newMessageHide);
    }
    
    newMessageHideClick() {
        document.getElementById('new-msg-entry').style.visibility = 'hidden';
        document.getElementById('new-msg-submit').style.visibility = 'hidden';
        document.getElementById('new-msg-hide').style.visibility = 'hidden';
        document.getElementById('new-msg-cnt').style.visibility = 'hidden';
    }
    
    createNewMessageCounter() {
        this.newMessageCounter = document.createElement('div');
        this.newMessageCounter.style.margin = '0';
        this.newMessageCounter.style.marginTop = '8px';
        this.newMessageCounter.style.marginLeft = '10%';
        this.newMessageCounter.style.visibility = 'hidden';
        this.newMessageCounter.style.color = 'red';
        this.newMessageCounter.innerHTML = '0 / 400';
        this.newMessageCounter.id = 'new-msg-cnt';
        
        this.container.appendChild(this.newMessageCounter);
    }

};
