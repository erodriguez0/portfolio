// GLOBAL VARS
var canvas;
var canvasObj = $("#canvas");
var number;
var deletedObjects = [];
var bgImg = '/delectable/public_html/assets/img/graphics/canvas.png';
var image = new Image();
image.src = bgImg;

// Grid
const grid = 15;
const gridX = 720;
const gridY = 540;
const canvasBg = 		'#f8f8f8';
const lineStroke = 		'#ebebeb';

// Tables
const tableFill = 		'rgba(150, 111, 51, 0.7)';
const tableStroke = 	'#694d23';
const tableShadow = 	'rgba(0, 0, 0, 0) 3px 3px 7px';
const squareMinSize =   60;
const squareMaxSize = 	150;
const rectMinWidth =	90;
const rectMaxWidth = 	210;
const rectMinHeight = 	60;
const rectMaxHeight = 	210;
const objMinHeight = 	15;
const objMaxHeight = 	gridY;
const objMaxAngHeight = 540;
const objMinWidth = 	15;
const objMaxWidth = 	gridX;
const objMaxAngWidth = 	270;
const chairMinWidth = 	30;
const chairMaxWidth = 	90;

// Chairs
const chairFill = 		'rgba(67, 42, 4, 0.7)';
const chairStroke = 	'#32230b';
const chairShadow = 	'rgba(0, 0, 0, 0) 3px 3px 7px';

// Other objects
const objectFill = 		'rgba(0, 93, 127, 0.7)';
const objectStroke = 	'#003e54';

// Wall
const wallFill = 		'rgba(136, 136, 136, 0.7)';
const wallStroke = 		'#686868';
const wallShadow = 		'rgba(0, 0, 0, 0) 5px 5px 20px';

// FUNCTIONS FOR FABRIC OBJECTS

// Moves canvas lines to background
function sendLinesToBack() {
	canvas.getObjects().map(o => {
		if (o.type === 'line') {
		  	canvas.sendToBack(o);
		}
	});
}

function addSquareTable(id, num, left, top, deg, width, height, customFill) {

	const o = new fabric.Rect({
		width: width,
		height: height,
		fill: customFill,
		stroke: tableFill,
		strokeWidth: 1,
		originX: 'center',
		originY: 'center',
		centeredRotation: true,
		snapAngle: 45,
		selectable: true
	});

	const t = new fabric.IText(number.toString(), {
		fontFamily: 'Calibri',
		fontSize: 14,
		fill: '#fff',
		textAlign: 'center',
		originX: 'center',
		originY: 'center',
		lockUniScaling: true,
		angle: -deg
	});

	const g = new fabric.Group([o, t], {
		left: left,
		top: top,
		centeredRotation: true,
		hasRotatingPoint: false,
		snapThreshold: 45,
		snapAngle: 45,
		angle: deg,
		selectable: true,
		type: 'square',
		table: true,
		id: id,
		number: num,
		new: false,
		hasControls: false,
		lockMovementX: true,
		lockMovementY: true,
		selectable: true,
		borderColor: "#00A0DD"
	});

	// Set resizing controls
	// Bottom right only for square
	g.setControlsVisibility({
		bl: false,
		ml: false,
		tl: false,
		tr: false,
		mt: false,
		mb: false
	});

	canvas.add(g);
	number++;
	return g;
}

function addCircleTable(id, num, left, top, deg, rad, customFill) {

	const o = new fabric.Circle({
		radius: rad / 2,
	    fill: customFill,
	    stroke: tableFill,
	    strokeWidth: 1,
	    originX: 'center',
	    originY: 'center',
	    centeredRotation: true
	});

	const t = new fabric.IText(number.toString(), {
		fontFamily: 'Calibri',
		fontSize: 14,
		fill: '#fff',
		textAlign: 'center',
		originX: 'center',
		originY: 'center',
		lockUniScaling: true
	});

	const g = new fabric.Group([o, t], {
		left: left,
		top: top,
		centeredRotation: true,
		hasRotatingPoint: false,
		snapThreshold: 45,
		snapAngle: 45,
		selectable: true,
		type: 'circle',
		table: true,
		id: id,
		number: num,
		new: false,
		hasControls: false,
		lockMovementX: true,
		lockMovementY: true,
		selectable: true,
		borderColor: "#00A0DD"
	});

	// Set resizing controls
	// Bottom right only for square
	g.setControlsVisibility({
		bl: false,
		ml: false,
		tl: false,
		tr: false,
		mt: false,
		mb: false
	});

	canvas.add(g);
	number++;
	return g;
}

function addRectangleTable(id, num, left, top, deg, width, height, customFill) {

	const o = new fabric.Rect({
		width: width,
		height: height,
		fill: customFill,
		stroke: customFill,
		strokeWidth: 1,
		originX: 'center',
		originY: 'center',
		centeredRotation: true,
		snapAngle: 45,
		selectable: true
	});

	const t = new fabric.IText(number.toString(), {
		fontFamily: 'Calibri',
		fontSize: 14,
		fill: '#fff',
		textAlign: 'center',
		originX: 'center',
		originY: 'center',
		angle: -deg
	});

	const g = new fabric.Group([o, t], {
		left: left,
		top: top,
		centeredRotation: true,
		hasRotatingPoint: false,
		snapThreshold: 45,
		snapAngle: 45,
		angle: deg,
		selectable: true,
		type: 'rectangle',
		table: true,
		id: id,
		number: num,
		new: false,
		hasControls: false,
		lockMovementX: true,
		lockMovementY: true,
		selectable: true,
		borderColor: "#00A0DD"
	});

	// Set resizing controls
	// Depending on angle controls
	// are displayed at the bottom
	if(deg == 0 || deg == 45) {
		g.setControlsVisibility({
			bl: false,
			ml: false,
			tl: false,
			tr: false,
			mt: false
		});
	} else {
		g.setControlsVisibility({
			tl: false,
			tr: false,
			mt: false,
			mr: false,
			br: false
		});
	}

	canvas.add(g);
	number++;
	return g;
}

