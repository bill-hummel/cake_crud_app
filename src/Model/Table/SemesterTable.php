<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Semester Model
 *
 * @method \App\Model\Entity\Semester get($primaryKey, $options = [])
 * @method \App\Model\Entity\Semester newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Semester[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Semester|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Semester|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Semester patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Semester[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Semester findOrCreate($search, callable $callback = null, $options = [])
 */
class SemesterTable extends Table
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

        $this->setTable('semester');
        $this->setDisplayField('semestername');
        $this->setPrimaryKey('id');

        $this->hasMany('Sections', [
            'foreignKey' => 'instructorid'
        ]);

        $this->hasMany('Sections', [
            'foreignKey' => 'semesterid'
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
            ->scalar('semestername')
            ->maxLength('semestername', 20)
            ->requirePresence('semestername', 'create')
            ->notEmpty('semestername');

        $validator
            ->scalar('semesterabr')
            ->maxLength('semesterabr', 1)
            ->requirePresence('semesterabr', 'create')
            ->notEmpty('semesterabr');

        $validator
            ->integer('semestercurrent')
            ->requirePresence('semestercurrent', 'create')
            ->notEmpty('semestercurrent');

        return $validator;
    }

    public function afterSaveCommit()
    {


        //update instructor semester course totals
        $allInstructors = $this->Sections->Instructors->find()->all();

        //iterate over all instructors
        foreach($allInstructors as $instructor)
        {
            //update all instructors' semester course totals
            $this->ComputeInstructorSemesterCourseTotals($instructor->id);
        }


        //update student GPA and credit values for the semester
        $allStudents = $this->Sections->Students->find()->all();

        //iterate over all students and update gpa values
        foreach($allStudents as $student) {

            //update each student GPA for the selected semester
            $this->computeStudentGpas($student->id);

           //update each student's credits for the semester
            $this->ComputeStudentSemesterCredits($student->id);

        }


    }


    //-------------------- Support Functions for student semester gpa and credits and instructor course count

    public function computeStudentGpas($studentid = null)
    {
        //todo -> upgrade to use weighted averages
        //Update gpa values for year and semester


        //count only courses for this semester
        $currentStudentSemesterClasses = $this->Sections->SectionsStudents->find()
            ->contain(['Sections' => ['Semester']])
            ->where(['SectionsStudents.studentid' => $studentid, 'Semester.semestercurrent' => '1'])
            ->all();

        //iterate over the sections and compute the gpa

        $rowCountSemester = 0;
        $sumOfGradesSemester = 0;
        $semesterGpa = 0;


        //semester grades sum and count
        foreach ($currentStudentSemesterClasses as $currentSemClass) {
            //count the values and sum for all courses
            $rowCountSemester++;
            $sumOfGradesSemester += $currentSemClass->numericgrade;
        }

        //compute gpa for current semester
        if ($rowCountSemester != 0) {
            $semesterGpa = $sumOfGradesSemester / $rowCountSemester;
        }


        //write gpa changes to the students table for the student

        //get the current student
        $thisStudent = $this->Sections->Students->find()->where(['id' => $studentid])->first();

        $currentStudent = $this->Sections->Students->patchEntity($thisStudent, ['semestergpa' => $semesterGpa]);

        //dump($currentStudent);

        if (!($this->Sections->Students->save($currentStudent))) {

            $this->Flash->error(__('Unable to update student GPA information.'));

        }

    }


    public function ComputeStudentSemesterCredits($studentid = null)
    {
        //get all sections for this student for the current semester
        $currentSemesterSections = $this->Sections->SectionsStudents->find()
                                   ->contain(['Sections'=>['Classes','Semester']])
                                   ->where(['studentid' => $studentid, 'semestercurrent'=>1])->all();


        //get the current student
        $thisStudent = $this->Sections->Students->find()->where(['id' => $studentid])->first();

        $studentSemesterCreditSum = 0;  //if there are no courses then the sum is set equal to zero.

        //iterate over each section and sum the credit values
        foreach($currentSemesterSections as $currentSection)
        {
            //find the credit value for this section and add it to the current sum
            $studentSemesterCreditSum += $currentSection->section->class->credits;
        }

        //add this value to the current student's total credits and current semester credits
        $currentStudent = $this->Sections->Students->patchEntity($thisStudent, ['semestercredits' => $studentSemesterCreditSum]);

        //write out data to the students table
            if (!($this->Sections->Students->save($currentStudent))) {

                $this->Flash->error(__('Unable to update instructor information.'));

            }


    }

    public function ComputeInstructorSemesterCourseTotals($instructorid=null)
    {
        //get the current instructor
        $thisInstructor = $this->Sections->Instructors->find()->where(['id' => $instructorid])->first();

        //
        $instructorCourseSum = 0;  //set to zero if the instructor has no courses this semseter

        //get all sections associated with this instructor for the current semester
        $currentSemesterSections = $this->Sections->find()
                                ->contain(['Semester'])
                                ->where(['instructorid' => $instructorid, 'semestercurrent'=>1])
                                ->all();

        //iterate over each section and sum the credit values
        foreach($currentSemesterSections as $currentSection)
        {
            //find the number of sections for this instructor
            $instructorCourseSum++;
        }

        //add this value to the current student's total credits and current semester credits
        $currentInstructor = $this->Sections->Instructors->patchEntity($thisInstructor, ['semesterclasses' => $instructorCourseSum]);

        //write out data to the students table
        if (!($this->Sections->Instructors->save($currentInstructor))) {

            $this->Flash->error(__('Unable to update instructor information.'));

        }



    }
}
