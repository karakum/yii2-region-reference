<?php

namespace karakum\region;

class BackendModule extends \yii\base\Module
{
    public $controllerNamespace = 'karakum\region\controllers\backend';

    /**
     * @inheritdoc
     */
    public $defaultRoute = 'region/index';

    public $layout = 'main';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
