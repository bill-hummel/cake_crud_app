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

        //dump($deletedCourseRelatedSections);






        //update all instructor and student records to reflect deleted sections and deleted section_student records




        //update counts for instructors and counts and gpa for students
        //instructors
//        $this->Sections->Instructors->InstructorTotalClassCount();
//        $this->Sections->Instructors->InstructorSemesterClassCount();
//
//        //students
//        $this->Sections->SectionsStudents->updateSingleStudentYearGPA();
//        $this->Sections->SectionsStudents->updateSingleStudentSemesterGPA();
//        $this->Sections->SectionsStudents->upDateStudentSemesterCredits();
//        $this->Sections->SectionsStudents->upDateStudentYearCredits();
    }

}


