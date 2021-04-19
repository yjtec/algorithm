<?php

namespace Yjtec\Algorithm\算法\全景图切面;

include_once '../../公共方法/autoload.php';

/**
 * Description of index
 *
 * @author Administrator
 */
class other {

    public function __construct() {
        
    }

    public function main() {
        $this->convert();
    }

    public function outImgToXYZ($i, $j, $face2, $edge) {
        $a = 2 * $i / $edge;
        $b = 2 * $j / $edge;
        if ($face2 == 0) {//right
            return ['x' => 1 - $a, 'y' => 1, "z" => 1 - $b];
        } elseif ($face2 == 1) {//left
            return ['x' => $a - 3, 'y' => -1, "z" => 1 - $b];
        } elseif ($face2 == 2) {//top
            return ['x' => $b - 1, 'y' => $a - 5, "z" => 1];
        } elseif ($face2 == 3) {//bottom
            return ['x' => 3 - $b, 'y' => $a - 1, "z" => -1];
        } elseif ($face2 == 4) {//front
            return ['x' => 1, 'y' => $a - 3, "z" => 3 - $b];
        } elseif ($face2 == 5) {//back
            return ['x' => -1, 'y' => 5 - $a, "z" => 3 - $b];
        }
    }

    public function convert() {
        $inSizeX = $this->w;
        $inSizeY = $this->h;
        $edge = $inSizeX / 4; //横向4个面
        $outSizeX = $edge * 3;
        $outSizeY = $edge * 2;
        $disImg = imagecreatetruecolor($outSizeX, $outSizeY);

        for ($i = 0; $i < $outSizeX; $i++) {
            $face = intval($i / $edge); //0right,1left,2top
            $face2 = null;
            for ($j = 0; $j < $outSizeY; $j++) {
                if ($j >= $edge) {
                    if ($face == 0) {
                        $face2 = 3; //bottom
                    } elseif ($face == 1) {
                        $face2 = 4; //front
                    } else {
                        $face2 = 5; //back
                    }
                } else {
                    $face2 = $face;
                }

                $v = $this->outImgToXYZ($i, $j, $face2, $edge);
                $theta = atan2($v['y'], $v['x']);
                $r = hypot($v['x'], $v['y']);
                $phi = atan2($v['z'], $r);
                $uf = 2 * $edge * ($theta + pi()) / pi();
                $vf = 2 * $edge * ( pi() / 2 - $phi) / pi();
                if ($vf < 0) {
                    $vf = 0;
                } elseif (intval($vf) > $inSizeY) {
                    $vf = $inSizeY - 1;
                }
                $rgb = imagecolorat($this->pano, intval($uf) % $inSizeX, intval($vf));
                imagesetpixel($disImg, $i, $j, $rgb);
            }
        }
        imagepng($disImg, __DIR__ . "/b.png");
        imagedestroy($disImg);
    }

}

$pano = new pano('./2.jpg');
$pano->main();
