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

        //semesters belong to many sectionsStudents
        $this->hasMany('SectionsStudents', [
            'foreignKey' => 'sectionid',


        ]);

        //sections have many students within them (sections_students)
        //semesters belong to many sections
        $this->belongsToMany('Students', [
            'targetForeignKey' => 'studentid',
            'foreignKey'=>'sectionid',
            'joinTable' => 'sections_students',

        ]);



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
        //check for instructor change... update instructor class totals for original instructor IF NOT NULL
        $sectionID = $section->id;
        $newInstructorID = $section->instructorid;

        //get the original instructor's information from the database before updating

        if($sectionBeforeUpdate = $this->find()->where(['Sections.id' => $sectionID])->first()) {
            //check to be sure that the instructor was not deleted and made null
            if ($sectionBeforeUpdate->instructorid != null && $newInstructorID != $sectionBeforeUpdate->instructorid) {

                //decrement classes count
                $thisInstructor = $this->Instructors->get($sectionBeforeUpdate->instructorid);

                $thisInstructorYearTotal = $thisInstructor->totalclasses - 1;


                //update instructors table for the year and semester
                $oldInstructor = $this->Instructors->patchEntity($thisInstructor, ['totalclasses' => $thisInstructorYearTotal]);

                //update the semester totals
                if ($this->find()->contain(['Semester'])->where(['Sections.id' => $sectionID, 'Semester.semestercurrent' => '1'])->first()) {
                    $thisInstructorSemesterTotal = $thisInstructor->semesterclasses - 1;

                    //add this value to the current student's total credits and current semester credits
                    $oldInstructor = $this->Instructors->patchEntity($thisInstructor, ['semesterclasses' => $thisInstructorSemesterTotal]);

                }

                //save to the instructors table
                if (!($this->Instructors->save($oldInstructor))) {

                    $this->Flash->error(__('Unable to update instructor course totals information.'));

                }
            }
        }

    }


    public function afterSaveCommit($event,$section,$options)
    {

        //if instructor changes then change course information for prior instructor in beforeSave, new instructor here
        // as an add


        //--------------------------------- Update the Instructor Course Totals for the selected Instructor ----------------//
        //Get the id of the current instructor
        $id = $section->instructorid;
        $sectionid = $section->id;

        //update the number of courses for semester and year for current instructor

        $thisInstructor = $this->Instructors->get($id);

        $thisInstructorYearTotal = $thisInstructor->totalclasses + 1;

        //update instructors table for the year and semester
        $currentInstructor = $this->Instructors->patchEntity($thisInstructor, ['totalclasses'=>$thisInstructorYearTotal]);

        //optionally update the semester totals
        if ($this->find()->contain(['Semester'])->where(['Sections.id' => $sectionid, 'Semester.semestercurrent' => '1'])->first()) {
            $thisInstructorSemesterTotal = $thisInstructor->semesterclasses + 1;

            //add this value to the current student's total credits and current semester credits
            $currentInstructor = $this->Instructors->patchEntity($thisInstructor, ['semesterclasses' => $thisInstructorSemesterTotal]);

        }

        //save to the instructors table
        if (!($this->Instructors->save($currentInstructor))) {

            $this->Flash->error(__('Unable to update instructor course totals information.'));

        }

    }




    public function beforeDelete($event,$section,$options)
    {
        //update Instructor course totals
        //Get the id of the current instructor
        $id = $section->instructorid;
        $sectionid = $section->id;

        //update the number of courses for semester and year for current instructor

        $thisInstructor = $this->Instructors->get($id);

        $thisInstructorYearTotal = $thisInstructor->totalclasses - 1;

        //update instructors table for the year and semester
        $currentInstructor = $this->Instructors->patchEntity($thisInstructor, ['totalclasses'=>$thisInstructorYearTotal]);

        //optionally update the semester totals
        if ($this->find()->contain(['Semester'])->where(['Sections.id' => $sectionid, 'Semester.semestercurrent' => '1'])->first()) {
            $thisInstructorSemesterTotal = $thisInstructor->semesterclasses - 1;

            //add this value to the current student's total credits and current semester credits
            $currentInstructor = $this->Instructors->patchEntity($thisInstructor, ['semesterclasses' => $thisInstructorSemesterTotal]);

        }

        //save to the instructors table
        if (!($this->Instructors->save($currentInstructor))) {

            $this->Flash->error(__('Unable to update instructor course totals information.'));

        }


        //Delete all section_student records - automatic via join table belongsToMany relationship
        //update student credits on deletion from a section


        //get the data commited to the sections_students table

        $sectionid = $section->id;


        //get all student ids from sections before they are deleted from the join table
        $currentSectionStudents = $this->SectionsStudents->find()
            ->where(['SectionsStudents.sectionid' => $sectionid])
            ->all();


        //sequentially change the gpa and credit values for each student
        foreach ($currentSectionStudents as $currentStudent) {
            $currentStudentID = $currentStudent->studentid;

            $modeFlag = 0;

            $this->SectionsStudents->computeStudentCredits($currentStudentID, $sectionid, $modeFlag);



            //A grade was changed - update the student's gpa values for semester and year
            $this->SectionsStudents->computeStudentGpas($currentStudentID , $sectionid, $modeFlag );




        }

    }




}
