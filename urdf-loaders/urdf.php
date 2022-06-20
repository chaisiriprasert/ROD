<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta charset="utf-8"/>

        <title>URDF Viewer</title>

        <script src="node_modules/@webcomponents/webcomponentsjs/webcomponents-bundle.js"></script>
        <script src="node_modules/three/build/three.js"></script>
        <script src="node_modules/three/examples/js/controls/OrbitControls.js"></script>
        <script src="node_modules/three/examples/js/loaders/GLTFLoader.js"></script>
        <script src="node_modules/three/examples/js/loaders/OBJLoader.js"></script>
        <script src="node_modules/three/examples/js/loaders/STLLoader.js"></script>
        <script src="node_modules/three/examples/js/loaders/ColladaLoader.js"></script>
        <script src="node_modules/threejs-model-loader/ModelLoader.js"></script>
        <script src="javascript/URDFLoader.js"></script>
        <script src="javascript/urdf-viewer-element.js"></script>
        <script src="javascript/urdf-manipulator-element.js"></script>
        <script>
            /* globals URDFViewer */
            customElements.define('urdf-viewer', URDFManipulator)
        </script>

        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300" rel="stylesheet"/>
        <link href="./styles.css" rel="stylesheet" />
        <style>
            body {
                background: #263238;
            }

            #controls {
                display: none;
            }
        </style>
    </head>
    <body tabindex="0">

        <div id="menu">
            <ul id="urdf-options">
                <li  urdf="<?php echo $_GET['urdf']; ?>">Unified Robot Description Format</li>
            </ul>

            <div id="controls" class="hidden">
                <div id="toggle-controls"></div>
                <div id="ignore-joint-limits" class="toggle">Ignore Joint Limits</div>
                <label>
                    Up Axis
                    <select id="up-select">
                        <option value="+X">+X</option>
                        <option value="-X">-X</option>
                        <option value="+Y">+Y</option>
                        <option value="-Y">-Y</option>
                        <option value="+Z">+Z</option>
                        <option value="-Z" selected>-Z</option>
                    </select>
                </label>
                <ul></ul>
            </div>
        </div>
        <urdf-viewer display-shadow tabindex="0"></urdf-viewer>

        <script src="./index.js"></script>
        <script>
            /* eslint-disable */
            /* globals viewer */
            document.querySelectorAll('#urdf-options li[urdf]').forEach(el => {

                el.addEventListener('click', e => {

                    const urdf = e.target.getAttribute('urdf');
                    const package = e.target.getAttribute('package');

                    viewer.package = package;
                    viewer.urdf = urdf;

                });

            });
            document.addEventListener('WebComponentsReady', () => viewer.camera.position.set(-1.0, 0.5, 0.5));
        </script>
    </body>
</html>
