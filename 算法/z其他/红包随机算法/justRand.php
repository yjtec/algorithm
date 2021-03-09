<?php

namespace Yjtec\Algorithm\算法\z其他\红包随机算法;

/**
 * Description of justRand
 *
 * @author Administrator
 */
class justRand {

    private function randNum($total, $num, $min = 0.01) {
        $data = array();
        for ($i = 1; $i < $num; $i++) {
            if ((int) ($total - ($num - $i) * $min) * 100 <= (int) $min * 100) {
                $money = $min;
            } else {
                $safeToal = ($total - ($num - $i) * $min) / ($num - $i);
                $money = mt_rand($min * 100, $safeToal * 100) / 100;
            }

            if ($money <= 0) {
                $moeny = $min;
            }
            $total = $total - $money;
            $data[$i] = number_format($money, 2, '.', '');
        }
        $data[$num] = number_format($total, 2, '.', '');
        return array_values($data);
    }

}
