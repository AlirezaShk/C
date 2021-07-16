<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ConfigFixture
 */
class ConfigFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'config';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'value' => ['type' => 'string', 'length' => 1000, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'name_idx' => ['type' => 'index', 'columns' => ['name'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'id_UNIQUE' => ['type' => 'unique', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_persian_ci'
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'value' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
