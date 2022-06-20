<?php
$pcdfile = $_REQUEST['pcd'];
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<title>three.js webgl - PCD</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link type="text/css" rel="stylesheet" href="main.css">
	</head>
	<body>
		<script type="module">

			import * as THREE from './three.module.js';

			import { OrbitControls } from './jsm/controls/OrbitControls.js';
			import { PCDLoader } from './jsm/loaders/PCDLoader.js';

			let camera, scene, renderer;

			init();
			render();

			function init() {

				renderer = new THREE.WebGLRenderer( { antialias: true } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize(window.innerWidth, window.innerHeight );
				document.body.appendChild( renderer.domElement );

				scene = new THREE.Scene();

				camera = new THREE.PerspectiveCamera( 30, window.innerWidth / window.innerHeight, 0.01, 40 );
				camera.position.set( 0, 0, 1 );
				scene.add( camera );

				const controls = new OrbitControls( camera, renderer.domElement );
				controls.addEventListener( 'change', render ); // use if there is no animation loop
				controls.minDistance = 0.5;
				controls.maxDistance = 10;

				//scene.add( new THREE.AxesHelper( 1 ) );

				const loader = new PCDLoader();
				loader.load( '<?php echo $pcdfile; ?>', function ( points ) {

					points.geometry.center();
				//	points.geometry.rotateX( Math.PI );
					points.geometry.rotateY( Math.PI );
					scene.add( points );

					render();

				} );

				window.addEventListener( 'resize', onWindowResize );

				window.addEventListener( 'keypress', keyboard );

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

			}

			function keyboard( ev ) {

				const points = scene.getObjectByName( 'PCDviewer' );

				switch ( ev.key || String.fromCharCode( ev.keyCode || ev.charCode ) ) {

					case '+':
						points.material.size *= 1.2;
						break;

					case '-':
						points.material.size /= 1.2;
						break;

					case 'c':
						points.material.color.setHex( Math.random() * 0xffffff );
						break;

				}

				render();

			}

			function render() {

				renderer.render( scene, camera );

			}

		</script>
	</body>
</html>
