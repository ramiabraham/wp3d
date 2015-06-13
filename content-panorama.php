<?php
/**
 * The template used for displaying panoramas
 *
 * @package wp3d
 */
?>

<div class="info-button">
	<a href="javascript:toggleInfo();"></a>
</div>

<div class="info">

	<?php the_field( 'primary_info_modal_text' ); ?>

	Oh also the current renderer is <span id="renderer-info">NOTHING, because canvas is not supported.</span>

</div>

<div class="crosshair"></div>
<div id="container"></div>

<div id="loading-box">OMG LOADING, GIVE ME A SEC...</div>

<div id="popout">

  <div class="popout-bg"></div>

  <div class="popout-content">

    <div class="thumb-container">

    	<img class="thumb" src="" width="150" height="150" />

    </div>

    <div class="member-info">

		<span class="name-tag">&nbsp;</span><br/>
		<span class="role-tag">&nbsp;</span>

    </div>

	</div>

</div>

<script type="text/javascript">

jQuery(document).ready(function($){

	  var ticker = 0;
	  $('.crosshair').css('visibility', 'hidden');



	function Ticker() {
	  this.handlers = [];
	  this.contextObjs = [];
	  this._lft = new Date().getTime();
	  this.frameRate = 15;
	  this.frameTime = 1000/this.frameRate;
	}

	Ticker.prototype = {
	  constructor: Ticker,
	  add: function (obj, handler) {
	    if(! handler instanceof Function) return;
	    if(this.contextObjs.indexOf(obj) == -1 || this.handlers.indexOf(handler) == -1) {
	      this.handlers.push(handler);
	      this.contextObjs.push(obj);
	    }
	  },
	  remove: function (handler) {
	    var pos = this.handlers.indexOf(handler);
	    if(pos >= 0) {
	      this.handlers.splice(pos,1);
	      this.contextObjs.splice(pos, 1);
	    }
	  },
	  tick: function() {
	    var now = new Date().getTime();
	    if((now - this._lft) > this.frameTime) {
	      this._lft = now;
	      for (var i = 0; i < this.handlers.length; ++i) {
	        this.handlers[i].call(this.contextObjs[i]);
	      }
	    }
	  }

	};
	Ticker.instance = new Ticker();

	//Implement Popout class wrap around a div
	function Popout() {
	  this._scrX = 0;
	  this._scrY = 0;
	  this._$ = $(this);
	}

	Popout.prototype = {
	  constructor: Popout,
	  setPosition: function(x, y) {
	    this._scrX = x;
	    this._scrY = y;
	    this.style.left = x + 'px';
	    this.style.top = y + 'px';
	  },
	  getX: function() { return this._scrX; },
	  getY: function() { return this._scrY; },
	  setData: function(markerObj) {
	    this._$.find('img')[0].src = markerObj.image;
	    this._$.find('.name-tag')[0].innerHTML = markerObj.name;
	    this._$.find('.role-tag')[0].innerHTML = markerObj.role;

	  },
	  show: function(time) {
	    if(this.style.display != 'block') this._$.stop().show(time);
	  },
	  hide: function(time) {
	    this._$.stop().hide(time);
	  }
	};

	Popout.extend = function (divEl) {
	  $.extend(divEl, Popout.prototype);
	  Popout.apply(divEl);
	  return divEl;
	};

	function BaseMarker() {
	  this._scrX = 0;
	  this._scrY = 0;
	  this._live = true;
	}

	BaseMarker.prototype = {
	  constructor: BaseMarker,
	  setPosition: function(x, y) {
	    this._scrX = x;
	    this._scrY = y;
	    this.style.left = x + 'px';
	    this.style.top = y + 'px';
	  },
	  getX: function() { return this._scrX; },
	  getY: function() { return this._scrY; },
	  visible: function(value) {
	    var isVisible = (this.style.display != 'none');
	    if(value === undefined) {
	      return isVisible;
	    } else {
	      if(value != isVisible) {
	        this.style.display = (value)?'block':'none';
	      }
	      return value;
	    }
	  },
	  activate: function() {
	    this._live = true;
	  },
	  deactivate: function() {
	    this._live = false;
	  }
	};

	function NavMarker(index, initObj) {
	  BaseMarker.call(this);
	  this.className = 'navigate-point';
	  this.id = 'navmarker'+index;
	  this.position = new THREE.Vector3(initObj.x, initObj.y, initObj.z);
	  this.target = initObj.target;

	  this.unitVector = new THREE.Vector3(initObj.x,  initObj.y, initObj.z).normalize();

	  this._bgX = 0;
	  Ticker.instance.add(this, this._animateBackground);
	}

	NavMarker.prototype = new BaseMarker();
	NavMarker.prototype.constructor = NavMarker;
	NavMarker.prototype._animateBackground = function (){
	  if(!this.visible()) return;
	  this.style.backgroundPosition = this._bgX +'px 0px';
	  this._bgX += 80;
	  if(this._bgX >= 1120) this._bgX = 0;
	};

	//factory function to create a PosMarker extends a Div element
	NavMarker.create = function(index, initObj) {
	  var element = document.createElement('div');
	  $.extend(element, NavMarker.prototype);
	  NavMarker.apply(element, arguments);
	  return element;
	};

	//Implement Marker class wrap around a div
	function PosMarker(index,x, y, z, personObj) {
	  BaseMarker.call(this);
	  this.className = 'marker';
	  this.id = 'marker'+index;
	  this.position = new THREE.Vector3(x, y, z);
	  this.data = personObj;
	  if(!personObj) alert('no personObj for marker ' + index);
	  this.innerHTML = '<span class="marker-label">' + personObj.name + '</span><br/><div class="marker-symbol"></div>';

	  this.addEventListener('click', this.markerClickHandler);
	  this.unitVector = new THREE.Vector3(x, y, z).normalize();

	  this._bgX = 0;
	  Ticker.instance.add(this, this._animateBackground);
	}

	PosMarker.prototype = new BaseMarker();
	PosMarker.prototype.constructor = PosMarker;
	PosMarker.prototype._animateBackground = function (){
	  if(!this.visible()) return;
	  this.childNodes[2].style.backgroundPosition = this._bgX +'px 0px';
	  this._bgX += 40;
	  if(this._bgX > 280) this._bgX = 0;
	};
	PosMarker.prototype.markerClickHandler = function(e) {
	  popout.setData(this.data);
	  popout.setPosition(this._scrX, this._scrY);
	  popout.targetMarker = this;
	  popout.show(200);
	  $('#container')[0].addEventListener('mouseup', this.clickOutsideHandler);
	};
	PosMarker.prototype.clickOutsideHandler = function (e) {
	  popout.hide(200);
	  popout.targetMarker = null;
	  e.currentTarget.removeEventListener('mouseup', this.clickOutsideHandler);
	};
	//override visible function
	PosMarker.prototype.visible = function(value) {
	  if(value) {
	    //move register point at center of div
	    this.style.marginLeft =(- this.offsetWidth / 2) + 'px';
	  }
	  return BaseMarker.prototype.visible.call(this, value);

	};

	//factory function to create a PosMarker extends a Div element
	PosMarker.create = function(index, x, y, z, personObj) {
	  var element = document.createElement('div');
	  $.extend(element, PosMarker.prototype);
	  PosMarker.apply(element, arguments);
	  return element;
	};

	function Panorama (panoramaObj, people) {
	  this.textureFile = panoramaObj.mapFile;

	  this.group = new THREE.Object3D();

	  var this_obj = this;
	  var panoMap = THREE.ImageUtils.loadTexture( this.textureFile, undefined, function() {
	    if(typeof this_obj.textureLoadCompleteCallback == 'function') {
	      this_obj.textureLoadCompleteCallback.apply();
	    }
	    render(true);
	  });

	  var sphere;
	  if(isWebGL) {
	    sphere = new THREE.Mesh( new THREE.SphereGeometry( 500, 60, 40 ), new THREE.MeshBasicMaterial( { map: panoMap, wireframe: false } ) );
	  } else {
	    //reduce number of poligons to improve performance
	    sphere = new THREE.Mesh( new THREE.SphereGeometry( 500, 60,40 ), new THREE.MeshBasicMaterial( { map: panoMap, wireframe: false} ) );
	    sphere.overdraw = true; //seamless texture
	  }
	  sphere.doubleSided = true;
	  sphere.scale.x = -1;
	  this.sphere = sphere;
	  this.group.add(sphere);
	  var i;
	  this.markers = [];
	  this.markerLen = panoramaObj.markers.length;
	  for (i = 0; i < this.markerLen; ++i) {
	    var markerObj = panoramaObj.markers[i];
	    var marker = PosMarker.create(i, markerObj.x, markerObj.y, markerObj.z, people[markerObj.person]);

	    //document.body.appendChild(marker);
	    this.markers.push(marker);
	  }

	  this.navMarkers = [];
	  for ( i = 0; i < panoramaObj.navMarkers.length; ++i) {
	    var navMarker = NavMarker.create(i, panoramaObj.navMarkers[i]);
	    this.navMarkers.push(navMarker);
	     navMarker.addEventListener('click', this.switchPanoramaHandler);
	  }
	}

	// TODO: copy global variable to inside class
	Panorama.prototype = {
	  constructor: Panorama,
	  place: function() {
	    this.group.position.x = 0;
	    this.group.position.y = 0;
	    this.group.position.z = 0;
	    scene.add( this.group);
	  },
	  placeMarkers: function() {
	    var i;
	    for ( i = 0; i < this.markerLen; ++i) {
	      document.body.appendChild(this.markers[i]);
	      this.markers[i].activate();
	    }
	    for (i = 0; i < this.navMarkers.length; ++i) {
	      document.body.appendChild(this.navMarkers[i]);
	      this.navMarkers[i].activate();
	    }
	  },
	  remove: function() {
	    scene.remove(this.group);
	  },
	  removeMarkers: function() {
	    for (var i = 0; i < this.markerLen; ++i) {
	      document.body.removeChild(this.markers[i]);
	      this.markers[i].deactivate();
	    }
	    for (i = 0; i < this.navMarkers.length; ++i) {
	      document.body.removeChild(this.navMarkers[i]);
	      this.navMarkers[i].deactivate();
	    }
	  },
	  switchPanoramaHandler: function(event) {
	    var navMarker = event.currentTarget;
	    var movePos = new THREE.Vector3(- navMarker.position.x, - navMarker.position.y, - navMarker.position.z);
	    movePos.normalize();
	    movePos.multiplyScalar( 300 );

	    var targetPano = panoList[navMarker.target];
	    activePano.removeMarkers();
	    needRender = true;
	    if(isWebGL) new TWEEN.Tween(activePano.sphere.materials[0]).to({opacity: 0}, 500).start();
	    new TWEEN.Tween(activePano.group.position).to({
	      x: movePos.x, y: movePos.y, z: movePos.z}, 500).onComplete(function() {
	          activePano.remove();
	          targetPano.place();

	          activePano = targetPano; //closure scope problem with this.target
	          lat = 0;
	          lon = 0;
	          if(isWebGL) {
	            targetPano.sphere.materials[0].opacity = 0;
	            new TWEEN.Tween(targetPano.sphere.materials[0]).to({opacity: 1}, 500).onComplete(function() {
	              targetPano.placeMarkers();
	              needRender = false;
	            }).start();
	          } else {
	            targetPano.placeMarkers();
	          }

	      }).start();
	  },
	  update: function() {
	    var camUnitVector = camera.target.clone().normalize();
	    var i, angle, sameSide, p2D, marker;
	    for (i = 0; i < this.markerLen; ++i) {
	      marker =  this.markers[i];
	      p2D = projector.projectVector(marker.position.clone(), camera);

	      p2D.x = (p2D.x + 1)/2 * window.innerWidth;
	      p2D.y = - (p2D.y - 1)/2 * window.innerHeight;

	      //my trick
	      angle = Math.acos(camUnitVector.dot(marker.unitVector)) * 180 / 3.14;
	      sameSide = (angle < 90);

	      if(!sameSide || p2D.x < 0 || p2D.x > window.innerWidth ||
	         p2D.y < 0 || p2D.y > window.innerHeight) {
	        marker.visible(false);
	      } else {
	        marker.visible(true);
	        marker.setPosition(p2D.x, p2D.y);
	      }
	      if (popout.targetMarker === marker) {
	        marker.visible(false);
	        popout.setPosition(marker.getX(), marker.getY());
	      }
	    }

	    for (i = 0; i < this.navMarkers.length; ++i) {
	      marker =  this.navMarkers[i];
	      p2D = projector.projectVector(marker.position.clone(), camera);

	      p2D.x = (p2D.x + 1)/2 * window.innerWidth;
	      p2D.y = - (p2D.y - 1)/2 * window.innerHeight;

	      //my trick
	      angle = Math.acos(camUnitVector.dot(marker.unitVector)) * 180 / 3.14;
	      sameSide = (angle < 90);

	      if(!sameSide || p2D.x < 0 || p2D.x > window.innerWidth ||
	         p2D.y < 0 || p2D.y > window.innerHeight) {
	        marker.visible(false);
	      } else {
	        marker.visible(true);
	        marker.setPosition(p2D.x, p2D.y);
	      }
	    }
	  }
	};

	// party time

	var camera, scene, renderer, projector;

	var mouse = { x: 0, y: 0 }, intersected;

	var fov = 70,
	    texture_placeholder,
	    isUserInteracting = false,
	    onMouseDownMouseX = 0, onMouseDownMouseY = 0,
	    lon = 0, onMouseDownLon = 0,
	    lat = 0, onMouseDownLat = 0,
	    phi = 0, theta = 0,
	    onPointerDownPointerX,
	    onPointerDownPointerY,
	    onPointerDownLon,
	    onPointerDownLat,
	    isWebGL = false,
	    needRender = false;

	var container, popout, activePano, panoList;

	//testing
	var targetPos = {x:0, y:0, z:0};

	init();
	animate();

	function init() {
	  $('.crosshair').visible=false;
	  container = document.getElementById( 'container' );

	  camera = new THREE.PerspectiveCamera( fov, window.innerWidth / window.innerHeight, 1, 1100 );

	  camera.target = new THREE.Vector3( 0, 0, 0 );

	  scene = new THREE.Scene();

	  projector = new THREE.Projector();

	  var rendererInfo = document.getElementById('renderer-info');

	  if (!Detector.canvas) {
	    document.getElementById('loading-box').innerHTML = 'Your browser doesn\'t support <br/>HTML5 Canvas.';
	  }

	  if (Detector.webgl) {
	    renderer = new THREE.WebGLRenderer();
	    rendererInfo.innerHTML = "WebGL Renderer (Fast)";
	    isWebGL = true;
	  } else {
	    renderer = new THREE.CanvasRenderer();
	    rendererInfo.innerHTML = "2D Canvas Renderer (Slow)";
	  }
	  renderer.setSize( window.innerWidth, window.innerHeight );

	 container.appendChild( renderer.domElement );

	  panoList = [];

	  for (var i = 0; i <  officeData.panorama.length; ++i) {
	    panoList.push(new Panorama(officeData.panorama[i], officeData.people));
	  }

	  activePano = panoList[0];
	  activePano.textureLoadCompleteCallback = panoramaReadyHandler;
	  activePano.place();
	  activePano.placeMarkers();

	  popout = Popout.extend($('#popout').get(0));

	  renderer.sortObjects = false;
	  container.appendChild( renderer.domElement );

	  document.addEventListener( 'mousedown', onDocumentMouseDown, false );
	  document.addEventListener( 'mousemove', onDocumentMouseMove, false );
	  document.addEventListener( 'mouseup', onDocumentMouseUp, false );
	  document.addEventListener( 'keydown', onDocumentKeyboard, false );
	}

	function panoramaReadyHandler() {
	  $('#loading-box').hide(500);
	  toggleInfo();
	}

	function onDocumentMouseDown( event ) {

	  event.preventDefault();

	  isUserInteracting = true;

	  onPointerDownPointerX = event.clientX;
	  onPointerDownPointerY = event.clientY;

	  onPointerDownLon = lon;
	  onPointerDownLat = lat;

	}


	function onDocumentMouseMove( event ) {

	  if ( isUserInteracting ) {

	    lon = ( onPointerDownPointerX - event.clientX ) * 0.1 + onPointerDownLon;
	    lat = ( event.clientY - onPointerDownPointerY ) * 0.1 + onPointerDownLat;
	    needRender = true;
	  }

	  mouse.x = ( event.clientX / window.innerWidth ) * 2 - 1;
	  mouse.y = - ( event.clientY / window.innerHeight ) * 2 + 1;
	}

	function onDocumentMouseUp( event ) {

	  isUserInteracting = false;

	  var vector = new THREE.Vector3( mouse.x, mouse.y, 0.5 );
	  projector.unprojectVector( vector, camera );

	  var ray = new THREE.Ray( camera.position, vector.subSelf( camera.position ).normalize() );

	  var intersects = ray.intersectScene( scene );
	  needRender = false;

	  if(ticker == 1){
	    console.log('X position: '+camera.target.x);
	    console.log('Y position: '+camera.target.y);
	    console.log('Z position: '+camera.target.z);
	    console.log('-----------------------------');
	  }
	}

	function onDocumentKeyboard(e){

	    var keyCode = e.keyCode;
	    var increment=1;

	    if (keyCode == 37) {
	      lon = lon-increment;
	      needRender = true;
	    }

	    else  if (keyCode == 70) {

	      if( ticker == 0){
	        $('.crosshair').css('visibility', 'visible');
	        ticker = 1;
	      } else {
	        $('.crosshair').css('visibility', 'hidden');
	        ticker = 0;
	      }

	    }

	    else  if (keyCode == 39) {
	      lon = lon+increment;
	      needRender = true;
	    }

	    else  if (keyCode == 38) {
	      lat = lat+increment;
	      needRender = true;
	    }

	    else  if (keyCode == 40) {
	      lat = lat-increment;
	      needRender = true;
	    }

	}

	function animate() {

	  requestAnimationFrame( animate );
	  render();
	  TWEEN.update();
	}

	function render(forceRender) {


	  lat = Math.max( - 85, Math.min( 85, lat ) );
	  phi = ( 90 - lat ) * Math.PI / 180;
	  theta = lon * Math.PI / 180;

	  camera.target.x = 500 * Math.sin( phi ) * Math.cos( theta );
	  camera.target.y = 500 * Math.cos( phi );
	  camera.target.z = 500 * Math.sin( phi ) * Math.sin( theta );

	  var newPos = camera.target.clone().normalize().multiplyScalar(-20);
	  camera.position.set(newPos.x, newPos.y, newPos.z);

	  camera.lookAt( camera.target );



	  if (needRender || forceRender) renderer.render( scene, camera );
	  if(activePano) {
	    activePano.update();
	  }
	  Ticker.instance.tick();
	}

});

</script>