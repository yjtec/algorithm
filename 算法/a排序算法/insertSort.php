<?php

namespace Yjtec\Algorithm\算法\a排序算法;

/**
 * 
 * 插入排序
 * 时间复杂度:O(N2)
 * 稳定性:Y
 * 基本思想：数列前面部分看为有序，依次将后面的无序数列元素插入到前面的有序数列中，初始状态有序数列仅有一个元素，即首元素。在将无序数列元素插入有序数列的过程中，采用了逆序遍历有序数列，相较于顺序遍历会稍显繁琐，但当数列本身已近排序状态效率会更高。
 * 阿鑫：从第一个数字往下走，有序的就继续下一个，知道遇到一个无序的，然后倒序找这个无序的数字应有的位置。
 * 我这种算法貌似比冒泡什么的快了点，好像跟别人的插入排序也不太一样。
 * @author 阿鑫
 * @date 2019/11/1
 * @link ax720.com
 */
class insertSort {

    public static function main($array) {
        for ($i = 0; $i < count($array) - 1; $i++) {
            if ($array[$i] > $array[$i + 1]) {//前边都是有序的
                continue;
            }
            $temp = $array[$i + 1];
            for ($j = $i; $j >= 0; $j--) {//遇到一个数字，跟前边的顺序不一致，这时候把这个数字插入到前边的有序中
                if ($array[$j] < $temp) {
                    $array[$j + 1] = $array[$j];
                } else {
                    break;
                }
            }
            $array[$j + 1] = $temp;
        }
        return $array;
    }

}
