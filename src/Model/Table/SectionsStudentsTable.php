<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SectionsStudents Model
 *
 * @method \App\Model\Entity\SectionsStudent get($primaryKey, $options = [])
 * @method \App\Model\Entity\SectionsStudent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SectionsStudent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SectionsStudent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SectionsStudent|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SectionsStudent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SectionsStudent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SectionsStudent findOrCreate($search, callable $callback = null, $options = [])
 */
class SectionsStudentsTable extends Table
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

        $this->setTable('sections_students');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');


        //students belong to many individual classes (sections_students) (belongsToMany)
        $this->belongsTo('Students', [
            'foreignKey' => 'studentid'  //todo - do I need a targetForeignKey??
        ]);

        //Sections belong to many individual classes (sections_students) (belongsToMany)
        $this->belongsTo('Sections', [
            'foreignKey' => 'sectionid'   //todo - do I need a targetForeignKey??
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
            ->integer('sectionid')
            ->requirePresence('sectionid', 'create')
            ->notEmpty('sectionid');

        $validator
            ->integer('studentid')
            ->requirePresence('studentid', 'create')
            ->notEmpty('studentid');

        $validator
            ->scalar('lettergrade')
            ->maxLength('lettergrade', 2);


        $validator
            ->numeric('numericgrade');


        return $validator;
    }


    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['studentid', 'sectionid']));
        return $rules;
    }

    /**
     * @param $event
     * @param $sectionsstudent
     * @param $options
     */


    public function afterSaveCommit($event, $sectionsstudent, $options)
    {
        //update the credit and gpa information for this student
        //get the data commited to the sections_students table
        $studentid = $sectionsstudent->studentid;
        $sectionid = $sectionsstudent->sectionid;
        $letterGradeFlag = $sectionsstudent->lettergrade; //was a letter grade or a course add submitted?
        $modeFlag = 1;   //modeflag = 1 ---> ad a course,   modeFlag = 0 ---> delete a course


        //Check that this afterSaveCommit is not being called on a grade change, but on a course add for the student!
        if (!$letterGradeFlag) {
            //do the year and semester credit updates
            $this->computeStudentCredits ($studentid, $sectionid,$modeFlag);


        } else {
            //A grade was changed - update the student's gpa values for semeter and year
            $this->computeStudentGpas($studentid , $sectionid );

        }



        //$this->updateSingleStudentYearGPA($id);
        //$this->updateSingleStudentSemesterGPA($id);


    }

    public function afterDeleteCommit($event, $sectionsstudent, $options)
    {
        //update student credits on deltetion from a sectionm

        //get the data commited to the sections_students table
        $studentid = $sectionsstudent->studentid;
        $sectionid = $sectionsstudent->sectionid;
        $modeFlag = 2;   //modeflag = 1 ---> ad a course,   modeFlag = 0 ---> delete a course

        //update the
        $this->computeStudentCredits ($studentid, $sectionid, $modeFlag);


        //A grade was changed - update the student's gpa values for semeter and year
        $this->computeStudentGpas($studentid , $sectionid );



    }

    //----------------------- support methods for updating student credits and gpa values ----------------------//

    //compute student gpa for semester and year and write out
    public function computeStudentGpas($studentid = null, $sectionid = null)
    {
        //todo -> upgrade to use weighted averages
        //Update gpa values for year and semester

        //get all courses and semester courses for this student
        $currentStudentYearClasses = $this->find()
            ->contain(['Sections' => ['Semester']])
            ->where(['SectionsStudents.studentid' => $studentid])->all();

        //count only courses for this semester
        $currentStudentSemesterClasses = $this->find()
            ->contain(['Sections' => ['Semester']])
            ->where(['SectionsStudents.studentid' => $studentid, 'Semester.semestercurrent' => '1'])
            ->all();


        //iterate over the sections and compute the gpa
        $rowCountYear = 0;
        $rowCountSemester = 0;
        $sumOfGradesYear = 0;
        $sumOfGradesSemester = 0;
        $yearGpa = 0;
        $semesterGpa=0;

        //year grades sum and count
        foreach ($currentStudentYearClasses as $currentClass) {
            //count the values and sum for all courses
            $rowCountYear++;
            $sumOfGradesYear += $currentClass->numericgrade;
        }

        //compute gpa for current year
        if($rowCountYear != 0){
            $yearGpa = $sumOfGradesYear / $rowCountYear;
        }

        //semester grades sum and count
        foreach ($currentStudentSemesterClasses as $currentSemClass) {
            //count the values and sum for all courses
            $rowCountSemester++;
            $sumOfGradesSemester += $currentSemClass->numericgrade;
        }

        //compute gpa for current semester
        if($rowCountSemester != 0){
            $semesterGpa = $sumOfGradesSemester / $rowCountSemester;
        }






        //write gpa changes to the students table for the student

        //get the current student
        $thisStudent = $this->Students->find()->where(['id' => $studentid])->firstOrFail();

        $currentStudent = $this->Students->patchEntity($thisStudent, ['semestergpa' => $semesterGpa, 'gpa'=>$yearGpa]);

        if (!($this->Students->save($currentStudent))) {

            $this->Flash->error(__('Unable to update student GPA information.'));

        }

    }

    //update student year and semester credits
    public function computeStudentCredits ($studentid = null, $sectionid = null, $modeFlag=1)
    {
        //get the value of a credit for this course
        $thisSection = $this->sections->find()->where(['id' => $sectionid])->firstOrFail();
        $thisClass = $this->sections->classes->find()->where(['id' => $thisSection->classid])->firstOrFail();
        $thisClassCreditValue = $thisClass->credits;

        //get the current student
        $thisStudent = $this->Students->find()->where(['id' => $studentid])->firstOrFail();


        if($modeFlag == 1) {
            //adding student credits ($modeFlag = 1)

            //update credit sums
            $creditYearTotal = $thisStudent->totalcredits + $thisClassCreditValue;

            //add this value to the current student's total credits and current semester credits
            $currentStudent = $this->Students->patchEntity($thisStudent, ['totalcredits' => $creditYearTotal]);

            //optionally update the semester totals
            if ($this->sections->find()->contain(['Semester'])->where(['Sections.id' => $sectionid, 'Semester.semestercurrent' => '1'])->first()) {
                $creditSemesterTotal = $thisStudent->semestercredits + $thisClassCreditValue;

                //add this value to the current student's total credits and current semester credits
                $currentStudent = $this->Students->patchEntity($thisStudent, ['semestercredits' => $creditSemesterTotal]);

            }


            //write out data to the students table
            if (!($this->Students->save($currentStudent))) {

                $this->Flash->error(__('Unable to update instructor information.'));

            }

        }

        else{
            //deleting credits - (modeFlag = 2)

            //update credit sums be subtracting the deleted courses's credit value
            $creditYearTotal = $thisStudent->totalcredits - $thisClassCreditValue;

            //subtract this value from the current student's total credits and current semester credits
            $currentStudent = $this->Students->patchEntity($thisStudent, ['totalcredits' => $creditYearTotal]);

            //optionally update the semester total by deleting value of removed course's credits
            if ($this->sections->find()->contain(['Semester'])->where(['Sections.id' => $sectionid, 'Semester.semestercurrent' => '1'])->first()) {
                $creditSemesterTotal = $thisStudent->semestercredits - $thisClassCreditValue;

                //add this value to the current student's total credits and current semester credits
                $currentStudent = $this->Students->patchEntity($thisStudent, ['semestercredits' => $creditSemesterTotal]);
            }

            //write out data to the students table
            if (!($this->Students->save($currentStudent))) {

                $this->Flash->error(__('Unable to update instructor information.'));

            }



        }

    }


   //--------------------old functions to eliminate-----------------------
