
var ContentEditable = React.createClass({displayName: "ContentEditable",
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
		var html = this.getDOMNode().innerHTML;
		if (this.props.onChange && html !== this.lastHtml) {

			this.props.onChange({
				target: {
					value: html
				}
			});
		}
		this.lastHtml = html;
	},
	render: function(){
		return React.createElement("div", {
			ref: "input", 
			onInput: this.emitChange, 
			onBlur: this.emitChange, 
			onKeyDown: this.props.onKeyDown, 
			onClick: this.props.onClick, 
			className: this.props.className, 
			onFocus: this.props.onFocus, 
			onBlur: this.props.onBlur, 
			onPaste: this.stripPaste, 
			"data-suggest": this.props.suggest, 
			contentEditable: true, 
			dangerouslySetInnerHTML: {__html: this.props.html}});
	}
});