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
class Course extends Entity
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
        'coursename' => true,
        'coursenumber' => true,
        'coursedescription' => true,
        'subject' => true,
        'credits' => true
    ];

    protected function _getFullCourseName()
    {
        return $this->_properties['coursenumber'] . '  ' .
            $this->_properties['coursename'];
    }
}



/*
 * protected function _getFullName()
    {
        return $this->_properties['first_name'] . '  ' .
            $this->_properties['last_name'];
    }
 */












