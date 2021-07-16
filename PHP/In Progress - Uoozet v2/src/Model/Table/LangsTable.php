<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Langs Model
 *
 * @method \App\Model\Entity\Lang newEmptyEntity()
 * @method \App\Model\Entity\Lang newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Lang[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Lang get($primaryKey, $options = [])
 * @method \App\Model\Entity\Lang findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Lang patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Lang[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Lang|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Lang saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Lang[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Lang[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Lang[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Lang[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class LangsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('langs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('lang_key')
            ->maxLength('lang_key', 150)
            ->requirePresence('lang_key', 'create')
            ->notEmptyString('lang_key')
            ->add('lang_key', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('type')
            ->maxLength('type', 100)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('english')
            ->allowEmptyString('english');

        $validator
            ->scalar('arabic')
            ->allowEmptyString('arabic');

        $validator
            ->scalar('farsi')
            ->allowEmptyString('farsi');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['id']), ['errorField' => 'id']);
        $rules->add($rules->isUnique(['lang_key']), ['errorField' => 'lang_key']);

        return $rules;
    }
}
