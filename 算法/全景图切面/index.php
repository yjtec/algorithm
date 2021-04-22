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
//        $this->getFace(0, 1250, 1250);return;
//        $this->getFace(1, 1250, 1250);
//        $this->getFace(2, 1250, 1250);
//        $this->getFace(3, 1250, 1250);
//        $this->getFace(4, 1250, 1250);
//        $this->getFace(5, 1250, 1250);
//        $this->ax(500, 500);
//        $this->getPc(-pi() / 4, -pi() / 4, -pi() / 4, pi() / 4);
//        $this->getPc2(-pi() / 4, -pi() / 4, deg2rad(-60), pi() / 4);
        $ltM = ['theta' => deg2rad(-35), 'phi' => deg2rad(-45)];
//        $ltL = ['theta' => deg2rad(-35), 'phi' => deg2rad(45)];
//        $ltM = ['theta' => deg2rad(-35), 'phi' => deg2rad(135)];
        $ltL = ['theta' => deg2rad(-35), 'phi' => deg2rad(-135)];
        $r = sqrt(2);
        $pM = $this->lt2xyz($ltM['theta'], $ltM['phi'], $r);
        $pL = $this->lt2xyz($ltL['theta'], $ltL['phi'], $r);
        $points = [
            ['x' => 4, 'y' => 4, 'z' => -4],
            ['x' => 10, 'y' => 4, 'z' => 4],
            ['x' => -4, 'y' => 4, 'z' => 4],
            ['x' => -4, 'y' => 4, 'z' => -4]
        ];
        $face = 'f';
        switch ($face) {
            case 'f':
                $pM = $points[0];
                $pL = $points[1];
                break;
            case 'r':
                $pM = $points[1];
                $pL = $points[2];
                break;
            case 'b':
                $pM = $points[2];
                $pL = $points[3];
                break;
            case 'l':
                $pM = $points[3];
                $pL = $points[0];
                break;
        }
