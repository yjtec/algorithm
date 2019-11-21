<?php

namespace Yjtec\Algorithm\算法\a排序算法;

/**
 * 
 * 选择排序
 * 时间复杂度:O(N2)
 * 稳定性:N
 * 基本思想：首先初始化最小元素索引值为首元素，依次遍历待排序数列，若遇到小于该最小索引位置处的元素则刷新最小索引为该较小元素的位置，直至遇到尾元素，结束一次遍历，并将最小索引处元素与首元素交换；然后，初始化最小索引值为第二个待排序数列元素位置，同样的操作，可得到数列第二个元素即为次小元素；以此类推。
 * 阿鑫：从头到尾找到一个最小的放在第一位，然后后边的序列找到第二小的放第二位。这个好慢啊
 *
 * @author 阿鑫
 * @date 2019/11/1
 * @link ax720.com
 */
class selectSort {

    public static function main($array) {
        for ($i = 0; $i < count($array); $i++) {
            $index = $i;
            for ($j = $i + 1; $j < count($array); $j++) {
                if ($array[$index] < $array[$j]) {
                    $index = $j;
                }
            }
            if ($index != $i) {
                $temp = $array[$index];
                $array[$index] = $array[$i];
                $array[$i] = $temp;
            }
        }
        return $array;
    }

}
