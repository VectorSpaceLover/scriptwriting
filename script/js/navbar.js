
var Navbar = React.createClass({displayName: 'Navbar',
	mixins: [ReactFireMixin, StopPropagationMixin, ReactRouter.State],
	getInitialState: function(){
		return {
			dropdowns: '',
			open: '',
			scriptId: this.getParams().scriptId,
			script: {},
			title: '',
			lines: [],
			// setScript: this.setScript.bind(this),
		};
	},
	componentWillMount: function() {
		// this.props.loadScript();
		this.loadScript();
	},
	componentDidMount: function() {
		this.getFromDatabase(this.state.scriptId);
		
		var self = this;

		setTimeout(function(){
			log('componene mounted before 2 seconds - navbar.js');
			self.forceUpdate();
		}, 2000);
	},
	componentWillReceiveProps: function() {
		// this.props.loadScript();
		this.loadScript();
	},
	getFromDatabase: function(){
		// send script data to server
		// this.state.script.title
		var self = this;
		$.get("script_create.php",
		  {
		    scriptId: this.state.scriptId,
		    function: 'getScript'
		  },
		  function(data, status){
		  	if(data != 'error')
		  	{
		  		self.setState({title: data.title});
		    	self.setScript('xml', data.contents);
		  	}
		  });
	},
	loadScript: function() {
		console.log('load script - navbar.js');
		if (this.firebaseRefs.script) this.unbind('script');
		this.bindAsObject(new Firebase("https://screenwrite.firebaseio.com/"+this.getParams().scriptId), "script");	
		// CLEANUP OLD DATA
		var fb = new Firebase("https://screenwrite.firebaseio.com/"+this.state.scriptId);
		fb.once('value', (function(snapshot){
			if (!snapshot.val()) {
				log('snapshot value is null');
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

	setScript: function(extension, result){
		
		console.log('set script in navbar.js');
		var lines, title, authors, email = '', type, doc, exts = ['html', 'xml', 'pdf'];

		if(exts.indexOf(extension) == -1){
			alert('Import file correctly');
			return;
		}

		type = 'text/' + extension;
		doc = new DOMParser().parseFromString(result, type);

		if(extension == 'html'){
			lines = doc.getElementsByTagName('li');
		}			
		else if(extension == 'xml'){
			lines = doc.getElementsByTagName('element');
		}	
		title = doc.getElementsByTagName('scripttitle')[0].innerHTML;
		authors = doc.getElementsByTagName('authors')[0].innerHTML;
		email = doc.getElementsByTagName('email')[0].innerHTML;		


		if (this.firebaseRefs.script) this.unbind('script');

		this.bindAsObject(new Firebase('https://screenwrite.firebaseio.com/' + this.state.scriptId), 'script');
		fb = new Firebase('https://screenwrite.firebaseio.com/' + this.state.scriptId);

		fb.once('value',(function(snapshot){	

			var length = lines.length;
			var type, value, line;

			fb.set({title, authors, email});

			if(extension == 'html'){
				type = trim(lines[0].getAttribute('type'));
				value = lines[0].firstElementChild.innerHTML;
			}else if(extension == 'xml' && length > 0){
				type = trim(lines[0].getElementsByTagName('type')[0].innerHTML);
				value = lines[0].getElementsByTagName('value')[0].innerHTML;
			}
			// console.log(this.firebaseRefs.script.title); return;
			var newLine = fb.child('lines').push({type: type, text: value});
			fb.update({firstLine: newLine.key()});
			// fb.update({firstLine: newRef.key()});

			// var previous = previousIndex = '';
			var previous = newLine, previousIndex = newLine.key();

			for(var i = 1; i < length; i++){

				if(extension == 'html'){
					type = lines[i].getAttribute('type');
					value = lines[i].firstElementChild.innerHTML;
				}else if(extension == 'xml'){
					type = lines[i].getElementsByTagName('type')[0].innerHTML;
					value = lines[i].getElementsByTagName('value')[0].innerHTML;
				}

				line = fb.child('lines').push({type: type, text: value, next: ''});
				var index = line.key();
				if(previous){
					fb.child('lines/'+previousIndex+'/next').set(index);
				}
				previous = line;
				previousIndex = index;
			}
			// line.
		}).bind(this));
		window.location.hash = '#/' + this.state.scriptId;
		// window.location.reload();
	},

	import: function(event) {
		
		var reader = new FileReader;
		var self = this;
		
		var extension;
		reader.onload = function(){
			self.setScript(extension, this.result);
 		}
		if(event.target.files.length > 0){
			var file = event.target.files[0];	
			extension = file.name.split('.').pop();
			reader.readAsText(file);
		}
	},
	download: function(strData, strFileName, strMimeType){
		var D = document,
	        A = arguments,
	        a = D.createElement("a"),
	        d = A[0],
	        n = A[1],
	        t = A[2] || "text/plain";

	    //build download link:
	    a.href = "data:" + strMimeType + "charset=utf-8," + escape(strData);


	    if (window.MSBlobBuilder) { // IE10
	        var bb = new MSBlobBuilder();
	        bb.append(strData);
	        return navigator.msSaveBlob(bb, strFileName);
	    } /* end if(window.MSBlobBuilder) */



	    if ('download' in a) { //FF20, CH19
	        a.setAttribute("download", n);
	        a.innerHTML = "downloading...";
	        D.body.appendChild(a);
	        setTimeout(function() {
	            var e = D.createEvent("MouseEvents");
	            e.initMouseEvent("click", true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
	            a.dispatchEvent(e);
	            D.body.removeChild(a);
	        }, 66);
	        return true;
	    }; /* end if('download' in a) */



	    //do iframe dataURL download: (older W3)
	    var f = D.createElement("iframe");
	    D.body.appendChild(f);
	    f.src = "data:" + (A[2] ? A[2] : "application/octet-stream") + (window.btoa ? ";base64" : "") + "," + (window.btoa ? window.btoa : escape)(strData);
	    setTimeout(function() {
	        D.body.removeChild(f);
	    }, 333);
	    return true;
	},

	makeXml: function(){

		let title = this.state.script.title;
		let lines = this.state.script["lines"];
		let firstLine = this.state.script["firstLine"];

		let authors = this.state.script.authors || '';
		// let leftAddress = this.state.script.leftAddress || '';
		let email = this.state.script.email || '';

		if(isEmpty(title) || isEmpty(lines) || isEmpty(firstLine)){
			alert('Input your script correctly.');

			return '';
		}else{
			let xmlbody = "<?xml version=\"1.0\" encoding=\"utf-8\"?><!DOCTYPE base>\n<base>\n";
			xmlbody += '<scripttitle>' + title + '</scripttitle>';
			xmlbody += '<firstLine>'+ firstLine +'</firstLine>'
					+ '<authors>'+ authors +'</authors>'
					// + '<leftAddress>' + leftAddress + '</leftAddress>'
					+ '<email>' + email + '</email>';
			
			while(firstLine != undefined && firstLine != '')
			{
				
				let line = lines[firstLine];
				log([firstLine, line])
				firstLine = line["next"];
				let type = trim(line["type"]);
				xmlbody = xmlbody + "<element>\n" + "<type>\n" + type + "\n</type>\n" + "<value>\n" + removeTags(line["text"]) + "\n</value>\n";
				if(line["comment"] != undefined)
				{
					xmlbody = xmlbody + "<comment>\n" + line["comment"] + "\n</comment>\n";
				}
				xmlbody = xmlbody + "</element>\n"
			}
			xmlbody += "</base>";
			return xmlbody;
		}
	},
	export2html: function(){
		log('export to html');
		// log(this.state.script);
		console.log(this.state.script);
		// var html = '';
		var html = '<html>' + 
					'<head>' +
					'<title>Screenwriter: ttttt</title>' +
					'<meta name="viewport" content="initial-scale=1, maximum-scale=1">' +
					
					'<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">' +
					'<link rel="stylesheet" href="styles.css">' +
					'<link rel="stylesheet" href="print.css">' +
					'</head>' +
					'<body>' +
					'<div id="container" class="container">' +
						'<div data-reactid=".0">' +
						'<div data-reactid=".0.0">' +
					
							'<header class="" data-reactid=".0.0.1">' +
								'<p class="uppercase" data-reactid=".0.0.1.0"></p>' +
								'<h1>ttttt</h1>' +
								'<p data-reactid=".0.0.1.2"></p>' +
								'<span data-reactid=".0.0.1.3"></span>' +
								'<address class="text-left" data-reactid=".0.0.1.4"></address>' +
								'<address class="text-right" data-reactid=".0.0.1.5"></address>' +
								'<scripttitle>' + this.state.script.title + '</scripttitle>' + 
								'<authors>' + this.state.script.authors+ '</authors>' +
								'<email>' + this.state.script.email+ '</email>' +
							'</header>' +
						'</div>' +
						'<ul class="script" data-reactid=".0.1">';
		var script = this.state.script

		// Object.keys(script).forEach(function(key){
		// 	var type = typeof(script[key]);
		// 	if(type == 'string'){
		// 		html += '<li type = "'+ key + '" >';
		// 		html += script[key];
		// 		html += '</li>'
		// 	}else if(type == 'object'){
		// 		Object.keys(script[key]).forEach(function(id){
		// 			var line = script[key][id];
		// 			html += '<li class="line ' + line['type'] +  '" type = "'+ line['type'] + '" >' +
		// 						'<div class="line-text" contenteditable="true" data-reactid=".0.1.$-MkKJ604grNQVI7okL_I.0">' + line['text'] + '</div>' +
		// 						'<a class="comment-add" data-reactid=".0.1.$-MkKJ604grNQVI7okL_I.1">' +
		// 							'<i class="glyphicon glyphicon-comment" data-reactid=".0.1.$-MkKJ604grNQVI7okL_I.1.0"></i>' +
		// 						'</a>' +
		// 					'</li>';

		// 		})
		// 	}
		// })
		var length = this.state.script.lines.length;
		var line;
		var keys = Object.keys(script.lines);
		var length = keys.length;
		if(length > 0)
			for(var i = 0; i < length; i++){
				line = this.state.script.lines[keys[i]];

				html += '<li class="line ' + line['type'] +  '" type = "'+ line['type'] + '" >' +
							'<div class="line-text" contenteditable="true" data-reactid=".0.1.$-MkKJ604grNQVI7okL_I.0">' + line['text'] + '</div>' +
							'<a class="comment-add" data-reactid=".0.1.$-MkKJ604grNQVI7okL_I.1">' +
								'<i class="glyphicon glyphicon-comment" data-reactid=".0.1.$-MkKJ604grNQVI7okL_I.1.0"></i>' +
							'</a>' +
						'</li>';
			}
		html += 		'</ul>' +
					'</div>' +
				'</div>' +
			'</body>' +
			'</html>';
		
		this.download(html,(new Date().getTime())+'.html','application/octet-stream');
	},
	export2xml: function(){
		let data = this.makeXml();
		if(data)
			this.download(data,(new Date().getTime())+'.xml','application/octet-stream');
	},

	export2pdf: function(){
		window.print();
	},
	newScript: function(){
		var fb = new Firebase("https://screenwrite.firebaseio.com/");
		var newRef = fb.push();

		window.location.hash = '#/' + newRef.key();
		window.location.reload(); // force firebase to reload
	},
	Save: function(){
		const SAVE_SUCCESSED = 1;
		const UPDATE_SUCCESSED = 2;
		const SAVE_FAIELD = 0;

		var mytitle = this.state.script.title;
		if(isEmpty(mytitle)){
			alert('Input your title correctly.');
			return;
		}else{
			let saveData =  {
				title: mytitle,
				scriptId: this.state.scriptId,
				content: this.makeXml(),
				function: 'saveScripts'
			};
			$.post("script_create.php",
				saveData,
				function(res, status){
					  if(res == 1){
						alert('Script save successed.');
					}else{
						alert('Script save failed.');
					}
				});
		}
		
		
		
	},
	handleTitleChange: function(input, event){
		this.firebaseRefs.script.child(input).set(event.target.value);
	},
	toggle: function(input, event){
		var that = this;
		if (this.state.open != input) {
			setTimeout((function(){
				document.addEventListener('click', function listener(e){
					e.preventDefault();
					that.setState({ open: false });
					document.removeEventListener('click', listener);
				});
				this.setState({ open: input });
			}).bind(this));
		}
	},
	handleChange: function(input, event){
		this.firebaseRefs.script.child(input).set(event.target.value);
	},
	render: function() {
		return (
			React.createElement('div', null,
			React.createElement('nav', {className: 'navbar navbar-inverse'},
			React.createElement('div', {className: 'container-fluid'}, 
				React.createElement('div', {className: 'navbar-header btn-block navbar-nav'},
					React.createElement('div', {className: 'col-sm-3 col-xs-12 navbar-btn dropdown'},
						React.createElement('div', {className: 'input-group'},
							React.createElement('input', 
								{
									className: 'form-control text-center', 
									type: 'text', 
									value: this.state.script.title, 
									onChange: this.handleChange.bind(this, 'title'), 
									style: {}, placeholder: 'Script Title'
								}
							),
							React.createElement('span', {className: 'input-group-btn', style: {paddingTop: '0px'}},
								React.createElement("a", 
									{
										className: 'btn btn-default slidetip ' + (this.state.dropdowns=='print'&&'active'), 
										onClick: this.toggle.bind(this,'print'), 
										title: "Print Options"
									}, 
									React.createElement("i", {className: "glyphicon glyphicon-print"})
								), 
							)
						),
						this.state.open == 'print' && React.createElement("div", {className: "popover bottom", style:  { display: 'block'}, onClick: this.stopProp}, 
							React.createElement("div", {className: "arrow"}), 
							// React.createElement("h3", {className: "popover-title btn btn-block", onClick: this.print}, "Print Script"), 
							React.createElement("div", {className: "popover-content"}, 
								React.createElement("div", {className: "form-group"}, 
									React.createElement("textarea", {placeholder: "Author(s)", value: this.state.script.authors, onChange: this.handleChange.bind(this,'authors'), className: "form-control", readOnly: this.props.readonly})
								), 
								// React.createElement("div", {className: "form-group"}, 
								// 	React.createElement("textarea", {placeholder: "Address (left side)", value: this.state.script.leftAddress, onChange: this.handleChange.bind(this,'leftAddress'), className: "form-control", readOnly: this.props.readonly})
								// ), 
								React.createElement("div", {className: "form-group"}, 
									React.createElement("textarea", {placeholder: "Email", value: this.state.script.email, onChange: this.handleChange.bind(this,'email'), className: "form-control", readOnly: this.props.readonly})
								), 
								// React.createElement("div", {className: "form-group"}, 
									// React.createElement("select", {className: "form-control", onChange: this.highlight, title: "Highlights a character when printing", value: this.state.highlight}, 
									// 	React.createElement("option", {value: ""}, "-- Highlighter --"), 
									// 	// characters
									// )
								// )
							)
						)
					),
				),
					
				React.createElement('div', {style: {position: 'absolute', right: '20px'}},
						React.createElement('ul', {className: 'nav navbar-nav'},
							React.createElement('ul', {className: 'nav navbar-nav'},
								React.createElement('li', {className: 'dropdown'},
									React.createElement('a', {onClick: this.newScript.bind(this)},
										'New Script '
										),
									)
								),
							
							React.createElement('ul', {className: 'nav navbar-nav'},
								React.createElement('li', {className: 'dropdown'},
									React.createElement('a', {onClick: this.Save.bind(this)},
										'Save Script '
									),
								),
							),
							React.createElement('li', {className: 'dropdown'},
								React.createElement('a', {className: 'dropdown-toggle', 'data-toggle': 'dropdown'},
									'Import ',
								React.createElement('span', {className: 'caret'}, '')
							),

							React.createElement('ul', {className: 'dropdown-menu'},
								React.createElement("li", null, 
									React.createElement('a',null,
										React.createElement('label', {className:'custom-file-upload', htmlFor:'file-upload'},'HTML'),
										React.createElement('input',{type: 'file', id: 'file-upload', accept: '*.html', onChange: this.import.bind(this)}),
									)
								),
								React.createElement("li", null, 
									React.createElement('a', null, 
										React.createElement('label', {className:'custom-file-upload', htmlFor:'file-upload'},'XML'),
										React.createElement('input',{type: 'file', id: 'file-upload', accept: '*.xml', onChange: this.import.bind(this)}),
									)
								),
								React.createElement("li", null, 
									React.createElement('a', null, 
										React.createElement('label', {className:'custom-file-upload', htmlFor:'file-upload'},'PDF'),
										React.createElement('input',{type: 'file', id: 'file-upload', accept: '*.pdf', onChange: this.import.bind(this)}),
									)
								),
							)
							)
						),

					// React.createElement('ul', {className: 'nav navbar-nav'},
					// 	React.createElement("li", null, 
					// 		React.createElement('label', {className:'custom-file-upload', htmlFor:'file-upload'},'Import'),
					// 		React.createElement('input',{type: 'file', id: 'file-upload', onChange: this.import.bind(this)}),
					// 	),
					// ),

					React.createElement('ul', {className: 'nav navbar-nav'},
						React.createElement('li', {className: 'dropdown'},
							React.createElement('a', {className: 'dropdown-toggle', 'data-toggle': 'dropdown'},
								'Export ',
							React.createElement('span', {className: 'caret'}, '')
						),

						React.createElement('ul', {className: 'dropdown-menu'},
							React.createElement('li',null,
								React.createElement('a',{onClick: this.export2html.bind(this)}, 'HTML'),
								React.createElement('a',{onClick: this.export2xml.bind(this)}, 'XML'),
								React.createElement('a',{onClick: this.export2pdf.bind(this)}, 'PDF'),
							)
						)
						)
					),
				),
			)
		),
				React.createElement("header", {className: "visible-print"}, 
					React.createElement("p", 
						{className: "uppercase"}, 
						this.state.script.title
					), 
					this.state.script.authors && React.createElement("p", null, "by"), 
					React.createElement("p", null, this.state.script.authors), 
					this.state.highlight && React.createElement("p", {className: "character-highlighted"}, "Character: ", this.state.highlight.toUpperCase()), 
					// React.createElement("address", {className: "text-left"}, this.state.script.leftAddress), 
					React.createElement("address", {className: "text-right"}, this.state.script.rightAddress)
				)
			)
		)
	}
})