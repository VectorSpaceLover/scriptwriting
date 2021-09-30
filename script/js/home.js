
var Home = React.createClass({displayName: "Home",
	newScript: function(){
		var fb = new Firebase("https://screenwrite.firebaseio.com/");
		var newRef = fb.push();
		window.location.hash = '#/' + newRef.key();
		window.location.reload(); // force firebase to reload
	},
	render: function() {
		var commentStyles = {
			color: '#dd0',
			textShadow: '0 1px 1px #000',
			fontSize: '120%'
		};
		return (
				React.createElement('div', null,
					React.createElement('div',{className: 'navbar navbar-inverse'},
						React.createElement('div',{className: 'container-fluid'},
							React.createElement('div', {className: 'navbar-header'}, 
								React.createElement('a', {className: 'navbar-brand', href: '#'}, 'ScreenWriter')
							)
						)
					),
					React.createElement("div", {className: "row container home"}, 

					React.createElement("h1", {className: "text-center"}, "Welcome To Screenwriter"),

					React.createElement("div", {className: "text-center"}, 
						React.createElement("a", {className: "btn btn-primary", onClick: this.newScript}, " New Script"), 
						"  ",
						React.createElement(Link, {className: "btn btn-primary", to: "/demo"}, "Demo Script")
					), 

					React.createElement("div", {className: "col-lg-3 col-md-6 col-sm-12 item-container"},
							React.createElement("h3", {className: "topics"}, "Collaborate"), 
							React.createElement("p", null, "Share your custom URL with friends to collaborate or add ", React.createElement("code", null, "/view"), " to the end for ", React.createElement("strong", null, "readonly"), " mode!"), 
					),

					React.createElement("div", {className: "col-lg-3 col-md-6 col-sm-12 item-container"},
						React.createElement("h3", {className: "topics"}, "Shortcuts"), 
						React.createElement("div", null, 
							React.createElement("strong", null, "Enter"), React.createElement("span", null, " Insert new line"), React.createElement("br", null), 
							React.createElement("strong", null, "(Shift+)Tab"), React.createElement("span", null, " Cycle through line types"), React.createElement("br", null), 
							React.createElement("strong", null, "Up/Down"), React.createElement("span", null, " Move through lines"), React.createElement("br", null), 
							React.createElement("strong", null, "Cmd/Ctrl+Up/Down"), React.createElement("span", null, " Reorder lines"), React.createElement("br", null), 
							React.createElement("strong", null, "Right"), React.createElement("span", null, " Autocomplete the character or scene"), React.createElement("br", null)
						), 
					),

					React.createElement("div", {className: "col-lg-3 col-md-6 col-sm-12 item-container"},
						React.createElement("h3", {className: "topics"}, "Comments"), 
						React.createElement("p", {className: "help"}, "Hover over a line and click comment button ", React.createElement("i", {className: "glyphicon glyphicon-comment", style: commentStyles})), 
					),

					React.createElement("div", {className: "col-lg-3 col-md-6 col-sm-12 item-container"},
						React.createElement("h3", {className: "topics"}, "Notes"), 
						React.createElement("span", null, "Scripts are not secure, if someone can figure out your URL, they can edit it. Print to PDF if you want a permanent copy.")
					),
				)
				)
		);
	}

});