<?php

namespace karakum\region\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%region_level}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $top_id
 * @property integer $type_id
 * @property string $name
 * @property integer $level
 *
 * @property RegionLevel $parent
 * @property RegionLevel $top
 * @property RegionType $type
 * @property RegionLevel[] $childLevels
 * @property Region[] $regions
 */
class RegionLevel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%region_level}}';
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $top = $this->parent ? ($this->parent->parent_id ? $this->parent->top_id : $this->parent_id) : null;
            if ($this->top_id != $top) {
                $this->top_id = $top;
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'top_id', 'type_id', 'level'], 'integer'],
            [['type_id'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model/region-level', 'ID'),
            'parent_id' => Yii::t('model/region-level', 'Parent'),
            'top_id' => Yii::t('model/region-level', 'Top'),
            'type_id' => Yii::t('model/region-level', 'Type'),
            'name' => Yii::t('model/region-level', 'Name'),
            'level' => Yii::t('model/region-level', 'Level'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this->hasMany(Region::className(), ['level_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(RegionLevel::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildLevels()
    {
        return $this->hasMany(RegionLevel::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTop()
    {
        return $this->hasOne(RegionLevel::className(), ['id' => 'top_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(RegionType::className(), ['id' => 'type_id']);
    }
}
