<?php
// Check this namespace:
namespace ansori\crudapiwithswagger;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'ansori\crudapiwithswagger\controllers';
    public $defaultRoute = 'docs/index';
    public function init()
    {
        parent::init();
    }
}