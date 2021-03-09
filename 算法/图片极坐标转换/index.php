<?php

namespace Yjtec\Algorithm\算法\图片极坐标转换;

include_once '../../公共方法/autoload.php';

/**
 * Description of index
 *
 * @author Administrator
 */
class index {

    public $resImg;
    public $resSize;

    public function __construct() {
        $this->resImg = __DIR__ . '/a.jpg';
    }

    public function main() {
        $res = imagecreatefromjpeg($this->resImg); //图片路径
        $w0 = imagesx($res);
        $h0 = imagesy($res);
        $radius = intval($w0 / (2 * pi()));
        $disImg = imagecreatetruecolor(2 * $radius, 2 * $radius);
        imagealphablending($disImg, false);
        imagesavealpha($disImg, true);
        for ($y = 0; $y < imagesy($res); $y++) {
            $theta = 2 * pi() * ($y / $h0);
            for ($x = 0; $x < imagesx($res); $x++) {
                $wrapped_radius = -($x - $w0) * $radius / $w0;
                $dy = $wrapped_radius * cos($theta) + $radius;
                $dx = $wrapped_radius * sin($theta) + $radius;
                $rgb = imagecolorat($res, $x, $y);
                imagesetpixel($disImg, $dx, $dy, $rgb);
            }
        }
        imagepng($disImg, __DIR__ . "/b.png");
        imagedestroy($res);
        imagedestroy($disImg);
    }

    function jzb() {
        $res = imagecreatefrompng(__DIR__ . "/b.png");
        $w0 = imagesx($res);
        $h0 = imagesy($res);
        for ($y = 0; $y < imagesy($res); $y++) {
            $theta = 2 * pi() * ($y / $h0);
            for ($x = 0; $x < imagesx($res); $x++) {
                
            }
        }
    }

# # 准备工作，计算原图像尺寸和变换后的图片大小
# x0 = img.shape[0]
# y0 = img.shape[1]
# # 最大半径计算
# radius = int(y0/(2*math.pi))
# w=2*radius
# h=2*radius
# wrapped_img = 255*np.ones((w, h, 3), dtype="u1")
# except_count = 0
# for j in range(y0):    
# # 1. 求极坐标系中对应的角度theta    
# theta = 2 * math.pi * (j /y0)      
# # 
# print(theta)    
# for i in range(x0):      
# # 2.1 计算半径缩放系数        
# wrapped_radius = -(i-x0)*radius/x0        
# # 2.2 利用对应关系进行换算        
# y = wrapped_radius * math.cos(theta) + radius          
# x = wrapped_radius * math.sin(theta) + radius        
# x, y = int(x), int(y)        
# try:            
# # 3. 将M处的灰度值，赋给M'处像素点的灰度值            wrapped_img[x, y, :] = img[i, j, :]             
# # 注意点,在数学坐标系中的坐标与数字图像中的坐标表示存在差异需要注意        except Exception as e:            except_count = except_count + 1
}

(new index())->main();
