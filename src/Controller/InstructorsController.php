<?php

namespace App\Controller;

use App\Controller\AppController;




class InstructorsController extends AppController
{
    public function initialize()  //boilerplate code to initialize the class
    {
        parent::initialize();   //call AppController initialize first

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // Include the FlashComponent

        $this->loadModel('Instructors');
        $this->loadModel('Sections');
        $this->loadModel('SectionsStudents');


    }

    public $paginate = [
        'limit' => 15,
        'order' => [
            'Instructors.lastname' => 'asc'
        ]
    ];

    /**
     *
     */
    public function index()
    {
        //find list of instructors
        $instructors = $this->Paginator->paginate($this->Instructors->find(),$this->paginate);

        $this->set(compact('instructors'));
    }

    public function view($id)
    {
        //use a finder method to find instructor by number with section, class and semester info
        $instructor = $this->Instructors->get($id, [
            'contain' => ['Sections'=>['Classes','Semester']]
        ]);


        $this->set(compact('instructor'));

    }

    public function add()
    {
        $instructor = $this->Instructors->newEntity();


        if ($this->request->is('post')) //verify that this is a post and not something else
        {
            $instructor = $this->Instructors->patchEntity($instructor, $this->request->getData());



            if ($this->Instructors->save($instructor)) {
                $this->Flash->success(__('The instructor information has been added.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add instructor information.'));
        }
        $this->set('instructor', $instructor);  //??

    }

    public function edit($id)
    {
        $instructor = $this->Instructors->get($id);
        if ($this->request->is(['post', 'put'])) {
            $this->Instructors->patchEntity($instructor, $this->request->getData());
            if ($this->Instructors->save($instructor)) {
                $this->Flash->success(__('Instructor information has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update instructor information.'));
        }

        $this->set('instructor', $instructor);
    }

    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $instructor = $this->Instructors->get($id);

        
        if ($this->Instructors->delete($instructor)) {

            //make the instructor value null in the section table

            //get all sections with this instructors id
            $instructorSections= $this->Sections->find()->where(['instructorid'=>$id]);

            //update each section instructor id to a null value
            foreach($instructorSections as $instructorSection)
            {
                $sectionInstructorNull =$this->Sections->patchEntity( $instructorSection, ['instructorid'=>null]);


                if (!($this->Sections->save($sectionInstructorNull))) {

                    $this->Flash->error(__('Unable to update instructor information.'));

                }

            }


            $this->Flash->success(__('The {0} instructor has been deleted.', $instructor->id));
            return $this->redirect(['action' => 'index']);
        }
    }
}
