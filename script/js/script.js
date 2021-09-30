var Script = React.createClass({displayName: "Script",
	mixins: [ReactFireMixin, ReactRouter.State],
	getInitialState: function() {
		highlight = '';

		return {
			scriptId: this.getParams().scriptId,
			action: this.getParams().action,
			script: {},
			editing: {},
			flag:'fasle',
		};
	},
	componentWillMount: function() {
		this.loadScript();
	},
	componentWillReceiveProps: function() {
		this.loadScript();
		var self = this;

		setTimeout(function(){
			console.log('component received props before 1s');
			self.forceUpdate();
		}, 2000);
	},
	componentDidMount: function() {
		var self = this;
		// console.log('component mounted');
		console.log(this.state.scriptId);
		setTimeout(function(){
			console.log('component mounted before 2s');
			self.forceUpdate();
		}, 2000);
	},
	loadScript: function() {
		if (this.firebaseRefs.script) this.unbind('script');
		this.bindAsObject(new Firebase("https://screenwrite.firebaseio.com/"+this.getParams().scriptId), "script");	
		// CLEANUP OLD DATA
		var fb = new Firebase("https://screenwrite.firebaseio.com/"+this.state.scriptId);
		fb.once('value', (function(snapshot){
			if (!snapshot.val()) {
				fb.set({});
				var newLine = fb.child('lines').push({ type: 'scene' });
				fb.update({ firstLine: newLine.key() });
				return;
			}
			if (snapshot.val().firstLine) return;
			var previous, previousIndex;
			fb.update({firstLine: '0'});
			_.each(snapshot.val().lines, function(line, index) {
				if (previous) {
					fb.child('lines/'+previousIndex+'/next').set(index);
				}
				previous = line;
				previousIndex = index;
			});
		}).bind(this));

		window.onunload = (function(){
			if (_.keys(this.state.script.lines).length <= 2)
				fb.remove();
		}).bind(this);
	},
	editing: function(line) {
		this.setState({editing:line});
	},
	getSuggestion: function(lineIndex, fromValue) {
		if (!this.state.script.lines[lineIndex].text) return '';
		var type = this.state.script.lines[lineIndex].type;
		var text = fromValue && fromValue.toUpperCase() || this.state.script.lines[lineIndex].text.toUpperCase();

		var suggestions = [];
		var passed = false;
		var iterate = (function(index){
			var line = this.state.script.lines[index];
			if (line.type == type
				&& line.text
				&& line.text.length > text.length
				&& line.text.toUpperCase().indexOf(text) === 0)
				suggestions.push(line.text.toUpperCase());
			if (index == lineIndex)
				passed = true;
			if (passed && suggestions.length) return;
			if (line.next)
				iterate(line.next);
		}).bind(this);
		iterate(this.state.script.firstLine);
		return (suggestions.pop() || '').substr(text.length);
	},
	handleKey: function(event, line, index, prevIndex, prevPrevIndex) {
		// console.log(line);console.log(event.keyCode);
		///222
		// console.log('second handle key');
		// placeCaretAtEnd(this.refs.text.getDOMNode());
		switch (event.keyCode) {
			case 38: // up
				if (prevIndex) {
					if (event.metaKey || event.ctrlKey) {
						// [a, b, C, d] => [a, C, b, d]
						// A points to C
						if (prevPrevIndex)
							this.firebaseRefs.script.child('lines/'+prevPrevIndex).update({next: index});
						else
							this.firebaseRefs.script.update({firstLine:index});
						// C points to B
						var newNext = line.next;
						this.firebaseRefs.script.child('lines/'+index).update({next: prevIndex });
						// B points to D
						if (line.next)
							this.firebaseRefs.script.child('lines/'+prevIndex).update({next: newNext });
						else
							this.firebaseRefs.script.child('lines/'+prevIndex+'/next').remove();
						this.refs['line'+index].focus(true);
						event.preventDefault();
					} else if (!cursorPos(event.target)) {
						this.refs['line'+prevIndex].focus(true);
						event.preventDefault();
					}
				}
				break;
			case 40: // down
				if (line.next) {
					
					if (event.metaKey || event.ctrlKey) {
						// [a, b, c, d] => [a, c, b, d]

						// A points to C
						if (prevIndex)
							this.firebaseRefs.script.child('lines/'+prevIndex).update({next: line.next});
						else
							this.firebaseRefs.script.update({firstLine:line.next});
						var newNext = this.state.script.lines[line.next].next;
						// C points to B
						this.firebaseRefs.script.child('lines/'+line.next).update({next: index});
						// B points to D
						if (newNext)
							this.firebaseRefs.script.child('lines/'+index).update({ next: newNext });
						else
							this.firebaseRefs.script.child('lines/'+index+'/next').remove();
						this.refs['line'+index].focus();
						event.preventDefault();
					} else if (cursorPos(event.target) >= event.target.textContent.length ) {
						this.refs['line'+line.next].focus();
						event.preventDefault();
					}
				}
				break;
			case 8: // backspace
				if (!line.text && prevIndex) {
					// update previous line
					if (line.next)
						this.firebaseRefs.script.child('lines/'+prevIndex).update({next:line.next});
					else
						this.firebaseRefs.script.child('lines/'+prevIndex+'/next').remove();

					// remove line
					this.firebaseRefs.script.child('lines/'+index).remove();
					this.refs['line'+prevIndex].focus(true);
					event.preventDefault();
				}
				break;
			case 13: // enter
				// console.log(event);
				// for(var i=0; i<lines.length;i++){
				// }
				if (line.text) {
					// create new line pointing to current line's `next`

					var newItem = { type: nextTypes[line.type] };
					if (line.next) newItem.next = line.next;
					newRef = this.firebaseRefs.script.child('lines').push(newItem);
					// point current line to the new line
					this.firebaseRefs.script.child('lines/'+index+'/next').set(newRef.key());
					setTimeout((function(){
						this.refs['line'+newRef.key()].focus();
					}).bind(this));
				}
		}
		// placeCaretAtEnd(this.refs.text.getDOMNode());
	},
	setScript: function(extension, result){

		// console.log(extension);
		
		var exts = ['html', 'xml', 'pdf'];
		if(exts.indexOf(extension) == -1){
			alert('Import file correctly');
			return;
		}

		if (this.firebaseRefs.script) this.unbind('script');
		var scriptId = new Date().getTime();
		var doc;
		var type = 'text/' + extension;
		var doc = new DOMParser().parseFromString(result, type);
		
		// console.log(doc);

		var lines;
		if(extension == 'html')
			lines = doc.getElementsByTagName('li')
		else if(extension == 'xml')
			lines = doc.getElementsByTagName('element');

		// console.log(lines); 
		
		this.bindAsObject(new Firebase('https://screenwrite.firebaseio.com/' + scriptId), 'script');
		var fb = new Firebase('https://screenwrite.firebaseio.com/' + scriptId);
		// var fb = new Firebase('https://screenwrite.firebaseio.com/');
		// var newRef = fb.push();

		fb.once('value',(function(snapshot){	
			// console.log('once value ');
			var length = lines.length;
			// var length = 10;
			
			// console.log(length);

			fb.set({});
			var newLine = fb.child('lines').push({type:'scene', text: new Date().toLocaleDateString()});
			fb.update({firstLine: newLine.key()});

			var previous = newLine, previousIndex = newLine.key();

			var type, value, line;
			for(var i = 0; i < length; i++){

				if(extension == 'html'){
					type = lines[i].getAttribute('type');
					value = lines[i].firstElementChild.innerHTML;
				}else if(extension == 'xml'){
					// type = lines[i].getElementsByTagName('type')[0].nodeValue;
					// value = lines[i].getElementsByTagName('value')[0].nodeValue;
					type = lines[i].getElementsByTagName('type')[0].innerHTML;
					value = lines[i].getElementsByTagName('value')[0].innerHTML;
					// console.log(type, value);
					// console.log(lines[i].getElementsByTagName('type')[0].innerHTML);
				}

				line = fb.child('lines').push({type: type, text: value});
				var index = line.key();
				if(previous){
					fb.child('lines/'+previousIndex+'/next').set(index);
				}
				previous = line;
				previousIndex = index;
			}
		}).bind(this));
		window.location.hash = '#/' + scriptId;
	},
	render: function() {
		var indexes = {};
		var lines = [];
		var newLines = [];
		var previous = null, prevPrevious = null;
		var next = (function(line, index){
			lines.push(
				React.createElement(Line, {line: line, key: index, index: index, ref: 'line'+index, 
					previous: previous, prevPrevious: prevPrevious, 
					onFocus: this.editing.bind(this, index), 
					getSuggestion: this.getSuggestion, 
					readonly: this.state.action == 'view', 
					onKeyDown: this.handleKey})
			);
			prevPrevious = previous;
			previous = index;
			if (line.next) next(this.state.script.lines[line.next], line.next);
		}).bind(this);


		if (this.state.script && this.state.script.lines && this.state.script.firstLine) {
			next(this.state.script.lines[this.state.script.firstLine], this.state.script.firstLine);
		} else {
			lines = React.createElement("h1", {className: "text-center"}, "Loading Script...")
		}
		var getStyle = function(e, styleName) {
		  var styleValue = "";
		  if (document.defaultView && document.defaultView.getComputedStyle) {
		    styleValue = document.defaultView.getComputedStyle(e, "").getPropertyValue(styleName);
		  } else if (e.currentStyle) {
		    styleName = styleName.replace(/\-(\w)/g, function(strMatch, p1) {
		      return p1.toUpperCase();
		    });
		    styleValue = e.currentStyle[styleName];
		  }
		  return styleValue;
		}
		var px2digit = function(px){
			return Number(px.split('px')[0]);
		}
		var linesForPage = [[]];
		
		// console.log('render ' + lines.length +' s lines');
		if(lines.length>0){
			// console.log('lines length bigger than 0');
			let height = 0;
			let index = 0;

			var h, mt, mb, t, pb, query, nodes, node;
			
			for(var i = 0; i<lines.length;i++){
				query = '[data-reactid=".0.2.1.$' + lines[i].key + '"]';
				nodes = document.querySelectorAll(query);
				node = nodes[0];
				// console.log(node);
				if(node != undefined){
					h = px2digit(getStyle(node, 'height'));
					mt = px2digit(getStyle(node, 'margin-top'));
					mb = px2digit(getStyle(node, 'margin-bottom'));
					height += (h + mt + mb);

					let cl = node.getAttribute('class');
					if(cl.indexOf('new_list') > -1){
						 var h = (1056 - height) + 'px';
						var splitter = React.createElement('div', {className:'next'});
						var spacer = React.createElement('div', { style: {height: h, border: 'none', width: '816px'}});
						newLines.push(spacer);
						newLines.push(splitter);
						height = 0;
					}

					
				}else{

				}
				
				if(height > 1056){
					height = 0;
					index ++;
					linesForPage[index] = [];

					
					
					var splitter = React.createElement('div', {className:'next'});
					newLines.push(splitter);
				}else{

				}
				// console.log(index);
				linesForPage[index].push(lines[i]);
				newLines.push(lines[i]);
			}
		}

		var cm = [...Array(9).keys()];
		var mm = [...Array(10).keys()];

		return (
			React.createElement("div", {style: {background: '#ddd'}}, 
				React.createElement(Navbar,{}),
				React.createElement(Nav, {
					script: this.state.script, 
					editingIndex: this.state.editing, 
					readonly: this.state.action=='view',
					setScript: this.setScript.bind(this),
				}),
				React.createElement('div',{style: {width: '816px', margin: 'auto'}},
					React.createElement('div',{className: 'ruler'},
						cm.map(() => {
							return React.createElement('div', {className: 'cm'},
								mm.map(()=>{
									return React.createElement('div', {className: 'mm'});
								})
							);
						}),
					),
					React.createElement("ul", {className: "script", style: {minHeight: '1056px', background: 'white'}}, newLines)
				)
			)
		)
	}
});

var highlight = '';
