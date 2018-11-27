<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class SectionsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('sections');
        $this->setDisplayField('description');
        $this->setPrimaryKey('id');

        //classes belong to many sections (belongsToMany)
        $this->belongsTo('Classes', [
            'foreignKey' => 'classid'
        ]);

        //instructors belong to many sections (belongsToMany)
        $this->belongsTo('Instructors', [
            'foreignKey' => 'instructorid'

        ]);
        //semesters belong to many sections (belongsToMany ??)
        $this->belongsTo('Semester', [
            'foreignKey' => 'semesterid'

        ]);

        //sections have many students within them (sections_students)
        //semesters belong to many sections (belongsToMany ??)
        $this->belongsToMany('Students', [
            'targetForeignKey' => 'studentid',
            'foreignKey'=>'sectionid',
            'joinTable' => 'sections_students'
        ]);
        //s


        //sections have many classes ??

        //'joinTable' => 'sections_students'

        //
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('year')
            ->requirePresence('year', 'create')
            ->notEmpty('year');

        $validator
            ->integer('semesterid')
            ->requirePresence('semesterid', 'create')
            ->notEmpty('semesterid');

        $validator
            ->scalar('meetingdays')
            ->maxLength('meetingdays', 6)
            ->requirePresence('meetingdays', 'create')
            ->notEmpty('meetingdays');

        $validator
            ->scalar('starttime')
            ->maxLength('starttime', 10)
            ->requirePresence('starttime', 'create')
            ->notEmpty('starttime');

        $validator
            ->scalar('endtime')
            ->maxLength('endtime', 10)
            ->requirePresence('endtime', 'create')
            ->notEmpty('endtime');

        $validator
            ->integer('totalstudents')
            ->requirePresence('totalstudents', 'create')
            ->notEmpty('totalstudents');

        $validator
            ->scalar('sectionid')
            ->maxLength('sectionid', 3)
            ->allowEmpty('sectionid');

        $validator
            ->integer('classid')
            ->allowEmpty('classid');

        $validator
            ->integer('instructorid')
            ->allowEmpty('instructorid');

        return $validator;
    }


    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['semesterid','meetingdays','starttime','endtime','sectionid','classid','instructorid']));
        return $rules;
    }

    public function beforeSave($event,$section,$options)
    {
        //todo - incremental changes to instructors and former instructors
        //check for instructor change... update instructor class totals if there is  a change
        //$sectionBeforeUpdate = $this->get($section->id);

        //decrement classes count
       // $oldInstructor = $this->Instructors->get($sectionBeforeUpdate->instructorid);

      //  $total = $oldInstructor->totalclasses - 1;

        //update old instructor class counts
        //update instructors table
//        $query =  $this->Instructors->query();
//        $query->update()->set(['totalclasses' => $total])
//            ->where(['id'=>$sectionBeforeUpdate->instructorid])
//            ->execute();



    }


    public function afterSaveCommit($event,$section,$options)
    {
        //update instructor class count for the instructor id via datbase methods
        $this->Instructors->InstructorTotalClassCount();
        $this->Instructors->InstructorSemesterClassCount();
        //$this->Instructors->InstructorTotalClassCount($section->instructorid);
        //$this->Instructors->InstructorSemesterClassCount($section->instructorid);

        //todo - complete implementation of this update method to replace the above method.
//        $id = $section->instructorid;
//        //update the number of courses for semester and year for current instructor
//             //update letter grade
//             $currentInstructor = $this->Instructors->get($id);
//
//             $total = $currentInstructor->totalclasses + 1;
//
//             //update instructors table

        // }


    }

    public function afterDelete($event,$section,$options)
    {
        //update instructor class count for the instructor id
        $this->Instructors->InstructorTotalClassCount($section->instructorid);
        $this->Instructors->InstructorSemesterClassCount($section->instructorid);



    }



}
