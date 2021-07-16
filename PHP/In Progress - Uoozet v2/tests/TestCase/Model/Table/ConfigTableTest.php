<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConfigTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConfigTable Test Case
 */
class ConfigTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConfigTable
     */
    protected $Config;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Config',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Config') ? [] : ['className' => ConfigTable::class];
        $this->Config = $this->getTableLocator()->get('Config', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Config);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
