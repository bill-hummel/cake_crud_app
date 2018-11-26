<?php

namespace App\Controller;

use App\Controller\AppController;




class StudentsController extends AppController
{
    public function initialize()  //boilerplate code to initialize the class
    {
        parent::initialize();   //call AppController initialize first

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // Include the FlashComponent

        $this->loadModel('Students');

    }

    public $paginate = [
        'limit' => 15,
        'order' => [
            'Students.lastname' => 'asc'
        ]
    ];

    public function index()
    {

        $students = $this->Paginator->paginate($this->Students->find(),$this->paginate);

        $this->set(compact('students'));
    }

    public function view($studentnumber)
    {
        //use a custom finder method to find student by number
        $student = $this->Students->find('studentsByStudentnumber',['studentnumber' => $studentnumber])
            ->contain(['Sections' => ['Classes', 'Instructors','Semester']])
            ->first();


        $this->set(compact('student'));

    }

    public function add()
    {
        $student = $this->Students->newEntity();


        if ($this->request->is('post')) //verify that this is a post and not something else
        {
            $student = $this->Students->patchEntity($student, $this->request->getData());


            if ($this->Students->save($student)) {
                $this->Flash->success(__('Your student record has been added.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add student record.'));
        }
        $this->set('student', $student);  //??

    }

    public function edit($studentnumber)
    {
        $student = $this->Students->find('studentsByStudentnumber', ['studentnumber' => $studentnumber])->firstOrFail();
        if ($this->request->is(['post', 'put'])) {
            $this->Students->patchEntity($student, $this->request->getData());
            if ($this->Students->save($student)) {
                $this->Flash->success(__('Student record has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update student record.'));
        }

        $this->set('student', $student);
    }

    public function delete($studentnumber)
    {
        $this->request->allowMethod(['post', 'delete']);

        $student = $this->Students->find('studentsByStudentnumber', ['studentnumber' => $studentnumber])->firstOrFail();


        if ($this->Students->delete($student)) {
            $this->Flash->success(__('The {0} student record has been deleted.', $student->studentnumber));
            return $this->redirect(['action' => 'index']);
        }
    }
}