function addObject(text = "", id, left, top, deg, width, height) {

  	const o = new fabric.Rect({
		width: width,
		height: height,
		fill: objectFill,
		stroke: objectFill,
		strokeWidth: 1,
		originX: 'center',
		originY: 'center',
		centeredRotation: true,
		snapAngle: 45,
		selectable: true
	});

	const t = new fabric.IText(text, {
		fontFamily: 'Calibri',
		fontSize: 14,
		fill: '#fff',
		textAlign: 'center',
		originX: 'center',
		originY: 'center',
		angle: -deg
	});

	const g = new fabric.Group([o, t], {
		left: left,
		top: top,
		centeredRotation: true,
		hasRotatingPoint: false,
		snapThreshold: 45,
		snapAngle: 45,
		angle: deg,
		selectable: true,
		type: 'other',
		table: false,
		id: id,
		new: false,
		hasControls: false,
		lockMovementX: true,
		lockMovementY: true,
		selectable: false,
		borderColor: "#38A62E"
	});

	canvas.add(g);
	return g;
}

function addChair(id, left, top, deg, width, height) {

	const o = new fabric.Rect({
		width: width,
		height: height,
		left: left,
		top: top,
		hasRotatingPoint: false,
		fill: chairFill,
		stroke: tableFill,
		strokeWidth: 1,
		originX: 'center',
		originY: 'center',
		centeredRotation: true,
		snapThreshold: 45,
		snapAngle: 45,
		angle: deg,
		selectable: true,
		type: 'chair',
		table: false,
		id: id,
		new: false,
		hasControls: false,
		lockMovementX: true,
		lockMovementY: true,
		selectable: false,
		borderColor: "#38A62E"
	});

	// Set resizing controls
	// Bottom right only for square
	o.setControlsVisibility({
		bl: false,
		ml: false,
		tl: false,
		tr: false,
		mt: false,
		mb: false,
		mr: false
	});

	canvas.add(o);
	return o;
}

function initCanvas() {
	if(canvas) {
    	canvas.clear()
    	canvas.dispose()
  	}

  	// Create new canvas
  	// Set initial table number
  	number = 1;
  	canvas = new fabric.Canvas('canvas');
  	canvas.backgroundColor = canvasBg;
  	// Checking if image was loaded else draw grid lines
  	if(image.width != 0) {
	  	// Load grid as image for faster loading times
	  	canvas.setBackgroundImage(bgImg, 
	  		canvas.renderAll.bind(canvas));
	} else {
	  	// Create horizonal grid lines
	  	for(let i = 0; i < (canvas.width / grid); i++) {
		    const lineX = new fabric.Line([ 0, i * grid, canvas.width, i * grid], {
				stroke: lineStroke,
				selectable: false,
				excludeFromExport: true,
				type: 'line'
		    });
		    canvas.add(lineX);
	  	}

	  	// Create vertical grid lines
	  	for(let i = 0; i < (canvas.width / grid); i++) {
		    const lineY = new fabric.Line([ i * grid, 0, i * grid, canvas.height], {
				stroke: lineStroke,
				selectable: false,
				excludeFromExport: true,
				type: 'line'
		    });
		    sendLinesToBack();
		    canvas.add(lineY);
	  	}
	}
}

function addObjects() {
	$.ajax({
		url: '/delectable/public_html/assets/scripts/restaurant-reviews.php',
		type: 'POST',
		data: {
			'loc_id': lid,
			'load_layout': true
		}
	}).done(function(response) {
		var res = JSON.parse(response);
		if(!res.error) {
			let objects = res.data;
			let customFill;
			if(objects.length > 0) {
				$.each(objects, function(k, v) {
					if(v.num_of_reviews > 0) {
						customFill = convertAvgToHexColor(v.avg);
					} else {
						customFill = tableFill;
					}
					switch(v.type) {
						case "square":
							addSquareTable(v.uuid, parseInt(v.num), parseInt(v.left), parseInt(v.top), parseInt(v.deg), parseInt(v.width), parseInt(v.height), customFill);
							break;
						case "rectangle":
							const o = addRectangleTable(v.uuid, parseInt(v.num), parseInt(v.left), parseInt(v.top), parseInt(v.deg), parseInt(v.width), parseInt(v.height), customFill);
							break;
						case "circle":
							addCircleTable(v.uuid, parseInt(v.num), parseInt(v.left), parseInt(v.top), parseInt(v.deg), parseInt(v.width), customFill);
							break;
						// case "chair":
						// 	addChair(v.uuid, parseInt(v.left), parseInt(v.top), parseInt(v.deg), parseInt(v.width), parseInt(v.height));
						// 	break;
						// case "other":
						// 	addObject("", v.uuid, parseInt(v.left), parseInt(v.top), parseInt(v.deg), parseInt(v.width), parseInt(v.height));
						// 	break;
						default:
							break;
					}
				});
			} else {
				$("#canvas-wrap").html("");
				let tmp_msg = "<h1>No Layout Set</h1>";
				$("#canvas-wrap").append(tmp_msg);
			}
		}
	});
}

initCanvas();

addObjects();

canvas.getObjects().map(o => {
	o.hasControls = false;
	o.lockMovementX = true;
	o.lockMovementY = true;
	o.borderColor = "#38A62E";
	if(o.type == "chair" || o.type == "other") {
		o.selectable = false;
	}
});
canvas.selection = false;
canvas.hoverCursor = "pointer";
canvas.discardActiveObject();
canvas.renderAll();