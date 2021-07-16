<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'username' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'password' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'fname' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'lname' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'age' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'mobile' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'email' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'completeSignup' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'registerDate' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => '0', 'collate' => 'utf8mb4_persian_ci', 'comment' => 'DD/MM/YYYY', 'precision' => null],
        'registerCode' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'registeredCode' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'registerPoints' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'access' => ['type' => 'tinyinteger', 'length' => null, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '100: admin
50: channel
2: user
1: guest
0: banned', 'precision' => null],
        'gender' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '0=>male
1=>female
2=>other', 'precision' => null],
        'email_vcode' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => 'email verification code', 'precision' => null],
        'device_id' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'lang' => ['type' => 'string', 'length' => 8, 'null' => true, 'default' => 'english', 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'thumbnail' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'cover' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'country' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => 'iran', 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'about' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'other_socials' => ['type' => 'string', 'length' => 600, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => 'for e.g.: 
instagram:sampleLink|youtube:sampleLink|tweeter:sampleLink|', 'precision' => null],
        'allow_notify' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'verified' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'last_active' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => 'timestamp', 'precision' => null, 'autoIncrement' => null],
        'active_time' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => 'timestamp', 'precision' => null, 'autoIncrement' => null],
        'active_expire' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => 'timestamp', 'precision' => null, 'autoIncrement' => null],
        'pro' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null],
        'imports' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'uploads' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'wallet' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'balance' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        'user_upload_limit' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => '0', 'collate' => 'utf8mb4_persian_ci', 'comment' => '0, unlimited', 'precision' => null],
        'two_factor' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'last_month' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_persian_ci', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'search' => ['type' => 'index', 'columns' => ['username', 'mobile', 'email', 'lname', 'fname'], 'length' => []],
            'admin' => ['type' => 'index', 'columns' => ['access'], 'length' => []],
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
                'username' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'fname' => 'Lorem ipsum dolor sit amet',
                'lname' => 'Lorem ipsum dolor sit amet',
                'age' => 1,
                'mobile' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'active' => 1,
                'completeSignup' => 1,
                'registerDate' => 'Lorem ip',
                'registerCode' => 'Lorem ip',
                'registeredCode' => 'Lorem ip',
                'registerPoints' => 1,
                'access' => 1,
                'gender' => 1,
                'email_vcode' => 'Lorem ipsum dolor sit amet',
                'device_id' => 'Lorem ipsum dolor sit amet',
                'lang' => 'Lorem ',
                'thumbnail' => 'Lorem ipsum dolor sit amet',
                'cover' => 'Lorem ipsum dolor sit amet',
                'country' => 'Lorem ipsum dolor sit amet',
                'about' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'other_socials' => 'Lorem ipsum dolor sit amet',
                'allow_notify' => 1,
                'verified' => 1,
                'last_active' => 1,
                'active_time' => 1,
                'active_expire' => 1,
                'pro' => 1,
                'imports' => 1,
                'uploads' => 1,
                'wallet' => 'Lorem ipsum dolor sit amet',
                'balance' => 'Lorem ipsum dolor sit amet',
                'user_upload_limit' => 'Lorem ipsum dolor sit amet',
                'two_factor' => 1,
                'last_month' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            ],
        ];
        parent::init();
    }
}
