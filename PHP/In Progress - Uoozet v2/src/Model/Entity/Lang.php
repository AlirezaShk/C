<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Lang Entity
 *
 * @property int $id
 * @property string $lang_key
 * @property string $type
 * @property string|null $english
 * @property string|null $arabic
 * @property string|null $farsi
 */
class Lang extends Entity
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
        'lang_key' => true,
        'type' => true,
        'english' => true,
        'arabic' => true,
        'farsi' => true,
    ];
}
