<?php

namespace karakum\region\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%region_type}}".
 *
 * @property integer $id
 * @property string $code
 */
class RegionType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%region_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['code'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model/region-type', 'ID'),
            'code' => Yii::t('model/region-type', 'Code'),
        ];
    }
}
