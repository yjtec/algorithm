var camera, scene, renderer, controls;
var mesh;

function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
    }
    return(false);
}

function initScene() {
    renderer = new THREE.WebGLRenderer({antialias: true});//抗锯齿
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(window.innerWidth, window.innerHeight);
    container.appendChild(renderer.domElement);
    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(120, window.innerWidth / window.innerHeight, 0.1, 10000);
    camera.position.set(0, 0, 0);
}
function initControls() {
    controls = new THREE.OrbitControls(camera, renderer.domElement);
    // 如果使用animate方法时，将此函数删除
    //controls.addEventListener( 'change', render );
    // 使动画循环使用时阻尼或自转 意思是否有惯性
    controls.enableDamping = true;
    //动态阻尼系数 就是鼠标拖拽旋转灵敏度
    //controls.dampingFactor = 0.25;
    //是否可以缩放
    controls.enableZoom = true;
    //是否自动旋转
    controls.autoRotate = false;
    //设置相机距离原点的最远距离
    controls.minDistance = 1;
    //设置相机距离原点的最远距离
    controls.maxDistance = 50;
    //是否开启右键拖拽
    controls.enablePan = true;
}

function addPlane(center, width, height, jiao) {
    let plane = new THREE.Mesh(new THREE.PlaneGeometry(width, height),
            new THREE.MeshBasicMaterial({
                color: 0xff0000,
                wireframe: true
            }));
    plane.position.set(center[0], center[1], center[2]);
    plane.rotation.y = jiao;
    scene.add(plane);
}

function addALine(startPoint, endPoint, color = 0xFF0000) {
    var geometry = new THREE.Geometry();
    geometry.vertices.push(new THREE.Vector3(startPoint[0], startPoint[1], startPoint[2]), new THREE.Vector3(endPoint[0], endPoint[1], endPoint[2]));
    var material = new THREE.MeshBasicMaterial();
    material = new THREE.LineBasicMaterial({color: color});
    var line = new THREE.Line(geometry, material);
    scene.add(line);
}
function getCenter(pA, pB, offset = [0, 0, 0]) {
    var pC = [pA[0], -pA[1], pA[2]];
    var pD = [pB[0], -pB[1], pB[2]];
    return [(pA[0] + pC[0] + pB[0] + pD[0]) / 4 + offset[0], (pA[1] + pC[1] + pB[1] + pD[1]) / 4 + offset[1], (pA[2] + pC[2] + pB[2] + pD[2]) / 4 + offset[2]];
}

function getPointsCenter(points, offset = [0, 0, 0]) {
    var center = [0, 0, 0];
    for (var i = 0; i < points.length; i++) {
        center[0] += points[i][0] / points.length;
        center[1] += points[i][1] / points.length;
        center[2] += points[i][2] / points.length;
    }
    return [center[0] + offset[0], center[1] + offset[1], center[2] + offset[2]];
}

function distance(pA, pB) {
    return Math.sqrt(Math.pow(pB[0] - pA[0], 2) + Math.pow(pB[1] - pA[1], 2) + Math.pow(pB[2] - pA[2], 2));
}
function r2d(rad) {
    return rad * 180 / Math.PI;
}
function d2r(degree) {
    return degree * Math.PI / 180;
}

function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
}
function addALine(startPoint, endPoint, color = 0xFF0000) {
    var geometry = new THREE.Geometry();
    geometry.vertices.push(startPoint, endPoint);
    var material = new THREE.MeshBasicMaterial();
    material = new THREE.LineBasicMaterial({color: color});
    var line = new THREE.Line(geometry, material);
    scene.add(line);
}

function addSphere() {
    var geometry = new THREE.SphereGeometry(1, 10, 10);
    var material = new THREE.MeshLambertMaterial({color: 0xFF0000});
    var mesh = new THREE.Mesh(geometry, material);
    scene.add(mesh);
}

function addPano(pano) {
    var material = new THREE.MeshBasicMaterial({map: new THREE.TextureLoader().load(pano)});

    skyBox = new THREE.Mesh(
            new THREE.SphereBufferGeometry(100, 100, 100),
            material
            );
    skyBox.geometry.scale(1, 1, -1);
    scene.add(skyBox);
}

function changePano(pano) {
    skyBox.material.map.image = pano;
    skyBox.material.map.needsUpdate = true;
}

function addCube() {
    cube = new THREE.Mesh(new THREE.CubeGeometry(20, 20, 20),
            new THREE.MeshBasicMaterial({wireframe: true, color: 0xff0000})
            );
    scene.add(cube);
}