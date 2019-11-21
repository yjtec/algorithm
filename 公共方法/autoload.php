<?php

namespace Yjtec\Algorithm\公共方法;

/**
 * Description of init
 *
 * @author Administrator
 */
class autoload {

    public static function autoload($class) {
        if (false !== strpos($class, '\\')) {
            $FirstNamespace = substr($class, 0, 16);

            $secondName = substr($class, strpos($class, '\\'));
            $filename = null;
            if ($FirstNamespace == 'Yjtec\\Algorithm\\') {
                $filename = dirname(dirname(dirname(realpath(__FILE__)))) . str_replace('\\', '/', $secondName);
            }
            if ($filename && is_file($filename . '.php')) {
                include $filename . '.php';
            }
        }
    }

}

spl_autoload_register('\Yjtec\Algorithm\公共方法\autoload::autoload');
