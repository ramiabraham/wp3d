<?php
/**
 * The template used for displaying page content in front-page.php
 *
 * @package wp3d
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>

<!--
 ___   __    _  ___      ___   __    _  _______    _______  __    _  ______
|   | |  |  | ||   |    |   | |  |  | ||       |  |   _   ||  |  | ||      |
|   | |   |_| ||   |    |   | |   |_| ||    ___|  |  |_|  ||   |_| ||  _    |
|   | |       ||   |    |   | |       ||   |___   |       ||       || | |   |
|   | |  _    ||   |___ |   | |  _    ||    ___|  |       ||  _    || |_|   |
|   | | | |   ||       ||   | | | |   ||   |___   |   _   || | |   ||       |
|___| |_|  |__||_______||___| |_|  |__||_______|  |__| |__||_|  |__||______|
 _______  ___      _______  _______  _______  __   __
|       ||   |    |       ||       ||       ||  | |  |
|  _____||   |    |   _   ||    _  ||    _  ||  |_|  |
| |_____ |   |    |  | |  ||   |_| ||   |_| ||       |
|_____  ||   |___ |  |_|  ||    ___||    ___||_     _|
 _____| ||       ||       ||   |    |   |      |   |
|_______||_______||_______||___|    |___|      |___|

-->
	<script>

	var sloppy_sitename = '<?php echo get_bloginfo( "name" );?>';

	var renderer = new THREE.WebGLRenderer( { alpha: true } );

	renderer.setSize( window.innerWidth, '600' );

	document.body.appendChild( renderer.domElement );

	var onRenderFcts= [];
	var scene	= new THREE.Scene();
	var camera	= new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.01, 1000);
	camera.position.z = 4;

	// text

	var mesh	= new THREEx.Text( sloppy_sitename + ':' )
	mesh.scale.multiplyScalar(1/2)
	mesh.position.y	= +0.6
	scene.add(mesh)
	var mesh	= new THREEx.Text('A city of')
	mesh.scale.multiplyScalar(1/2)
	scene.add(mesh)
	var mesh	= new THREEx.Text('cheesesteaks!')
	mesh.scale.multiplyScalar(1/2)
	mesh.position.y	= -0.6
	scene.add(mesh)

	// camera controls

	var mouse	= {x : 0, y : 0}
	document.addEventListener('mousemove', function(event){
		mouse.x	= (event.clientX / window.innerWidth ) - 0.5
		mouse.y	= (event.clientY / window.innerHeight) - 0.5
	}, false)
	onRenderFcts.push(function(delta, now){
		camera.position.x += (mouse.x*5 - camera.position.x) * (delta*3)
		camera.position.y += (mouse.y*5 - camera.position.y) * (delta*3)
		camera.lookAt( scene.position )
	})

	// render

	onRenderFcts.push(function(){
		renderer.render( scene, camera );
	})

	// loop

	var lastTimeMsec= null
	requestAnimationFrame(function animate(nowMsec){
		// keep looping
		requestAnimationFrame( animate );
		// measure time
		lastTimeMsec	= lastTimeMsec || nowMsec-1000/60
		var deltaMsec	= Math.min(200, nowMsec - lastTimeMsec)
		lastTimeMsec	= nowMsec
		// call each update function
		onRenderFcts.forEach(function(onRenderFct){
			onRenderFct(deltaMsec/1000, nowMsec/1000)
		})
	})
</script>


	</div><!-- .entry-content -->


</article><!-- #post-## -->
