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
		+ '-' + Math.random().toString(36).substr(2, 9)
		+ '-' + Math.random().toString(36).substr(2, 9)
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

// CREATE NEW FABRIC OBEJCTS

function addNewSquareTable(deg = 0) {
  	const id = generateId();
  	let gleft = 315;
  	let gtop = 215;

  	if(deg > 0) {
  		gleft = 352;
  		gtop = 200;
  	}

	const o = new fabric.Rect({
		width: 75,
		height: 75,
		fill: tableFill,
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
		left: gleft,
		top: gtop,
		centeredRotation: true,
		hasRotatingPoint: false,
		snapThreshold: 45,
		snapAngle: 45,
		angle: deg,
		selectable: true,
		type: 'square',
		table: true,
		id: id,
		number: number,
		new: true
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

function addNewCircleTable() {
  	const id = generateId();
  	let gleft = 315;
  	let gtop = 215;

	const o = new fabric.Circle({
		radius: 37.5,
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
		left: gleft,
		top: gtop,
		centeredRotation: true,
		hasRotatingPoint: false,
		snapThreshold: 45,
		snapAngle: 45,
		selectable: true,
		type: 'circle',
		table: true,
		id: id,
		number: number,
		new: true
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

function addNewRectangleTable(deg = 0) {
  	const id = generateId();
  	let gleft = 315;
  	let gtop = 215;

  	if(deg > 0) {
  		gleft = 352;
  		gtop = 200;
  	}

	const o = new fabric.Rect({
		width: 105,
		height: 75,
		fill: tableFill,
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
		angle: -deg
	});

	const g = new fabric.Group([o, t], {
		left: gleft,
		top: gtop,
		centeredRotation: true,
		hasRotatingPoint: false,
		snapThreshold: 45,
		snapAngle: 45,
		angle: deg,
		selectable: true,
		type: 'rectangle',
		table: true,
		id: id,
		number: number,
		new: true
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

function addNewObject(text = "", deg = 0) {
	const id = generateId();
	let gleft = 315;
  	let gtop = 215;

  	if(deg > 0) {
  		gleft = 352;
  		gtop = 200;
  	}

  	const o = new fabric.Rect({
		width: 75,
		height: 75,
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
		left: gleft,
		top: gtop,
		centeredRotation: true,
		hasRotatingPoint: false,
		snapThreshold: 45,
		snapAngle: 45,
		angle: deg,
		selectable: true,
		type: 'other',
		table: false,
		id: id,
		new: true
	});

	canvas.add(g);
	return g;
}

function addNewChair(deg = 0) {
  	const id = generateId();
  	let gleft = 315;
  	let gtop = 215;

  	if(deg > 0) {
  		gleft = 352;
  		gtop = 200;
  	}

	const o = new fabric.Rect({
		width: 45,
		height: 45,
		left: gleft,
		top: gtop,
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
		new: true
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
		new: false
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
		new: false
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
		new: false
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
		new: false
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
		new: false
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

// CREATE/SAVE EXISTING FABRIC OBJECTS

function sortRowsByObjectNum(a, b) {
	const num1 = a.number;
	const num2 = b.number;
	let comparison = 0;
	if(num1 > num2) {
		comparison = 1;
	} else if(num1 < num2) {
		comparison = -1;
	}
	return comparison;
}

function saveObjects() {
	let o = canvas.getObjects();
	let rows = [];
	$.each(o, function(k, v) {
		let row;
		if(o[k].table) {
			row = {
				id: o[k].id,
				number: o[k]._objects[1].text,
				type: o[k].type,
				table: true,
				left:  o[k].left,
				top:  o[k].top,
				width:  o[k].width,
				height:  o[k].height,
				deg:  o[k].angle,
				new: o[k].new
			};
			rows.push(row);
		} else if(o[k].type == "other") {
			row = {
				id: o[k].id,
				text: o[k]._objects[1].text,
				type: o[k].type,
				table: false,
				left:  o[k].left,
				top:  o[k].top,
				width:  o[k].width,
				height:  o[k].height,
				deg:  o[k].angle,
				new: o[k].new
			};
			rows.push(row);
		} else {
			// Exclude lines from being saved
			if(o[k].type != "line") {
				row = {
					id: o[k].id,
					type: o[k].type,
					table: false,
					left:  o[k].left,
					top:  o[k].top,
					width:  o[k].width,
					height:  o[k].height,
					deg:  o[k].angle,
					new: o[k].new
				};
				rows.push(row);
			}
		}
	});

	rows.sort(sortRowsByObjectNum);
	
	$.ajax({
		url: '/delectable/public_html/assets/scripts/restaurant-layout.php',
		type: 'POST',
		data: {
			'loc_id': lid,
			'objects': rows,
			'deletedObjects': deletedObjects,
			'save_layout': true
		}
	}).done(function(res) {
		// Optional: to show it works
		location.reload();
	});
}

// EVENT LISTENERS FOR OBJECT BUTTONS

$(".rectangle-0").click(function() {
	const o = addNewRectangleTable();
	canvas.setActiveObject(o);
});

$(".rectangle-45").click(function() {
	const o = addNewRectangleTable(45);
	canvas.setActiveObject(o);
});

$(".rectangle-315").click(function() {
	const o = addNewRectangleTable(-45);
	canvas.setActiveObject(o);
});

$(".square-0").click(function() {
	const o = addNewSquareTable(0);
	canvas.setActiveObject(o);
});

$(".square-45").click(function() {
	const o = addNewSquareTable(45);
	canvas.setActiveObject(o);
});

$(".round-0").click(function() {
	const o = addNewCircleTable();
	canvas.setActiveObject(o);
});

$(".object-0").click(function() {
	// var text = prompt("Enter text to display", "Text...");
	const o = addNewObject();
	canvas.setActiveObject(o);
});

$(".object-45").click(function() {
	// var text = prompt("Enter text to display", "Text...");
	const o = addNewObject("", 45);
	canvas.setActiveObject(o);
});

$(".chair-0").click(function() {
	const o = addNewChair();
	canvas.setActiveObject(o);
});

$(".chair-45").click(function() {
	const o = addNewChair(45);
	canvas.setActiveObject(o);
});

$(".remove").click(function() {
	const o = canvas.getActiveObject();
	if(o) {
		// If object is  a table then
		// it iterates through objects
		// to renumber each table
		if(o.table) {
			let num = o.number;
			let hi = num;
			const obj = canvas.getObjects();

			// Change number and render new
			// text numbers for shifting
			// after object removal
			$.each(obj, function(k, v) {
				if(obj[k].table) {
					if(obj[k].number > num) {
						// Keep track of the highest number
						// for new objects
						if(obj[k].number > hi) {
							hi = obj[k].number;
						}
						obj[k].number -= 1;
						obj[k]._objects[1].setText(obj[k].number.toString());
					}
				}
			});
			number = hi;
		}
		if(!o.new) {
			let obj = {"id": o.id, "table": o.table};
			deletedObjects.push(obj);
		}
		o.remove();
		canvas.remove(o);
		canvas.discardActiveObject();
		canvas.renderAll();
	}
});

$(".clear").click(function() {
	let objs = canvas.getObjects();
	$.each(objs, function(o) {
		if(!objs[o].new) {
			let obj = {"id": objs[o].id, "table": objs[o].table};
			deletedObjects.push(obj);
		}
	});
	canvas.remove(...canvas.getObjects());
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
	number = 1;
	canvas.renderAll();
});

var toggle = 0;
$(".save-layout").click(function() {
	// if(toggle % 2 == 0) {
	// 	canvas.getObjects().map(o => {
	// 		o.hasControls = false;
	// 		o.lockMovementX = true;
	// 		o.lockMovementY = true;
	// 		o.borderColor = "#38A62E";
	// 		if(o.type == "chair" || o.type == "other") {
	// 			o.selectable = false;
	// 		}
	// 	});
	// 	canvas.selection = false;
	// 	canvas.hoverCursor = "pointer";
	// 	canvas.discardActiveObject();
	// 	canvas.renderAll();
	// } else {
	// 	canvas.getObjects().map(o => {
	// 		o.hasControls = true;
	// 		o.lockMovementX = false;
	// 		o.lockMovementY = false;
	// 		o.borderColor = tableFill;
	// 		if(o.type == "chair" || o.type == "other") {
	// 			o.selectable = true;
	// 		}
	// 	});
	// 	canvas.selection = true;
	// 	canvas.hoverCursor = "move";
	// 	canvas.discardActiveObject();
	// 	canvas.renderAll();
	// }
	// $(".admin-mode").toggleClass("d-none");
	// $(".customer-mode").toggleClass("d-block");
	// toggle++;
	saveObjects();
});

// INITIALIZE CANVAS AND OBJECTS

initCanvas();

addObjects();