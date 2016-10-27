<?php

namespace karakum\region\models;


use Yii;
use yii\base\Model;

class CreateRegionForm extends Model
{
    public $parent_id;
    public $level_id;
    public $status;
    public $name;
    public $fullname;
    public $code;

    public $mode;
    public $multiple_data;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'level_id', 'status'], 'integer'],
            [['level_id', 'status'], 'required'],
            [['code', 'name', 'fullname'], 'string', 'max' => 255],
            ['name', 'required',
                'when' => function (CreateRegionForm $model) {
                    return !$model->mode;
                },
                'whenClient' => "function (attribute, value) {
                    return (
                        $('#createregionform-mode').val()=='0'
                    );
                }",
            ],
            ['mode', 'boolean'],
            ['multiple_data', 'string'],
            ['multiple_data', 'required',
                'when' => function (CreateRegionForm $model) {
                    return $model->mode;
                },
                'whenClient' => "function (attribute, value) {
                    return (
                        $('#createregionform-mode').val()=='1'
                    );
                }",
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent_id' => Yii::t('model/region', 'Parent'),
            'level_id' => Yii::t('model/region', 'Level'),
            'code' => Yii::t('model/region', 'Code'),
            'name' => Yii::t('model/region', 'Name'),
            'fullname' => Yii::t('model/region', 'Fullname'),
            'status' => Yii::t('model/region', 'Status'),
        ];
    }

    public function attributeHints()
    {
        return [
            'multiple_data' => Yii::t('model/region', 'Multiple format(each line): NAME[,CODE[,FULLNAME]]. Delimiters: colon(:),semicolon(;),comma(,),tabulation'),
        ];
    }


    public function createRegion()
    {
        $region = new Region();
        $region->parent_id = $this->parent_id;
        $region->level_id = $this->level_id;
        $region->status = $this->status;
        if ($this->mode) {

            $cond = [];
            if ($this->parent_id) {
                $parent = Region::findOne($this->parent_id);
                $cond = ['country_id' => $parent->country_id];
            } else {
                $cond = ['parent_id' => null];
            }

            $lines = array_values(array_map(function ($el) {
                return array_map('trim', mb_split('[\t,;:]', $el));
            }, array_filter(explode("\n", $this->multiple_data), 'trim')));
            foreach ($lines as $line) {
                $r = null;
                if (isset($line[1])) {// code
                    $r = Region::find()->andWhere($cond)->andWhere(['code' => $line[1]])->one();
                } else {
                    $r = Region::find()->andWhere($cond)->andWhere(['name' => $line[0]])->one();
                    if ($r) {
                        continue;
                    }
                }
                if (!$r) {
                    $r = new Region();
                }
                $r->parent_id = $this->parent_id;
                $r->level_id = $this->level_id;
                $r->status = $this->status;
                $r->name = $line[0];
                if (isset($line[1])) {
                    $r->code = $line[1];
                }
                if (isset($line[2])) {
                    $r->fullname = $line[2];
                }
                $r->save();
            }
            return true;
        } else {
            $region->code = $this->code;
            $region->name = $this->name;
            $region->fullname = $this->fullname;
            return $region->save();
        }
    }

}