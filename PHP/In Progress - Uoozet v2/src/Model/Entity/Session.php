<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Session Entity
 *
 * @property int $id
 * @property string $session_id
 * @property int $user_id
 * @property string $platform
 * @property int $time
 *
 * @property \App\Model\Entity\Session[] $sessions
 * @property \App\Model\Entity\User $user
 */
class Session extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'session_id' => true,
        'user_id' => true,
        'platform' => true,
        'time' => true,
        'sessions' => true,
        'user' => true,
    ];
}
