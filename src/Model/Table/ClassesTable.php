<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use cake\error\Debugger;

class ClassesTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('classes');
        //$this->setEntityClass('App\Model\Entity\Course'); \\todo - fix this so that full class name is presented
        //$this->setDisplayField('full_class_name');
        $this->setDisplayField('coursename');
        $this->setPrimaryKey('id');


        //classes belong to many sections (belongsToMany)
        $this->hasMany('Sections', [
            'foreignKey' => 'classid'
        ]);


    }


    public function findClassesByCoursenumber(Query $query, array $options)
    {
        $course = $options['coursenumber'];

        return $query->where(['coursenumber' => $course]);
    }


    //validation
    public function validationDefault(Validator $validator)
    {

        $validator
            ->requirePresence('coursenumber','create')
            ->notEmpty('coursenumber')
            ->requirePresence('coursename','create')
            ->notEmpty('coursename')
            ->requirePresence('coursedescription','create')
            ->notEmpty('coursedescription')
            ->lengthBetween('coursedescription', [10, 100])
            ->requirePresence('subject','create')
            ->notEmpty('subject')
            ->requirePresence('credits','create')
            ->notEmpty('credits')
            ->lengthBetween('credits', [1, 3]);

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['coursenumber']));
        return $rules;
    }


    public function afterDeleteCommit( $event,  $entity,  $options)
    {
        //todo - fix this : generates a not included error in Cake
        //update counts for instructors and counts and gpa for students
        //instructors
        $this->Sections->Instructors->InstructorTotalClassCount();
        $this->Sections->Instructors->InstructorSemesterClassCount();

        //students
        $this->Sections->SectionsStudents->updateSingleStudentYearGPA();
        $this->Sections->SectionsStudents->updateSingleStudentSemesterGPA();
        $this->Sections->SectionsStudents->upDateStudentSemesterCredits();
        $this->Sections->SectionsStudents->upDateStudentYearCredits();
    }

}


