<?php

namespace App\Controller;

use App\Controller\AppController;




class ClassesController extends AppController
{
    public function initialize()  //boilerplate code to initialize the class
    {
        parent::initialize();   //call AppController initialize first

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // Include the FlashComponent

        $this->loadModel('Classes');
        $this->loadModel('Sections');
        $this->loadModel('Instructors');
        $this->loadModel('Students');

    }

    public $paginate = [
        'limit' => 15,
        'order' => [
            'Classes.coursename' => 'asc'
        ]
    ];

    public function index()
    {

        $classes = $this->Paginator->paginate($this->Classes->find(),$this->paginate);

        $this->set(compact('classes'));
    }

    public function view($coursenumber)
    {
        //use a dynamically built finder method to find class by number
        $class = $this->Classes->find('classesByCoursenumber', ['coursenumber' => $coursenumber])
            ->contain(['Sections' => ['Instructors','Semester']])
            ->first();


        $this->set(compact('class'));

    }

    public function add()
    {
        $class = $this->Classes->newEntity();


        if ($this->request->is('post')) //verify that this is a post and not something else
        {
            $class = $this->Classes->patchEntity($class, $this->request->getData());



            if ($this->Classes->save($class)) {
                $this->Flash->success(__('The class information has been added.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add class information.'));
        }
        $this->set('class', $class);  //??

    }

    public function edit($coursenumber)
    {
        $class = $this->Classes->find('classesByCoursenumber', ['coursenumber' => $coursenumber])->firstOrFail();
        if ($this->request->is(['post', 'put'])) {
            $this->Classes->patchEntity($class, $this->request->getData());
            if ($this->Classes->save($class)) {
                $this->Flash->success(__('Class information has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update class information.'));
        }

        $this->set('class', $class);
    }

    public function delete($coursenumber)
    {
        $this->request->allowMethod(['post', 'delete']);

        $class = $this->Classes->find('classesByCoursenumber', ['coursenumber' => $coursenumber])->firstOrFail();

        
        if ($this->Classes->delete($class)) {
            $this->Flash->success(__('The {0} class has been deleted.', $class->coursenumber));
            return $this->redirect(['action' => 'index']);
        }
    }
}
