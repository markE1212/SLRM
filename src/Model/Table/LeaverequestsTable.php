<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class LeaverequestsTable extends Table
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

        $this->setTable('leaverequests');
        $this->setDisplayField('request_id');
        $this->setPrimaryKey('request_id');

        // Behaviors
        $this->addBehavior('Timestamp'); // Adds created/modified fields
        $this->addBehavior('AuditStash.AuditLog'); // Tracks changes
        $this->addBehavior('Search.Search'); // Enables search functionality

        // Relationships
        $this->belongsTo('Students', [
            'foreignKey' => 'student_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('LeaveTypes', [
            'className' => 'LeaveTypes',
            'foreignKey' => 'leave_type_id',
            'propertyName' => 'leaveType'
        ]);

        $this->hasOne('Approvals', [
            'foreignKey' => 'request_id'
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
        // Request ID
        $validator
            ->integer('request_id')
            ->allowEmptyString('request_id');

        // Student ID
        $validator
            ->integer('student_id')
            ->requirePresence('student_id', 'create')
            ->notEmptyString('student_id', __('Student is required'));

        // Leave Type ID
        $validator
            ->integer('leave_type_id')
            ->requirePresence('leave_type_id', 'create')
            ->notEmptyString('leave_type_id', __('Leave type is required'));

        // Date
        $validator
    ->date('leave_date', ['ymd'])
    ->requirePresence('leave_date', 'create')
    ->notEmptyDate('leave_date', __('Leave date is required'))
    ->add('leave_date', 'validDateFormat', [
        'rule' => ['date', 'ymd'],
        'message' => __('Please enter a valid leave date (YYYY-MM-DD).')
    ]);

        // Reason
        $validator
            ->scalar('reason')
            ->maxLength('reason', 500, __('Reason cannot exceed 500 characters.'))
            ->allowEmptyString('reason');

        // Status
        $validator
            ->scalar('status')
            ->inList('status', ['Pending', 'Approved', 'Rejected'])
            ->requirePresence('status', 'create')
            ->notEmptyString('status', __('Status is required'));

        // Attachment Path
        $validator
            ->scalar('attachment_path')
            ->allowEmptyString('attachment_path');

        // Archived Flag
        $validator
            ->boolean('is_archived')
            ->allowEmptyString('is_archived');

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
        // Foreign key checks
        $rules->add($rules->existsIn(['student_id'], 'Students'), ['errorField' => 'student_id']);
        $rules->add($rules->existsIn(['leave_type_id'], 'LeaveTypes'), ['errorField' => 'leave_type_id']);

        return $rules;
    }
}