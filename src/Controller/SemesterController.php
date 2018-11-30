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
        $semester = $this->Semester->find()->where(['semestercurrent' => 1])->first();

        $this->set(compact('semester'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Semester id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {   //todo - fix to toggle semester
        $currentSemester = $this->Semester->find()->where(['semestercurrent' => '1'])->first();

        $semester = $this->Semester->get($currentSemester->id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $semester = $this->Semester->patchEntity($semester, $this->request->getData());

            //change current semester value to indicate inactive (0)

//            if ($this->Semester->save($semester)) {
//                $this->Flash->success(__('The semester has been saved.'));
//
//                return $this->redirect(['action' => 'index']);
//            }
//            $this->Flash->error(__('The semester could not be saved. Please, try again.'));
        }
        $this->set(compact('semester'));
    }

}
