<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use cake\error\Debugger;

class InstructorsTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('instructors');
        $this->setDisplayField('full_name');
        $this->setPrimaryKey('id');

        //classes belong to many sections (belongsToMany)
        $this->hasMany('Sections', [
            'foreignKey' => 'instructorid'
        ]);


    }

    //validation
    public function validationDefault(Validator $validator)
    {

        $validator
            ->requirePresence('firstname','create')
            ->notEmpty('firstname')

            ->requirePresence('lastname','create')
            ->notEmpty('lastname')
            ->requirePresence('department','create')
            ->notEmpty('department');

        return $validator;
    }

    public function afterDeleteCommit()
    {
        //todo - fix this : generates a not included error in Cake
        //update student information
        //students
        $this->SectionsStudents->updateSingleStudentYearGPA();
        $this->SectionsStudents->updateSingleStudentSemesterGPA();
        $this->SectionsStudents->upDateStudentSemesterCredits();
        $this->SectionsStudents->upDateStudentYearCredits();


    }




    public function InstructorTotalClassCount($id=null)
    {
        if($id) {
            $totalClassCount = $this->Sections->find('all',
                ['conditons' => ['$this->id' => $id]])
                ->select([
                    'id' => 'instructors.id',
                    'total' => 'COUNT(*)'

                ])
                ->innerjoin('instructors', 'instructors.id = Sections.instructorid')
                ->group('instructors.id');

        }
        else{
            //update all
            $totalClassCount = $this->Sections->find()
                ->select([
                    'id' => 'instructors.id',
                    'total' => 'COUNT(*)'

                ])
                ->innerjoin('instructors', 'instructors.id = Sections.instructorid')
                ->group('instructors.id');


        }

        foreach($totalClassCount as $classcount)
        {

            $query =  $this->query();
            $query->update()->set(['totalclasses' => $classcount->total])
                ->where(['id'=>$classcount->id])
                ->execute();

        }



    }


    public function InstructorSemesterClassCount($id=null)
    {
        if($id) {
            $semesterClassCount = $this->Sections->find('all',
                ['conditons' => ['$this->id' => $id]])//todo - fix this
            ->select([
                'id' => 'instructors.id',
                'total' => 'COUNT(*)'

            ])
                ->innerjoin('instructors', 'instructors.id = Sections.instructorid')
                ->innerjoin('semester', 'Sections.semesterid = semester.semestercurrent')
                ->where(['semester.semestercurrent' => '1'])
                ->group('instructors.id');

        }
        else{
            //update all
            $semesterClassCount = $this->Sections->find()
            ->select([
                'id' => 'instructors.id',
                'total' => 'COUNT(*)'

            ])
                ->innerjoin('instructors', 'instructors.id = Sections.instructorid')
                ->innerjoin('semester', 'Sections.semesterid = semester.semestercurrent')
                ->where(['semester.semestercurrent' => '1'])
                ->group('instructors.id');



        }
        foreach($semesterClassCount as $semclasscount)
        {

            $query =  $this->query();
            $query->update()->set(['semesterclasses' => $semclasscount->total])
                ->where(['id'=>$semclasscount->id])
                ->execute();

        }



    }
}

