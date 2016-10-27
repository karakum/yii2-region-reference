<?php

namespace karakum\region\models;

use karakum\common\components\MarkDeletedBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $country_id
 * @property integer $level_id
 * @property string $code
 * @property string $name
 * @property string $fullname
 * @property integer $status
 * @property string $created
 * @property string $updated
 * @property string $deleted
 *
 * @property Region $parent
 * @property Region $country
 * @property RegionLevel $level
 * @property Region[] $childRegions
 */
class Region extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 10;

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('model/region', 'Active'),
            self::STATUS_INACTIVE => Yii::t('model/region', 'Inactive'),
            self::STATUS_DELETED => Yii::t('model/region', 'Deleted'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%region}}';
    }


    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $country = $this->parent_id ? ($this->parent->parent_id ? $this->parent->country_id : $this->parent_id) : null;
            if ($this->country_id != $country) {
                $this->country_id = $country;
            }
            return true;
        }
        return false;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['parent_id', 'level_id', 'code', 'name', 'fullname', 'status'];
        $scenarios[self::SCENARIO_UPDATE] = ['level_id', 'code', 'name', 'fullname', 'status'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => MarkDeletedBehavior::className(),
                'deletedAtAttribute' => 'deleted',
                'deletedStatus' => self::STATUS_DELETED,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'country_id', 'level_id', 'status'], 'integer'],
            [['level_id', 'name', 'status'], 'required'],
            [['created', 'updated', 'deleted'], 'safe'],
            [['code', 'name', 'fullname'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model/region', 'ID'),
            'parent_id' => Yii::t('model/region', 'Parent'),
            'country_id' => Yii::t('model/region', 'Country'),
            'level_id' => Yii::t('model/region', 'Level'),
            'code' => Yii::t('model/region', 'Code'),
            'name' => Yii::t('model/region', 'Name'),
            'fullname' => Yii::t('model/region', 'Fullname'),
            'status' => Yii::t('model/region', 'Status'),
            'statusName' => Yii::t('model/region', 'Status'),
            'created' => Yii::t('model/region', 'Created'),
            'updated' => Yii::t('model/region', 'Updated'),
            'deleted' => Yii::t('model/region', 'Deleted'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Region::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Region::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(RegionLevel::className(), ['id' => 'level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildRegions()
    {
        return $this->hasMany(Region::className(), ['parent_id' => 'id']);
    }

    public function getStatusName()
    {
        $statuses = $this->getStatuses();
        if (isset($statuses[$this->status])) {
            return $statuses[$this->status];
        } else {
            return '';
        }
    }

}
