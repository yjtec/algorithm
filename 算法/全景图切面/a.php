<?php

namespace Yjtec\Algorithm\算法\全景图切面;

include_once '../../公共方法/autoload.php';

/**
 * Description of index
 *
 * @author Administrator
 */
class a {

    public function __construct() {
        
    }

    public function main() {
        $this->convert(60, 10, 20, 100, 300);
    }

    public function convert($FOV, $THETA, $PHI, $height, $width, $RADIUS = 128) {
        $equ_cx = ($this->w - 1) * 2;
        $equ_cy = ($this->h - 1) * 2;

        $wFOV = $FOV;
        $hFOV = $height / $width * $wFOV;

        $c_x = ($width - 1) / 2;
        $c_y = ($height - 1) / 2;

        $wangle = (180 - $wFOV) / 2;
        $w_len = 2 * $RADIUS * sin(deg2rad($wFOV / 2)) / sin(deg2rad($wangle));
        $w_interval = $w_len / ($width - 1);

        $hangle = (180 - $hFOV) / 2;
        $h_len = 2 * $RADIUS * sin(deg2rad($hFOV / 2)) / sin(deg2rad($hangle));
        $h_interval = $h_len / ($height - 1);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $x_map[$y][$x] = $RADIUS;
                $y_map[$y][$x] = ($x - $c_x) * $w_interval;
                $z_map[$y][$x] = -($y - $c_y) * $h_interval;
            }
        }
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $D[$y][$x] = sqrt(pow($x_map[$y][$x], 2) + pow($y_map[$y][$x], 2) + pow($z_map[$y][$x], 2));
            }
        }
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $xyz[$y][$x] = [
                    $RADIUS / $D[$y][$x] * $x_map[$y][$x],
                    $RADIUS / $D[$y][$x] * $y_map[$y][$x],
                    $RADIUS / $D[$y][$x] * $z_map[$y][$x]
                ];
            }
        }
        $y_axis = [0, 1, 0];
        $z_axis = [0, 0, 1];
        print_r($xyz[84]);
        exit;
        for ($y = 0; $y < $height; $y++) {
            
        }
    }

}

$pano = new a('./2.jpg');
$pano->main();
