<?php

namespace Yjtec\Algorithm\算法\全景图切面;

include_once '../../公共方法/autoload.php';

/**
 * Description of index
 *
 * @author Administrator
 */
class index {

    public $pano, $w, $h;

    public function __construct($pano) {
        $this->pano = imagecreatefromjpeg($pano);
        $this->w = imagesx($this->pano);
        $this->h = imagesy($this->pano);
    }

    public function main() {
//        $ltM = ['theta' => deg2rad(-35), 'phi' => deg2rad(-45)];
//        $ltL = ['theta' => deg2rad(-35), 'phi' => deg2rad(45)];
//        $r = sqrt(2);
//        $pM = $this->lt2xyz($ltM['theta'], $ltM['phi'], $r);
//        $pL = $this->lt2xyz($ltL['theta'], $ltL['phi'], $r);
        $points = [
            ['x' => 4, 'y' => 4, 'z' => -4],
            ['x' => 6, 'y' => 4, 'z' => 0],
            
            ['x' => 4, 'y' => 4, 'z' => 4],
            ['x' => 0, 'y' => 4, 'z' => 6],
            
            ['x' => -4, 'y' => 4, 'z' => 4],
            ['x' => -6, 'y' => 4, 'z' => 0],
            
            ['x' => -4, 'y' => 4, 'z' => -4],
            ['x' => 0, 'y' => 4, 'z' => -6],
            
        ];
        for ($i = 0; $i < count($points); $i++) {
            $next = $i + 1 == count($points) ? 0 : $i + 1;
            $this->getPc3($points[$i], $points[$next], $i);
        }
    }

    /**
     * @param type $thetaM
     * @param type $phiM
     * @param type $thetaL
     * @param type $phiL
     */
    public function getPc3($pM, $pL, $id = 0) {
        $pO = ['x' => 0, 'y' => 0, 'z' => 0];
        $pN = ['x' => $pM['x'], 'y' => -$pM['y'], 'z' => $pM['z']];
        $pQ = ['x' => $pL['x'], 'y' => -$pL['y'], 'z' => $pL['z']];
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
        $len_NQ = $this->distance($pN, $pQ);
        $jiao_ROP = $this->jiao($len_RP, $len_OR, $len_OP);
        $jiao_MON = $this->jiao($len_MN, $len_OM, $len_ON);
        $jiao_LOQ = $this->jiao($len_LQ, $len_OL, $len_OQ);
        $jiao_OPR = $this->jiao($len_OR, $len_OP, $len_RP);
        $jiao_ORP = $this->jiao($len_OP, $len_OR, $len_RP);

        $h = 500;
        $c = $this->thirdLen(min($len_OR, $len_OQ), min($len_OR, $len_OQ), $jiao_ROP);
        $w = intval($c / max($len_MN, $len_LQ) * $h);
        $disImg = imagecreatetruecolor($w, $h);
        $jiao_LMG = acos($len_RP / $len_ML);
        $jiao_xOR = -(($pR['x'] < 0 ? pi() : 0) + atan(-$pR['z'] / $pR['x']));
//        exit;
        for ($x = 0; $x < $w; $x++) {
            $len_RT = $x / $w * $len_RP;
            $len_OT = $this->thirdLen($len_RT, $len_OR, $jiao_ORP);
            $jiao_ROT = $this->jiao($len_RT, $len_OR, $len_OT);
            $len_MS = $len_ML * $len_RT / $len_RP;
            $len_ST = min($len_MN, $len_LQ) / 2 + $len_MS * sin($jiao_LMG);
            for ($y = 0; $y < $h; $y++) {
                $len_HT = (1 - $y / ($h / 2)) * $len_ST;
                $S_theta = $jiao_TOH = -atan($len_HT / $len_OT);
                $S_phi = $jiao_ROT + $jiao_xOR; // + $phiM
                $u = $S_phi / pi() / 2 + 0.5;
                $v = $S_theta / pi() + 0.5;
                $m = intval($u * $this->w);
                $n = intval($v * $this->h);
                $m = $m < 0 ? $this->w + $m : $m;
                $rgb = imagecolorat($this->pano, $m % $this->w, $n);
                imagesetpixel($disImg, $x, $y, $rgb);
            }
        }
        imagepng($disImg, __DIR__ . '/x' . $id . '.png');
        imagedestroy($disImg);
    }

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

    public function thirdLen($a, $b, $jiao_ab) {
        return sqrt($a * $a + $b * $b - 2 * $a * $b * cos($jiao_ab));
    }

    public function lt2xyz($theta, $phi, $r) {
        $x = cos($theta) * cos($phi) * $r;
        $y = sin($theta) * $r;
        $z = -cos($theta) * sin($phi) * $r;
        return ['x' => $x, 'y' => $y, 'z' => $z];
    }

    public function xyz2lt($x, $y, $z, $r) {
        $phi = atan(-$z / $x);
        $theta = asin($y / ($x * $x + $y * $y + $z * $z));
        return ['theta' => $theta, 'phi' => $phi];
    }

}

(new index(__DIR__ . '/3.jpg'))->main();
