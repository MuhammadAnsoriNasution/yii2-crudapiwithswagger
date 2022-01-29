<?php

namespace ansori\crudapiwithswagger\controllers;

class DocsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
