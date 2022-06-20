
<script type="module">

import { OrbitControls } from './jsm/controls/OrbitControls.js';
import { GLTFLoader } from './jsm/loaders/GLTFLoader.js';
import URDFLoader from 'urdf-loader';
// ...init three.js scene...

const loader = new URDFLoader();
loader.loadMeshCb = function( path, manager, onComplete ) {

    const gltfLoader = new GLTFLoader( manager );
    gltfLoader.load(
        path,
        result => {

            onComplete( result.scene );

        },
        undefined,
        err => {

            // try to load again, notify user, etc

            onComplete( null, err );

        }
    );

};
loader.load( 'http://174.138.23.208/urdf/RobotArm.urdf', robot => {

    // The robot is loaded!
    scene.add( robot );

} );


</script>
