<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use cake\error\Debugger;

class StudentsTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('students');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        //students are assigned to many sections (sections_students)

        $this->belongsToMany('Sections', [
        'targetForeignKey' => 'sectionid',
        'foreignKey'=>'studentid',
        'joinTable' => 'sections_students'
        ]);
    }

    public function findStudentsByStudentnumber(Query $query, array $options)
    {
        $student = $options['studentnumber'];

        return $query->where(['studentnumber' => $student]);
    }


    //validation
    public function validationDefault(Validator $validator)
    {

        $validator
            ->requirePresence('firstname','create')
            ->notEmpty('firstname')
            ->requirePresence('lastname','create')
            ->notEmpty('lastname');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['studentnumber']));
        return $rules;
    }




}


