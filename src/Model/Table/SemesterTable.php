<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Semester Model
 *
 * @method \App\Model\Entity\Semester get($primaryKey, $options = [])
 * @method \App\Model\Entity\Semester newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Semester[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Semester|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Semester|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Semester patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Semester[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Semester findOrCreate($search, callable $callback = null, $options = [])
 */
class SemesterTable extends Table
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

        $this->setTable('semester');
        $this->setDisplayField('semestername');
        $this->setPrimaryKey('id');

        $this->hasMany('Sections', [
            'foreignKey' => 'instructorid'
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
            ->scalar('semestername')
            ->maxLength('semestername', 20)
            ->requirePresence('semestername', 'create')
            ->notEmpty('semestername');

        $validator
            ->scalar('semesterabr')
            ->maxLength('semesterabr', 1)
            ->requirePresence('semesterabr', 'create')
            ->notEmpty('semesterabr');

        $validator
            ->integer('semestercurrent')
            ->requirePresence('semestercurrent', 'create')
            ->notEmpty('semestercurrent');

        return $validator;
    }
}
