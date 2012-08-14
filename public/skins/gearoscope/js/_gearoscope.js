function pager(pager, gear) {     
    
    $('#comments').html("<div id='loading'></div>");
    $('#comments').load('/_gearoscope/hu/comments/gear/format/html/', {
        page: pager,
        id: gear
    });    
       
}