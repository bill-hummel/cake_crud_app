<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM;

/**
 * SectionsStudents Controller
 *
 * @property \App\Model\Table\SectionsStudentsTable $SectionsStudents
 * @property \App\Model\Table\SectionsTable $Sections
 *  @property \App\Model\Table\StudentsTable $Students
 *  @property \App\Model\Table\ClassesTable $Classes
 *  @property \App\Model\Table\SemesterTable $Semester
 * @method \App\Model\Entity\SectionsStudent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SectionsStudentsController extends AppController
{

    public function initialize()  //boilerplate code to initialize the class
    {
        parent::initialize();   //call AppController initialize first

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // Include the FlashComponent

       $this->loadModel('Sections');
       $this->loadModel('Students');
       $this->loadModel('Classes');
       $this->loadModel('Semester');
        //$this->loadModel('Classes');

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $sectionsStudents = $this->Paginator->paginate($this->SectionsStudents->find('all',
            ['contain' => ['Students','Sections'=>['Classes','Semester']]
        ]),$this->paginate);
        //$sectionsStudents = $this->paginate($this->SectionsStudents);

        $this->set(compact('sectionsStudents'));


    }

    /**
     * View method
     *
     * @param string|null $id Sections Student id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sectionsStudent = $this->SectionsStudents->get($id, [
            'contain' => ['Students','Sections'=>['Classes']]
        ]);

        $this->set('sectionsStudent', $sectionsStudent);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sectionsStudent = $this->SectionsStudents->newEntity();
        if ($this->request->is('post')) {
            $sectionsStudent = $this->SectionsStudents->patchEntity($sectionsStudent, $this->request->getData());




            if ($this->SectionsStudents->save($sectionsStudent)) {

                //update student credits for semester and year
                $this->SectionsStudents->upDateStudentYearCredits($sectionsStudent->studentid);
                $this->SectionsStudents->upDateStudentSemesterCredits($sectionsStudent->studentid);

                $this->Flash->success(__('The sections student has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sections student could not be saved. Please, try again.'));
        }


        //get selections for dropdown lists
        $sections = $this->Sections->find('list', [
                    'keyField'=>'id',
                    'valueField'=> function($section) {

                        $sectionNumber = $section->sectionid;

                        $course = $this->Sections->Classes->get($section->classid);
                        $courseName = $course->coursename;

                        $semestercur = $this->Sections->Semester->get($section->semesterid);
                       $sem = $semestercur->semesterabr;

                        return "{$courseName} ($sectionNumber) ($sem)";

                    }
       ]);


        $students = $this->Students->find('list', [
            'keyField'=>'id',
            'valueField'=> function($students) {

                $studentName = $students->firstname.' '.$students->lastname;

                //$course = $this->Sections->Classes->get($section->classid);
                //$courseName = $course->coursename;

                return "{$studentName}";

            }
        ]);



        $this->set(compact('sectionsStudent', 'sections', 'students'));

    }

    /**
     * Edit method
     *
     * @param string|null $id Sections Student id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sectionsStudent = $this->SectionsStudents->get($id, [
            'contain' => ['Students','Sections'=>['Classes']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sectionsStudent = $this->SectionsStudents->patchEntity($sectionsStudent, $this->request->getData());

            //convert the letter gade to a numeric value before saving the data - use convert_grade in the Table class!:the
            $sectionsStudent->numericgrade = $this->SectionsStudents->convert_grade($sectionsStudent->lettergrade);

            if ($this->SectionsStudents->save($sectionsStudent)) {
                $this->Flash->success(__('The sections student has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sections student could not be saved. Please, try again.'));
        }
        $this->set(compact('sectionsStudent'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sections Student id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sectionsStudent = $this->SectionsStudents->get($id);
        if ($this->SectionsStudents->delete($sectionsStudent)) {

             //update student credits for semester and year
            $this->SectionsStudents->upDateStudentYearCredits($sectionsStudent->studentid);
            $this->SectionsStudents->upDateStudentSemesterCredits($sectionsStudent->studentid);

            $this->Flash->success(__('The sections student has been deleted.'));
        } else {
            $this->Flash->error(__('The sections student could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
