<?php

namespace Yjtec\Algorithm\算法\全景图切面;

include_once '../../公共方法/autoload.php';

/**
 * Description of index
 *
 * @author Administrator
 */
class index {

    const W = 500;

    public $pano, $w, $h;
    public $houseData;
    public $roomPoints = [
        'pano1' => [
            ['x' => 7, 'y' => 4, 'z' => -4.7],
            ['x' => 5.3, 'y' => 4, 'z' => -0.7],
            ['x' => 13.5, 'y' => 4, 'z' => 2.7],
            ['x' => 11.5, 'y' => 4, 'z' => 9],
            ['x' => 4, 'y' => 4, 'z' => 4.6],
            ['x' => -8.7, 'y' => 4, 'z' => -0.7],
            ['x' => -4, 'y' => 4, 'z' => -10],
        ],
        'pano2' => [
            ['x' => 9, 'y' => 4, 'z' => -2.9],
            ['x' => 7.2, 'y' => 4, 'z' => 1.5],
            ['x' => 4.5, 'y' => 4, 'z' => 7.9],
            ['x' => -7.48, 'y' => 4, 'z' => 3],
            ['x' => -3, 'y' => 4, 'z' => -7.9],
        ],
        'pano6' => [
            ['x' => 6.8, 'y' => 4, 'z' => -2.5],
            ['x' => 3.1, 'y' => 4, 'z' => 6.5],
            ['x' => -6.8, 'y' => 4, 'z' => 2.3],
            ['x' => -2.6, 'y' => 4, 'z' => -6.9],
        ]
    ];
    public $panoConfig;

    public function __construct() {
        $this->panoConfig = [
            'base' => 'pano1',
            'pub_edge' => [
                'pano2' => ['base' => 'pano1', 'bp' => [5, 6], 'rp' => [0, 1]],
                'pano6' => ['base' => 'pano1', 'bp' => [0, 1], 'rp' => [2, 3]],
            ],
            'offset' => [
                'pano1' => [0, 0, 0],
                'pano2' => [-15.2, 0, -3.8],
                'pano6' => [12.3, 0, -3.2],
            ]
        ];
    }

    public function main() {
//        $pano = 'pano6';
//        $panoPath = __DIR__ . '/' . $pano . '.jpg';
//        $this->pano = imagecreatefromjpeg($panoPath);
//        $this->w = imagesx($this->pano);
//        $this->h = imagesy($this->pano);
//        $points = $this->panoPoints[$pano];
//        $rect = $this->getMaxRect($points);
//        $pM = $rect[0];
//        $pL = $rect[1];
//        $pQ = $rect[2];
//        $pN = $rect[3];
//        $this->getTop($pM, $pL, $pQ, $pN, $pano, $pano . '_top');
//        return;
        foreach ($this->roomPoints as $room => $points) {
            $panoPath = __DIR__ . '/' . $room . '.jpg';
            if (!file_exists($panoPath)) {
                continue;
            }
            if (!file_exists(__DIR__ . '/' . $room)) {
                mkdir($room, 0777);
            }
            $this->pano = imagecreatefromjpeg($panoPath);
            $this->w = imagesx($this->pano);
            $this->h = imagesy($this->pano);
            $this->houseData[$room] = null;
            $this->houseData[$room]['rata'] = $this->getPanoRota($room);
            $this->houseData[$room]['offset'] = $this->panoConfig['offset'][$room];
            for ($i = 0; $i < count($points); $i++) {
                $next = $i + 1 == count($points) ? 0 : $i + 1;
                $faceName = $room . '_' . $i;
                $this->houseData[$room]['points'][$i] = [$points[$i]['x'], $points[$i]['y'], $points[$i]['z']];
                $pN = ['x' => $points[$i]['x'], 'y' => -$points[$i]['y'], 'z' => $points[$i]['z']];
                $pQ = ['x' => $points[$next]['x'], 'y' => -$points[$next]['y'], 'z' => $points[$next]['z']];
                $this->getAroudPic($points[$i], $points[$next], $pQ, $pN, $room, $faceName);
                $this->houseData[$room]['walls'][$i] = ['M' => $i, 'L' => $next, 'name' => $faceName];
            }
            $rect = $this->getMaxRect($points);
            $pM = $rect[0];
            $pL = $rect[1];
            $pQ = $rect[2];
            $pN = $rect[3];
            $this->houseData[$room]['floor']['points'] = [
                [$pM['x'], -$pM['y'], $pM['z']],
                [$pL['x'], -$pL['y'], $pL['z']],
                [$pQ['x'], -$pQ['y'], $pQ['z']],
                [$pN['x'], -$pN['y'], $pN['z']],
            ];
            $this->houseData[$room]['top']['points'] = [
                [$pM['x'], $pM['y'], $pM['z']],
                [$pL['x'], $pL['y'], $pL['z']],
                [$pQ['x'], $pQ['y'], $pQ['z']],
                [$pN['x'], $pN['y'], $pN['z']],
            ];

            $this->getFloor($pM, $pL, $pQ, $pN, $room, $room . '_floor');
            $this->getFloor($pM, $pL, $pQ, $pN, $room, $room . '_top', true);
            imagedestroy($this->pano);
            file_put_contents(__DIR__ . '/three/' . 'data.js', 'var panos =' . json_encode($this->houseData));
        }
    }

