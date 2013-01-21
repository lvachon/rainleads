var sourceobj=Object();
var previewobj=Object();

function setupRT(source, preview){
	sourceobj = source;
	previewobj = preview;
	setTimeout('updatePreview()',500);
}

function updatePreview(){
	previewobj.innerHTML = String(sourceobj.value).replace(/\n/gm,"<br/>\n");
	setTimeout('updatePreview()',500);
}

function addTag(tag){
	t = String(sourceobj.value);
	pos = get_selection(sourceobj.id);
	out = t.substring(0,pos.start) + "<"+tag+">" + pos.text + "</"+tag+">" + t.substring(pos.end);
	sourceobj.value=out;
	set_selection(sourceobj.id,pos.start+2+String(tag).length,pos.start+2+String(tag).length+pos.length);
	updatePreview();
}


function addLink(){
	var url = prompt("Enter the URL for the link");
	if(String(url).toLowerCase().substring(0,7)!="http://" && String(url).toLowerCase().substring(0,8)!="https://"){
		url = "http://"+url
	}
	t = String(sourceobj.value);
	pos = get_selection(sourceobj.id);
	out = t.substring(0,pos.start) + "<a href='"+url+"'>" + pos.text + "</a>" + t.substring(pos.end);
	sourceobj.value=out;
	set_selection(sourceobj.id,pos.start+11+String(url).length,pos.start+11+String(url).length+pos.length);
	updatePreview();
}


//Copypasta from stackoverflow, I'm not dealing with IE's bullshit myself.
//Seriously, look at that crap.  These functions could be 10 lines total, but instead they take up almost 60.


function get_selection(the_id)
{
    var e = document.getElementById(the_id);

    //Mozilla and DOM 3.0
    if('selectionStart' in e)
    {
        var l = e.selectionEnd - e.selectionStart;
        return { start: e.selectionStart, end: e.selectionEnd, length: l, text: e.value.substr(e.selectionStart, l) };
    }
    //IE
    else if(document.selection)
    {
        e.focus();
        var r = document.selection.createRange();
        var tr = e.createTextRange();
        var tr2 = tr.duplicate();
        tr2.moveToBookmark(r.getBookmark());
        tr.setEndPoint('EndToStart',tr2);
        if (r == null || tr == null) return { start: e.value.length, end: e.value.length, length: 0, text: '' };
        var text_part = r.text.replace(/[\r\n]/g,'.'); //for some reason IE doesn't always count the \n and \r in the length
        var text_whole = e.value.replace(/[\r\n]/g,'.');
        var the_start = text_whole.indexOf(text_part,tr.text.length);
        return { start: the_start, end: the_start + text_part.length, length: text_part.length, text: r.text };
    }
    //Browser not supported
    else return { start: e.value.length, end: e.value.length, length: 0, text: '' };
}

function set_selection(the_id,start_pos,end_pos)
{
    var e = document.getElementById(the_id);

    //Mozilla and DOM 3.0
    if('selectionStart' in e)
    {
        e.focus();
        e.selectionStart = start_pos;
        e.selectionEnd = end_pos;
    }
    //IE
    else if(document.selection)
    {
        e.focus();
        var tr = e.createTextRange();

        //Fix IE from counting the newline characters as two seperate characters
        var stop_it = start_pos;
        for (i=0; i < stop_it; i++) if( e.value[i].search(/[\r\n]/) != -1 ) start_pos = start_pos - .5;
        stop_it = end_pos;
        for (i=0; i < stop_it; i++) if( e.value[i].search(/[\r\n]/) != -1 ) end_pos = end_pos - .5;

        tr.moveEnd('textedit',-1);
        tr.moveStart('character',start_pos);
        tr.moveEnd('character',end_pos - start_pos);
        tr.select();
    }
    return get_selection(the_id);
}	