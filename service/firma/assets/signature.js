console.log("holaaa");
(function (global, factory) {
	typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
	typeof define === 'function' && define.amd ? define(factory) :
	(global.SignaturePad = factory());
}(this, (function () { 'use strict';

function Point(x, y, time) {
	this.x = x;
	this.y = y;
	this.time = time || new Date().getTime();
}

Point.prototype.velocityFrom = function (start) {
	return this.time !== start.time ? this.distanceTo(start) / (this.time - start.time) : 1;
};

Point.prototype.distanceTo = function (start) {
	return Math.sqrt(Math.pow(this.x - start.x, 2) + Math.pow(this.y - start.y, 2));
};

function Bezier(startPoint, control1, control2, endPoint) {
	this.startPoint = startPoint;
	this.control1 = control1;
	this.control2 = control2;
	this.endPoint = endPoint;
}
Bezier.prototype.length = function () {
	var steps = 10;
	var length = 0;
	var px = void 0;
	var py = void 0;
	for (var i = 0; i <= steps; i += 1) {
		var t = i / steps;
		var cx = this._point(t, this.startPoint.x, this.control1.x, this.control2.x, this.endPoint.x);
		var cy = this._point(t, this.startPoint.y, this.control1.y, this.control2.y, this.endPoint.y);
		if (i > 0) {
		var xdiff = cx - px;
		var ydiff = cy - py;
		length += Math.sqrt(xdiff * xdiff + ydiff * ydiff);
		}
		px = cx;
		py = cy;
	}
	return length;
};

Bezier.prototype._point = function (t, start, c1, c2, end) {
	return start * (1.0 - t) * (1.0 - t) * (1.0 - t) + 3.0 * c1 * (1.0 - t) * (1.0 - t) * t + 3.0 * c2 * (1.0 - t) * t * t + end * t * t * t;
};

function SignaturePad(canvas, options) {
	var self = this;
	var opts = options || {};
	this.velocityFilterWeight = opts.velocityFilterWeight || 0.7;
	this.minWidth = opts.minWidth || 0.5;
	this.maxWidth = opts.maxWidth || 1.5; 
	this.dotSize = opts.dotSize || function () {
		return (this.minWidth + this.maxWidth) / 2;
	};
	this.penColor = opts.penColor || '#333';
	this.backgroundColor = opts.backgroundColor || 'rgba(0,0,0,0)';
	this.onBegin = opts.onBegin;
	this.onEnd = opts.onEnd;

	this._canvas = canvas;
	this._ctx = canvas.getContext('2d');
	this.clear();
	this._handleMouseDown = function (event) {
		if (event.which === 1) {
		document.getElementById("note").innerHTML="";
		self._mouseButtonDown = true;
		self._strokeBegin(event);
		}
	};
	this._handleMouseMove = function (event) {
		if (self._mouseButtonDown) {
		self._strokeUpdate(event);
		}
	};

	this._handleMouseUp = function (event) {
		if (event.which === 1 && self._mouseButtonDown) {
		self._mouseButtonDown = false;
		self._strokeEnd(event);
		}
	};

	this._handleTouchStart = function (event) {
		if (event.targetTouches.length === 1) {
		var touch = event.changedTouches[0];
		self._strokeBegin(touch);
		}
	};

	this._handleTouchMove = function (event) {
		event.preventDefault();
		var touch = event.targetTouches[0];
		self._strokeUpdate(touch);
	};

	this._handleTouchEnd = function (event) {
		var wasCanvasTouched = event.target === self._canvas;
		if (wasCanvasTouched) {
		event.preventDefault();
		self._strokeEnd(event);
		}
	};
	this.on();
}

SignaturePad.prototype.clear = function () {
	var ctx = this._ctx;
	var canvas = this._canvas;
	ctx.fillStyle = this.backgroundColor;
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	ctx.fillRect(0, 0, canvas.width, canvas.height);
	this._data = [];
	this._reset();
	this._isEmpty = true;
};

SignaturePad.prototype.fromDataURL = function (dataUrl) {
	var _this = this;
	var image = new Image();
	var ratio = window.devicePixelRatio || 1;
	var width = this._canvas.width / ratio;
	var height = this._canvas.height / ratio;
	this._reset();
	image.src = dataUrl;
	image.onload = function () {
	_this._ctx.drawImage(image, 0, 0, width, height);
	};
	this._isEmpty = false;
};

SignaturePad.prototype.toDataURL = function (type) {
	var _canvas;
	switch (type) {
	case 'image/svg+xml':
	return this._toSVG();
	default:
	for (var _len = arguments.length, options = Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
		options[_key - 1] = arguments[_key];
	}
	return (_canvas = this._canvas).toDataURL.apply(_canvas, [type].concat(options));
	}
};

SignaturePad.prototype.on = function () {
	this._handleMouseEvents();
	this._handleTouchEvents();
};

SignaturePad.prototype.off = function () {
	this._canvas.removeEventListener('mousedown', this._handleMouseDown);
	this._canvas.removeEventListener('mousemove', this._handleMouseMove);
	document.removeEventListener('mouseup', this._handleMouseUp);
	this._canvas.removeEventListener('touchstart', this._handleTouchStart);
	this._canvas.removeEventListener('touchmove', this._handleTouchMove);
	this._canvas.removeEventListener('touchend', this._handleTouchEnd);
};

SignaturePad.prototype.isEmpty = function () {
	return this._isEmpty;
};

// Private methods
SignaturePad.prototype._strokeBegin = function (event) {
	this._data.push([]);
	this._reset();
	this._strokeUpdate(event);
	if (typeof this.onBegin === 'function') {
		this.onBegin(event);
	}
};

SignaturePad.prototype._strokeUpdate = function (event) {
	var x = event.clientX;
	var y = event.clientY;
	var point = this._createPoint(x, y);
	var _addPoint = this._addPoint(point),
	curve = _addPoint.curve,
	widths = _addPoint.widths;
	if (curve && widths) {
		this._drawCurve(curve, widths.start, widths.end);
	}
	this._data[this._data.length - 1].push({
		x: point.x,
		y: point.y,
		time: point.time
	});
};

SignaturePad.prototype._strokeEnd = function (event) {
	var canDrawCurve = this.points.length > 2;
	var point = this.points[0];
	if (!canDrawCurve && point) {
		this._drawDot(point);
	}
	if (typeof this.onEnd === 'function') {
		this.onEnd(event);
	}
};

SignaturePad.prototype._handleMouseEvents = function () {
	this._mouseButtonDown = false;
	this._canvas.addEventListener('mousedown', this._handleMouseDown);
	this._canvas.addEventListener('mousemove', this._handleMouseMove);
	document.addEventListener('mouseup', this._handleMouseUp);
};

SignaturePad.prototype._handleTouchEvents = function () {
	this._canvas.style.msTouchAction = 'none';
	this._canvas.style.touchAction = 'none';

	this._canvas.addEventListener('touchstart', this._handleTouchStart);
	this._canvas.addEventListener('touchmove', this._handleTouchMove);
	this._canvas.addEventListener('touchend', this._handleTouchEnd);
};

SignaturePad.prototype._reset = function () {
	this.points = [];
	this._lastVelocity = 0;
	this._lastWidth = (this.minWidth + this.maxWidth) / 2;
	this._ctx.fillStyle = this.penColor;
};

SignaturePad.prototype._createPoint = function (x, y, time) {
	var rect = this._canvas.getBoundingClientRect();
	return new Point(x - rect.left, y - rect.top, time || new Date().getTime());
};

SignaturePad.prototype._addPoint = function (point) {
	var points = this.points;
	var tmp = void 0;
	points.push(point);
	if (points.length > 2) {
		if (points.length === 3) points.unshift(points[0]);
		tmp = this._calculateCurveControlPoints(points[0], points[1], points[2]);
		var c2 = tmp.c2;
		tmp = this._calculateCurveControlPoints(points[1], points[2], points[3]);
		var c3 = tmp.c1;
		var curve = new Bezier(points[1], c2, c3, points[2]);
		var widths = this._calculateCurveWidths(curve);
		points.shift();
		return { curve: curve, widths: widths };
	}
	return {};
};

SignaturePad.prototype._calculateCurveControlPoints = function (s1, s2, s3) {
	var dx1 = s1.x - s2.x;
	var dy1 = s1.y - s2.y;
	var dx2 = s2.x - s3.x;
	var dy2 = s2.y - s3.y;
	var m1 = { x: (s1.x + s2.x) / 2.0, y: (s1.y + s2.y) / 2.0 };
	var m2 = { x: (s2.x + s3.x) / 2.0, y: (s2.y + s3.y) / 2.0 };
	var l1 = Math.sqrt(dx1 * dx1 + dy1 * dy1);
	var l2 = Math.sqrt(dx2 * dx2 + dy2 * dy2);
	var dxm = m1.x - m2.x;
	var dym = m1.y - m2.y;
	var k = l2 / (l1 + l2);
	var cm = { x: m2.x + dxm * k, y: m2.y + dym * k };
	var tx = s2.x - cm.x;
	var ty = s2.y - cm.y;
	return {
	c1: new Point(m1.x + tx, m1.y + ty),
	c2: new Point(m2.x + tx, m2.y + ty)
	};
};

SignaturePad.prototype._calculateCurveWidths = function (curve) {
	var startPoint = curve.startPoint;
	var endPoint = curve.endPoint;
	var widths = { start: null, end: null };
	var velocity = this.velocityFilterWeight * endPoint.velocityFrom(startPoint) + (1 - this.velocityFilterWeight) * this._lastVelocity;
	var newWidth = this._strokeWidth(velocity);
	widths.start = this._lastWidth;
	widths.end = newWidth;
	this._lastVelocity = velocity;
	this._lastWidth = newWidth;
	return widths;
};

SignaturePad.prototype._strokeWidth = function (velocity) {
	return Math.max(this.maxWidth / (velocity + 1), this.minWidth);
};

SignaturePad.prototype._drawPoint = function (x, y, size) {
	var ctx = this._ctx;
	ctx.moveTo(x, y);
	ctx.arc(x, y, size, 0, 2 * Math.PI, false);
	this._isEmpty = false;
};

SignaturePad.prototype._drawCurve = function (curve, startWidth, endWidth) {
	var ctx = this._ctx;
	var widthDelta = endWidth - startWidth;
	var drawSteps = Math.floor(curve.length());
	ctx.beginPath();
	for (var i = 0; i < drawSteps; i += 1) {
		var t = i / drawSteps;
		var tt = t * t;
		var ttt = tt * t;
		var u = 1 - t;
		var uu = u * u;
		var uuu = uu * u;
		var x = uuu * curve.startPoint.x;
		x += 3 * uu * t * curve.control1.x;
		x += 3 * u * tt * curve.control2.x;
		x += ttt * curve.endPoint.x;
		var y = uuu * curve.startPoint.y;
		y += 3 * uu * t * curve.control1.y;
		y += 3 * u * tt * curve.control2.y;
		y += ttt * curve.endPoint.y;
		var width = startWidth + ttt * widthDelta;
		this._drawPoint(x, y, width);
	}
	ctx.closePath();
	ctx.fill();
};

SignaturePad.prototype._drawDot = function (point) {
	var ctx = this._ctx;
	var width = typeof this.dotSize === 'function' ? this.dotSize() : this.dotSize;
	ctx.beginPath();
	this._drawPoint(point.x, point.y, width);
	ctx.closePath();
	ctx.fill();
};

SignaturePad.prototype._fromData = function (pointGroups, drawCurve, drawDot) {
	for (var i = 0; i < pointGroups.length; i += 1) {
		var group = pointGroups[i];
		if (group.length > 1) {
		for (var j = 0; j < group.length; j += 1) {
		var rawPoint = group[j];
		var point = new Point(rawPoint.x, rawPoint.y, rawPoint.time);
		if (j === 0) {
		this._reset();
		this._addPoint(point);
		} else if (j !== group.length - 1) {
		var _addPoint2 = this._addPoint(point),
		curve = _addPoint2.curve,
		widths = _addPoint2.widths;

		if (curve && widths) {
		drawCurve(curve, widths);
		}
		} else {
		}
		}
		} else {
		this._reset();
		var _rawPoint = group[0];
		drawDot(_rawPoint);
		}
	}
};

SignaturePad.prototype._toSVG = function () {
	var _this2 = this;
	var pointGroups = this._data;
	var canvas = this._canvas;
	var minX = 0;
	var minY = 0;
	var maxX = canvas.width;
	var maxY = canvas.height;
	var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
	svg.setAttributeNS(null, 'width', canvas.width);
	svg.setAttributeNS(null, 'height', canvas.height);
	this._fromData(pointGroups, function (curve, widths) {
		var path = document.createElement('path');
		if (!isNaN(curve.control1.x) && !isNaN(curve.control1.y) && !isNaN(curve.control2.x) && !isNaN(curve.control2.y)) {
		var attr = 'M ' + curve.startPoint.x.toFixed(3) + ',' + curve.startPoint.y.toFixed(3) + ' ' + ('C ' + curve.control1.x.toFixed(3) + ',' + curve.control1.y.toFixed(3) + ' ') + (curve.control2.x.toFixed(3) + ',' + curve.control2.y.toFixed(3) + ' ') + (curve.endPoint.x.toFixed(3) + ',' + curve.endPoint.y.toFixed(3));

		path.setAttribute('d', attr);
		path.setAttribute('stroke-width', (widths.end * 2.25).toFixed(3));
		path.setAttribute('stroke', _this2.penColor);
		path.setAttribute('fill', 'none');
		path.setAttribute('stroke-linecap', 'round');

		svg.appendChild(path);
		}
	}, function (rawPoint) {
		var circle = document.createElement('circle');
		var dotSize = typeof _this2.dotSize === 'function' ? _this2.dotSize() : _this2.dotSize;
		circle.setAttribute('r', dotSize);
		circle.setAttribute('cx', rawPoint.x);
		circle.setAttribute('cy', rawPoint.y);
		circle.setAttribute('fill', _this2.penColor);
		svg.appendChild(circle);
	});
	var prefix = 'data:image/svg+xml;base64,';
	var header = '<svg' + ' xmlns="http://www.w3.org/2000/svg"' + ' xmlns:xlink="http://www.w3.org/1999/xlink"' + (' viewBox="' + minX + ' ' + minY + ' ' + maxX + ' ' + maxY + '"') + (' width="' + maxX + '"') + (' height="' + maxY + '"') + '>';
	var body = svg.innerHTML;
	if (body === undefined) {
	var dummy = document.createElement('dummy');
	var nodes = svg.childNodes;
	dummy.innerHTML = '';
	for (var i = 0; i < nodes.length; i += 1) {
		dummy.appendChild(nodes[i].cloneNode(true));
	}
	body = dummy.innerHTML;
	}
	var footer = '</svg>';
	var data = header + body + footer;
	return prefix + btoa(data);
};

SignaturePad.prototype.fromData = function (pointGroups) {
	var _this3 = this;
	this.clear();
	this._fromData(pointGroups, function (curve, widths) {
	return _this3._drawCurve(curve, widths.start, widths.end);
	}, function (rawPoint) {
	return _this3._drawDot(rawPoint);
	});
};

SignaturePad.prototype.toData = function () {
	return this._data;
};

return SignaturePad;

})));
