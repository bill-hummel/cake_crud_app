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




    public function beforeDelete($event,$class,$options)
    {

       //update student and Instructor records


        //get the id for this class record
        $classid = $class->id;

        $modeFlag = 2;   //modeflag = 1 ---> ad a course,   modeFlag = 2 ---> delete a course

        //Get the sections associated with this course
        $currentClassSections = $this->Sections->find()
            ->where(['Sections.classid' => $classid])
            ->all();

        //iterate through all sections assigned to this class
        foreach($currentClassSections as $currentSection) {
            $currentSectionID = $currentSection->id;

            //-----------------------update instructors removed from sections associated with this class -------------//

            //find the list of instructors (1 per section) for each section
            $currentSectionInstructors = $this->Sections->find()
                ->where(['Sections.id'=>$currentSectionID])
                ->all();

            //sequentially update the semester and year credits for each instructor affected
            foreach ($currentSectionInstructors as $thisInstructor) {
                //$thisInstructorID = $currentInstructor->id;

                $thisInstructorYearTotal = $thisInstructor->totalclasses - 1;

                //update instructors table for the year and semester
                $currentInstructor = $this->Sections->Instructors->patchEntity($thisInstructor, ['totalclasses'=>$thisInstructorYearTotal]);

                //optionally update the semester totals
                if ($this->Sections->find()->contain(['Semester'])->where(['Sections.id' => $currentSectionID, 'Semester.semestercurrent' => '1'])->first()) {
                    $thisInstructorSemesterTotal = $thisInstructor->semesterclasses - 1;

                    //add this value to the current student's total credits and current semester credits
                    $currentInstructor = $this->Sections->Instructors->patchEntity($thisInstructor, ['semesterclasses' => $thisInstructorSemesterTotal]);

                }

                //save to the instructors table
                if (!($this->Sections->Instructors->save($currentInstructor))) {

                    $this->Flash->error(__('Unable to update instructor course totals information.'));

                }

            }



            //-------------------------update students removed from sections associated with class ------------------//
            //get all student ids from sections before they are deleted from the join table


            $currentSectionStudents = $this->Sections->SectionsStudents->find()
                ->where(['SectionsStudents.sectionid' => $currentSectionID])
                ->all();


            //sequentially change the gpa and credit values for each student
            foreach ($currentSectionStudents as $currentStudent) {
                $currentStudentID = $currentStudent->studentid;

                $modeFlag = 0;

                $this->Sections->SectionsStudents->computeStudentCredits($currentStudentID, $currentSectionID, $modeFlag);


                //A grade was changed - update the student's gpa values for semester and year
                $this->Sections->SectionsStudents->computeStudentGpas($currentStudentID, $currentSectionID, $modeFlag);

            }
        }
    }

    public function afterDeleteCommit( $event,  $classes,  $options)
    {
         //Delete all section records withe this course and all section_students records with any sections of the course
         //get the id of the course that is being deleted
        $deletedCourseID = $classes->id;

        //find all section records with that course id
        $deletedCourseRelatedSections = $this->Sections->find()->where(['classid'=>$deletedCourseID])->all();


        //delete all section_students with these course ids
        //itertate through the list of sections for the given course
        foreach($deletedCourseRelatedSections as $relatedSection)
        {
            //get the id of each individual section for the course to be deleted
            $relatedSectionID = $relatedSection->id;

            //find and delete section students - note need to acces sections_students through students
            $sectionsStudentRelatedRecord = $this->Sections->SectionsStudents->find()->where(['sectionid'=> $relatedSectionID])->all();

            //delete all records for sections_students with the related section id
            foreach($sectionsStudentRelatedRecord as $relatedSectionStudent)
            {
                //delete section_student records with matching section ids
               if( !($this->Sections->SectionsStudents->delete($relatedSectionStudent))){

                        $this->Flash->error(__('The sections student could not be deleted. Please, try again.'));


                }
            }

            //delete the section after deleting all section_student records for the section
            if( !($this->Sections->delete($relatedSection))){

                $this->Flash->error(__('The sections student could not be deleted. Please, try again.'));


            }
        }


    }

}


