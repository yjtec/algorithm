<?php

namespace Yjtec\Algorithm\算法\a排序算法;

include_once '../../公共方法/autoload.php';

/**
 * Description of index
 *
 * @author Administrator
 */
class index {

    public $array = [];
    public $result = [];
    public $numCnt = 100; //待排序数据数量
    public $forTimes = 10000; //对比性能，排序多少次，对比消耗时间

    public function main() {
        $t1 = $this->doFunc('Yjtec\\Algorithm\\算法\\a排序算法\\bubbleSort');
        $t2 = $this->doFunc('Yjtec\\Algorithm\\算法\\a排序算法\\selectSort');
        $t3 = $this->doFunc('Yjtec\\Algorithm\\算法\\a排序算法\\quickSort');
        $t4 = $this->doFunc('Yjtec\\Algorithm\\算法\\a排序算法\\insertSort');
        print_r($t1 . ',' . $t2 . ',' . $t3 . ',' . $t4 . PHP_EOL);
//        print_r(implode(',', $this->result));
    }

    public function test() {
        $this->resetArray();
        print_r(implode(',', $this->array) . PHP_EOL);
        $this->result = insertSort::main($this->array);
        print_r(implode(',', $this->result));
    }

    public function doFunc($func) {
        $t1 = microtime(1);
        for ($i = 0; $i < $this->forTimes; $i++) {
            $this->resetArray();
            $this->result = $func::main($this->array);
        }
        return (microtime(1) - $t1);
    }

    public function resetArray() {
        $this->array = [];
        for ($i = 0; $i < $this->numCnt; $i++) {
            $this->array[] = rand(1, 10000);
        }
    }

}

(new index())->main();
