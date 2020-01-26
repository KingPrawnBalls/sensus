<?php
/**
 * @copyright
 * @license
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * FONT AWESOME asset bundle.
 */
class FontAwesomeAssets extends AssetBundle
{
    public $sourcePath = '../vendor/fortawesome/font-awesome';
    public $css = [
        'css/all.css',
    ];
    public $js = [
    ];
    public $depends = [
    ];
}
