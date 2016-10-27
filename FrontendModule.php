<?php

namespace karakum\region;

class FrontendModule extends \yii\base\Module
{
    public $controllerNamespace = 'karakum\region\controllers\frontend';

    /**
     * @inheritdoc
     */
    public $defaultRoute = 'region/index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
