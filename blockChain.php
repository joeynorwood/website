<?php
require_once('./base.php');
echo $htmlGen->createPageBeforeContent('Blockchain');
?>

<h4>Blockchain</h4>
<p>This is a javascript implementation of the blockchain data structure.</p>

<script type='text/javascript'>

    window.onload = function(){
        
    }
    
    //this hash function taken from:
    //http://werxltd.com/wp/2010/05/13/javascript-implementation-of-javas-string-hashcode-method/
    function hash(str) {
        var hash = 0, i, chr;
        if (str.length === 0) {
            return hash;
        }
        for (i = 0; i < str.length; i++) {
            chr   = str.charCodeAt(i);
            hash  = ((hash << 5) - hash) + chr;
            hash |= 0; // Convert to 32bit integer
        }
        return hash;
    }
    
    function xmlCleanString(s) {
        return (s.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&apos;')
                .replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/\t/g, '&#x9;').replace(/\n/g, '&#xA;').replace(/\r/g, '&#xD;'));
    }
    
    class BlockChain {
        constructor(transPerBlock, hashFunc) {
            this.transPerBlock = transPerBlock;
            this.hashFunc = hashFunc;
            this.list = new Array();
            
            this.createNewBlock(true);
        }
        
        createNewBlock(first=false) {
            var block = {
                previousHash : 0,
                hash : null,
                transactions : new Array()
            };
            
            if(!first) {
                block.previousHash = this.getLastBlock().hash;
            };
            
            for(var i = 0; i < this.transPerBlock; i++){
                var trans = {
                    giverID : null,
                    receiverID : null,
                    amount : null
                };
                block.transactions.push(trans);
            }
            
            this.list.push(block);
        }
        
        getLastBlock() {
            return (this.list.length-1 >= 0) ? this.list[this.list.length-1] : null;
        }
        
        addTran(giverID, receiverID, amount) {
            var block = this.getLastBlock();
            var transIX = this.getNewTransIndex();
            
            if(transIX == -1) {
                block.hash = this.hashBlock(block);
                this.createNewBlock();
                var blockNew = this.getLastBlock();
                blockNew.previousHash = block.hash;
                block = blockNew;
                transIX = 0;
            }
            
            var tran = block.transactions[transIX];
            tran.giverID = giverID;
            tran.receiverID = receiverID;
            tran.amount = amount;
            
            block.hash = this.hashBlock(block);
        }
        
        getNewTransIndex(){
            var block = this.getLastBlock();
            for(var i = 0; i < this.transPerBlock; i++) {
                if(block.transactions[i].giverID === null) {
                    return i;
                }
            }
            return -1;
        }
        
        hashBlock(block){
            var data = '<BLOCK><PREV>' + block.previousHash.toString() + '</PREV>';
            for(var i = 0; i < this.transPerBlock; i++){
                var tran = block.transactions[i];
                if(tran.giverID === null){
                    break;
                }
                var gid = '<GID>' + xmlCleanString(tran.giverID.toString()) + '</GID>';
                var rid = '<RID>' + xmlCleanString(tran.receiverID.toString()) + '</RID>';
                var amt = '<AMT>' + xmlCleanString(tran.amount.toString()) + '</AMT>';
                data = data + '<TRAN>' + gid + rid + amt + '</TRAN>';
            }
            data = data + '</BLOCK>';
            return this.hashFunc(data);
        }
        
        printChain(){
            var HTML = '';
            for(var i = 0; i < this.list.length; i++){
                var block = this.list[i];
                HTML = HTML + '<table style="width: 80%; border:1px solid black; margin: auto;"><tr><td style="width:33.33%; margin:auto;">Block&nbsp;' + i.toString() + '</td><td style="width:33.33%; margin:auto;">Hash:&nbsp;' + block.hash + '</td><td style="width:33.33%; margin:auto;">Prev Hash:&nbsp;' + block.previousHash + '</td></tr>';
                HTML = HTML + '<tr><td>Giver ID</td>' + '<td>Receiver ID</td>' + '<td>Amount</td></tr>';
                for(var j = 0; j < block.transactions.length; j++){
                    var tran = block.transactions[j];
                    if(tran.giverID === null){
                        break;
                    }
                    var gid = '<td>' + xmlCleanString(tran.giverID.toString()) + '</td>';
                    var rid = '<td>' + xmlCleanString(tran.receiverID.toString()) + '</td>';
                    var amt = '<td>' + xmlCleanString(tran.amount.toString()) + '</td>';
                    HTML = HTML + '<tr>' + gid + rid + amt + '</tr>';
                }
                HTML = HTML + '</table>';
            }
            return HTML;
        }
        
    }
    
    var bc = new BlockChain(3, hash);
    bc.addTran('Joey', 'Caroline', 10);
    bc.addTran('Mange', 'Joey', 1.2);
    bc.addTran('Snowball', 'Alabastor', 9.8);
    bc.addTran('Caroline', 'Mange', 11.1);
    bc.addTran('Alabastor', 'Caroline', 5.467);
    bc.addTran('Echo', 'Pyong', 4.89);
    bc.addTran('Blaster', 'Hattie', 2.1);
    bc.addTran('Duckman', 'Jerry', 3.333);
    bc.addTran('Sassy', 'Caroline', 4.45);
    bc.addTran('Joey', 'Alabastor', 0.99);
    bc.addTran('Hattie', 'Moose', 1);
    bc.addTran('Jerry', 'Sassy', 10);
    bc.addTran('Sassy', 'Duckman', 6.29);
    
    document.getElementById('content').innerHTML = bc.printChain();
    
</script>
    

<?php
echo $htmlGen->createPageAfterContent();
?>