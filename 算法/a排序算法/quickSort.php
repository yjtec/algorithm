<?php

namespace Yjtec\Algorithm\算法\a排序算法;

/**
 * 
 * 快速排序
 * 时间复杂度：O(NlogN)
 * 稳定性:N
 * 基本思想：（类似于选择排序的定位思想）选一基准元素，依次将剩余元素中小于该基准元素的值放置其左侧，大于等于该基准元素的值放置其右侧；然后，取基准元素的前半部分和后半部分分别进行同样的处理；以此类推，直至各子序列剩余一个元素时，即排序完成（类比二叉树的思想，from up to down）
 * 阿鑫：左右两半，递归调用，佩服佩服。速度快
 *
 * @author 阿鑫
 * @date 2019/11/1
 * @link ax720.com
 */
class quickSort {

    public static function main($array) {
        self::qkSt($array, 0, count($array) - 1);
        return $array;
    }

    /**
     * 子序列排序思想
     * 对于子序列，从左右两侧向中间压缩，先右侧，遇到比基准小的，挪到基准处，这时候再左侧，遇到比基准大的，放到右侧挪出的那个位置，这样循环，最后把中间位置放上基准元素
     * @param type $array
     * @param type $begin
     * @param type $end
     * @return type
     */
    public static function qkSt(&$array, $begin, $end) {
        if ($end - 1 <= $begin) {
            return;
        }
        $base = $array[$begin];
        $left = $begin; //左侧位置
        $right = $end; //右侧位置
        while ($left < $right) {
            while ($left < $right) {
                if ($array[$right] > $base) {//发现一个比基准小的，挪到左侧索引处
                    $array[$left] = $array[$right];
                    $left++;
                    break;
                }
                $right--;
            }
            while ($left < $right) {
                if ($array[$left] < $base) {//发现一个比基准小的，挪到左侧索引处
                    $array[$right] = $array[$left];
                    $right--;
                    break;
                }
                $left++;
            }
        }
        $array[$left] = $base;
        self::qkSt($array, $begin, $left);
        self::qkSt($array, $left + 1, $end);
    }

}