    public function getPanoRota($pano) {
        $keys = array_keys($this->panoConfig['pub_edge']);
        if (!in_array($pano, $keys)) {
            return 0;
        }
        $edge = $this->panoConfig['pub_edge'][$pano];
        $base_k = ($this->roomPoints[$edge['base']][$edge['bp'][0]]['z'] - $this->roomPoints[$edge['base']][$edge['bp'][1]]['z']) / ($this->roomPoints[$edge['base']][$edge['bp'][0]]['x'] - $this->roomPoints[$edge['base']][$edge['bp'][1]]['x']);
        $rota_k = ($this->roomPoints[$pano][$edge['rp'][0]]['z'] - $this->roomPoints[$pano][$edge['rp'][1]]['z']) / ($this->roomPoints[$pano][$edge['rp'][0]]['x'] - $this->roomPoints[$pano][$edge['rp'][1]]['x']);
        $theta = atan(($rota_k - $base_k) / (1 + $rota_k * $base_k));
        return $theta;
    }

    public function getAroudPic($pM, $pL, $pQ, $pN, $pano, $faceName) {
        $pO = ['x' => 0, 'y' => 0, 'z' => 0];
        $pR = ['x' => ($pM['x'] + $pN['x']) / 2, 'y' => ($pM['y'] + $pN['y']) / 2, 'z' => ($pM['z'] + $pN['z']) / 2];
        $pP = ['x' => ($pL['x'] + $pQ['x']) / 2, 'y' => ($pL['y'] + $pQ['y']) / 2, 'z' => ($pL['z'] + $pQ['z']) / 2];

        $len_MN = $this->distance($pM, $pN);
        $len_LQ = $this->distance($pL, $pQ);
        $len_OR = $this->distance($pO, $pR);
        $len_OP = $this->distance($pO, $pP);
        $len_RP = $this->distance($pR, $pP);
        $len_OM = $this->distance($pO, $pM);
        $len_ON = $this->distance($pO, $pN);
        $len_OL = $this->distance($pO, $pL);
        $len_OQ = $this->distance($pO, $pQ);
        $len_ML = $this->distance($pM, $pL);
        $jiao_ROP = $this->jiao($len_RP, $len_OR, $len_OP);
        $jiao_ORP = $this->jiao($len_OP, $len_OR, $len_RP);

        $h = self::W;
        $len_RY = $this->thirdLen(min($len_OR, $len_OQ), min($len_OR, $len_OQ), $jiao_ROP);
        $w = intval($len_RY / max($len_MN, $len_LQ) * $h);
        $disImg = imagecreatetruecolor($w, $h);
        $jiao_LMG = acos($len_RP / $len_ML);
        $jiao_xOR = -(($pR['x'] < 0 ? pi() : 0) + atan(-$pR['z'] / $pR['x'])); //R点与X轴的夹角

        $jiao_xOW = pi() - ($jiao_xOR - $jiao_ORP); //这个真好用！！，这个角用于在渲染场景时，当前切片图绕Y轴旋转多少角度
        $this->houseData[$pano]['jiaos'][] = $jiao_xOW;
//        file_put_contents(__DIR__ . '/' . $pano . '/' . 'jiao.log', $jiao_xOW . ',' . PHP_EOL, FILE_APPEND);
        for ($x = 0; $x < $w; $x++) {
            $len_RT = $x / $w * $len_RP;
            $len_OT = $this->thirdLen($len_RT, $len_OR, $jiao_ORP);
            $jiao_ROT = $this->jiao($len_RT, $len_OR, $len_OT);
            $len_MS = $len_ML * $len_RT / $len_RP;
            $len_ST = min($len_MN, $len_LQ) / 2 + $len_MS * sin($jiao_LMG);
            for ($y = 0; $y < $h; $y++) {
                $len_HT = (1 - $y / ($h / 2)) * $len_ST;
                $H_theta = $jiao_TOH = -atan($len_HT / $len_OT);
                $H_phi = $jiao_ROT + $jiao_xOR; // + $phiM
                $u = $H_phi / pi() / 2 + 0.5;
                $v = $H_theta / pi() + 0.5;
                $m = intval($u * $this->w);
                $n = intval($v * $this->h);
                $m = $m < 0 ? $this->w + $m : $m;
                $rgb = imagecolorat($this->pano, $m % $this->w, $n);
                imagesetpixel($disImg, $x, $y, $rgb);
            }
        }
        imagepng($disImg, __DIR__ . '/' . $pano . '/' . $faceName . '.png');
        imagedestroy($disImg);
    }

