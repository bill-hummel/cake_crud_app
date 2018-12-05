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

//    public function beforeSHave($event,$section,$options)
//    {
//        //todo - incremental changes to instructors and former instructors
//        //check for instructor change... update instructor class totals for original instructor IF NOT NULL
//        $sectionID = $section->id;  //todo - remove
//        $newInstructorID = $section->instructorid; //todo - remove
//
//        //get the original instructor's information from the database before updating
//
//        //todo - use getOriginal method
//
//        if($sectionBeforeUpdate = $this->find()->where(['Sections.id' => $sectionID])->first()) {
//            //check to be sure that the instructor was not deleted and made null
//            if ($this->Instructors->find()->where(['id' => $sectionBeforeUpdate->instructorid])->first()) {
//
//
//
//                if ($sectionBeforeUpdate->instructorid != null && $newInstructorID != $sectionBeforeUpdate->instructorid) {
//
//                    //decrement classes count
//                    $thisInstructor = $this->Instructors->get($sectionBeforeUpdate->instructorid);
//
//                    $thisInstructorYearTotal = $thisInstructor->totalclasses - 1;
//
//
//                    //update instructors table for the year and semester
//                    $oldInstructor = $this->Instructors->patchEntity($thisInstructor, ['totalclasses' => $thisInstructorYearTotal]);
//
//                    //update the semester totals
//                    if ($this->find()->contain(['Semester'])->where(['Sections.id' => $sectionID, 'Semester.semestercurrent' => '1'])->first()) {
//                        $thisInstructorSemesterTotal = $thisInstructor->semesterclasses - 1;
//
//                        //add this value to the current student's total credits and current semester credits
//                        $oldInstructor = $this->Instructors->patchEntity($thisInstructor, ['semesterclasses' => $thisInstructorSemesterTotal]);
//
//                    }
//
//                    //save to the instructors table
//                    if (!($this->Instructors->save($oldInstructor))) {
//
//                        //todo - remove and replace with a try{} - catch the exception in the controller and then display the flash message
//                        $this->Flash->error(__('Unable to update instructor course totals information.'));
//
//                    }
//                }
//            }
//
//        }
//    }
//
//
//    public function afterSHaveCommit($event,$section,$options)
//    {
//
//
//        //if instructor changes then change course information for prior instructor in beforeSave, new instructor here as an add
//
//        //--------------------------------- Update the Instructor Course Totals for the selected Instructor ----------------//
//        //Get the id of the current instructor
//        $id = $section->instructorid;  //todo -- remove
//        $sectionid = $section->id;  //todo -- remove
//
//        //check if instructor was deleted first!
//        if ($this->Instructors->find()->where(['id' => $id])->first()) {
//
//            //update the number of courses for semester and year for current instructor
//
//            $thisInstructor = $this->Instructors->get($id);
//
//            $thisInstructorYearTotal = $thisInstructor->totalclasses + 1;
//
//            //update instructors table for the year and semester
//            $currentInstructor = $this->Instructors->patchEntity($thisInstructor, ['totalclasses' => $thisInstructorYearTotal]);
//
//            //optionally update the semester totals
//            if ($this->find()->contain(['Semester'])->where(['Sections.id' => $sectionid, 'Semester.semestercurrent' => '1'])->first()) {
//                $thisInstructorSemesterTotal = $thisInstructor->semesterclasses + 1;
//
//                //add this value to the current student's total credits and current semester credits
//                $currentInstructor = $this->Instructors->patchEntity($thisInstructor, ['semesterclasses' => $thisInstructorSemesterTotal]);
//
//            }
//
//            //save to the instructors table
//            if (!($this->Instructors->save($currentInstructor))) {
//
//                $this->Flash->error(__('Unable to update instructor course totals information.'));
//
//            }
//        }
//    }

    //ammended afterSaveCommit function
    public function afterSaveCommit($event,$section,$options)
    {
        //Get instructor id before change to new instructor - see Cake API documentation
        $oldInstructorID = $section->getOriginal('instructorid');
        /*
         * Note: getOriginal() will return the id of the instructor that was last assigned, even if you
         * deleted that instructor and then try to update instructor totals. You still need to check the
         * db table to be sure that the instructor is deleted before attempting to update that record and
         * thus generating a record not found error.
         */

        //get current / new instructor and section id(s)
        $newInstructorID = $section->instructorid;  //todo -- remove and insert directly on refactor
        $sectionID = $section->id;  //todo -- remove and insert directly on refactor


        //if the instructor was NOT deleted then this commit was issued from the edit method or the add method (sections)
        if($newInstructorID ) {

            //instructor was just deleted, was added, or was updated from non null id
            //Update new instructor -- increment new (+1)
            $newInstructor = $this->Instructors->get($newInstructorID);

            $newInstructorYearTotal = $newInstructor->totalclasses + 1;

            //update instructors table for the year and semester
            $currentInstructor = $this->Instructors->patchEntity($newInstructor, ['totalclasses' => $newInstructorYearTotal]);

            //optionally update the semester totals
            if ($this->find()->contain(['Semester'])->where(['Sections.id' => $sectionID, 'Semester.semestercurrent' => '1'])->first()) {
                $newInstructorSemesterTotal = $newInstructor->semesterclasses + 1;

                //add this value to the current student's total credits and current semester credits
                $currentInstructor = $this->Instructors->patchEntity($newInstructor, ['semesterclasses' => $newInstructorSemesterTotal]);

            }

            //save to the instructors table
            if (!($this->Instructors->save($currentInstructor))) {

                //todo - remove and replace with a try{} - catch the exception in the controller and then display the flash message
                //$this->Flash->error(__('Unable to update instructor course totals information.'));

            }

            //decrement old instructor account only if a change of instructor has occurred
            if($oldInstructorID != null && $newInstructorID != $oldInstructorID) {

                //instructor was updated -- decrement old instructor (-1) and increment new (+1)
                //decrement classes count
                $oldInstructor = $this->Instructors->get($oldInstructorID);

                $oldInstructorYearTotal = $oldInstructor->totalclasses - 1;


                //update instructors table for the year and semester
                $oldInstructordata = $this->Instructors->patchEntity($oldInstructor, ['totalclasses' => $oldInstructorYearTotal]);

                //update the semester totals
                if ($this->find()->contain(['Semester'])->where(['Sections.id' => $sectionID, 'Semester.semestercurrent' => '1'])->first()) {
                    $oldInstructorSemesterTotal = $oldInstructor->semesterclasses - 1;

                    //add this value to the current student's total credits and current semester credits
                    $oldInstructordata = $this->Instructors->patchEntity($oldInstructor, ['semesterclasses' => $oldInstructorSemesterTotal]);

                }

                if (!($this->Instructors->save($oldInstructordata))) {

                    //todo - remove and replace with a try{} - catch the exception in the controller and then display the flash message
                    //$this->Flash->error(__('Unable to update instructor course totals information.'));

                }
            }

        }
        //old was not null but new was null - nothing to do

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
