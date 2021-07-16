<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\DevicesTable&\Cake\ORM\Association\BelongsTo $Devices
 * @property \App\Model\Table\AnnouncementViewsTable&\Cake\ORM\Association\HasMany $AnnouncementViews
 * @property \App\Model\Table\CatAffiliationTable&\Cake\ORM\Association\HasMany $CatAffiliation
 * @property \App\Model\Table\CommentsTable&\Cake\ORM\Association\HasMany $Comments
 * @property \App\Model\Table\HistoryTable&\Cake\ORM\Association\HasMany $History
 * @property \App\Model\Table\LikesDislikesTable&\Cake\ORM\Association\HasMany $LikesDislikes
 * @property \App\Model\Table\ListsTable&\Cake\ORM\Association\HasMany $Lists
 * @property \App\Model\Table\MediaTable&\Cake\ORM\Association\HasMany $Media
 * @property \App\Model\Table\MediaTransactionsTable&\Cake\ORM\Association\HasMany $MediaTransactions
 * @property \App\Model\Table\PaymentsTable&\Cake\ORM\Association\HasMany $Payments
 * @property \App\Model\Table\RegisterCodeTable&\Cake\ORM\Association\HasMany $RegisterCode
 * @property \App\Model\Table\SessionsTable&\Cake\ORM\Association\HasMany $Sessions
 * @property \App\Model\Table\SubscriptionsTable&\Cake\ORM\Association\HasMany $Subscriptions
 * @property \App\Model\Table\UserAdsTable&\Cake\ORM\Association\HasMany $UserAds
 * @property \App\Model\Table\UsrProfFieldsTable&\Cake\ORM\Association\HasMany $UsrProfFields
 * @property \App\Model\Table\VerificationRequestsTable&\Cake\ORM\Association\HasMany $VerificationRequests
 * @property \App\Model\Table\VideoAdsTable&\Cake\ORM\Association\HasMany $VideoAds
 * @property \App\Model\Table\ViewsTable&\Cake\ORM\Association\HasMany $Views
 * @property \App\Model\Table\WithdrawalRequestsTable&\Cake\ORM\Association\HasMany $WithdrawalRequests
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Devices', [
            'foreignKey' => 'device_id',
        ]);
        $this->hasMany('AnnouncementViews', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('CatAffiliation', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('History', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('LikesDislikes', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Lists', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Media', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('MediaTransactions', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Payments', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('RegisterCode', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Sessions', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Subscriptions', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('UserAds', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('UsrProfFields', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('VerificationRequests', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('VideoAds', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Views', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('WithdrawalRequests', [
            'foreignKey' => 'user_id',
        ]);
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
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('username')
            ->maxLength('username', 45)
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $validator
            ->scalar('password')
            ->maxLength('password', 45)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('fname')
            ->maxLength('fname', 45)
            ->requirePresence('fname', 'create')
            ->notEmptyString('fname');

        $validator
            ->scalar('lname')
            ->maxLength('lname', 45)
            ->requirePresence('lname', 'create')
            ->notEmptyString('lname');

        $validator
            ->boolean('age')
            ->requirePresence('age', 'create')
            ->notEmptyString('age');

        $validator
            ->scalar('mobile')
            ->maxLength('mobile', 45)
            ->requirePresence('mobile', 'create')
            ->notEmptyString('mobile');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        $validator
            ->boolean('completeSignup')
            ->notEmptyString('completeSignup');

        $validator
            ->scalar('registerDate')
            ->maxLength('registerDate', 10)
            ->notEmptyString('registerDate');

        $validator
            ->scalar('registerCode')
            ->maxLength('registerCode', 10)
            ->allowEmptyString('registerCode');

        $validator
            ->scalar('registeredCode')
            ->maxLength('registeredCode', 10)
            ->allowEmptyString('registeredCode');

        $validator
            ->integer('registerPoints')
            ->notEmptyString('registerPoints');

        $validator
            ->requirePresence('access', 'create')
            ->notEmptyString('access');

        $validator
            ->boolean('gender')
            ->allowEmptyString('gender');

        $validator
            ->scalar('email_vcode')
            ->maxLength('email_vcode', 50)
            ->allowEmptyString('email_vcode');

        $validator
            ->scalar('lang')
            ->maxLength('lang', 8)
            ->allowEmptyString('lang');

        $validator
            ->scalar('thumbnail')
            ->maxLength('thumbnail', 100)
            ->allowEmptyString('thumbnail');

        $validator
            ->scalar('cover')
            ->maxLength('cover', 100)
            ->allowEmptyString('cover');

        $validator
            ->scalar('country')
            ->maxLength('country', 45)
            ->allowEmptyString('country');

        $validator
            ->scalar('about')
            ->allowEmptyString('about');

        $validator
            ->scalar('other_socials')
            ->maxLength('other_socials', 600)
            ->allowEmptyString('other_socials');

        $validator
            ->boolean('allow_notify')
            ->notEmptyString('allow_notify');

        $validator
            ->boolean('verified')
            ->notEmptyString('verified');

        $validator
            ->integer('last_active')
            ->allowEmptyString('last_active');

        $validator
            ->integer('active_time')
            ->allowEmptyString('active_time');

        $validator
            ->integer('active_expire')
            ->allowEmptyString('active_expire');

        $validator
            ->boolean('pro')
            ->allowEmptyString('pro');

        $validator
            ->integer('imports')
            ->allowEmptyString('imports');

        $validator
            ->integer('uploads')
            ->allowEmptyString('uploads');

        $validator
            ->scalar('wallet')
            ->maxLength('wallet', 200)
            ->allowEmptyString('wallet');

        $validator
            ->scalar('balance')
            ->maxLength('balance', 100)
            ->allowEmptyString('balance');

        $validator
            ->scalar('user_upload_limit')
            ->maxLength('user_upload_limit', 50)
            ->notEmptyString('user_upload_limit');

        $validator
            ->integer('two_factor')
            ->notEmptyString('two_factor');

        $validator
            ->scalar('last_month')
            ->allowEmptyString('last_month');

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
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username']);
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);
        $rules->add($rules->isUnique(['id']), ['errorField' => 'id']);
        $rules->add($rules->existsIn(['device_id'], 'Devices'), ['errorField' => 'device_id']);

        return $rules;
    }
}
