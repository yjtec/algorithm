<?php

namespace Yjtec\Algorithm\算法\最优路径;

/**
 * Description of index
 *
 * @author Administrator
 */
class index {

    public $w = 20, $h = 20;
    public $map, $dis;
    public $start, $end;
    public $open, $close;

    public function __construct() {
        for ($i = 0; $i < $this->w; $i++) {
            for ($j = 0; $j < $this->h; $j++) {//随机生成地图
                $this->map[$i][$j] = rand(0, 5) ? 0 : 1; //0代表可行走，1代表不可行走（墙）
                $this->dis[$i][$j] = -1; //-1代表还没有检查的点
            }
        }
        while (true) {
            $this->start = [rand(1, $this->w), rand(1, $this->h)];
            $this->end = [rand(1, $this->w), rand(1, $this->h)];
            $this->dis[$this->start[0]][$this->start[1]] = 0;
            if ($this->map[$this->start[0]][$this->start[1]] == 0 && $this->map[$this->end[0]][$this->end[1]] == 0 && $this->start[0] != $this->end[0] && $this->start[1] != $this->end[1]) {
                break;
            }
        }
    }

    public function main() {
        $this->drawOrgMap();
        $cPoint = $this->start;
        $this->getRound8Point($cPoint);
        while ($onePoint = array_pop($this->open)) {
            $cPoint = explode(',', $onePoint);
            $this->getRound8Point($cPoint);
        }
        $this->drawMap();
    }

    private function getRound8Point($cPoint) {
        $distance = $this->dis[$cPoint[0]][$cPoint[1]];
        for ($x = -1; $x < 2; $x++) {//一列一列的搜索
            for ($y = -1; $y < 2; $y++) {
                if ($cPoint[0] + $x >= 0 && $cPoint[1] + $y >= 0 && $cPoint[0] + $x < $this->w && $cPoint[1] + $y < $this->h) {
                    $temDis = sqrt(pow($x, 2) + pow($y, 2)); //这个点离中心点的距离
                    if ($this->map[$cPoint[0] + $x][$cPoint[1] + $y] != 0) {//这个点不行走
                        $this->dis[$cPoint[0] + $x][$cPoint[1] + $y] = -2;
                    } else if ($this->dis[$cPoint[0] + $x][$cPoint[1] + $y] == -1 || $this->dis[$cPoint[0] + $x][cPoint[1] + $y] > $distance + $temDis) {//这个点没计算过距离，或者这个点的距离比之前的小
                        $this->dis[$cPoint[0] + $x][cPoint[1] + $y] = $distance + $temDis;
                        $this->open[] = ($cPoint[0] + $x) . ',' . ($cPoint[1] + $y);
                        $this->disLast[$cPoint[0] + $x][$cPoint[1] + $y] = [$cPoint[0], $cPoint[1]];
                    }
                }
            }
        }
    }

    private function drawOrgMap() {
        $box = 50;
        $image = imagecreatetruecolor($this->w * $box, $this->h * $box);
        $whiteColor = imagecolorallocate($image, 255, 255, 255);
        $redColor = imagecolorallocate($image, 255, 0, 0);
        $greenColor = imagecolorallocate($image, 0, 255, 0);
        $greenBlueColor = imagecolorallocate($image, 0, 0, 0);
        $blueColor = imagecolorallocate($image, 0, 0, 255);
        $blueRedColor = imagecolorallocate($image, 255, 0, 255);
        imagefill($image, 0, 0, $whiteColor);
        for ($i = 0; $i < $this->w; $i++) {
            for ($j = 0; $j < $this->h; $j++) {
                $color = $this->map[$i][$j] ? $redColor : $whiteColor;
                imagefilledrectangle($image, $i * $box, $j * $box, $i * $box + $box, $j * $box + $box, $color);
                imagestring($image, 1, $i * $box, $j * $box, '(' . $i . ',' . $j . ')' . $this->dis[$i][$j], $greenBlueColor); //画距离
            }
        }
        imagefilledrectangle($image, $this->start[0] * $box, $this->start[1] * $box, $this->start[0] * $box + $box, $this->start[1] * $box + $box, $blueColor); //绘制start
        imagestring($image, 1, $this->start[0] * $box, $this->start[1] * $box, '(' . $this->start[0] . ',' . $this->start[1] . ')' . $this->dis[$this->start[0]][$this->start[1]], $greenBlueColor); //画距离
        imagefilledrectangle($image, $this->end[0] * $box, $this->end[1] * $box, $this->end[0] * $box + $box, $this->end[1] * $box + $box, $blueRedColor); //绘制end
        imagestring($image, 1, $this->end[0] * $box, $this->end[1] * $box, '(' . $this->end[0] . ',' . $this->end[1] . ')' . $this->dis[$this->end[0]][$this->end[1]], $greenBlueColor); //画距离
        imagepng($image, './ox.png');
        imagedestroy($image);
    }

