<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SemesterTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SemesterTable Test Case
 */
class SemesterTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SemesterTable
     */
    public $Semester;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.semester'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Semester') ? [] : ['className' => SemesterTable::class];
        $this->Semester = TableRegistry::getTableLocator()->get('Semester', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Semester);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
