<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query\SelectQuery;
use Cake\Datasource\EntityInterface;

/**
 * Lecturers Model
 *
 * @method \App\Model\Entity\Lecturer get(mixed $primaryKey, array|string $finder = 'all', $cache = null, $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Lecturer newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Lecturer> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Lecturer|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method bool delete(\Cake\Datasource\EntityInterface $entity, array $options = [])
 */
class LecturersTable extends Table
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

        $this->setTable('lecturers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('lecturer_id');

        // Behaviors
        $this->addBehavior('Timestamp');
        $this->addBehavior('AuditStash.AuditLog');
        $this->addBehavior('Search.Search');

         $this->hasMany('Approvals', [
        'foreignKey' => 'lecturer_id']);

        // Search configuration
        $this->searchManager()
            ->add('search', 'Search.Like', [
                'fieldMode' => 'OR',
                'multiValue' => true,
                'multiValueSeparator' => '|',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'fields' => ['name', 'email', 'department']
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
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('department')
            ->maxLength('department', 100)
            ->allowEmptyString('department');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('status')
            ->notEmptyString('status');

        return $validator;
    }
}