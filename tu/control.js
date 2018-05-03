
function pageLoad() {
    var navHidden = getCookie('TU_navHidden');
    if(navHidden == '1'){
        hideNav(true);
    }
}

//this will hide and display the navigation menu
function hideNav(forceHide=false) {
    var nav = document.getElementById('leftNav');
    var expand = document.getElementById('expand_nav');
    var mainW = document.getElementById('mainWindow');
    var foot = document.getElementById('foot');
    
    if(nav.style.display === 'none' && !forceHide){
        setCookie('TU_navHidden', '0', 2);
        
        nav.style.display = 'block';
        expand.style.left = '13%';
        
        mainW.style.width = '85.5%';
        mainW.style.marginLeft = '14.5%';
        
        foot.style.width = '85.5%';
        foot.style.marginLeft = '14.5%';
    }
    else {
        setCookie('TU_navHidden', '1', 2);
        
        nav.style.display = 'none';
        expand.style.left = '0';
        
        mainW.style.width = '90%';
        mainW.style.marginLeft = '5%';
        
        foot.style.width = '98.5%';
        foot.style.marginLeft = '1.5%';
    }
}

//this function courtesy of w3 schools, with minor modifications
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

//this function courtesy of w3 schools, with minor modifications
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

//this function courtesy of w3 schools, with minor modifications
function sortTable(n, tableID) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById(tableID);
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc"; 
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.getElementsByTagName("TR");
        /*Loop through all table rows (except the
        first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
            one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /*check if the two rows should switch place,
            based on the direction, asc or desc:*/
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch= true;
                break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch= true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            //Each time a switch is done, increase this count by 1:
            switchcount ++; 
        } else {
            /*If no switching has been done AND the direction is "asc", set the direction to "desc" and run the while loop again.*/
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}

//this will hide and display ALL definition tables on the user profile
function expandAllProfileAssignments() {
    var tbls = document.getElementsByClassName('def_tbl');
    var icons = document.getElementsByClassName('exp_icon');
    
    for(var i = 0; i < tbls.length; i++){
        expandProfileAssignment(tbls[i].id, true);
    }
    
    for(var i = 0; i < icons.length; i++){
        icons[i].innerHTML = '-';
    }
}

//this will hide and display definition tables on the user profile
function expandProfileAssignment(id, forceShow=false) {
    var tbl = document.getElementById(id);
    var btn = document.getElementById(id + '_icon');
    
    if(!(tbl == null)){
        if(tbl.style.display === 'none' || tbl.style.display === '' || forceShow){
            tbl.style.display = 'table';
            btn.innerHTML = '-';
        }
        else {
            tbl.style.display = 'none';
            btn.innerHTML = '+';
        }
    }
    else{
        if(btn.innerHTML == '+'){
            btn.innerHTML = '-';
        }
        else{
            btn.innerHTML = '+';
        }
    }

}

