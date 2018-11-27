<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Semester Controller
 *
 * @property \App\Model\Table\SemesterTable $Semester
 *
 * @method \App\Model\Entity\Semester[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SemesterController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $semester = $this->paginate($this->Semester);

        $this->set(compact('semester'));
    }

//    /**
//     * View method
//     *
//     * @param string|null $id Semester id.
//     * @return \Cake\Http\Response|void
//     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
//     */
//    public function view($id = null)
//    {
//        $semester = $this->Semester->get($id, [
//            'contain' => []
//        ]);
//
//        $this->set('semester', $semester);
//    }

//    /**
//     * Add method
//     *
//     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
//     */
//    public function add()
//    {
//        $semester = $this->Semester->newEntity();
//        if ($this->request->is('post')) {
//            $semester = $this->Semester->patchEntity($semester, $this->request->getData());
//            if ($this->Semester->save($semester)) {
//                $this->Flash->success(__('The semester has been saved.'));
//
//                return $this->redirect(['action' => 'index']);
//            }
//            $this->Flash->error(__('The semester could not be saved. Please, try again.'));
//        }
//        $this->set(compact('semester'));
//    }

    /**
     * Edit method
     *
     * @param string|null $id Semester id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {   //todo - fix to toggle semester
        $currentSemester = $this->Semester->find()->where(['semestercurrent'=>'1'])->first();

        $semester = $this->Semester->get($currentSemester->id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $semester = $this->Semester->patchEntity($semester, $this->request->getData());

//            if ($this->Semester->save($semester)) {
//                $this->Flash->success(__('The semester has been saved.'));
//
//                return $this->redirect(['action' => 'index']);
//            }
//            $this->Flash->error(__('The semester could not be saved. Please, try again.'));
        }
        $this->set(compact('semester'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Semester id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
//    public function delete($id = null)
//    {
//        $this->request->allowMethod(['post', 'delete']);
//        $semester = $this->Semester->get($id);
//        if ($this->Semester->delete($semester)) {
//            $this->Flash->success(__('The semester has been deleted.'));
//        } else {
//            $this->Flash->error(__('The semester could not be deleted. Please, try again.'));
//        }
//
//        return $this->redirect(['action' => 'index']);
//    }
}
