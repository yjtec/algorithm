class player {
    constructor(props) {
        this.canvas = props.canvas;//画图用的
        this.box = props.box;//小正方形的宽高
        this.w = props.w;//图片最大宽度
        this.h = props.h;//图片最大高度
        this.map = new Array();//地图数据，0代表可行走，正数代表不能行走（墙）
        this.points = new Array();
        this.results = new Array();
        if (this.map.length <= 0) {
            this.initMap();
        }
    }
    initMap() {
        for (var $i = 0; $i < this.w; $i++) {
            this.map[$i] = new Array();
            for (var $j = 0; $j < this.h; $j++) {//随机生成地图
                this.map[$i][$j] = parseInt(Math.random() * 10) > 0 ? 0 : 1; //0代表可行走，1代表不可行走（墙）
            }
        }
    }
    randStart2End() {
        for (var i = 0; i < 2; i++) {
            let point = [parseInt(Math.random() * (this.w - 1) + 1), parseInt(Math.random() * (this.h - 1) + 1)];
            this.points.push(point);
//            this.dis[point[0]][point[1]] = 0;
//            this.disLast[point[0]][point[1]] = [point[0], point[1]];
//            if (this.map[point[0]][point[1]] != 0) {
//                i--;
//            }
        }
    }
    main() {
        for (var x = 0; x < this.points.length - 1; x++) {
            console.log(x)
            let startPoint = this.points[x];
            let endPoint = this.points[x + 1];
            let line = this.getRoad(startPoint, endPoint)
            this.results.push(line);
        }
        console.log(this.results)
        this.drawMap();
        this.drawRouad();
        return this.results;
    }
    getRoad(startPoint, endPoint) {
        var cPoint = startPoint;
        this.init();
        do {
            this.getRound8Point(cPoint);//当前点周围的八个点分别检测，并按照从大到小顺序压入open数组
            var cPoint = this.open.pop();//取得最后一个元素，也就是最小的元素
            if (cPoint[0] == endPoint[0] && cPoint[1] == endPoint[1]) {//如果这个元素是end点，也就是寻找到了结束点，即可跳出循环，这里是跟广度最大的区别，因为上一步取得的是最小点，也就保证了这里跳出的时候，是最短路径
                break;
            }
        } while (cPoint);
        return this.getResult(endPoint, startPoint);
    }
    init() {
        this.dis = new Array();//已经检测过的点距离起始点的距离数据
        this.start = new Array();//起始点
        this.end = new Array();//结束点
        this.open = new Array();//所有待检测的点
        this.disLast = new Array();//上一点的坐标
        for (var $i = 0; $i < this.w; $i++) {
            this.dis[$i] = new Array();
            this.disLast[$i] = new Array();
            for (var $j = 0; $j < this.h; $j++) {//随机生成地图
                this.dis[$i][$j] = -1; //-1代表还没有检查的点
            }
        }
    }
    getRound8Point(cPoint) {
        var distance = this.dis[cPoint[0]][cPoint[1]];
        var x, y;
        for (x = -1; x < 2; x++) {//一列一列的搜索
            for (y = -1; y < 2; y++) {
                if (cPoint[0] + x >= 0 && cPoint[1] + y >= 0 && cPoint[0] + x < this.w && cPoint[1] + y < this.h) {
                    if (this.map[cPoint[0] + x][cPoint[1] + y] != 0) {//这个点不行走
                        this.dis[cPoint[0] + x][cPoint[1] + y] = -2;
                        continue;
                    }
//                    let temDis = Math.sqrt(Math.pow(x, 2) + Math.pow(y, 2));//这个点离中心点的距离
                    let temDis = Math.abs(x) - Math.abs(y) == 0 ? 1.414 : 1;//这里简化了一步，如果用上一行的距离计算公式也可以
                    if (this.dis[cPoint[0] + x][cPoint[1] + y] == -1 || this.dis[cPoint[0] + x][cPoint[1] + y] > distance + temDis) {//这个点没计算过距离，或者这个点的距离比之前的小
                        this.dis[cPoint[0] + x][cPoint[1] + y] = distance + temDis;
                        this.insertOpen([cPoint[0] + x, cPoint[1] + y]);
                        this.disLast[cPoint[0] + x][cPoint[1] + y] = [cPoint[0], cPoint[1]];
                    }
                }
            }
        }
    }
    insertOpen(point) {
        if (this.open.length == 0) {
            this.open.push(point);
        } else if (this.dis[this.open[0][0]][this.open[0][1]] < this.dis[point[0]][point[1]]) {
            this.open.unshift(point);
        } else {
            for (var i = 1; i < this.open.length; i++) {
                this.cnts++;
                if (this.dis[this.open[i][0]][this.open[i][1]] < this.dis[point[0]][point[1]]) {
                    this.open.splice(i - 1, 0, point);
                    return;
                }
            }
            this.open.push(point);
        }
    }
    getResult(endPoint, startPoint) {//路径已经找到，组合成一个数组
        var result = new Array();
        var $curLine = endPoint;
        while (true) {
            result.unshift([this.disLast[$curLine[0]][$curLine[1]][0], this.disLast[$curLine[0]][$curLine[1]][1]]);
            if ($curLine[0] == startPoint[0] && $curLine[1] == startPoint[1]) {
                break;
            }
            $curLine = this.disLast[$curLine[0]][$curLine[1]];
        }
        return result;
    }
    drawMap() {
//        console.log(this.disLast)
        for (var $i = 0; $i < this.w; $i++) {
            for (var $j = 0; $j < this.h; $j++) {
                var color = this.map[$i][$j] ? "#FF0000" : "#FFFFFF";
                this.drawBox($i * this.box, $j * this.box, this.box, color);
            }
        }
    }
    drawRouad() {
        for (var x = 0; x < this.results.length - 1; x++) {
            var curRoad = this.results[x];
            for (var y = 0; y < curRoad.length; y++) {
                this.drawBox(curRoad[y][0] * this.box, curRoad[y][1] * this.box, this.box, "#00FF00");
            }
            this.drawBox(curRoad[0][0] * this.box, curRoad[0][1] * this.box, this.box, "#00F0F0");
            this.drawText(x, curRoad[0][0] * this.box, curRoad[0][1] * this.box + 8);
        }
//        this.drawBox(this.results[x][y][0] * this.box, this.results[x][y][1] * this.box, this.box, "#00FFFF");
    }
    drawText(text, x, y) {
        this.canvas.font = '10px Arial';
        this.canvas.fillStyle = '#000000';
        this.canvas.fillText(text, x, y);
    }
    drawBox(x, y, w, color, fill = true) {
        this.canvas.fillStyle = color;
        if (fill) {//填充方块儿
            this.canvas.fillRect(x, y, w, w);
        } else {//空方块
            this.canvas.rect(x, y, w, w);
            this.canvas.stroke();
        }
        return;
    }
}


