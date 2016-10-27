Region reference Yii2 module
==================================
This extension provide a region reference module(backend & frontend) with feature:
backend:
- Customizable regions structure(country-region-city...)
- Mass region creation
- Import/export whole country structure
- Two backend module theme: default yii2 & AdminLTE
- Auto generate region structure example data(while install migration), optional

frontend & backend:
- Ajax controller for search regions. Applicable for Select2 field for select region.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist karakum/yii2-region-reference "*"
```

or add

```
"karakum/yii2-region-reference": "*"
```

to the require section of your `composer.json` file.


Configuration
-------------

In application config add:
```
    'modules' => [
    	...
        'regions' => [
            'class' => 'karakum\region\BackendModule',
        ],
    	...
    ],
    'components' => [
    	...
        'regionManager' => [
            'class' => 'karakum\region\RegionManager',
        ],
    	...
    ],
```

For frontend application you can use `karakum\region\FrontendModule`, it provide only ajax controller with unauthorized access.
`karakum\region\BackendModule` already include ajax controller.

Default country.
If you want to use only one country in your application, you can set `country` property in region manager(default is `false`) with country code.
For example, if you set `RU` then it will change some behavior of manager's functions: function `getRegionFullname` will omit country name in output
and ajax output will contain only regions in selected country and country record excluded.

Migrations
----------

Before applying migration you can set `exampleData` property to `true`(by default it is `false`) for `regionManager` component for generate some example data.
If you do that then migration will create:
- region types. Default: COUNTRY, REGION, CITY. It depends on region manager property `defaultTypes`.
- region levels. Default: Country, Region in Country, City in Region in Country, City in Country. It depends on region manager property `defaultLevels`.

Migration will create tables with default names `{{%region}}`, `{{%region_level}}`, `{{%region_type}}`.
To change it use properties `regionTypeTable`, `regionLevelTable`, `regionTable` of region manager component.

To apply migration you can add `@karakum/region/migrations` to migrations lookup list(if you use extension like `cyberz/yii2-multipath-migrations`) or run:
```
$ yii migrate --migrationPath=@karakum/region/migrations
```

Theme select
------------

Backend module provided with two predefined themes. By default it use standard Yii2 theme.
If you prefer AdminLTE template and use extension `dmstr/yii2-adminlte-asset`, you can use predefined AdminLTE theme:

```
    'components' => [
	    ...
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@karakum/region/views' => '@karakum/region/themes/adminlte/views',
                ],
            ],
        ],
	    ...
    ],
```
Both themes use module predefined layouts.
To use your own layout just set `layout` to `null` in module config:
```
    'modules' => [
        'regions' => [
            'class' => 'karakum\region\BackendModule',
            'layout' => null,
        ],
    ],
```

Usage
-----

Example of usage kartik-v Select2 for autocomplete selecting region
```
	<?= $form->field($model, 'region_id')->widget(\kartik\select2\Select2::className(), [
		'data' => $model->region ? [$model->region_id => $model->region->name] : [],
		'options' => ['placeholder' => 'Select region ...'],
		'pluginOptions' => [
			'allowClear' => false,
			'ajax' => [
				'url' => Url::to(['/regions/ajax']),
				'dataType' => 'json',
				'quietMillis' => 100,
				'data' => new JsExpression('function (term, page) {
					return {
						page_limit: 10,
						RegionSearch: { search: term.term, status: ' . \karakum\region\models\Region\Region::STATUS_ACTIVE . ' },
					};
				}'),
				'results' => new JsExpression('function (data, page) {
					return { results: data.results };
				}'),
			],
		],
	]) ?>
```