    public function getFloor($pM, $pL, $pQ, $pN, $pano, $faceName, $isTop = false) {
        $pO = ['x' => 0, 'y' => 0, 'z' => 0];
        $pW = ['x' => 0, 'y' => ($pM['y'] + $pN['y'] + $pL['y'] + $pQ['y']) / 4, 'z' => 0];

        $len_MN = $this->distance($pM, $pN);
        $len_ML = $this->distance($pM, $pL);
        $len_WM = $this->distance($pW, $pM);
        $len_WN = $this->distance($pW, $pN);
        $len_WL = $this->distance($pW, $pL);
        $len_OW = $this->distance($pO, $pW);

        $len_WV = $this->area($len_WM, $len_WN, $len_MN) / $len_MN * 2;
        $len_WG = $this->area($len_WM, $len_WL, $len_ML) / $len_ML * 2;
        $jiao_VWM = acos($len_WV / $len_WM);

        $h = self::W * 2;
        $w = intval($len_ML / $len_MN * $h);
        $disImg = imagecreatetruecolor($w, $h);

        $jiao_xWM = -(($pM['x'] < 0 ? pi() : 0) + atan(-$pM['z'] / $pM['x'])) - $jiao_VWM; //与X轴的夹角
        $this->houseData[$pano]['floors']['rz'] = $jiao_xWM;
//        file_put_contents(__DIR__ . '/' . $pano . '/' . 'xWM.log', $jiao_xWM);

        for ($x = 0; $x < $w; $x++) {
            $len_MS = $x / $w * $len_ML;
            $len_WU = $len_WV - $len_MS;
            for ($y = 0; $y < $h; $y++) {
                $len_SH = $y / $h * $len_MN;
                $len_UH = $len_WG - $len_SH;
                $jiao_UWH = atan($len_UH / $len_WU);
                $len_WH = $len_UH / sin($jiao_UWH);
                $H_theta = $jiao_WHO = atan($len_OW / $len_WH);
                $H_phi = $jiao_UWH + $jiao_xWM;
                if ($len_WU < 0) {
                    $H_phi = $H_phi + pi();
                }
                if ($len_WH < 0) {
                    $H_theta = -$H_theta;
                }
                if ($isTop) {//上下截取
                    $H_theta = -$H_theta;
                }
                $u = $H_phi / pi() / 2 + 0.5;
                $v = $H_theta / pi() + 0.5;
                $m = intval($u * $this->w);
                $n = intval($v * $this->h);
                $m = $m < 0 ? $this->w + $m : $m;
                $rgb = imagecolorat($this->pano, $m % $this->w, $n);
                imagesetpixel($disImg, $x, $y, $rgb);
            }
        }
        imagepng($disImg, __DIR__ . '/' . $pano . '/' . $faceName . '.png');
        imagedestroy($disImg);
    }

