<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SectionsStudent Entity
 *
 * @property int $id
 * @property int $sectionid
 * @property int $studentid
 * @property string $lettergrade
 * @property float $numericgrade
 */
class SectionsStudent extends Entity
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
        'sectionid' => true,
        'studentid' => true,
        'lettergrade' => true,
        'numericgrade' => true
    ];
}
