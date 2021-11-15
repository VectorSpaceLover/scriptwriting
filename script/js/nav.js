
var Nav = React.createClass({displayName: "Nav",
	mixins: [ReactFireMixin, StopPropagationMixin, ReactRouter.State],
	getInitialState: function() {
		return {
			open: null,
			script: {},
			scriptId: this.getParams().scriptId,
			highlight: '',
			printType:1,
			printTypes: ['HTML','XML','PDF'],
		};
	},
	componentWillMount: function() {
		this.bindAsObject(new Firebase("https://screenwrite.firebaseio.com/"+this.state.scriptId), "script");

		// window.fb = this.firebaseRefs;
	},
	toggle: function(dropdown, event) {
		var that = this;
		if (this.state.open != dropdown) {
			setTimeout((function(){
				document.addEventListener('click', function listener(){
					that.setState({ open: false });
					document.removeEventListener('click', listener);
				});
				this.setState({ open: dropdown });
			}).bind(this));
		}
	},
	setType: function(type) {
		if (!this.props.editingIndex) return;
		this.firebaseRefs.script.child('lines/'+this.props.editingIndex+'/type').set(type);
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
	readCssFile: function(){
		var fileReader = new FileReader();
		fileReader.onload = function() {
			console.log(this.result);
		//   var o = JSON.parse(this.result);
		  //Object.assign(localStorage, o);   // use this with localStorage
		//   alert("done, myKey=" + o["myKey"]); // o[] -> localStorage.getItem("myKey")
		};
		fileReader.readAsText(new File([],'style.css'));
	},
	makeXml: function(){
		// let authors = this.state.script["authors"];
		// let leftAddress = this.state.script["leftAddress"];
		// let rightAddress = this.state.script["rightAddress"];
		// let title = this.state.script["title"];
		let lines = this.state.script["lines"];
		let firstLine = this.state.script["firstLine"]
		let xmlbody = "<?xml version=\"1.0\" encoding=\"utf-8\"?><!DOCTYPE base>\n<base>\n";
		// xmlbody = xmlbody + "<element>\n" + "<type>\n" + "title\n" + "</type>\n" + "<value>\n" + title + "\n</value>\n" + "</element>\n";
		// xmlbody = xmlbody + "<element>\n" + "<type>\n" + "authors\n" + "</type>\n" + "<value>\n" + authors + "\n</value>\n" + "</element>\n";
		// xmlbody = xmlbody + "<element>\n" + "<type>\n" + "leftAddress\n" + "</type>\n" + "<value>\n" + leftAddress + "\n</value>\n" + "</element>\n";
		// xmlbody = xmlbody + "<element>\n" + "<type>\n" + "rightAddress\n" + "</type>\n" + "<value>\n" + rightAddress + "\n</value>\n" + "</element>\n";

		while(firstLine != undefined)
		{
			let line = lines[firstLine];
			firstLine = line["next"];
			let type = line["type"];
			xmlbody = xmlbody + "<element>\n" + "<type>\n" + type + "\n</type>\n" + "<value>\n" + line["text"] + "\n</value>\n";
			if(line["comment"] != undefined)
			{
				xmlbody = xmlbody + "<comment>\n" + line["comment"] + "\n</comment>\n";
			}
			xmlbody = xmlbody + "</element>\n"
		}
		xmlbody += "</base>";
		return xmlbody;
	},
	export2html: function(){
		console.log('print as html');
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
							'</header>' +
						'</div>' +
						'<ul class="script" data-reactid=".0.1">';
		var script = this.state.script
		console.log(script);
		Object.keys(script).forEach(function(key){
			var type = typeof(script[key]);
			// console.log(key);
			if(type == 'string'){
				console.log('type is string');
				// html += '<li>';
				// html += key + ':' + script[key];
				// html += '</li>'
			}else if(type == 'object'){
				Object.keys(script[key]).forEach(function(id){
					var line = script[key][id];
					html += '<li class="line ' + line['type'] +  '" type = "'+ line['type'] + '" >' +
								'<div class="line-text" contenteditable="true" data-reactid=".0.1.$-MkKJ604grNQVI7okL_I.0">' + line['text'] + '</div>' +
								'<a class="comment-add" data-reactid=".0.1.$-MkKJ604grNQVI7okL_I.1">' +
									'<i class="glyphicon glyphicon-comment" data-reactid=".0.1.$-MkKJ604grNQVI7okL_I.1.0"></i>' +
								'</a>' +
							'</li>';

				})
			}
		})
		html += 		'</ul>' +
					'</div>' +
				'</div>' +
			'</body>' +
			'</html>';
		
		this.download(html,(new Date().getTime())+'.html','application/octet-stream');
	},
	export2xml: function(){
		let data = this.makeXml();
		// console.log(data);
		this.download(data,(new Date().getTime())+'.xml','application/octet-stream');
	},
	print: function() {
		// console.log(ReactFireMixin);
		// window.print();
		var type = Number(this.state.printType);
		console.log(type);

		switch(type){
			case 1:
			this.export2html();
			break;
			case 2:
			// console.log('print as xml');
			this.export2xml();
			break;
			case 3:
				window.print();
			break;
			default:
		}
	},
	
	import: function(event) {
		
		var reader = new FileReader;
		var self = this;
		
		var extension;
		reader.onload = function(){
			// self.props.setScript(this.result);

			// var extension
			// console.log(this);
			// if(extension == 'html'){
			// 	self.importAsHtml(this.result);
			// }else if(extension == 'xml'){
			// 	self.importAsXml(this.result);
			// }else if(extension == 'pdf'){

			// }else{

			// }
			self.props.setScript(extension, this.result);
 		}
		// console.log(event.target.files[0].name.split('.').pop());
		
		if(event.target.files.length > 0){
			var file = event.target.files[0];	
			extension = file.name.split('.').pop();
			reader.readAsText(file);
		}
	},
	importAsHtml: function(result){

	},
	importAsXml: function(result){

	},
	highlight: function(event) {
		highlight = event.target.value;
		this.setState({highlight: event.target.value});
	},
	handleChange: function(input, event) {
		console.log('title change handle');
		console.log(event.target.value);
		this.firebaseRefs.script.child(input).set(event.target.value);
	},
	newScript: function(){
		var fb = new Firebase("https://screenwrite.firebaseio.com/");
		var newRef = fb.push();

		// console.log(fb, newRef);
		window.location.hash = '#/' + newRef.key();
		window.location.reload(); // force firebase to reload
	},
	setPrintType: function(event) {
		var value = event.target.value;
		console.log(value);
		if(value == 0) return;

		this.setState({printType: value});
	},
	render: function() {
		var printTypes = ['html', 'xml', 'pdf'];

		if (!this.state.script) return React.createElement("div", null);

		if (this.state.script.title)
			document.title = 'Screenwriter: ' + this.state.script.title;

		var editing = this.state.script.lines && this.state.script.lines[this.props.editingIndex] || {};
		if (this.state.open=='print') {
			var characters = [];
			_.each(_.uniq(_.map(_.pluck(_.where(this.state.script.lines, {type:'character'}), 'text'), function(character){
				return character && character.toUpperCase();
			})), function(character){
				if (character)
					characters.push(React.createElement("option", {key: character}, character))
			});
		}
		return (
			React.createElement("div", null, 
				React.createElement("div", {className: "navbar hidden-print", role: "navigation"}, 
					React.createElement("div", {className: ""}, 
						React.createElement("ul", {className: "nav navbar-nav btn-block row"}, 
							React.createElement("li", {className: "col-sm-12 col-xs-12 navbar-btn dropdown"}, 
								React.createElement("div", {className: "input-group", style: {margin: 'auto'}}, 
									types.map(function(type, index){
										return (React.createElement("a", {onClick: this.setType.bind(this, type), 
											key: type, 
											className: 'btn btn-primary btn-script-type '+(editing.type==type&&'active')}, 
											React.createElement('span', {}, typeButtonStr[type]),
											React.createElement('span',{className: 'badge'}, index + 1)
										))
									}, this)
								), 
								
							), 
							
							
						)

					)
				), 
				// React.createElement("header", {className: "visible-print"}, 
				// 	React.createElement("p", {className: "uppercase"}, this.props.script.title), 
				// 	this.props.script.authors && React.createElement("p", null, "by"), 
				// 	React.createElement("p" , null, this.props.script.authors), 
				// 	this.state.highlight && React.createElement("p", {className: "character-highlighted"}, "Character: ", this.state.highlight.toUpperCase()), 
				// 	React.createElement("address", {className: "text-left"}, this.props.script.leftAddress), 
				// 	React.createElement("address", {className: "text-right"}, this.props.script.rightAddress)
				// )
			)
		);
	}
});