//        $pM = ['x' => -0.81915204428899, 'y' => 0.81115957534528, 'z' => -0.81915204428899];
//        $pL = ['x' => 0.81915204428899, 'y' => 0.81115957534528, 'z' => -0.81915204428899];
        $this->getPc3($pM, $pL);
    }

    /**
     * @param type $thetaM
     * @param type $phiM
     * @param type $thetaL
     * @param type $phiL
     */
    public function getPc3($pM, $pL) {
        $pO = ['x' => 0, 'y' => 0, 'z' => 0];
        $pN = ['x' => $pM['x'], 'y' => -$pM['y'], 'z' => $pM['z']];
        $pQ = ['x' => $pL['x'], 'y' => -$pL['y'], 'z' => $pL['z']];
//        $r = 1;
//        $jiao_ROP = abs($phiL - $phiM) < pi() ? $phiL - $phiM : pi() * 2 + $phiL - $phiM;
//        $jiao_MON = $thetaM + $thetaM;
//        $jiao_LOQ = $thetaL + $thetaL;
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
//        $area_ROP = 0.5 * $len_OR * $len_OP * sin($jiao_ROP);
//        $len_OW = $area_ROP / $len_RP * 2;
//        $len_RW = sqrt(pow($len_OR, 2) - pow($len_OW, 2));

        $h = 500;
        $c = $this->thirdLen(min($len_OR, $len_OQ), min($len_OR, $len_OQ), $jiao_ROP);
        $w = intval($c / max($len_MN, $len_LQ) * $h);
        $disImg = imagecreatetruecolor($w, $h);
        $jiao_LMG = acos($len_RP / $len_ML);
        $jiao_xOR = -(($pR['x'] < 0 ? pi() : 0) + atan(-$pR['z'] / $pR['x']));
//        exit;
        for ($x = 0; $x < $w; $x++) {
            $len_RT = $x / $w * $len_RP;
//            $jiao_ROT = $len_RT / $len_RP * $jiao_ROP;
//            $len_TW = abs($len_RW - $len_RT);
//            $jiao_TOW = $len_TW / $len_RP * $jiao_ROP;
//            $jiao_TOW = atan($len_TW / $len_OW);
//            $len_OT = $len_OW / cos($jiao_TOW);
//            $len_OT = atan($len_TW / $len_OW);
            $len_OT = $this->thirdLen($len_RT, $len_OR, $jiao_ORP);
            $jiao_ROT = $this->jiao($len_RT, $len_OR, $len_OT);
            $len_MS = $len_ML * $len_RT / $len_RP;
            $len_ST = $len_MN / 2 + $len_MS * sin($jiao_LMG);
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
        imagepng($disImg, __DIR__ . '/x3.png');
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

    /**
     * @param type $thetaM
     * @param type $phiM
     * @param type $thetaL
     * @param type $phiL
     */
    public function getPc2($thetaM, $phiM, $thetaL, $phiL) {
        $r = 1;
        $jiao_ROP = abs($phiL - $phiM) < pi() ? $phiL - $phiM : pi() * 2 + $phiL - $phiM;

        $jiao_MON = $thetaM + $thetaM;
        $len_MN = (sin($jiao_MON / 2) * $r * 2);
        $len_OR = sqrt(pow($r, 2) - pow($len_MN / 2, 2));
        $jiao_LOQ = $thetaL + $thetaL;
        $len_LQ = (sin($jiao_LOQ / 2) * $r * 2);
        $len_OP = sqrt(pow($r, 2) - pow($len_LQ / 2, 2));

        $len_RP = sqrt(pow($len_OR, 2) + pow($len_OP, 2) - 2 * $len_OP * $len_OR * cos($jiao_ROP));
        $len_ML = $len_NQ = sqrt(pow($len_LQ / 2 - $len_MN / 2, 2) + pow($len_RP, 2));

        $h = intval(abs(max($len_MN, $len_LQ) / $len_RP) * 500);
        $w = intval(abs($len_RP / max($len_MN, $len_LQ)) * 500);
        $disImg = imagecreatetruecolor($w, $h);

        $area_ROP = 0.5 * $len_OR * $len_OP * sin($jiao_ROP);
        $len_OW = $area_ROP / $len_RP * 2;
        $len_RW = sqrt(pow($len_OR, 2) - pow($len_OW, 2));
        $jiao_LMG = acos($len_RP / $len_ML);
//        $jiao_ORP = asin($area_ROP / 0.5 / $len_OR / $len_RP);
        for ($x = 0; $x < $w; $x++) {
            $len_RT = $x / $w * $len_RP;
            $jiao_ROT = $len_RT / $len_RP * $jiao_ROP;
            $len_TW = abs($len_RW - $len_RT);
//            $len_OT = hypot($len_OW, $len_TW);
            $jiao_TOW = $len_TW / $len_RP * $jiao_ROP;
            $len_OT = $len_OW / cos($jiao_TOW);
            if ($len_OT == 0) {
                var_dump($jiao_TOW);
                exit;
            }
            $len_MS = $len_ML * $len_RT / $len_RP;
            $len_ST = $len_MN / 2 + $len_MS * sin($jiao_LMG);
//            $len_MS = $x / $w * $len_ML;
//            $len_SV = $len_ML / 2 - $len_MS;
//            $jiao_MUS = $len_MS / $len_ML * $jiao_MUL;
//            $jiao_SUV = $len_SV / $len_ML * $jiao_MUL;
//            $len_US = $len_UV / cos($jiao_SUV);
            for ($y = 0; $y < $h; $y++) {
                $len_HT = ($h / 2 - $y) / $h * $len_ST * 2;
                $S_theta = $jiao_TOH = atan($len_HT / $len_OT);
                $S_phi = $jiao_ROT + $phiM;
                $u = $S_phi / pi() / 2 + 0.5;
                $v = $S_theta / pi() + 0.5;
                $m = intval($u * $this->w);
                $n = intval($v * $this->h);
                $rgb = imagecolorat($this->pano, $m % $this->w, $n);
                imagesetpixel($disImg, $x, $y, $rgb);
            }
        }
        imagepng($disImg, __DIR__ . '/x2.png');
        imagedestroy($disImg);
    }

    /**
     * @param type $theta1
     * @param type $phi1
     * @param type $theta2
     * @param type $phi2
     */
    public function getPc($theta1, $phi1, $theta2, $phi2) {
        $r = 1;
        $jiao_MUL = abs($phi2 - $phi1) < pi() ? $phi2 - $phi1 : pi() * 2 + $phi2 - $phi1;
        $jiao_MON = $theta1 + $theta1;
        $len_MN = (sin($jiao_MON / 2) * $r * 2);
        $len_OR = sqrt(pow($r, 2) - pow($len_MN / 2, 2));
        $len_ML = (sin($jiao_MUL / 2) * $len_OR * 2);
        $len_UV = cos($jiao_MUL / 2) * $len_OR;
        $h = intval(abs($len_MN / $len_ML) * 500);
        $w = intval(abs($len_ML / $len_MN) * 500);
        $disImg = imagecreatetruecolor($w, $h);
        for ($x = 0; $x < $w; $x++) {
            $len_MS = $x / $w * $len_ML;
            $len_SV = $len_ML / 2 - $len_MS;
            $jiao_MUS = $len_MS / $len_ML * $jiao_MUL;
            $jiao_SUV = $len_SV / $len_ML * $jiao_MUL;
            $len_US = $len_UV / cos($jiao_SUV);
            for ($y = 0; $y < $h; $y++) {
                $len_ST = ($h / 2 - $y) / $h * $len_MN;
                $S_theta = $jiao_TOS = atan($len_ST / $len_US);
                $S_phi = $jiao_ROT = $jiao_MUS + $phi1;
                $u = $S_phi / pi() / 2 + 0.5;
                $v = $S_theta / pi() + 0.5;
                $m = intval($u * $this->w);
                $n = intval($v * $this->h);
                $rgb = imagecolorat($this->pano, $m % $this->w, $n);
                imagesetpixel($disImg, $x, $y, $rgb);
            }
        }
        imagepng($disImg, __DIR__ . '/x.png');
        imagedestroy($disImg);
    }

    public function getFace($sideId, $w, $h) {
        $disImg = imagecreatetruecolor($w, $h);
        $imageTransform = [
            [0, 0], //center,front
            [pi() / 2, 0], //center,right
            [pi(), 0], //center,back
            [-pi() / 2, 0], //center,left
            [0, -pi() / 2], //top
            [0, pi() / 2]//bottom
        ];
        // Calculate adjacent (ak) and opposite (an) of the
        // triangle that is spanned from the sphere center 
        //to our cube face.
        $an = sin(pi() / 4); //45度三角形的sin和cos值，即1/√2
        $ak = cos(pi() / 4); //计算相邻ak和相反an的三角形张成球体中心
        $ftu = $imageTransform[$sideId][0];
        $ftv = $imageTransform[$sideId][1];
        // For each point in the target image, 
        // calculate the corresponding source coordinates.                      对于每个图像计算相应的源坐标
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                // Map face pixel coordinates to [-1, 1] on plane               将坐标映射在平面上
                $nx = $y / $h - 0.5;
                $ny = $x / $w - 0.5;
                $nx *= 2;
                $ny *= 2;
                // Map [-1, 1] plane coord to [-an, an]                          
                // thats the coordinates in respect to a unit sphere 
                // that contains our box. 
                $nx *= $an;
                $ny *= $an;
                // Project from plane to sphere surface.
                if ($ftv == 0) {// Center faces，中间4张图
                    $u = atan2($nx, $ak);
                    $v = atan2($ny * cos($u), $ak);
                    $u += $ftu;
                } else if ($ftv > 0) {// Bottom face，下图
                    $d = sqrt($nx * $nx + $ny * $ny);
                    $v = pi() / 2 - atan2($d, $ak);
                    $u = atan2($ny, $nx);
                } else {// Top face，上图
                    $d = sqrt($nx * $nx + $ny * $ny);
                    $v = -pi() / 2 + atan2($d, $ak);
                    $u = atan2(-$ny, $nx);
                }
                // Map from angular coordinates to [-1, 1], respectively.
                $u = $u / pi();
                $v = $v / (pi() / 2);
                // Warp around, if our coordinates are out of bounds. 
                while ($v < -1) {
                    $v += 2;
                    $u += 1;
                }
                while ($v > 1) {
                    $v -= 2;
                    $u += 1;
                }
                while ($u < -1) {
                    $u += 2;
                }
                while ($u > 1) {
                    $u -= 2;
                }
//                // Map from [-1, 1] to in texture space
                $u = $u / 2.0 + 0.5;
                $v = $v / 2.0 + 0.5;
                $u = ($u) * ($this->w - 1);
                $v = ($v) * ($this->h - 1);

                var_dump($u, $v);
                return;
                $rgb = imagecolorat($this->pano, intval($u) % $this->w, intval($v));
                imagesetpixel($disImg, $y, $x, $rgb);
            }
        }
        imagepng($disImg, __DIR__ . "/" . $sideId . '.png');
        imagedestroy($disImg);
    }

}

(new index(__DIR__ . '/1.jpg'))->main();
