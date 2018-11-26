<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SectionsFixture
 *
 */
class SectionsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'year' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '2018', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'semester' => ['type' => 'string', 'fixed' => true, 'length' => 1, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'meetingdays' => ['type' => 'string', 'length' => 6, 'null' => false, 'default' => 'MWF', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'starttime' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'endtime' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'totalstudents' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'sectionid' => ['type' => 'string', 'length' => 3, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'classid' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'instructorid' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'instructorid' => ['type' => 'index', 'columns' => ['instructorid'], 'length' => []],
            'classid' => ['type' => 'index', 'columns' => ['classid'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'sections_ibfk_2' => ['type' => 'foreign', 'columns' => ['instructorid'], 'references' => ['instructors', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'sections_ibfk_3' => ['type' => 'foreign', 'columns' => ['classid'], 'references' => ['classes', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'year' => 1,
                'semester' => 'L',
                'meetingdays' => 'Lore',
                'starttime' => 'Lorem ip',
                'endtime' => 'Lorem ip',
                'totalstudents' => 1,
                'sectionid' => 'L',
                'classid' => 1,
                'instructorid' => 1
            ],
        ];
        parent::init();
    }
}
