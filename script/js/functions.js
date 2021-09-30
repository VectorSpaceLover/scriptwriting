
var types = [
	'general', 
	'scene',
	'action',
	'character',
	'dialogue',
	'parenthetical',
	'transition',
	'shot',
	'cast_list',
	'new_list',
	'end_of_act'
];
var nextTypes = {
	general: 'general',
	scene: 'action',
	action: 'character',
	character: 'dialogue',
	dialogue: 'character',
	parenthetical: 'character',
	transition: 'scene',
	shot: 'action',
	cast_list: 'cast_list',
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
	cast_list: 'Cast List',
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
