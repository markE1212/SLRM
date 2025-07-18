<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

/**
 * Approvals Model
 *
 * @property \App\Model\Table\LeaverequestsTable&\Cake\ORM\Association\BelongsTo $Leaverequests
 * @property \App\Model\Table\LecturersTable&\Cake\ORM\Association\BelongsTo $Lecturers
 *
 * @method \App\Model\Entity\Approval newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Approval get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Approval findOrCreate($search, ?callable $callback = null, array $options = [])
 */
class ApprovalsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
{
    parent::initialize($config);

    $this->setTable('approvals');
    $this->setDisplayField('status');
    $this->setPrimaryKey('approval_id');

    // Add behaviors
    $this->addBehavior('Timestamp');
    $this->addBehavior('AuditStash.AuditLog');
    $this->addBehavior('Search.Search');

    // Associations
    $this->belongsTo('Leaverequests', [
        'foreignKey' => 'request_id',
        'joinType' => 'INNER'
    ]);

    // Updated association to use correct field name
    $this->belongsTo('Lecturers', [
        'foreignKey' => 'lecturer_id',
        'bindingKey' => 'lecturer_id',  // This tells CakePHP which field to use in the lecturers table
        'joinType' => 'LEFT'
    ]);

    // Search setup
    $this->searchManager()
        ->add('search', 'Search.Like', [
            'fieldMode' => 'OR',
            'fields' => ['status', 'remarks']
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
        ->integer('request_id')
        ->requirePresence('request_id', 'create')
        ->notEmptyString('request_id', __('Leave request is required'));

    $validator
        ->integer('lecturer_id')
        ->requirePresence('lecturer_id', 'create')
        ->notEmptyString('lecturer_id', __('Lecturer is required'));

    $validator
        ->scalar('status')
        ->inList('status', ['Pending', 'Approved', 'Rejected'], 'Status must be Pending, Approved, or Rejected')
        ->requirePresence('status', 'create')
        ->notEmptyString('status', __('Status is required'));

    $validator
        ->scalar('remarks')
        ->maxLength('remarks', 500, 'Remarks cannot exceed 500 characters.')
        ->allowEmptyString('remarks');

    $validator
        ->dateTime('approved_at')
        ->allowEmptyDateTime('approved_at');

    return $validator;
}

    /**
     * Returns a rules checker object that will be used for validating application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
  public function buildRules(RulesChecker $rules): RulesChecker
{
    // Ensure request_id is valid
    $rules->add($rules->existsIn(['request_id'], 'Leaverequests'));
    
    // Ensure lecturer_id exists - using correct field name
    $rules->add($rules->existsIn(['lecturer_id'], 'Lecturers', [
        'bindingKey' => 'lecturer_id'  // Use lecturer_id as the key in lecturers table
    ]));

    // Ensure lecturer_id exists only when provided
    $rules->add(function ($entity, $ruleContext) {
        if (empty($entity->lecturer_id)) {
            return true; // Skip validation if empty
        }

        return $this->Lecturers->exists(['lecturer_id' => $entity->lecturer_id]);
    }, 'valid_lecturer', [
        'errorField' => 'lecturer_id',
        'message' => 'The lecturer does not exist.'
    ]);

    // Make sure each leave request has only one approval - but allow updates
    $rules->add($rules->isUnique(['request_id'], [
        'allowMultipleNulls' => false,
        'message' => 'An approval for this leave request already exists.'
    ]), 'unique_request', [
        'on' => 'create' // Only check uniqueness on create, not update
    ]);

    return $rules;
}
}