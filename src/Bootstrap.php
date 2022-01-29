<?php

namespace ansori\crudapiwithswagger;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * @author ansori <muhammadansorinasution@gmail.com>
 * @since 1.0
 */
class Bootstrap implements BootstrapInterface {

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app) {
        Yii::setAlias("@crudapiwithswagger", __DIR__);
        Yii::setAlias("@ansori/crudapiwithswagger", __DIR__);
        if ($app->hasModule('gii')) {
            if (!isset($app->getModule('gii')->generators['crudapiwithswagger'])) {
                $app->getModule('gii')->generators['crudapiwithswagger'] = 'ansori\crudapiwithswagger\generators\Generator';
            }
        }
    }

}
