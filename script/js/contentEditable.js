
var ContentEditable = React.createClass({displayName: "ContentEditable",
getInitialState: function() {

	return {
		elementId: this._rootNodeID,
		currentPos: 0,
		currentHtml: ''
	};
},
stripPaste: function(e){
	// Strip formatting on paste
	var tempDiv = document.createElement("DIV");
	var item = _.findWhere(e.clipboardData.items, { type: 'text/plain' });
	item.getAsString(function (value) {
		tempDiv.innerHTML = value;
		document.execCommand('inserttext', false, tempDiv.innerText);
	});
	e.preventDefault();
},
emitChange: function(){
	var nCurPos = cursorPos(this.getDOMNode());
	var html = this.getDOMNode().innerHTML;
	this.setState({ currentPos: nCurPos });
	if (this.props.onChange && html !== this.lastHtml) {

		this.props.onChange({
			target: {
				value: html
			}
		});
	}
	this.lastHtml = html;
},
setCursorAfterElement: function(){
	try {
		const el = document.getElementById('editable' + this.state.elementId);
		if (!el) return;
		const range = document.createRange();
		const sel = window.getSelection();
		if (!el.childNodes[0]) return;
		range.setStart(el.childNodes[0], this.state.currentPos);
		range.collapse(true);
		sel.removeAllRanges();
		sel.addRange(range);
		el.focus();
		
	} catch (err) {
		console.log(err.message);
	}
},

componentDidUpdate: function(prevProps, prevState) {
	if (this.props.html !== this.state.currentHtml) {
		this.setState({ currentHtml: this.props.html });
	}
	if (prevState.currentHtml !== this.state.currentHtml) {
		this.setCursorAfterElement();
	}
},

render: function(){

	return React.createElement("div", {
		id: 'editable'+this.state.elementId,
		ref: "input", 
		onInput: this.emitChange, 
		onBlur: this.emitChange, 
		onKeyDown: this.props.onKeyDown, 
		className: this.props.className, 
		onFocus: this.props.onFocus, 
		onBlur: this.props.onBlur, 
		onPaste: this.stripPaste, 
		"data-suggest": this.props.suggest, 
		contentEditable: true,
		autoFocus: true,
		dangerouslySetInnerHTML: {__html: this.state.currentHtml }});
}
});