<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Leaverequest Entity
 *
 * @property int $request_id
 * @property int $student_id
 * @property \Cake\I18n\Date $leave_date
 * @property string $leave_type
 * @property string|null $reason
 * @property string|null $attachment_path
 * @property string $status
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Student $student
 */
class Leaverequest extends Entity
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
        'student_id' => true,
        'leave_date' => true,
        'leave_type_id' => true,
        'reason' => true,
        'attachment_path' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'student' => true,
        'leaveType' => true
    ];
}
