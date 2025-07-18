<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Approval Entity
 *
 * @property int $approval_id
 * @property int $request_id
 * @property int $lecturer_id
 * @property \Cake\I18n\DateTime|null $approved_at
 * @property string|null $remarks
 * @property string $status
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Lecturer $lecturer
 */
class Approval extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'request_id' => true,
        'lecturer_id' => true,
        'approved_at' => true,
        'remarks' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'lecturer' => true,
    ];
}
