<?php

namespace karakum\region;


use karakum\region\models\Region;
use karakum\region\models\RegionLevel;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class RegionManager extends Component
{
    public $country = false;

    public $regionTypeTable = '{{%region_type}}';
    public $regionLevelTable = '{{%region_level}}';
    public $regionTable = '{{%region}}';

    public $defaultTypes = ['COUNTRY', 'REGION', 'CITY'];
    public $defaultLevels = [
        'Country-(Region-City|City)' => [
            'COUNTRY' => [
                'REGION' => [
                    'CITY',
                ],
                'CITY',
            ],
        ],
    ];
    public $exampleData = false;

    /**
     * @param $model Region
     * @return RegionLevel[]
     */
    public function getNextLevels($model)
    {
        if ($model->parent_id) {
            $list = $model->parent->level->getChildLevels()->all();
        } else {
            $list = RegionLevel::find()->where([
                'parent_id' => null,
            ])->all();
        }
        return $list;
    }

    /**
     * @param $model Region
     * @param bool $onlyParents
     * @return models\Region[]
     */
    public function getRegionsPath($model, $onlyParents = false)
    {
        $list = [];
        if (!$onlyParents) {
            $list[$model->level->level] = $model;
        }
        while ($model->parent_id) {
            $model = $model->parent;
            $list[$model->level->level] = $model;
        };
        ksort($list);
        return array_values($list);
    }

    /**
     * @param $model Region
     * @param $city
     */
    public function getRegionFullname($model, $city)
    {
        $path = $this->getRegionsPath($model);
        $country = $path[0];
        if ($this->country && $country->code == $this->country) {
            array_shift($path);
        }
        $res = ArrayHelper::getColumn($path, 'name');
        if ($model->level->type->code != 'CITY' && !empty($city)) {
            $res[] = $city;
        }
        return implode(', ', $res);
    }

    /**
     * Get regions structure in array
     * @param $region
     * @return array
     */
    public function exportRegion($region)
    {
        return $this->_exportRegion($region);
    }

    /**
     * Create regions structure from array
     * @param array $item
     * @param RegionLevel $level
     * @return array
     */
    public function importRegion($item, $level)
    {
        $result = [
            'insert' => 0,
            'update' => 0,
            'delete' => 0,
            'errors' => [],
        ];
        $tr = Region::getDb()->beginTransaction();
        try {
            $r = null;
            if (!is_null($item['code'])) {
                $r = Region::find()->where([
                    'code' => $item['code'],
                    'parent_id' => null,
                    'status' => Region::STATUS_ACTIVE,
                ])->one();
            }
            if (!$r) {
                $r = Region::find()->where([
                    'name' => $item['name'],
                    'parent_id' => null,
                    'status' => Region::STATUS_ACTIVE,
                ])->one();
            }
            $found = $r;
            if (!$r) {
                $r = new Region();
                $r->status = Region::STATUS_ACTIVE;
                $r->level_id = $level->id;
            }
            $r->name = $item['name'];
            $r->code = $item['code'];
            $r->fullname = $item['fullname'];
            if ($r->getDirtyAttributes(['name', 'code', 'fullname'])) {
                if ($r->save()) {
                    $r->refresh();
                    if ($found) {
                        $result['update']++;
                    } else {
                        $result['insert']++;
                    }
                } else {
                    if ($r->getFirstErrors()) {
                        $error = implode(', ', array_values($r->getFirstErrors()));
                    } else {
                        $error = Yii::t('regions', 'No errors');
                    }
                    throw new \RuntimeException(Yii::t('regions', 'Region {name} save failed: {error}', [
                        'name' => $r->name,
                        'error' => $error,
                    ]));
                }
            }

            if (isset($item['items'])) {
                $res = $this->_importRegions($r, $item['items']);
                if ($res['errors']) {
                    $tr->rollBack();
                    $result['errors'] = array_merge($result['errors'], $res['errors']);
                } else {
                    $tr->commit();
                    $result['insert'] += $res['insert'];
                    $result['update'] += $res['update'];
                    $result['delete'] += $res['delete'];
                }
            } else {
                $tr->commit();
            }

        } catch (\Exception $e) {
            $tr->rollBack();
            $result['errors'][] = $e->getMessage();
        }
        return $result;
    }

    private function _exportRegion(Region $region)
    {
        $result = $region->toArray(['name', 'code', 'fullname']);
        $result['type'] = $region->level->type->code;
        $result['level'] = $region->level->level;
        $items = [];
        foreach ($region->getChildRegions()->where(['status' => Region::STATUS_ACTIVE])->orderBy(['code' => SORT_ASC])->all() as $child) {
            $items[] = $this->_exportRegion($child);
        }
        if ($items) {
            $result['items'] = $items;
        }
        return $result;
    }

    private function _importRegions(Region $parent, $data)
    {
        $result = [
            'insert' => 0,
            'update' => 0,
            'delete' => 0,
            'errors' => [],
        ];

        $levels = [];
        /** @var RegionLevel $level */
        foreach ($parent->level->getChildLevels()->all() as $level) {
            $levels[$level->type->code] = $level->id;
        }
        $checked = [];
        foreach ($data as $item) {
            $r = null;
            if (!is_null($item['code'])) {
                $r = Region::find()->where([
                    'code' => $item['code'],
                    'parent_id' => $parent->id,
                    'status' => Region::STATUS_ACTIVE,
                ])->one();
            }
            if (!$r) {
                $r = Region::find()->where([
                    'name' => $item['name'],
                    'parent_id' => $parent->id,
                    'status' => Region::STATUS_ACTIVE,
                ])->one();
            }
            $found = $r;
            if (!$found) {
                $r = new Region();
                $r->parent_id = $parent->id;
                $r->status = Region::STATUS_ACTIVE;
                if (isset($levels[$item['type']])) {
                    $r->level_id = $levels[$item['type']];
                } else {
                    $result['errors'][] = Yii::t('regions', 'Region type {type} for item {name} not found in subtypes of {level}', [
                        'type' => $item['type'],
                        'name' => $item['name'],
                        'level' => $parent->level->name ?: $parent->level->type->code,
                    ]);
                    continue;
                }
            }

            $r->name = $item['name'];
            $r->code = $item['code'];
            $r->fullname = $item['fullname'];
            if ($r->getDirtyAttributes(['name', 'code', 'fullname'])) {
                if ($r->save()) {
                    $r->refresh();
                    $checked[] = $r->id;
                    if ($found) {
                        $result['update']++;
                    } else {
                        $result['insert']++;
                    }
                } else {
                    if ($r->getFirstErrors()) {
                        $error = implode(', ', array_values($r->getFirstErrors()));
                    } else {
                        $error = Yii::t('regions', 'No errors');
                    }
                    $result['errors'][] = Yii::t('regions', 'Region {name} save failed: {error}', [
                        'name' => $r->name,
                        'error' => $error,
                    ]);
                    continue;
                }
            } else {
                $checked[] = $r->id;
            }
            if (isset($item['items'])) {
                $res = $this->_importRegions($r, $item['items']);
                if ($res['errors']) {
                    $result['errors'] = array_merge($result['errors'], $res['errors']);
                } else {
                    $result['insert'] += $res['insert'];
                    $result['update'] += $res['update'];
                    $result['delete'] += $res['delete'];
                }
            }

        }
        if ($checked) {
            $not_used = Region::find()
                ->andWhere(['parent_id' => $parent->id, 'status' => Region::STATUS_ACTIVE])
                ->andWhere(['not', ['id' => $checked]])->all();
            foreach ($not_used as $r) {
                $r->delete();// mark as deleted
                $result['delete']++;
            }
        }
        return $result;
    }

}