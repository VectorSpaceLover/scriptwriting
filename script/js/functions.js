
var types = [
	'general', 
	'scene',
	'action',
	'character',
	'dialogue',
	'parenthetical',
	'transition',
	'shot',
	'new_list',
	'end_of_act'
];
var nextTypes = {
	general: 'general',
	scene: 'action',
	action: 'action',
	character: 'dialogue',
	dialogue: 'action',
	parenthetical: 'dialogue',
	transition: 'scene',
	shot: 'action',
	new_list: 'scene',
	end_of_act: 'action',
}
var typeButtonStr = {
	general: 'General',
	scene: 'Scene',
	action: 'Action',
	character: 'Character',
	dialogue: 'Dialogue',
	parenthetical: 'Parenthetical',
	transition: 'Transition',
	shot: 'Shot',
	new_list: 'New List',
	end_of_act: 'End of Act',
}
var StopPropagationMixin = {
	stopProp: function(event) {
		event.nativeEvent.stopImmediatePropagation();
	},
};

function cursorPos(element) {
	var caretOffset = 0;
	var doc = element.ownerDocument || element.document;
	var win = doc.defaultView || doc.parentWindow;
	var sel;
	if (typeof win.getSelection != "undefined") {
		sel = win.getSelection();
		if (sel.rangeCount > 0) {
			var range = win.getSelection().getRangeAt(0);
			var preCaretRange = range.cloneRange();
			preCaretRange.selectNodeContents(element);
			preCaretRange.setEnd(range.endContainer, range.endOffset);
			caretOffset = preCaretRange.toString().length;
		}
	} else if ( (sel = doc.selection) && sel.type != "Control") {
		var textRange = sel.createRange();
		var preCaretTextRange = doc.body.createTextRange();
		preCaretTextRange.moveToElementText(element);
		preCaretTextRange.setEndPoint("EndToEnd", textRange);
		caretOffset = preCaretTextRange.text.length;
	}
	return caretOffset;
};

function placeCaretAtEnd(el) {
	el.focus();
	if (typeof window.getSelection != "undefined"
			&& typeof document.createRange != "undefined") {
		var range = document.createRange();
		range.selectNodeContents(el);
		range.collapse(false);
		var sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(range);
	} else if (typeof document.body.createTextRange != "undefined") {
		var textRange = document.body.createTextRange();
		textRange.moveToElementText(el);
		textRange.collapse(false);
		textRange.select();
	}
}

function S4() {
   return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
}
function guid() {
   return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
}
function isEmpty(value){
	if(value == undefined || value == '' || value == {} || value == [] || !value){
		return true;
	}
	return false;
}

function log(values){
	var type = typeof(values);
	if(type == 'string')
		console.log(values)
	else if(type = 'object')
		console.log([...values]);
	else 
		console.log(values);
}
function trim(someText){
	return someText.replace(/(\r\n|\n|\r)/gm, "");
}

function removeTags(str) {
    if ((str===null) || (str===''))
        return false;
    else
        str = str.toString();
          
    // Regular expression to identify HTML tags in 
    // the input string. Replacing the identified 
    // HTML tag with a null string.
    return str.replace( /(<([^>]+)>)/ig, '');
}