    public function getTop($pM, $pL, $pQ, $pN, $pano, $faceName) {
//        var_dump($pM,$pL,$pQ,$pN);exit;
        $pO = ['x' => 0, 'y' => 0, 'z' => 0];
        $pW = ['x' => 0, 'y' => ($pM['y'] + $pN['y'] + $pL['y'] + $pQ['y']) / 4, 'z' => 0];

        $len_MN = $this->distance($pM, $pN);
        $len_ML = $this->distance($pM, $pL);
        $len_WM = $this->distance($pW, $pM);
        $len_WN = $this->distance($pW, $pN);
        $len_WL = $this->distance($pW, $pL);
        $len_OW = $this->distance($pO, $pW);

        $len_WV = $this->area($len_WM, $len_WN, $len_MN) / $len_MN * 2;
        $len_WG = $this->area($len_WM, $len_WL, $len_ML) / $len_ML * 2;
        $jiao_VWM = acos($len_WV / $len_WM);

        $h = self::W * 2;
        $w = intval($len_ML / $len_MN * $h);
        $disImg = imagecreatetruecolor($w, $h);

        $jiao_xWM = -(($pM['x'] < 0 ? pi() : 0) + atan(-$pM['z'] / $pM['x'])) - $jiao_VWM; //与X轴的夹角
        $this->houseData[$pano]['tops']['rz'] = $jiao_xWM;
//        file_put_contents(__DIR__ . '/' . $pano . '/' . 'xWM.log', $jiao_xWM);

        for ($x = 0; $x < $w; $x++) {
            $len_MS = $x / $w * $len_ML;
            $len_WU = $len_WV - $len_MS;
            for ($y = 0; $y < $h; $y++) {
                $len_SH = $y / $h * $len_MN;
                $len_UH = $len_WG - $len_SH;
                $jiao_UWH = atan($len_UH / $len_WU);
                $len_WH = $len_UH / sin($jiao_UWH);
                $H_theta = $jiao_WHO = atan($len_OW / $len_WH);
                $H_phi = $jiao_UWH + $jiao_xWM;
                if ($len_WU < 0) {
                    $H_phi = $H_phi + pi();
                }
                if ($len_WH < 0) {
                    $H_theta = -$H_theta;
                }
                $u = ($H_phi) / pi() / 2 + 0.5;
                $v = (-$H_theta) / pi() + 0.5;
                $m = intval($u * $this->w);
                $n = intval($v * $this->h);
                $m = $m < 0 ? $this->w + $m : $m;
                $rgb = imagecolorat($this->pano, $m % $this->w, $n);
                imagesetpixel($disImg, $x, $y, $rgb);
            }
        }
        imagepng($disImg, __DIR__ . '/' . $pano . '/' . $faceName . '.png');
        imagedestroy($disImg);
    }

    /**
     * 空间中任意两点的距离
     * @param type $pA
     * @param type $pB
     * @return type
     */
    public function distance($pA, $pB) {
        return sqrt(pow($pB['x'] - $pA['x'], 2) + pow($pB['y'] - $pA['y'], 2) + pow($pB['z'] - $pA['z'], 2));
    }

    /**
     * 已知三角形三边，求a对应的角
     * @param type $a
     * @param type $b
     * @param type $c
     * @return type
     */
    public function jiao($a, $b, $c) {
        return acos(($b * $b + $c * $c - $a * $a) / 2 / $b / $c);
    }

    /**
     * 一直三角形两边及其夹角算第三边长度
     * @param type $a
     * @param type $b
     * @param type $jiao_ab
     * @return type
     */
    public function thirdLen($a, $b, $jiao_ab) {
        return sqrt($a * $a + $b * $b - 2 * $a * $b * cos($jiao_ab));
    }

