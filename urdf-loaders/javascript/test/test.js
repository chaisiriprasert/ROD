/* global
    THREE URDFLoader
    jest
    describe it beforeAll afterAll beforeEach afterEach expect
*/
const puppeteer = require('puppeteer');
const pti = require('puppeteer-to-istanbul');
const path = require('path');
const { loadURDF, testJointAngles } = require('./utils.js');

// TODO: Add tests for multipackage loading, other files
// TODO: Don't load from the web
// TODO: Test that joint functions rotate the joints properly
// TODO: Verify joint limits, names, etc
// TODO: Verify that the workingPath option works
// TODO: Add r2d2 model test and ensure that the appropriate primitive geometry is loaded

// set the timeout to 30s because we download geometry from the web
// which could overrun the timer.
jest.setTimeout(30000);

let browser = null, page = null;
beforeAll(async() => {

    browser = await puppeteer.launch({
        headless: true,

        // --no-sandbox is required to run puppeteer in Travis.
        // https://github.com/GoogleChrome/puppeteer/blob/master/docs/troubleshooting.md#running-puppeteer-on-travis-ci
        args: ['--no-sandbox'],
    });
    page = await browser.newPage();
    await page.addScriptTag({ path: path.join(__dirname, '../node_modules/three/build/three.min.js') });
    await page.addScriptTag({ path: path.join(__dirname, '../node_modules/three/examples/js/loaders/GLTFLoader.js') });
    await page.addScriptTag({ path: path.join(__dirname, '../node_modules/three/examples/js/loaders/OBJLoader.js') });
    await page.addScriptTag({ path: path.join(__dirname, '../node_modules/three/examples/js/loaders/STLLoader.js') });
    await page.addScriptTag({ path: path.join(__dirname, '../node_modules/three/examples/js/loaders/ColladaLoader.js') });
    await page.addScriptTag({ path: path.join(__dirname, '../URDFLoader.js') });
    await page.coverage.startJSCoverage();

    page.on('error', e => { throw new Error(e); });
    page.on('pageerror', e => { throw new Error(e); });
    page.on('console', e => {

        if (e.type() === 'error') {

            throw new Error(e.text());

        }

    });

});

describe('Options', () => {

    describe('loadMeshCb', () => {

        it('should get called to load all meshes', async() => {

            const meshesLoaded = await page.evaluate(() => {

                return new Promise(resolve => {

                    const loader = new URDFLoader();
                    loader.load(
                        'https://rawgit.com/gkjohnson/urdf-loaders/master/urdf/TriATHLETE_Climbing/urdf/TriATHLETE.URDF',
                        'https://rawgit.com/gkjohnson/urdf-loaders/master/urdf/TriATHLETE_Climbing',
                        robot => {
                            let ct = 0;
                            robot.traverse(c => {

                                if (c.fromCallback) {

                                    ct++;

                                }

                            });

                            resolve(ct);

                        },
                        {
                            loadMeshCb: (path, ext, done) => {

                                const mesh = new THREE.Mesh();
                                mesh.fromCallback = true;
                                done(mesh);

                            },
                        }
                    );
                });
            });

            expect(meshesLoaded).toEqual(28);

        });

    });

});

describe('TriATHLETE Climbing URDF', async() => {

    beforeEach(async() => {

        // Model loads STL files and has continuous, prismatic, and revolute joints
        await loadURDF(
            page,
            'https://rawgit.com/gkjohnson/urdf-loaders/master/urdf/TriATHLETE_Climbing/urdf/TriATHLETE.URDF',
            'https://rawgit.com/gkjohnson/urdf-loaders/master/urdf/TriATHLETE_Climbing'
        );

    });

    it('should have the correct number of links', async() => {

        const jointCt = await page.evaluate(() => Object.keys(window.robot.joints).length);
        const linkCt = await page.evaluate(() => Object.keys(window.robot.links).length);

        expect(jointCt).toEqual(27);
        expect(linkCt).toEqual(28);

    });

    it('should load the correct joint types', async() => {

        const joints = await page.evaluate(() => Object.keys(window.robot.joints));
        for (var key of joints) {

            const jointType = await page.evaluate(`window.robot.joints['${ key }'].jointType`);

            if (/^W/.test(key)) expect(jointType).toEqual('continuous');
            else if (/^TC\d/.test(key)) expect(jointType).toEqual('prismatic');
            else expect(jointType).toEqual('revolute');

        }

    });

    it('should respect joint limits for different joint types', async() => {

        await testJointAngles(page);

    });

    afterEach(async() => {

        await page.evaluate(() => window.robot = null);

    });

});

[
    {
        desc: 'Robonaut',
        urdf: 'https://rawgit.com/gkjohnson/nasa-urdf-robots/master/r2_description/robots/r2b.urdf',
        pkg: 'https://rawgit.com/gkjohnson/nasa-urdf-robots/master/',
    },
    {
        desc: 'Valkyrie',
        urdf: 'https://rawgit.com/gkjohnson/nasa-urdf-robots/master/val_description/model/robots/valkyrie_A.urdf',
        pkg: 'https://rawgit.com/gkjohnson/nasa-urdf-robots/master/',
    },
    {
        desc: 'Multipackage',
        urdf: 'https://raw.githubusercontent.com/ipa-jfh/urdf-loaders/2170f75bacaec933c17aeb2ee59d73643a4bab3a/multipkg_test.urdf',
        pkg: {
            blending_end_effector:
            'https://rawgit.com/ros-industrial-consortium/godel/kinetic-devel/godel_robots/blending_end_effector',

            abb_irb1200_support:
            'https://rawgit.com/ros-industrial/abb_experimental/kinetic-devel/abb_irb1200_support',

            godel_irb1200_support:
            'https://rawgit.com/ros-industrial-consortium/godel/kinetic-devel/godel_robots/abb/godel_irb1200/godel_irb1200_support',
        },
    },
].forEach(data => {

    describe(`${ data.desc } URDF`, async() => {

        beforeEach(async() => {

            // Model loads STL files and has continuous, prismatic, and revolute joints
            await loadURDF(page, data.urdf, data.pkg);

        });

        it('should respect joint limits for different joint types', async() => {

            await testJointAngles(page);

        });

        afterEach(async() => {

            await page.evaluate(() => window.robot = null);

        });

    });

});

afterAll(async() => {

    const coverage = await page.coverage.stopJSCoverage();
    const urdfLoaderCoverage = coverage.filter(o => /URDFLoader\.js$/.test(o.url));
    pti.write(urdfLoaderCoverage);

    browser.close();

});
