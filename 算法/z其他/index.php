<?php

namespace Yjtec\Algorithm\算法\z其他;

use Yjtec\Algorithm\算法\z其他\寻找最优点\findBestPoint;

include_once '../../公共方法/autoload.php';

/**
 * Description of index
 *
 * @author Administrator
 */
class index {

    public function main() {
        (new findBestPoint())->main();
    }

}

(new index())->main();
