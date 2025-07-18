<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;  // <-- Add this import

class LeaveTypesTable extends Table
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

        // Set the table name in the database
        $this->setTable('leave_types');
        
        // Set the display field (for internal representation)
        $this->setDisplayField('type_name');
        
        // Set the primary key field
        $this->setPrimaryKey('id');

        // Establish the relationship with Leaverequests
        $this->hasMany('Leaverequests', [
            'foreignKey' => 'leave_type_id'
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
        // Validate the 'id' column
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'auto'); // Allow empty string for auto-incremented field
        
        // Validate the 'type_name' column
        $validator
            ->scalar('type_name')
            ->maxLength('type_name', 100)
            ->requirePresence('type_name', 'create') // Ensure it's present when creating
            ->notEmptyString('type_name', __('Leave type name is required'));

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
        // Check if 'type_name' already exists
        $rules->add($rules->isUnique(['type_name']), 'unique', [
            'errorField' => 'type_name',
            'message' => 'Leave type name must be unique.'
        ]);

        return $rules;
    }
}
