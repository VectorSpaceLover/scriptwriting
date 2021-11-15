
var Line = React.createClass({displayName: "Line",
	mixins: [ReactFireMixin, StopPropagationMixin, ReactRouter.State],
	getInitialState: function() {
		// console.log(this.props.line);
		return {
			// comments: this.props.line['comments'] || '',
			commenting: false,
			scriptId: this.getParams().scriptId,
			focused: false,
		};
	},
	componentWillMount: function() {
		this.bindAsObject(new Firebase("https://screenwrite.firebaseio.com/"+this.state.scriptId+"/lines/" + this.props.index), "line");
	},
	componentWillReceiveProps: function(newProps){
		// console.log('line received new props');
		// console.log(newProps);
	},
	handleChange: function(event) {
		var value = event.target.value;
		var type = this.props.line.type;

		if(type == 'action')
			value = this.capitalize(value);
		this.firebaseRefs.line.update({'text': value});
		// placeCaretAtEnd(this.refs.text.getDOMNode());
	},
	capitalize: function(str){

		var res = '';
		if(str.length > 0)
			res = str[0].toUpperCase() + str.substring(1);
		
		return res;
	},
	handleComment: function(event) {
		
		this.firebaseRefs.line.update({'comment':event.target.value});
		// this
	},
	nextType: function(){
		var index = types.indexOf(this.props.line.type) + 1;
		index = (index < types.length) ? index : 0;
		this.setType(types[index]);
	},
	prevType: function() {
		var index = types.indexOf(this.props.line.type) - 1;
		index = (index >= 0) ? index : types.length - 1;
		this.setType(types[index]);
	},
	setType: function(type) {
		this.firebaseRefs.line.update({type:type});
	},
	handleKey: function(event) {
		//111
		// console.log('first handle key');
		// placeCaretAtEnd(this.refs.text.getDOMNode());
		switch (event.keyCode) {
			case 39: // right
				if (~['character', 'scene'].indexOf(this.props.line.type) && cursorPos(event.target) >= event.target.textContent.length) {
					var suggestion;
					if (suggestion = this.props.getSuggestion(this.props.index)) {
						this.firebaseRefs.line.update({ text: this.props.line.text + suggestion }, (function(){
							// placeCaretAtEnd(this.refs.text.getDOMNode());
						}).bind(this));
					}
				}
				break;
			case 13: // enter
				event.preventDefault();
				if (this.props.line.text) {
					break;
				}
			case 9: // tab
				event.preventDefault();
				if (event.shiftKey) {
					this.prevType();
				} else {
					this.nextType();
				}
		}
		
		this.props.onKeyDown(event, this.props.line, this.props.index, this.props.previous, this.props.prevPrevious);
		// placeCaretAtEnd(this.refs.text.getDOMNode());
	},
	comment: function(event) {
		event.stopPropagation();
		this.setState({ commenting: !this.state.commenting }, function(){
			if (this.state.commenting) {
				var that = this;
				document.addEventListener('click', function listener(){
					that.setState({ commenting: false });
					document.removeEventListener('click', listener);
				});
				this.refs.commentBox.getDOMNode().focus();
			}
		});
	},
	focus: function(atEnd) {
		if (atEnd)
			placeCaretAtEnd(this.refs.text.getDOMNode());
		else
			this.refs.text.getDOMNode().focus();
	},
	onFocus: function(event) {
		this.setState({focused:true});
		this.props.onFocus(event);
	},
	onBlur: function(event) {
		this.setState({focused:false});
	},
	render: function() {
		var classes = {
			line: true,
			// commented: this.props.line.comment,
			commented: '',
			highlight: highlight && this.props.line.text && highlight.toUpperCase()==this.props.line.text.toUpperCase()
		};
		// console.log(this.props.line);
		var type = trim(this.props.line.type)
		if(type == undefined || type == '') return;
		classes[type] = true;
		classes = React.addons.classSet(classes);

		var line, suggest;
		if (this.props.readonly) {
			line = React.createElement("div", {className: "line-text", dangerouslySetInnerHTML: {__html: this.props.line.text}});
		} else {
			if (this.state.focused) {
				suggest = this.props.getSuggestion(this.props.index);
			}

			line = React.createElement(ContentEditable, {
					ref: "text", 
					html: this.props.line.text, 
					onChange: this.handleChange, 
					onKeyDown: this.handleKey, 
					onFocus: this.onFocus, 
					onBlur: this.onBlur, 
					suggest: suggest, 
					className: "line-text"})
		}

		return (
			React.createElement("li", {className: classes}, 
				line, 
				React.createElement("a", {onClick: this.comment, className: "comment-add"}, 
					React.createElement("i", {className: "glyphicon glyphicon-comment"})
				), 

				this.state.commenting && React.createElement(ContentEditable, {
					ref: "commentBox", 
					onChange: this.handleComment, 
					onClick: this.stopProp, 
					className: "comment-box", 
					html: this.props.line.comment})
			)
		);
	}
});