    /**
     * 根据三边长度算三角形面积
     * @param type $a
     * @param type $b
     * @param type $c
     * @return type
     */
    public function area($a, $b, $c) {
        $p = ($a + $b + $c) / 2;
        return sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));
    }

    /**
     * 本方法仅用于截取底面和顶面，因此要忽略y轴
     * 已知空间中多个点构成多边形，将这些点垂直映射到xz平面后，计算一个面积最小的矩形，将这些映射点包含进去
     * 算法：找到距离最远的两点，确定为长，计算其他点到这条长所在的直线距离，在直线的两边距离最远的两点之和为宽
     * @param type $points
     */
    public function getMaxRect($points) {
        $maxLine = $this->getMaxDis($points); //获取最长的那条线作为宽
        //计算直线公式的Ax+By+C=0，y=kx+b
        $k = ($maxLine['start']['z'] - $maxLine['end']['z']) / ($maxLine['start']['x'] - $maxLine['end']['x']);
        $b = $maxLine['start']['z'] - $k * $maxLine['start']['x'];
        $k_chui = -1 / $k; //垂线斜率
        $A = -$k;
        $B = 1;
        $C = -$b;

        $maxPoint = $minPoint = [];
        $max = $min = 0;
        for ($i = 0; $i < count($points); $i++) {
            $dis = ($A * $points[$i]['x'] + $B * $points[$i]['z'] + $C) / hypot($A, $B); //点到直线的距离，去掉正负号代表在直线的两侧
            if ($dis > $max) {
                $maxPoint = ['i' => $i, 'point' => $points[$i], 'dis' => $dis];
                $max = $dis;
            } elseif ($dis < $min) {
                $minPoint = ['i' => $i, 'point' => $points[$i], 'dis' => $dis];
                $min = $dis;
            }
        }

        $b_max = $maxPoint['point']['z'] - $k * $maxPoint['point']['x']; //获取两条平行线,y=kx+b_max
        $b_min = $minPoint['point']['z'] - $k * $minPoint['point']['x']; //y=kx+b_min

        $b_M = $maxLine['start']['z'] - $k_chui * $maxLine['start']['x']; //起点垂线,y=k_chui*x+b_M
        $b_L = $maxLine['end']['z'] - $k_chui * $maxLine['end']['x']; //终点垂线,y=k_chui*x+b_L

        $pMx = ($b_max - $b_M) / ($k_chui - $k);
        $pMz = $k * $pMx + $b_max;
        $pM = ['x' => $pMx, 'y' => $maxLine['start']['y'], 'z' => $pMz];

        $pLx = ($b_max - $b_L) / ($k_chui - $k);
        $pLz = $k * $pLx + $b_max;
        $pL = ['x' => $pLx, 'y' => $maxLine['start']['y'], 'z' => $pLz];

        $pQx = ($b_min - $b_L) / ($k_chui - $k);
        $pQz = $k * $pQx + $b_min;
        $pQ = ['x' => $pQx, 'y' => $maxLine['start']['y'], 'z' => $pQz];

        $pNx = ($b_min - $b_M) / ($k_chui - $k);
        $pNz = $k * $pNx + $b_min;
        $pN = ['x' => $pNx, 'y' => $maxLine['start']['y'], 'z' => $pNz];
        return [$pM, $pL, $pQ, $pN];
    }

    public function getMaxDis($points) {
        $maxPoint = [];
        $maxDistance = 0;
        for ($i = 0; $i < count($points); $i++) {
            for ($j = $i + 1; $j < count($points); $j++) {//自己不用计算到自己的距离
                $dis = hypot($points[$j]['z'] - $points[$i]['z'], $points[$j]['x'] - $points[$i]['x']);
                if ($dis > $maxDistance) {
                    $maxPoint = ['i' => $i, 'j' => $j, 'start' => $points[$i], 'end' => $points[$j], 'dis' => $dis];
                    $maxDistance = $dis;
                }
            }
        }
        return $maxPoint;
    }

    public function test() {
        $pM = ['x' => 4, 'y' => -4, 'z' => -4];
        $pL = ['x' => 4, 'y' => -4, 'z' => 4];
        $pQ = ['x' => -4, 'y' => -4, 'z' => 4];
        $pN = ['x' => -4, 'y' => -4, 'z' => -4];
        $this->getFloor($pM, $pL, $pQ, $pN);
    }

}

(new index())->main();
