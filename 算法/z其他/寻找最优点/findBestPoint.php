<?php

namespace Yjtec\Algorithm\算法\z其他\寻找最优点;

class findBestPoint {

    public $pointA;
    public $pointB;
    public $pointP;
    public $pointC;
    public $goal;
    public $degree;

    public function __construct() {
        $this->degree = pi() / 3;
        $this->pointA = $this->createPoint();
        $this->pointB = $this->createPoint();
        $this->pointP = $this->getPointP();
        for ($i = 0; $i < 30; $i++) {
            $this->pointC[] = $this->createPoint();
        }
    }

    public function main() {
        $enablePoints = $this->getEnablePoints();
        if (!empty($enablePoints)) {
            $minPoint = $this->getMinPoint($enablePoints);
//            $disAC = $this->calDistance($this->pointA, $minPoint);
//            $disAB = $this->calDistance($this->pointA, $this->pointB);
//            if ($disAB >= $disAC / 4) {
//                $this->goal = $minPoint; //寻找到的goal点
//            }
            $this->goal = $minPoint; //寻找到的goal点
            $this->draw();
        }
    }

    public function getMinPoint($enablePoints) {
        $minPoint = 0;
        for ($i = 1; $i < count($enablePoints); $i++) {
            if (($enablePoints[$i]['disBC'] / $enablePoints[$i]['COS_B']) < ($enablePoints[$minPoint]['disBC'] / $enablePoints[$minPoint]['COS_B'])) {
                $minPoint = $i;
            }
        }
        return $enablePoints[$minPoint];
    }

    function getEnablePoints() {
        $enablePoints = [];
        foreach ($this->pointC as $k => $C) {
            $COS_B = $this->calDegree($this->pointB, $this->pointP, $C);
            if ($COS_B >= cos($this->degree)) {
                $C['disBC'] = $this->calDistance($this->pointB, $C);
                $C['COS_B'] = $COS_B;
                $enablePoints[] = $C;
            }
        }
        return $enablePoints;
    }

    public function calDistance($pointA, $pointB) {
        return sqrt(pow($pointB['x'] - $pointA['x'], 2) + pow($pointB['y'] - $pointA['y'], 2));
    }

    public function calDegree($pointB, $pointP, $pointC) {
        $disBP = $this->calDistance($pointB, $pointP);
        $disBC = $this->calDistance($pointB, $pointC);
        $disPC = $this->calDistance($pointP, $pointC);
        $COS_B = (pow($disBC, 2) + pow($disBP, 2) - pow($disPC, 2)) / (2 * $disBC * $disBP);
        return $COS_B;
    }

    ///////////////以下方法不在算法内///////////////

    /**
     * 在-10到10范围内生成一些随机点
     * @return type
     */
    function getPointP() {
        $kAB = ($this->pointB['y'] - $this->pointA['y']) / ( $this->pointB['x'] - $this->pointA['x']);
        $bAB = $this->pointB['y'] - $kAB * $this->pointB['x'];
        $pointP['x'] = $this->pointB['x'] > $this->pointA['x'] ? $this->pointB['x'] + 3 : $this->pointB['x'] - 3;
        $pointP['y'] = $kAB * $pointP['x'] + $bAB;
        if ($pointP['y'] > 10 || $pointP['y'] < -10) {
            $pointP['y'] = $this->pointB['y'] > $this->pointA['y'] ? $this->pointB['y'] + 3 : $this->pointB['y'] - 3;
            $pointP['x'] = ($pointP['y'] - $bAB) / $kAB;
        }
        return $pointP;
    }

    public function createPoint() {
        return ['x' => rand(-1000, 1000) / 100, 'y' => rand(-1000, 1000) / 100];
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
