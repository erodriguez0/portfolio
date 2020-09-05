if(document.getElementById("canvas") !== null) {
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

// Generate an ID for tables
// Format: xxxxxxxxx-xxxxxxxxx
function generateId() {
	return Math.random().toString(36).substr(2, 9) 
		+ '-' + Math.random().toString(36).substr(2, 9);
}

function roundSize(num) {
    return Math.round(num / grid) * grid;
}

// Moves canvas lines to background
function sendLinesToBack() {
	canvas.getObjects().map(o => {
		if (o.type === 'line') {
		  	canvas.sendToBack(o);
		}
	});
}

// Snap object to grid
function snapToGrid(target) {
	target.set({
		left: roundSize(target.left),
		top: roundSize(target.top)
	});
}

function checkBoundingBox(e) {
	const obj = e.target;

	// Invalid object
	if (!obj) {
		return;
	}

	obj.setCoords();

	const objBoundingBox = obj.getBoundingRect();

	if(obj.type == "square" || obj.type == "circle" || obj.type == "chair") {
		// Check if out of bounds
		// Top
		if(objBoundingBox.top < 0) {
			if(obj.angle == 45) {
				obj.set('top', 15);
				obj.setCoords();
			} else {
				obj.set('top', 0);
				obj.setCoords();
			}
		}

		// Width
		if(objBoundingBox.left > canvas.width - objBoundingBox.width) {
			if(obj.angle == 45) {
				obj.set('left', canvas.width - (obj.width - roundSize(obj.width / 15)));
				obj.setCoords();
			} else {
				obj.set('left', canvas.width - objBoundingBox.width);
				obj.setCoords();
			}
		}

		// Height
		if(objBoundingBox.top > canvas.height - objBoundingBox.height) {
			if(obj.angle == 45) {
				obj.set('top', canvas.height - objBoundingBox.height - 15);
				obj.setCoords();
			} else {
				obj.set('top', canvas.height - objBoundingBox.height);
				obj.setCoords();
			}
		}

		// Left
		if(objBoundingBox.left < 0) {

			// Check if object is rotated
			// Apply scaling
			// TODO: check object type
			if(obj.angle == 45) {
				obj.set('left', obj.width - roundSize((obj.width / 15)));
				obj.setCoords();
			} else {
				obj.set('left', 0);
				obj.setCoords();
			}
		}
	}

	if(obj.type == "rectangle") {
		// Check if out of bounds
		// Top
		if(objBoundingBox.top < 0) {
			if(obj.angle == 45) {
				obj.set('top', 15);
				obj.setCoords();
			} else if(obj.angle == -45) {
				obj.set('top', roundSize(obj.height * 1.1));
				obj.setCoords();
			} else {
				obj.set('top', 0);
				obj.setCoords();
			}
		}

		// Width
		if(objBoundingBox.left > canvas.width - objBoundingBox.width) {
			if(obj.angle == 45) {
				obj.set('left', canvas.width - obj.width + 15);
				obj.setCoords();
			} else if(obj.angle == -45) {
				obj.set('left', canvas.width - obj.width - 45);
				obj.setCoords();
			} else {
				obj.set('left', canvas.width - objBoundingBox.width);
				obj.setCoords();
			}
		}

		// Height
		if(objBoundingBox.top > canvas.height - objBoundingBox.height) {
			if(obj.angle == 45) {
				obj.set('top', canvas.height - objBoundingBox.height - 15);
				obj.setCoords();
			} else if(obj.angle == -45) {
				obj.set('top', canvas.height - obj.height + 15);
				obj.setCoords();
			}else {
				obj.set('top', canvas.height - objBoundingBox.height);
				obj.setCoords();
			}
		}

		// Left
		if(objBoundingBox.left < 0) {
			if(obj.angle == 45) {
				obj.set('left', roundSize(obj.width * 0.5));
				obj.setCoords();
			} else {
				obj.set('left', 0);
				obj.setCoords();
			}
		}
	}

	if(obj.type == "other") {
		if(obj.angle == 0) {
			if(objBoundingBox.top < 0) {
				obj.set('top', 0);
				obj.setCoords();
			}
			if (objBoundingBox.left > canvas.width - objBoundingBox.width) {
				obj.set('left', canvas.width - objBoundingBox.width)
				obj.setCoords()
			}
			if (objBoundingBox.top > canvas.height - objBoundingBox.height) {
				obj.set('top', canvas.height - objBoundingBox.height)
				obj.setCoords()
			}
			if (objBoundingBox.left < 0) {
				obj.set('left', 0)
				obj.setCoords()
			}
		} else {
			if(objBoundingBox.top < 0) {
				obj.set('top', 0);
				obj.setCoords();
			}
			if (objBoundingBox.left > canvas.width - objBoundingBox.width) {
				obj.set('left', canvas.width - objBoundingBox.width * 0.5)
				obj.setCoords()
			}
			if (objBoundingBox.top > canvas.height - objBoundingBox.height) {
				obj.set('top', canvas.height - objBoundingBox.height)
				obj.setCoords()
			}
			if (objBoundingBox.left < 0) {
				obj.set('left', objBoundingBox.width)
				obj.setCoords()
			}
		}
	}
}

// CREATE EXISTING FABRIC OBEJCTS

function addSquareTable(id, num, left, top, deg, width, height) {

	const o = new fabric.Rect({
		width: width,
		height: height,
		fill: tableFill,
		stroke: tableFill,
		strokeWidth: 1,
		originX: 'center',
		originY: 'center',
		centeredRotation: true,
		snapAngle: 45,
		selectable: true,
		lockMovementX: true,
		lockMovementY: true
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
		hasControls: false
	});

	canvas.add(g);
	number++;
	return g;
}

function addCircleTable(id, num, left, top, deg, rad) {

	const o = new fabric.Circle({
		radius: rad / 2,
	    fill: tableFill,
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
		hasControls: false
	});

	canvas.add(g);
	number++;
	return g;
}

function addRectangleTable(id, num, left, top, deg, width, height) {

	const o = new fabric.Rect({
		width: width,
		height: height,
		fill: tableFill,
		stroke: tableFill,
		strokeWidth: 1,
		originX: 'center',
		originY: 'center',
		centeredRotation: true,
		snapAngle: 45,
		selectable: true,
		lockMovementX: true,
		lockMovementY: true
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
		hasControls: false
	});

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
		selectable: false,
		lockMovementX: true,
		lockMovementY: true
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
		selectable: false,
		type: 'other',
		table: false,
		id: id,
		hasControls: false
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
		selectable: false,
		type: 'chair',
		table: false,
		id: id,
		lockMovementX: true,
		lockMovementY: true
	});

	o.hasControls = false;

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
  	canvas.selection = false;
  	canvas.hoverCursor = "pointer";
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

  	// Snap objects to grid when moving
	canvas.on('object:moving', function(e) {
		snapToGrid(e.target);
	});

	// Fixed increments on resizing objects
	canvas.on('object:scaling', function(e) {
		// o = group, obj = object
		// roundSize() rounds to nearest grid size (15)
		// type is type of table, chair, etc.
		fabric.Object.prototype.objectCaching = false;
		let o = e.target;
		let edit = (o.table || o.type == "other") ? true : false;
		let obj;
		let text;
		if(edit) {
			obj = e.target._objects[0];
			text = o._objects[1];
		}
		let l = roundSize(o.left);
		let t = roundSize(o.top);
		let w = roundSize(o.getWidth());
		let h = roundSize(o.getHeight());
		let a = o.angle;
		let table = o.table;
		let type = o.type;

		// Check min and max size for objects
		// Round and square tables are considered square objects
		if(type == "square" || type == "circle") {
			if(h < squareMinSize) { w = h = squareMinSize; }
			if(h > squareMaxSize) { w = h = squareMaxSize; }
		}

		if(type == "rectangle") {
			if(h < rectMinHeight) { h = rectMinHeight; }
			if(h > rectMaxHeight) { h = rectMaxHeight; }
			if(w < rectMinWidth)  { w = rectMinWidth;  }
			if(w > rectMaxWidth)  { w = rectMaxWidth;  }
		}

		if(type == "other") {
			if(a == 0) {
				if(h < objMinHeight) { h = objMinHeight; }
				if(h > objMaxHeight) { h = objMaxHeight; }
				if(w < objMinWidth)  { w = objMinWidth;  }
				if(w > objMaxWidth)  { w = objMaxWidth;  }
				if(w < 60) { 
					if(o.left < 270) {
						text.angle = 270;
					} else {
						text.angle = 90;
					}
				} else if(w > 60 && text.angle > 90) {
					text.angle = 0;
				}
			} else {
				if(h < objMinHeight) 	{ h = objMinHeight; 	}
				if(h > objMaxAngWidth)  { h = objMaxAngWidth; 	}
				if(w < objMinWidth)  	{ w = objAngWidth;  	}
				if(w > objMaxAngWidth)  { w = objMaxAngWidth;  	}
			}
		}

		if(type == "chair") {
			if(w < chairMinWidth) { w = h = chairMinWidth; }
			if(w > chairMaxWidth) { w = h = chairMaxWidth; }
		}

		// Set left, top, width, and height
		// Resets scaleX and scaleY for incrementing size by grid
		o.set({
			left: 	l,
			top: 	t,
			width: 	w,
			height: h,
			scaleX: 1,
			scaleY: 1
		});

		if(edit) {
			// Sets the rect object's width and height
			// to fill in the group object
			obj.set({
				width: 	w,
				height: h
			});
		}

		// For circle objects the radius is width or height * 0.5
		if(type == "circle") { obj.set({ radius: w * 0.5 }); }

		// Prevent object flipping
		if(e.target.flipX == true || e.target.flipY == true) {
			e.target.flipX = false;
			e.target.flipY = false;
		}

	});

	// 
	canvas.on('object:modified', function(e) {

		snapToGrid(e.target);

		// Tables moved to top of other objects
		// e.target.table return t/f
		if(e.target.table) {
		  	canvas.bringToFront(e.target);
		}
		else {
		  	canvas.sendToBack(e.target);
		}

		sendLinesToBack();
	});

	// Check if objects were moved or resized and are out of bounds
	canvas.observe('object:moving', function(e) {
		checkBoundingBox(e);
	});
	canvas.observe('object:rotating', function(e) {
		checkBoundingBox(e);
	});
	canvas.observe('object:scaling', function(e) {
		checkBoundingBox(e);
	});

}