    private function drawMap() {
        $box = 50;
        $image = imagecreatetruecolor($this->w * $box, $this->h * $box);
        $whiteColor = imagecolorallocate($image, 255, 255, 255);
        $redColor = imagecolorallocate($image, 255, 0, 0);
        $greenColor = imagecolorallocate($image, 0, 255, 0);
        $greenBlueColor = imagecolorallocate($image, 0, 0, 0);
        $blueColor = imagecolorallocate($image, 0, 0, 255);
        $blueRedColor = imagecolorallocate($image, 255, 0, 255);
        imagefill($image, 0, 0, $whiteColor);
        for ($i = 0; $i < $this->w; $i++) {
            for ($j = 0; $j < $this->h; $j++) {
                $color = $this->map[$i][$j] ? $redColor : $whiteColor;
                imagefilledrectangle($image, $i * $box, $j * $box, $i * $box + $box, $j * $box + $box, $color);
                imagestring($image, 1, $i * $box, $j * $box, '(' . $i . ',' . $j . ')' . $this->dis[$i][$j], $greenBlueColor); //画距离
                imagestring($image, 1, $i * $box, $j * $box + 10, $this->disLast[$i][$j][0] . ',' . $this->disLast[$i][$j][1], $greenBlueColor); //画上一个点
            }
        }
        imagefilledrectangle($image, $this->start[0] * $box, $this->start[1] * $box, $this->start[0] * $box + $box, $this->start[1] * $box + $box, $blueColor); //绘制start
        imagestring($image, 1, $this->start[0] * $box, $this->start[1] * $box, '(' . $this->start[0] . ',' . $this->start[1] . ')' . $this->dis[$this->start[0]][$this->start[1]], $greenBlueColor); //画距离
        imagestring($image, 1, $this->start[0] * $box, $this->start[1] * $box + 10, $this->disLast[$this->start[0]][$this->start[1]][0] . ',' . $this->disLast[$this->start[0]][$this->start[1]][1], $greenBlueColor); //画上一个点
        imagefilledrectangle($image, $this->end[0] * $box, $this->end[1] * $box, $this->end[0] * $box + $box, $this->end[1] * $box + $box, $blueRedColor); //绘制end
        imagestring($image, 1, $this->end[0] * $box, $this->end[1] * $box, '(' . $this->end[0] . ',' . $this->end[1] . ')' . $this->dis[$this->end[0]][$this->end[1]], $greenBlueColor); //画距离
        imagestring($image, 1, $this->end[0] * $box, $this->end[1] * $box + 10, $this->disLast[$this->end[0]][$this->end[1]][0] . ',' . $this->disLast[$this->end[0]][$this->end[1]][1], $greenBlueColor); //画上一个点
        $curLine = $this->end;
        while (true) {
            imagefilledrectangle($image, $this->disLast[$curLine[0]][$curLine[1]][0] * $box + $box / 3, $this->disLast[$curLine[0]][$curLine[1]][1] * $box + $box / 3, $this->disLast[$curLine[0]][$curLine[1]][0] * $box + $box, $this->disLast[$curLine[0]][$curLine[1]][1] * $box + $box, $greenColor); //画出绿色的路线
            if ($curLine[0] == $this->start[0] && $curLine[1] == $this->start[1]) {
                break;
            }
            $curLine = $this->disLast[$curLine[0]][$curLine[1]];
        }
        imagepng($image, './o.png');
        imagedestroy($image);
    }

