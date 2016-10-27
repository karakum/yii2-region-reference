<?php

namespace karakum\region\models;


use Yii;
use yii\base\Model;

class ExportForm extends Model
{
    public $region_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region_id'], 'integer'],
            [['region_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'region_id' => Yii::t('model/export-form', 'Country'),
        ];
    }

}