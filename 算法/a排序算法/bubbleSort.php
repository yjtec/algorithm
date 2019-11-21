<?php

namespace Yjtec\Algorithm\算法\a排序算法;

/**
 * 
 * 冒泡排序
 * 时间复杂度:O(N2)
 * 稳定性:Y
 * 基本思想：依次比较相邻两元素，若前一元素大于后一元素则交换之，直至最后一个元素即为最大；然后重新从首元素开始重复同样的操作，直至倒数第二个元素即为次大元素；依次类推。如同水中的气泡，依次将最大或最小元素气泡浮出水面。
 * 阿鑫：这个好慢啊
 *
 * @author 阿鑫
 * @date 2019/11/1
 * @link ax720.com
 */
class bubbleSort {

    public static function main($array) {
        for ($i = count($array) - 1; $i >= 0; $i--) {//主循环每次出1个结果
            for ($j = 0; $j < $i; $j++) {//子片段循环
                if ($array[$j] < $array[$j + 1]) {//相邻比较，下沉
                    $temp = $array[$j];
                    $array[$j] = $array[$j + 1];
                    $array[$j + 1] = $temp;
                }
            }
        }
        return $array;
    }

}
