<?php 

namespace ansori\crudapiwithswagger;

use yii\web\AssetBundle;

/**
 * @author ansori <muhammadansorinasution@gmail.com>
 * @since 1.0
 */
class CrudAsset extends AssetBundle
{
    public $sourcePath = '@crudapiwithswagger/assets';

//    public $publishOptions = [
//        'forceCopy' => true,
//    ];

    public $css = [
        'dist/swagger-ui.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\grid\GridViewAsset',
    ];
    
   public function init() {
       // In dev mode use non-minified javascripts
       $this->js = [
            'dist/swagger-ui-bundle.js',
            'dist/swagger-ui-standalone-preset.js',
            'dist/swagger-ui-bundle.js'
       ];

       parent::init();
   }
}
