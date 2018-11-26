<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Sections Controller
 *
 * @property \App\Model\Table\SectionsTable $Sections
 *
 * @method \App\Model\Entity\Section[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SectionsController extends AppController
{

    public function initialize()  //boilerplate code to initialize the class
    {
        parent::initialize();   //call AppController initialize first

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // Include the FlashComponent

        $this->loadModel('Sections');
        $this->loadModel('Instructors');
        //$this->loadModel('Classes');

    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        //$sections = $this->paginate($this->Sections);
        $sections = $this->Paginator->paginate($this->Sections->find('all', [
                    'contain' => ['Classes','Semester','Instructors']
                ]),$this->paginate);

        $this->set(compact('sections'));
    }

    /**
     * View method
     *
     * @param string|null $id Section id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        //access sections_students and pass list of all students for section with the given id
        $section = $this->Sections->get($id, [
            'contain' => ['Classes','Semester','Instructors','Students']
        ]);

        $this->set(compact('section', 'section','studentsInSection'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $section = $this->Sections->newEntity();
        if ($this->request->is('post')) {

            $section = $this->Sections->patchEntity($section, $this->request->getData());

            if ($this->Sections->save($section)) {

                $this->Flash->success(__('The section has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The section could not be saved. Please, try again.'));
        }

        //populate the lists of instructors and classes/sections
        $classes = $this->Sections->Classes->find('list', ['limit' => 200]);
        $instructors = $this->Sections->Instructors->find('list', ['limit' => 200]);
        $semester = $this->Sections->Semester->find('list', ['limit' => 2]);

        //compact and write the array information to the view
        $this->set(compact('section', 'classes','instructors','semester'));
    }



    /**
     * Edit method
     *
     * @param string|null $id Section id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $section = $this->Sections->get($id, [
            'contain' => ['Classes']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $section = $this->Sections->patchEntity($section, $this->request->getData());
            if ($this->Sections->save($section)) {
                $this->Flash->success(__('The section has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The section could not be saved. Please, try again.'));
        }
        $classes = $this->Sections->Classes->find('list', ['limit' => 200]);

        $instructors = $this->Sections->Instructors->find('list', ['limit' => 200]);

        $semester = $this->Sections->Semester->find('list', ['limit' => 2]);

        $this->set(compact('section', 'classes','instructors','semester'));



        $this->set(compact('section', 'classes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Section id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $section = $this->Sections->get($id);
        if ($this->Sections->delete($section)) {
            $this->Flash->success(__('The section has been deleted.'));
        } else {
            $this->Flash->error(__('The section could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
