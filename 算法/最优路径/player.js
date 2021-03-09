class player {
    constructor(props) {
        this.canvas = props.canvas;
        this.box = props.box;
        this.w = props.w;
        this.h = props.h;
        this.map = new Array();
        this.dis = new Array();
        this.start = new Array();
        this.end = new Array();
        this.open = new Array();
        this.disLast = new Array();

        this.initMap();
    }
    initMap() {
        for (var $i = 0; $i < this.w; $i++) {
            this.map[$i] = new Array();
            this.dis[$i] = new Array();
            this.disLast[$i] = new Array();
            for (var $j = 0; $j < this.h; $j++) {//随机生成地图
                this.map[$i][$j] = parseInt(Math.random() * 4) > 0 ? 0 : 1; //0代表可行走，1代表不可行走（墙）
                this.dis[$i][$j] = -1; //-1代表还没有检查的点
            }
        }
        while (true) {
            this.start = [parseInt(Math.random() * (this.w - 1) + 1), parseInt(Math.random() * (this.h - 1) + 1)];
            this.end = [parseInt(Math.random() * (this.w - 1) + 1), parseInt(Math.random() * (this.h - 1) + 1)];
            this.dis[this.start[0]][this.start[1]] = 0;
            this.disLast[this.start[0]][this.start[1]] = [this.start[0], this.start[1]];
            if (this.map[this.start[0]][this.start[1]] == 0 && this.map[this.end[0]][this.end[1]] == 0 && this.start[0] != this.end[0] && this.start[1] != this.end[1]) {
                break;
            }
        }
        console.log(this.map, this.start, this.end);
    }
    main() {
        var cPoint = this.start;
        this.getRound8Point(cPoint);
        var onePoint = this.open.pop()
        while (onePoint) {
            let temp = onePoint.split(',');
            cPoint = [parseInt(temp[0]), parseInt(temp[1])];
            this.getRound8Point(cPoint);
            onePoint = this.open.pop();
        }
        this.drawMap();
    }
    getRound8Point(cPoint) {
        var distance = this.dis[cPoint[0]][cPoint[1]];
        var x, y;
        for (x = -1; x < 2; x++) {//一列一列的搜索
            for (y = -1; y < 2; y++) {
                if (cPoint[0] + x >= 0 && cPoint[1] + y >= 0 && cPoint[0] + x < this.w && cPoint[1] + y < this.h) {
                    let temDis = Math.sqrt(Math.pow(x, 2) + Math.pow(y, 2));//这个点离中心点的距离
                    if (this.map[cPoint[0] + x][cPoint[1] + y] != 0) {//这个点不行走
                        this.dis[cPoint[0] + x][cPoint[1] + y] = -2;
                    } else if (this.dis[cPoint[0] + x][cPoint[1] + y] == -1 || this.dis[cPoint[0] + x][cPoint[1] + y] > distance + temDis) {//这个点没计算过距离，或者这个点的距离比之前的小
                        this.dis[cPoint[0] + x][cPoint[1] + y] = distance + temDis;
                        this.open.push((cPoint[0] + x) + ',' + (cPoint[1] + y));
                        this.disLast[cPoint[0] + x][cPoint[1] + y] = [cPoint[0], cPoint[1]];
                    }
                }
            }
        }
////        $p1 = [cPoint[0] - 1, cPoint[1] - 1];
//        if (cPoint[0] - 1 >= 0 && cPoint[1] - 1 >= 0) {
//            if (this.map[cPoint[0] - 1][cPoint[1] - 1] != 0) {
//                this.dis[cPoint[0] - 1][cPoint[1] - 1] = -2;
//            } else if (this.dis[cPoint[0] - 1][cPoint[1] - 1] == -1 || this.dis[cPoint[0] - 1][cPoint[1] - 1] > distance + 1.4) {
//                this.dis[cPoint[0] - 1][cPoint[1] - 1] = distance + 1.4;
//                this.open.push((cPoint[0] - 1) + ',' + (cPoint[1] - 1));
//                this.disLast[cPoint[0] - 1][cPoint[1] - 1] = [cPoint[0], cPoint[1]];
//            }
//        }
////        $p2 = [cPoint[0], cPoint[1] - 1];
//        if (cPoint[1] - 1 >= 0) {
//            if (this.map[cPoint[0]][cPoint[1] - 1] != 0) {
//                this.dis[cPoint[0]][cPoint[1] - 1] = -2;
//            } else if (this.dis[cPoint[0]][cPoint[1] - 1] == -1 || this.dis[cPoint[0]][cPoint[1] - 1] > distance + 1) {
//                this.dis[cPoint[0]][cPoint[1] - 1] = distance + 1;
//                this.open.push((cPoint[0]) + ',' + (cPoint[1] - 1));
//                this.disLast[cPoint[0]][cPoint[1] - 1] = [cPoint[0], cPoint[1]];
//            }
//        }
////        $p3 = [cPoint[0] + 1, cPoint[1] - 1];
//        if (cPoint[0] + 1 < this.w && cPoint[1] - 1 >= 0) {
//            if (this.map[cPoint[0] + 1][cPoint[1] - 1] != 0) {
//                this.dis[cPoint[0] + 1][cPoint[1] - 1] = -2;
//            } else if (this.dis[cPoint[0] + 1][cPoint[1] - 1] == -1 || this.dis[cPoint[0] + 1][cPoint[1] - 1] > distance + 1.4) {
//                this.dis[cPoint[0] + 1][cPoint[1] - 1] = distance + 1.4;
//                this.open.push((cPoint[0] + 1) + ',' + (cPoint[1] - 1));
//                this.disLast[cPoint[0] + 1][cPoint[1] - 1] = [cPoint[0], cPoint[1]];
//            }
//        }
////        $p4 = [cPoint[0] - 1, cPoint[1]];
//        if (cPoint[0] - 1 >= 0) {
//            if (this.map[cPoint[0] - 1][cPoint[1]] != 0) {
//                this.dis[cPoint[0] - 1][cPoint[1]] = -2;
//            } else if (this.dis[cPoint[0] - 1][cPoint[1]] == -1 || this.dis[cPoint[0] - 1][cPoint[1]] > distance + 1) {
//                this.dis[cPoint[0] - 1][cPoint[1]] = distance + 1;
//                this.open.push((cPoint[0] - 1) + ',' + (cPoint[1]));
//                this.disLast[cPoint[0] - 1][cPoint[1]] = [cPoint[0], cPoint[1]];
//            }
//        }
////        $p5 = [cPoint[0] + 1, cPoint[1]];
//        if (cPoint[0] + 1 < this.w) {
//            if (this.map[cPoint[0] + 1][cPoint[1]] != 0) {
//                this.dis[cPoint[0] + 1][cPoint[1]] = -2;
//            } else if (this.dis[cPoint[0] + 1][cPoint[1]] == -1 || this.dis[cPoint[0] + 1][cPoint[1]] > distance + 1) {
//                this.dis[cPoint[0] + 1][cPoint[1]] = distance + 1;
//                this.open.push((cPoint[0] + 1) + ',' + (cPoint[1]));
//                this.disLast[cPoint[0] + 1][cPoint[1]] = [cPoint[0], cPoint[1]];
//            }
//        }
////        $p6 = [cPoint[0] - 1, cPoint[1] + 1];
//        if (cPoint[0] - 1 >= 0 && cPoint[1] + 1 < this.h) {
//            if (this.map[cPoint[0] - 1][cPoint[1] + 1] != 0) {
//                this.dis[cPoint[0] - 1][cPoint[1] + 1] = -2;
//            } else if (this.dis[cPoint[0] - 1][cPoint[1] + 1] == -1 || this.dis[cPoint[0] - 1][cPoint[1] + 1] > distance + 1.4) {
//                this.dis[cPoint[0] - 1][cPoint[1] + 1] = distance + 1.4;
//                this.open.push((cPoint[0] - 1) + ',' + (cPoint[1] + 1));
//                this.disLast[cPoint[0] - 1][cPoint[1] + 1] = [cPoint[0], cPoint[1]];
//            }
//        }
////        $p7 = [cPoint[0], cPoint[1] + 1];
//        if (cPoint[1] + 1 < this.h) {
//            if (this.map[cPoint[0]][cPoint[1] + 1] != 0) {
//                this.dis[cPoint[0]][cPoint[1] + 1] = -2;
//            } else if (this.dis[cPoint[0]][cPoint[1] + 1] == -1 || this.dis[cPoint[0]][cPoint[1] + 1] > distance + 1) {
//                this.dis[cPoint[0]][cPoint[1] + 1] = distance + 1;
//                this.open.push((cPoint[0]) + ',' + (cPoint[1] + 1));
//                this.disLast[cPoint[0]][cPoint[1] + 1] = [cPoint[0], cPoint[1]];
//            }
//        }
////        $p8 = [cPoint[0] + 1, cPoint[1] + 1];
//        if (cPoint[0] + 1 < this.w && cPoint[1] + 1 < this.h) {
//            if (this.map[cPoint[0] + 1][cPoint[1] + 1] != 0) {
//                this.dis[cPoint[0] + 1][cPoint[1] + 1] = -2;
//            } else if (this.dis[cPoint[0] + 1][cPoint[1] + 1] == -1 || this.dis[cPoint[0] + 1][cPoint[1] + 1] > distance + 1.4) {
//                this.dis[cPoint[0] + 1][cPoint[1] + 1] = distance + 1.4;
//                this.open.push((cPoint[0] + 1) + ',' + (cPoint[1] + 1));
//                this.disLast[cPoint[0] + 1][cPoint[1] + 1] = [cPoint[0], cPoint[1]];
//            }
//        }
    }
    drawMap() {
        for (var $i = 0; $i < this.w; $i++) {
            for (var $j = 0; $j < this.h; $j++) {
                var color = this.map[$i][$j] ? "#FF0000" : "#FFFFFF";
                this.drawBox($i * this.box, $j * this.box, this.box, color);
                this.drawText('(' + $i + ',' + $j + ')' + this.dis[$i][$j], $i * this.box, $j * this.box + 8);
//                this.drawText('(' + this.disLast[$i][$j][0] + ',' + this.disLast[$i][$j][1] + ')' + this.dis[$i][$j], $i * this.box, $j * this.box + 16);
            }
        }
        var $curLine = this.end;
        while (true) {
            this.drawBox(this.disLast[$curLine[0]][$curLine[1]][0] * this.box + this.box / 4 * 3, this.disLast[$curLine[0]][$curLine[1]][1] * this.box + this.box / 4 * 3, this.box / 4, "#00FF00");
            if ($curLine[0] == this.start[0] && $curLine[1] == this.start[1]) {
                break;
            }
            $curLine = this.disLast[$curLine[0]][$curLine[1]];
        }
        this.drawBox(this.start[0] * this.box + this.box / 3, this.start[1] * this.box + this.box / 3, this.box / 3, "#0000FF");
        this.drawText('(' + this.start[0] + ',' + this.start[1] + ')' + this.dis[this.start[0]][this.start[1]], this.start[0] * this.box, this.start[1] * this.box + 8);
        this.drawBox(this.end[0] * this.box + this.box / 3, this.end[1] * this.box + this.box / 3, this.box / 3, "#00FFFF");
        this.drawText('(' + this.end[0] + ',' + this.end[1] + ')' + this.dis[this.end[0]][this.end[1]], this.end[0] * this.box, this.end[1] * this.box + 8);
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


