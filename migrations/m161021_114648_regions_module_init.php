<?php

use karakum\region\models\RegionLevel;
use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\db\Query;

class m161021_114648_regions_module_init extends Migration
{

    private function getRegionManager()
    {
        /** @var \karakum\region\RegionManager $manager */
        $manager = Yii::$app->regionManager;
        if (!$manager || !($manager instanceof \karakum\region\RegionManager)) {
            throw new InvalidConfigException('You should configure "regionManager" component to use database before executing this migration.');
        }
        return $manager;
    }

    public function up()
    {
        $m = $this->getRegionManager();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($m->regionTypeTable, [
            'id' => $this->primaryKey(),
            'code' => $this->string(32)->notNull(),
        ], $tableOptions);

        $this->createTable($m->regionLevelTable, [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'top_id' => $this->integer(),
            'type_id' => $this->integer()->notNull(),

            'name' => $this->string(),
            'level' => $this->boolean(),
        ], $tableOptions);

        $t1 = $this->getDb()->getSchema()->getRawTableName($m->regionTypeTable);
        $t2 = $this->getDb()->getSchema()->getRawTableName($m->regionLevelTable);
        $t3 = $this->getDb()->getSchema()->getRawTableName($m->regionTable);

        $this->addForeignKey("fk-$t2-type_id-$t1-id", $m->regionLevelTable, ['type_id'], $m->regionTypeTable, ['id'], 'RESTRICT', 'RESTRICT');
        $this->addForeignKey("fk-$t2-parent_id-$t2-id", $m->regionLevelTable, ['parent_id'], $m->regionLevelTable, ['id'], 'RESTRICT', 'RESTRICT');
        $this->addForeignKey("fk-$t2-top_id-$t2-id", $m->regionLevelTable, ['top_id'], $m->regionLevelTable, ['id'], 'RESTRICT', 'RESTRICT');

        $this->createTable($m->regionTable, [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'country_id' => $this->integer(),
            'level_id' => $this->integer()->notNull(),

            'code' => $this->string(),
            'name' => $this->string()->notNull(),
            'fullname' => $this->string(),

            'status' => $this->smallInteger()->notNull(),
            'created' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated' => 'TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
            'deleted' => 'TIMESTAMP NULL DEFAULT NULL',
        ], $tableOptions);

        $this->addForeignKey("fk-$t3-level_id-$t2-id", $m->regionTable, ['level_id'], $m->regionLevelTable, ['id'], 'RESTRICT', 'RESTRICT');
        $this->addForeignKey("fk-$t3-parent_id-$t3-id", $m->regionTable, ['parent_id'], $m->regionTable, ['id'], 'CASCADE', 'RESTRICT');
        $this->addForeignKey("fk-$t3-country_id-$t3-id", $m->regionTable, ['country_id'], $m->regionTable, ['id'], 'CASCADE', 'RESTRICT');

        if ($m->exampleData) {
            foreach ($m->defaultTypes as $type) {
                $this->insert($m->regionTypeTable, ['code' => $type]);
            }
            $types = (new Query())->from($m->regionTypeTable)->indexBy('code')->all();

            foreach ($m->defaultLevels as $topName => $levels) {
                $parent_id = null;
                $l = 1;
                $first = array_keys($levels)[0];
                $regionLevel = new RegionLevel(['parent_id' => $parent_id, 'type_id' => $types[$first]['id'], 'level' => $l, 'name' => $topName]);
                $regionLevel->save();
                $parent_id = $regionLevel->id;
                $this->createLevel($l + 1, $parent_id, array_shift($levels), $types);
            }
        }
    }

    private function createLevel($l, $parent_id, $levels, $types)
    {
        foreach ($levels as $k => $next) {
            if (is_array($next)) {
                $regionLevel = new RegionLevel(['parent_id' => $parent_id, 'type_id' => $types[$k]['id'], 'level' => $l]);
                $regionLevel->save();
                $this->createLevel($l + 1, $regionLevel->id, $next, $types);
            } else {
                $regionLevel = new RegionLevel(['parent_id' => $parent_id, 'type_id' => $types[$next]['id'], 'level' => $l]);
                $regionLevel->save();
            }
        }
    }

    public function down()
    {
        $m = $this->getRegionManager();
        $this->dropTable($m->regionTable);
        $this->dropTable($m->regionLevelTable);
        $this->dropTable($m->regionTypeTable);
    }
}
