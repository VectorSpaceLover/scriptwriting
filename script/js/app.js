

var App = React.createClass({displayName: "App",
	render: function() {
		return React.createElement(RouteHandler, null);
	}
});

Route = ReactRouter.Route;
Link = ReactRouter.Link;
RouteHandler = ReactRouter.RouteHandler;
DefaultRoute = ReactRouter.DefaultRoute;
var routes = (
	React.createElement(Route, {handler: App}, 
		React.createElement(Route, {handler: Home}), 
		React.createElement(Route, {name: "script", path: "/:scriptId", handler: Script}), 
		React.createElement(Route, {name: "scriptAction", path: "/:scriptId/:action", handler: Script})
	)
);

ReactRouter.run(routes, function (Handler) {
  React.render(React.createElement(Handler, null), document.getElementById('container'));
});