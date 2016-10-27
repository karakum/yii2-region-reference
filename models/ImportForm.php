<?php

namespace karakum\region\models;


use Yii;
use yii\base\Model;

class ImportForm extends Model
{
    public $level_id;
    public $importFile;

    public $data;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level_id'], 'integer'],
            [['level_id', 'importFile'], 'required'],
            ['importFile', 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'level_id' => Yii::t('model/import-form', 'Levels schema'),
            'importFile' => Yii::t('model/import-form', 'Import file'),
        ];
    }

}