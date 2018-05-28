function shuffle(array) {
	var currentIndex = array.length, temporaryValue, randomIndex;

	// While there remain elements to shuffle...
	while (0 !== currentIndex) {
		// Pick a remaining element...
		randomIndex = Math.floor(Math.random() * currentIndex);
		currentIndex -= 1;

		// And swap it with the current element.
		temporaryValue = array[currentIndex];
		array[currentIndex] = array[randomIndex];
		array[randomIndex] = temporaryValue;
	}

	return array;
}
function rand(min, max) {
	return Math.floor(Math.random() * (max - min + 1)) + min;
}

var plugboard = document.getElementById('plugboard');
var plugs = plugboard.getElementsByTagName('div');

var rotors_closed = sessionStorage.getItem('rotors_closed');
var plugboard_closed = sessionStorage.getItem('plugboard_closed');

if (rotors_closed == 'true') {
	document.getElementById('rotors').classList.add('closed');
	document.querySelector('.collapse_button.rotors').classList.add('closed');
}
if (plugboard_closed == 'true') {
	document.getElementById('plugboard_options').classList.add('closed');
	document.querySelector('.collapse_button.plugboard').classList.add('closed');
}

var common = {
	connector:["Bezier", {curviness:50}],
	endpointStyle:{fill:"#000"},
	
	anchor:"Bottom", //[0.5,1,0,1,-10,-20]
	isSource:true,
	isTarget:true
};

jsPlumb.ready(function() {
	jsPlumb.setContainer('plugboard_options');
	
	for (var i = 0; i < plugs.length; i++) {
		jsPlumb.addEndpoint(plugs[i].id, {uuid:'plug_' + i, connectorStyle:{stroke:"hsla("+rand(0,360)+",100%,30%,.7)", strokeWidth:3}}, common);
	}
	
	if (document.getElementById('plugboard_config').value != '') {
		var oldConnections = JSON.parse(document.getElementById('plugboard_config').value);
		
		for (var i = 0; i < oldConnections.length; i++) {
			if (oldConnections[i] != i) {
				// Restore connection
				var e1 = jsPlumb.getEndpoints(plugs[i])[0];
				var e2 = jsPlumb.getEndpoints(plugs[oldConnections[i]])[0];
				if (e1.connections.length == 0) {
					jsPlumb.connect({
						uuids:[e1.getUuid(),e2.getUuid()]
					});
				}
			}
		}
	}
});

window.onresize = function() {
	jsPlumb.repaintEverything();
};

function redraw() {
	jsPlumb.repaintEverything();
}

new MutationObserver(redraw).observe(document.getElementById('input'), {
	attributes: true, attributeFilter: ["style"]
});

document.getElementById('input').onkeypress = function(e) {
	var reg = /^[A-Z\s]*$/;
	var lower = /^[a-z]*$/;
	var c = String.fromCharCode(e.which)
	if (reg.test(c)) {
		return true;
	} else {
		if (lower.test(c)) {
			this.value += c.toUpperCase();
		}
		return false;
	}
};

function randomRotors() {
	options = document.getElementById('pos1').getElementsByTagName('option');
	options[Math.floor(Math.random() * options.length)].selected = 'selected';
	options = document.getElementById('pos2').getElementsByTagName('option');
	options[Math.floor(Math.random() * options.length)].selected = 'selected';
	options = document.getElementById('pos3').getElementsByTagName('option');
	options[Math.floor(Math.random() * options.length)].selected = 'selected';
}

function resetRotors() {
	options = document.getElementById('pos1').getElementsByTagName('option');
	options[0].selected = 'selected';
	options = document.getElementById('pos2').getElementsByTagName('option');
	options[0].selected = 'selected';
	options = document.getElementById('pos3').getElementsByTagName('option');
	options[0].selected = 'selected';
}

function randomPlugs() {
	var oldConnections = jsPlumb.getConnections();
	for (var i = 0; i < oldConnections.length; i++) {
		jsPlumb.deleteConnection(oldConnections[i]);
	}
	
	var amount = Math.floor(Math.random() * (plugs.length / 2));
	
	var plugIds = [];
	for (var i = 0; i < plugs.length; i++) {
		plugIds[i] = plugs[i].id;
	}
	
	shuffledPlugs = shuffle(plugIds);
	
	plugsToConnect = shuffledPlugs.slice(0, amount*2);
	
	for (var i = 0; i < amount; i+=2) {
		var e1 = jsPlumb.getEndpoints(plugsToConnect[i])[0];
		var e2 = jsPlumb.getEndpoints(plugsToConnect[i+1])[0];
		jsPlumb.connect({
			uuids:[e1.getUuid(),e2.getUuid()]
		});
	}
}

function resetPlugs() {
	var oldConnections = jsPlumb.getConnections();
	for (var i = 0; i < oldConnections.length; i++) {
		jsPlumb.deleteConnection(oldConnections[i]);
	}
}

function submitForm() {
	var connections = jsPlumb.getConnections();
	var plugboardConfig = [];
	for (var i = 0; i < connections.length; i++) {
		plugboardConfig[connections[i].source.dataset.index] = parseInt(connections[i].target.dataset.index);
		plugboardConfig[connections[i].target.dataset.index] = parseInt(connections[i].source.dataset.index);
	}
	for (var i = 0; i < plugs.length; i++) {
		if (plugboardConfig[i] === undefined) {
			plugboardConfig[i] = i;
		}
	}
	document.getElementById('plugboard_config').value = JSON.stringify(plugboardConfig);
	document.querySelector('form').submit();
}