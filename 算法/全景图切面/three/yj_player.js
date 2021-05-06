class yj_player {
    tween;
    camera;
    scene;
    renderer;
    controls;
    skyBox;
    raycaster;
    constructor(props) {
        this.container = props.container || null;
    }

    initScene() {
        this.renderer = new THREE.WebGLRenderer({antialias: true}); //抗锯齿
        this.renderer.setPixelRatio(window.devicePixelRatio);
        this.renderer.setSize(window.innerWidth, window.innerHeight);
        // 参数是1表示颜色值，参数2表示透明度值
        this.renderer.setClearColor(0x3a548c, 1); //设置背景颜色
        this.renderer.localClippingEnabled = true;//对象可以被剪切
        this.container.appendChild(this.renderer.domElement);
        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(120, window.innerWidth / window.innerHeight, 0.1, 10000);
        this.camera.position.set(0, 0, 0.01);
        this.raycaster = new THREE.Raycaster();
//        this.helper()
    }
    initControls() {
        this.controls = new THREE.OrbitControls(this.camera, this.renderer.domElement);
        // 如果使用animate方法时，将此函数删除
        //controls.addEventListener( 'change', render );
        // 使动画循环使用时阻尼或自转 意思是否有惯性
        this.controls.enableDamping = true;
        //动态阻尼系数 就是鼠标拖拽旋转灵敏度
        //controls.dampingFactor = 0.25;
        //是否可以缩放
        this.controls.enableZoom = true;
        //是否自动旋转
        this.controls.autoRotate = false;
        //设置相机距离原点的最远距离
        this.controls.minDistance = 0.0001;
        //设置相机距离原点的最远距离
        this.controls.maxDistance = 50;
        //是否开启右键拖拽
        this.controls.enablePan = false;
    }
    helper() {
        var grid = new THREE.GridHelper(100, 20, 0xFF0000, 0xFFFFFF);
        grid.material.opacity = 0.1;
        grid.material.transparent = true;
        this.scene.add(grid);

        var axesHelper = new THREE.AxesHelper(30);
        this.scene.add(axesHelper);
    }
    getSelectLine(x, y) {
        var mouse = new THREE.Vector2();
        //通过鼠标点击的位置计算出raycaster所需要的点的位置，以屏幕中心为原点，值的范围为-1到1.
        mouse.x = (x / window.innerWidth) * 2 - 1;
        mouse.y = -(y / window.innerHeight) * 2 + 1;
        // 通过鼠标点的位置和当前相机的矩阵计算出raycaster
        this.raycaster.setFromCamera(mouse, this.camera);
        // 获取raycaster直线和所有模型相交的数组集合
        var crossObjs = this.raycaster.intersectObjects(this.scene.children);
        if (crossObjs.length > 0 && crossObjs[0].object.type == 'Line') {
            return crossObjs[0].object;
        }
        return false;
    }
    getClickObj(x, y, groups = null) {
        var all = new Array();
        var mouse = new THREE.Vector2();
        //通过鼠标点击的位置计算出raycaster所需要的点的位置，以屏幕中心为原点，值的范围为-1到1.
        mouse.x = (x / window.innerWidth) * 2 - 1;
        mouse.y = -(y / window.innerHeight) * 2 + 1;
        // 通过鼠标点的位置和当前相机的矩阵计算出raycaster
        this.raycaster.setFromCamera(mouse, this.camera);
        for (var i = 0; i < this.scene.children.length; i++) {
            if (this.scene.children[i].type == 'Group') {
                var crossObjs = this.raycaster.intersectObjects(this.scene.children[i].children);
                if (crossObjs.length > 0) {
                    all.push(crossObjs);
                }
            }
        }
        return all;
    }
    updateAllLines() {
        var i;
        for (i = 0; i < this.scene.children.length; i++) {
            let obj = this.scene.children[i];
            if (obj.type == 'Line') {
                obj.geometry.verticesNeedUpdate = true;
            }
        }
    }
    /**
     * 画一个面儿
     * @param {type} img
     * @param {type} center
     * @param {type} width
     * @param {type} height
     * @param {type} jiao
     * @returns {yj_player.addAroudFace.face|THREE.Mesh}
     */
    addImgFace(img, center, width, height, jiao, door = false) {
        var texture = new THREE.TextureLoader().load(img);
        var material = new THREE.MeshBasicMaterial({map: texture, transparent: true}); //side: THREE.DoubleSide 两面可见
//        if (door) {
//            var plane = new THREE.Plane();
//            var p1 = new THREE.Vector3(door[0][0], door[0][1], door[0][2]);
//            var p2 = new THREE.Vector3(door[1][0], door[1][1], door[1][2]);
//            var p3 = new THREE.Vector3(door[2][0], door[2][1], door[2][2]);
//            plane.setFromCoplanarPoints(p1, p2, p3);// 通过三个点定义一个平面
//            material.clipIntersection = true;
//            material.clippingPlanes = plane;
//        }
        var quad = new THREE.PlaneBufferGeometry(width, height);
        var face = new THREE.Mesh(quad, material);
        face.position.set(center[0], center[1], center[2]);
        face.rotation.y = jiao;
//        this.scene.add(face);
        return face;
    }
    /**
     * 画一个面儿
     * @param {type} img
     * @param {type} center
     * @param {type} width
     * @param {type} height
     * @param {type} jiao
     * @returns {yj_player.addAroudFace.face|THREE.Mesh}
     */
    addFloorFace(img, center, width, height, jiao) {
        var texture = new THREE.TextureLoader().load(img);
        var material = new THREE.MeshBasicMaterial({map: texture, transparent: true}); //side: THREE.DoubleSide 两面可见
        var quad = new THREE.PlaneBufferGeometry(width, height);
        var floor = new THREE.Mesh(quad, material);
        floor.position.set(center[0], center[1], center[2]);
//        floor.rotation.y = jiao;
        floor.rotation.x = Math.PI / 2;
        floor.rotation.y = Math.PI;
        floor.rotation.z = -jiao;
//        this.scene.add(floor);
        return floor;
    }

    assignFloorUVs(geometry, tx_pts) {
        var pM = [tx_pts[0][0], tx_pts[0][2]];
        var pL = [tx_pts[1][0], tx_pts[1][2]];
        var pQ = [tx_pts[2][0], tx_pts[2][2]];
        var len_ML = yjCommon.disXY(pM, pL);
        var len_LQ = yjCommon.disXY(pL, pQ);
        var faces = geometry.faces;
        geometry.faceVertexUvs[0] = [];
        for (var i = 0; i < faces.length; i++) {
            var v1 = geometry.vertices[faces[i].a], v2 = geometry.vertices[faces[i].b], v3 = geometry.vertices[faces[i].c];
            var pV1 = this.getNewXY(pM, pL, pQ, v1, len_ML, len_LQ);
            var pV2 = this.getNewXY(pM, pL, pQ, v2, len_ML, len_LQ);
            var pV3 = this.getNewXY(pM, pL, pQ, v3, len_ML, len_LQ);
//                    console.log(pV1, pV2, pV3);
            geometry.faceVertexUvs[0].push([pV1, pV2, pV3]);
        }
        geometry.uvsNeedUpdate = true;
    }
    getNewXY(pM, pL, pQ, pH, len_ML, len_LQ) {
        var area_HML = yjCommon.areaXY(pM, pL, [pH.x, pH.y]);
        var len_H_ML = area_HML / len_ML * 2;
        var area_HLQ = yjCommon.areaXY(pL, pQ, [pH.x, pH.y]);
        var len_H_LQ = area_HLQ / len_LQ * 2;
        return new THREE.Vector2(1 - len_H_LQ / len_ML, 1 - len_H_ML / len_LQ);
    }
    /**
     * 画一个矩形面
     * @param {type} center
     * @param {type} width
     * @param {type} height
     * @param {type} jiao
     * @returns {undefined}
     */
    addPlane(center, width, height, jiao) {
        let plane = new THREE.Mesh(new THREE.PlaneGeometry(width, height),
                new THREE.MeshBasicMaterial({
                    color: 0xff0000,
                    wireframe: true
                }));
        plane.position.set(center[0], center[1], center[2]);
        plane.rotation.y = jiao;
        this.scene.add(plane);
    }
    /**
     * 画一个多边形（仅线条）
     * @param {type} points
     * @param {type} offset
     * @returns {undefined}
     */
    addPolygon(points, offset) {
        var shaPoint = [];
        for (var i = 0; i < points.length; i++) {
            this.drawText(i, points[i][0] + offset[0], -points[i][1] - offset[1], points[i][2] + offset[2]);
            shaPoint.push(new THREE.Vector2(points[i][0] + offset[0], points[i][2] + offset[2])); //保证顺序，依次push
        }
        var shape = new THREE.Shape(shaPoint);
        var geometry = new THREE.ShapeGeometry(shape);
        this.ssignPolygonUVs(geometry);
        var material = new THREE.MeshBasicMaterial({color: 0xff0000, wireframe: true});
        var Polygon = new THREE.Mesh(geometry, material);
        Polygon.position.set(0, -3.9, 0);
        Polygon.rotation.x = Math.PI / 2;
        this.scene.add(Polygon);
    }
    ssignPolygonUVs(geometry) {
        geometry.computeBoundingBox();
        var max = geometry.boundingBox.max, min = geometry.boundingBox.min;
        var offset = new THREE.Vector2(0 - min.x, 0 - min.y);
        var range = new THREE.Vector2(max.x - min.x, max.y - min.y);
        var faces = geometry.faces;
        geometry.faceVertexUvs[0] = [];
        for (var i = 0; i < faces.length; i++) {
            var v1 = geometry.vertices[faces[i].a], v2 = geometry.vertices[faces[i].b], v3 = geometry.vertices[faces[i].c];
            geometry.faceVertexUvs[0].push([
                new THREE.Vector2((v1.x + offset.x) / range.x, (v1.y + offset.y) / range.y),
                new THREE.Vector2((v2.x + offset.x) / range.x, (v2.y + offset.y) / range.y),
                new THREE.Vector2((v3.x + offset.x) / range.x, (v3.y + offset.y) / range.y)
            ]);
        }
        geometry.uvsNeedUpdate = true;
    }

    drawText(text, x = 0, y = 0, z = 0) {
//创建canvas对象用来绘制文字
        let canvas = document.createElement('canvas');
        let ctx = canvas.getContext('2d');
        ctx.fillStyle = "rgb(255,255,255)";
        ctx.font = " 22px Arial ";
        ctx.fillText(text, 20, 20);
// 将画布生成的图片作为贴图给精灵使用，并将精灵创建在设定好的位置
        let texture = new THREE.Texture(canvas);
        texture.needsUpdate = true;
//创建精灵，将该材质赋予给创建的精灵
        let spriteMaterial = new THREE.PointsMaterial({
            map: texture,
//            transparent: true
        });
//创建坐标点，并将材质给坐标
        let geometry = new THREE.BufferGeometry();
        let vertices = [0, 0, 0];
        geometry.setAttribute('position', new THREE.Float32BufferAttribute(vertices, 3));
        let sprite = new THREE.Points(geometry, spriteMaterial);
        sprite.position.set(x, y, z);
        this.scene.add(sprite);
    }
    /**
     * 空间中画一条直线
     * @param {type} startPoint
     * @param {type} endPoint
     * @param {type} color
     * @returns {undefined}
     */
    addALine(startPoint, endPoint, color = 0xFF0000) {
        var geometry = new THREE.Geometry();
        geometry.vertices.push(startPoint, endPoint);
        var material = new THREE.MeshBasicMaterial();
        material = new THREE.LineBasicMaterial({color: color});
        var line = new THREE.Line(geometry, material);
        this.scene.add(line);
    }
    /**
     * 空间中画一个球
     * @returns {undefined}
     */
    addSphere() {
        var geometry = new THREE.SphereGeometry(1, 10, 10);
        var material = new THREE.MeshLambertMaterial({color: 0xFF0000});
        var mesh = new THREE.Mesh(geometry, material);
        this.scene.add(mesh);
    }
    /**
     * 空间中画一个全景（球+球面贴图）
     * @param {type} pano
     * @returns {undefined}
     */
    addPano(pano) {
        var material = new THREE.MeshBasicMaterial({map: new THREE.TextureLoader().load(pano)});
        this.skyBox = new THREE.Mesh(new THREE.SphereBufferGeometry(100, 100, 100), material);
//        this.skyBox.name = "pano";
        this.skyBox.geometry.scale(1, 1, -1);
        this.scene.add(this.skyBox);
    }
    /**
     * 更换全景图的贴图
     * @param {type} pano
     * @returns {undefined}
     */
    changePano(pano) {
        this.skyBox.material.map = new THREE.TextureLoader().load(pano);
        this.skyBox.material.map.needsUpdate = true;
    }
    /**
     * 空间中画一个立方体
     * @returns {undefined}
     */
    addCube() {
        var cube = new THREE.Mesh(new THREE.CubeGeometry(20, 20, 20), new THREE.MeshBasicMaterial({wireframe: true, color: 0xff0000}));
        this.scene.add(cube);
    }

    onWindowResize() {
        this.camera.aspect = window.innerWidth / window.innerHeight;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(window.innerWidth, window.innerHeight);
    }

// current1 相机当前的位置
// target1 相机的controls的target
// current2 新相机的目标位置
// target2 新的controls的target
    animateCamera(position) {
        let positionVar = {
            x1: yjPlayer.camera.position.x,
            y1: yjPlayer.camera.position.y,
            z1: yjPlayer.camera.position.z,
            x2: yjPlayer.controls.target.x,
            y2: yjPlayer.controls.target.y,
            z2: yjPlayer.controls.target.z
        };
        //关闭控制器
        this.controls.enabled = false;
        this.tween = new TWEEN.Tween(positionVar);
        this.tween.to({
            x1: position[0],
            y1: position[1],
            z1: position[2],
            x2: position[0],
            y2: position[1],
            z2: position[2] - 0.001
        }, 2000);
        this.tween.onUpdate(function () {
            yjPlayer.camera.position.set(positionVar.x1, positionVar.y1, positionVar.z1);
            yjPlayer.controls.target.set(positionVar.x2, positionVar.y2, positionVar.z2);
        })

        this.tween.onComplete(function () {
            yjPlayer.controls.enabled = true;///开启控制器
        })

        this.tween.easing(TWEEN.Easing.Cubic.InOut);
        this.tween.start();
    }
    drawText(text, x, y, z) {
        var that = this;
        this.textloader = new THREE.FontLoader();
        this.textloader.load('helvetiker_regular.typeface.json', function (resp) {
            let color = 0x006699;
            let matLite = new THREE.MeshBasicMaterial({color: color, side: THREE.DoubleSide, transparent: true});
            let shapes = resp.generateShapes(text, 0.2);
            let geometry = new THREE.ShapeBufferGeometry(shapes);
            let texts = new THREE.Mesh(geometry, matLite);
            texts.position.set(x, y, z);
            that.scene.add(texts);
        });
    }
}

