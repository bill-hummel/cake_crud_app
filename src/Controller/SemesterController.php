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

        $semester = $this->Semester->get($currentSemester->id, ['contain' => [] ]);


        if ($this->request->is(['patch', 'post', 'put'])) {

            $semesters = $this->Semester->find()->all();

            //get the current semester change value from $request:
            $semesterChangeValue = (int)$this->request->getData('semestercurrent');

            if($semesterChangeValue === $semester->semestercurrent){
                return $this->redirect(['action' => 'index']);

            }
            else {
                foreach($semesters as $semester)
                {
                    if($semester->semestercurrent == 0)
                         $semester = $this->Semester->patchEntity($semester, ['semestercurrent'=>'1']);
                    else
                         $semester = $this->Semester->patchEntity($semester, ['semestercurrent'=>'0']);

                    $this->Semester->save($semester);


                }

                $this->Flash->success(__('The semester has been saved.'));

                return $this->redirect(['action' => 'index']);

            }
       }
        $this->set(compact('semester'));
    }

}