//
//


    public function upDateStudentSemesterCredits($id=null)
    {
        //do a global update on semester change
        if($id) {
            $SemesterStudentCredits = $this->find('all',
                ['conditons' => ['$this->Students.id' => $id]])
                ->select([
                    'id' => 'students.id',
                    'totalcredits' => 'SUM(classes.credits)'

                ])
                ->innerjoin('students', 'students.id = SectionsStudents.studentid')
                ->innerjoin('sections', 'SectionsStudents.sectionid=sections.id')
                ->innerjoin('classes', 'classes.id=sections.classid')
                ->innerjoin('semester', 'sections.semesterid = semester.semestercurrent')
                ->where(['semester.semestercurrent' => '1'])
                ->group('students.studentnumber');
        }
        else {
            //update all for course delete
            $SemesterStudentCredits = $this->find()
                ->select([
                    'id' => 'students.id',
                    'totalcredits' => 'SUM(classes.credits)'

                ])
                ->innerjoin('students', 'students.id = SectionsStudents.studentid')
                ->innerjoin('sections', 'SectionsStudents.sectionid=sections.id')
                ->innerjoin('classes', 'classes.id=sections.classid')
                ->innerjoin('semester', 'sections.semesterid = semester.semestercurrent')
                ->where(['semester.semestercurrent' => '1'])
                ->group('students.studentnumber');
        }

        foreach($SemesterStudentCredits as $studentSmCredits)
        {
            $query =  $this->Students->query();
           $query->update()->set(['semestercredits' =>$studentSmCredits->totalcredits])
                                     ->where(['id'=>$studentSmCredits->id])
                                     ->execute();

        }

    }

    //TODO - refactor and eliminate these 6 methods using single row updates in lifecycle callbacks
    //
    //

    public function upDateStudentYearCredits($id=null)
    {
        //do a global update on semester change
        if($id) {
            $allStudentCredits = $this->find('all',
                ['conditons' => ['$this->Students.id' => $id]])
                ->select([
                    'id' => 'students.id',
                    'totalcredits' => 'SUM(classes.credits)'

                ])
                ->innerjoin('students', 'students.id = SectionsStudents.studentid')
                ->innerjoin('sections', 'SectionsStudents.sectionid=sections.id')
                ->innerjoin('classes', 'classes.id=sections.classid')
                ->group('students.studentnumber');

        }
        else {
            $allStudentCredits = $this->find()
                ->select([
                    'id' => 'students.id',
                    'totalcredits' => 'SUM(classes.credits)'

                ])
                ->innerjoin('students', 'students.id = SectionsStudents.studentid')
                ->innerjoin('sections', 'SectionsStudents.sectionid=sections.id')
                ->innerjoin('classes', 'classes.id=sections.classid')
                ->group('students.studentnumber');


        }
        foreach($allStudentCredits as $studentCredits)
        {
            $query =  $this->Students->query();
            $query->update()->set(['totalcredits' =>$studentCredits->totalcredits])
                ->where(['id'=>$studentCredits->id])
                ->execute();

        }

    }



    public function updateSingleStudentYearGPA($id=null)
    {
        if($id) {

            $studentsYearGpa = $this->Students->find('all',
                ['conditons' => ['$this->Students.id' => $id]])
                ->select([
                    'id' => 'Students.id',
                    'yeargpa' => 'AVG(sections_students.numericgrade)'

                ])
                ->innerjoin('sections_students', 'Students.id = sections_students.studentid')
                ->where(['sections_students.lettergrade IS NOT' => 'I'])
                ->group('Students.id');
        }
        else{
            $studentsYearGpa = $this->Students->find()
                ->select([
                    'id' => 'Students.id',
                    'yeargpa' => 'AVG(sections_students.numericgrade)'

                ])
                ->innerjoin('sections_students', 'Students.id = sections_students.studentid')
                ->where(['sections_students.lettergrade IS NOT' => 'I'])
                ->group('Students.id');
        }

        foreach($studentsYearGpa as $studentYrGpa)
        {
            $query =  $this->Students->query();
            $query->update()->set(['gpa' => $studentYrGpa->yeargpa])
                ->where(['id'=>$studentYrGpa->id])
                ->execute();

        }

    }
    public function updateSingleStudentSemesterGPA($id=null)
    {
        if($id) {
            $studentsSemesterGpa = $this->Students->find('all',
                ['conditons' => ['$this->Students.id' => $id]])
                ->select([
                    'id' => 'Students.id',
                    'semestergpa' => 'AVG(sections_students.numericgrade)'

                ])
                ->innerjoin('sections_students', 'Students.id = sections_students.studentid')
                ->innerjoin('sections', 'sections.id=sections_students.sectionid')
                ->innerjoin('semester', 'sections.semesterid = semester.semestercurrent')
                ->where(['sections_students.lettergrade IS NOT' => 'I'])
                ->where(['semester.semestercurrent' => '1'])
                ->group('Students.id');
        }
        else {
            $studentsSemesterGpa = $this->Students->find()
                ->select([
                    'id' => 'Students.id',
                    'semestergpa' => 'AVG(sections_students.numericgrade)'

                ])
                ->innerjoin('sections_students', 'Students.id = sections_students.studentid')
                ->innerjoin('sections', 'sections.id=sections_students.sectionid')
                ->innerjoin('semester', 'sections.semesterid = semester.semestercurrent')
                ->where(['sections_students.lettergrade IS NOT' => 'I'])
                ->where(['semester.semestercurrent' => '1'])
                ->group('Students.id');

        }

        foreach($studentsSemesterGpa as $studentSmGpa)
        {
            $query =  $this->Students->query();
            $query->update()->set(['semestergpa' => $studentSmGpa->semestergpa])
                ->where(['id'=>$studentSmGpa->id])
                ->execute();

        }
    }




    public function convert_grade($letter_grade)  //I wondered if I would ever have a use for this again
    {                                     // 1998 to 2018 -- 20 years of grade conversion fun!!!!
        switch($letter_grade)
        {
            case "A+":
                return 4.33;
                break;
            case "A":
                return 4.00;
                break;
            case "A-":
                return 3.67;
                break;
            case "B+":
                return 3.33;
                break;
            case "B":
                return 3.00;
                break;
            case "B-":
                return 2.67;
                break;
            case "C+":
                return 2.33;
                break;
            case "C":
                return 2.00;
                break;
            case "C-":
                return 1.67;
                break;
            case "D+":
                return 1.33;
                break;
            case "D":
                return 1.00;
                break;
            case "D-":
                return 0.67;
                break;
            case "F":
                return 0.0;
            case "I":
                return 0.0;
        }
    }

}
/*
 * "UPDATE students SET gpa = $row[1] WHERE id = $row[0]";
 * $query = "SELECT students.id as id,
                  AVG(classschedule.numericgrade) as gpa FROM students JOIN classschedule
                  ON students.id = classschedule.studentid where classschedule.lettergrade != 'I'
                  GROUP BY id  ORDER BY students.lastname ASC";
 */