    //画出这些点，方便查看
    function draw($imgName = 't') {
        $pyl = 400; //便宜量
        $fd = 20; //放大倍数
        $image = imagecreatetruecolor(800, 800);
        imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));
        //设置字体颜色
        $redColor = imagecolorallocate($image, 255, 0, 0);
        $greenColor = imagecolorallocate($image, 0, 255, 0);
        $blueColor = imagecolorallocate($image, 0, 0, 255);
        $gColor = imagecolorallocate($image, 255, 0, 255);
        imageline($image, 0, $pyl, 800, $pyl, $greenColor);
        imageline($image, $pyl, 0, $pyl, 800, $greenColor);
        imageline($image, $this->pointA['x'] * $fd + $pyl, (-$this->pointA['y']) * $fd + $pyl, $this->pointB['x'] * $fd + $pyl, (-$this->pointB['y']) * $fd + $pyl, $greenColor); //AB
        imageline($image, $this->pointB['x'] * $fd + $pyl, (-$this->pointB['y']) * $fd + $pyl, $this->pointP['x'] * $fd + $pyl, (-$this->pointP['y']) * $fd + $pyl, $greenColor); //BP
        imagefilledellipse($image, $this->pointA['x'] * $fd + $pyl, (-$this->pointA['y']) * $fd + $pyl, 5, 5, $redColor); //画点A
        imagestring($image, 1, $this->pointA['x'] * $fd + $pyl + 5, (-$this->pointA['y']) * $fd + $pyl - 3, 'A', $redColor); //画字母A
        imagefilledellipse($image, $this->pointB['x'] * $fd + $pyl, (-$this->pointB['y']) * $fd + $pyl, 5, 5, $redColor); //画点B
        imagestring($image, 1, $this->pointB['x'] * $fd + $pyl + 5, (-$this->pointB['y']) * $fd + $pyl - 3, 'B', $redColor); //画字母B
        imagefilledellipse($image, $this->pointP['x'] * $fd + $pyl, (-$this->pointP['y']) * $fd + $pyl, 5, 5, $redColor); //画点P
        imagestring($image, 1, $this->pointP['x'] * $fd + $pyl + 5, (-$this->pointP['y']) * $fd + $pyl - 3, 'P', $redColor); //画字母P
        foreach ($this->pointC as $k => $p) {
            imageline($image, $this->pointB['x'] * $fd + $pyl, (-$this->pointB['y']) * $fd + $pyl, $p['x'] * $fd + $pyl, (-$p['y']) * $fd + $pyl, $blueColor); //先画线
            imagefilledellipse($image, $p['x'] * $fd + $pyl, (-$p['y']) * $fd + $pyl, 5, 5, $redColor); //画点
            imagestring($image, 1, $p['x'] * $fd + $pyl + 5, (-$p['y']) * $fd + $pyl - 3, 'C' . $k, $redColor); //画字母
        }
        imagefilledellipse($image, $this->goal['x'] * $fd + $pyl, (-$this->goal['y']) * $fd + $pyl, 5, 5, $gColor); //画点G
        imagestring($image, 1, $this->goal['x'] * $fd + $pyl + 5, (-$this->goal['y']) * $fd + $pyl - 10, 'G', $gColor); //画字母G
        if (!file_exists('./img')) {
            mkdir('./img');
        }
        imagepng($image, './img/' . $imgName . ".png");
        imagedestroy($image);
    }

}

(new index())->main();
