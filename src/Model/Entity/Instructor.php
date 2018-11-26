<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Section Entity
 *
 * @property int $id
 * @property int $year
 * @property string $semester
 * @property string $meetingdays
 * @property string $starttime
 * @property string $endtime
 * @property int $totalstudents
 * @property string $sectionid
 * @property int $classid
 * @property int $instructorid
 *
 * @property \App\Model\Entity\Student[] $students
 */
class Instructor extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'firstname' => true,
        'lastname' => true,
        'department' => true,
        'semesterclasses' => true,
        'totalclasses' => true
    ];

    protected function _getFullName()
    {
        return $this->_properties['firstname'] . '  ' .
            $this->_properties['lastname'];
    }
}

