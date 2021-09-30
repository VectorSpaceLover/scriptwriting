
var Navbar = React.createClass({displayName: 'Navbar',
	mixins: [ReactFireMixin, ReactRouter.State],
	getInitialState: function(){
		return {
			scriptId: this.getParams().scriptId,
			script: {},
			title: '',
			setScript: this.setScript.bind(this),
		};
	},
	componentWillMount: function() {
		this.loadScript();
	},
	componentDidMount: function() {
		this.getFromDatabase(this.state.scriptId);
	},
	componentWillReceiveProps: function() {
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
		  	console.log(data);
		  	if(data != 'error')
		  	{
		  		self.setState({title: data.title});
		    	self.setScript('xml', data.contents);
		  	}
		  });
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

	setScript: function(extension, result){

		console.log(extension);
		
		var exts = ['html', 'xml', 'pdf'];
		if(exts.indexOf(extension) == -1){
			alert('Import file correctly');
			return;
		}

		if (this.firebaseRefs.script) this.unbind('script');
		var doc;
		var type = 'text/' + extension;
		var doc = new DOMParser().parseFromString(result, type);
		
		// console.log(doc);

		var lines;
		if(extension == 'html')
			lines = doc.getElementsByTagName('li')
		else if(extension == 'xml')
			lines = doc.getElementsByTagName('element');

		this.bindAsObject(new Firebase('https://screenwrite.firebaseio.com/' + this.state.scriptId), 'script');
		var fb = new Firebase('https://screenwrite.firebaseio.com/' + this.state.scriptId);
		// var fb = new Firebase('https://screenwrite.firebaseio.com/');
		// var newRef = fb.push();

		fb.once('value',(function(snapshot){	
			// console.log('once value ');
			var length = lines.length;
			// var length = 10;
			
			console.log(length);

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
		window.location.hash = '#/' + this.state.scriptId;
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

	export2pdf: function(){
		window.print();
	},
	newScript: function(){
		var fb = new Firebase("https://screenwrite.firebaseio.com/");
		var newRef = fb.push();

		console.log(fb, newRef);
		window.location.hash = '#/' + newRef.key();
		window.location.reload(); // force firebase to reload
	},
	Save: function(){
		// send script data to server
		// this.state.script.title
		const SAVE_SUCCESSED = 1;
		const UPDATE_SUCCESSED = 2;
		const SAVE_FAIELD = 0;

		var mytitle = prompt("Please enter you script title", this.state.title);
		if(mytitle == null)
			return;
		
		let data = {
			title: mytitle,
			content: this.makeXml(),
			function: 'saveScripts'
		}

		// let charScriptId = this.state.scriptId.toString();
		// if(charScriptId[0] == 'C')
		// {

			let saveData =  {
			    title: mytitle,
			    scriptId: this.state.scriptId,
			    content: this.makeXml(),
			    function: 'saveScripts'
			  };
			  console.log(saveData);
			$.post("script_create.php",
			 saveData,
			  function(res, status){
			  	console.log(res);
			    if(res == 1){
			    	alert('Script save successed.');
			    }else{
			    	alert('Script save failed.');
			    }
			  });
		//}
		// else
		// {
		// 	let updateData = {
		// 	    scriptId: this.state.scriptId,
		// 	    function: 'updateScripts',
		// 	    content: this.makeXml(),
		// 	  };
		// 	  console.log(updateData);
		// 	$.post("script_create.php",
		// 	  updateData,
		// 	  function(res, status){
		// 	    if(res == 1){
		// 	    	alert('Script update successed.');
		// 	    }else{
		// 	    	alert('Script update failed.');
		// 	    }
		// 	  });
		// }
		
	},
	render: function() {
		return (
			
			React.createElement('nav', {className: 'navbar navbar-inverse'},
					React.createElement('div', {className: 'container-fluid'}, 
						React.createElement('div', {className: 'navbar-header'},
							React.createElement('a', {className: 'navbar-brand', href: '#'}, 'ScreenWriter')
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
					)
				)
			)
		)
	}
})