function addObjects() {
	$.ajax({
		url: '/delectable/public_html/assets/scripts/restaurant-layout.php',
		type: 'POST',
		data: {
			'loc_id': lid,
			'load_layout': true
		}
	}).done(function(res) {
		var objects = JSON.parse(res);
		// console.log(objects);
		$.each(objects, function(k, v) {
			switch(v.type) {
				case "square":
					addSquareTable(v.uuid, parseInt(v.num), parseInt(v.left), parseInt(v.top), parseInt(v.deg), parseInt(v.width), parseInt(v.height));
					break;
				case "rectangle":
					addRectangleTable(v.uuid, parseInt(v.num), parseInt(v.left), parseInt(v.top), parseInt(v.deg), parseInt(v.width), parseInt(v.height));
					break;
				case "circle":
					addCircleTable(v.uuid, parseInt(v.num), parseInt(v.left), parseInt(v.top), parseInt(v.deg), parseInt(v.width));
					break;
				case "chair":
					addChair(v.uuid, parseInt(v.left), parseInt(v.top), parseInt(v.deg), parseInt(v.width), parseInt(v.height));
					break;
				case "other":
					addObject("", v.uuid, parseInt(v.left), parseInt(v.top), parseInt(v.deg), parseInt(v.width), parseInt(v.height));
					break;
			}
		});
	});
}

// INITIALIZE CANVAS AND OBJECTS

initCanvas();

addObjects();

	$(document).ready(function() {
		$("#reserve").click(function() {
			if(canvas.getActiveObject()) {
				const o = canvas.getActiveObject();
				$("#rsvn-table").html(o.number);
			}
		});
	});
}