<?php
/**
 * @link http://psesd.org/
 *
 * @copyright Copyright (c) 2015 Puget Sound ESD
 * @license http://psesd.org/license/
 */

namespace cascade\modules\core\TypeIndividual;

use cascade\components\types\Module as TypeModule;
use cascade\models\Registry;
use Yii;

/**
 * Module [[@doctodo class_description:cascade\modules\core\TypeIndividual\Module]].
 *
 * @author Jacob Morrison <email@ofjacob.com>
 */
class Module extends TypeModule
{
    /**
     * @inheritdoc
     */
    protected $_title = 'Individual';
    /**
     * @inheritdoc
     */
    public $icon = 'fa fa-user';
    /**
     * @inheritdoc
     */
    public $uniparental = false;
    /**
     * @inheritdoc
     */
    public $hasDashboard = true;
    /**
     * @inheritdoc
     */
    public $priority = 110;
    /**
     * @inheritdoc
     */
    public $widgetNamespace = 'cascade\modules\core\TypeIndividual\widgets';
    /**
     * @inheritdoc
     */
    public $modelNamespace = 'cascade\modules\core\TypeIndividual\models';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->registerMigrationAlias('@cascade/modules/core/TypeIndividual/migrations');
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'Authority' => [
                'class' => 'cascade\components\security\AuthorityBehavior',
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getPrimaryAsParent(TypeModule $child)
    {
        if (isset($parent)) {
            \d($parent->systemId);
            if (in_array($parent->systemId, ['Agreement', 'Account'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getPrimaryAsChild(TypeModule $parent)
    {
        if (isset($parent)) {
            if (in_array($parent->systemId, ['Agreement', 'Account'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function setup()
    {
        $results = [true];
        if (!empty($this->primaryModel)) {
            $primaryAccount = Yii::$app->gk->primaryAccount;
            if ($primaryAccount) {
                $results[] = $this->objectTypeModel->setRole(['system_id' => 'editor'], $primaryAccount, true);
            }
            $publicGroup = Yii::$app->gk->publicGroup;
            if ($publicGroup) {
                $results[] = $this->objectTypeModel->setRole(['system_id' => 'browser'], $publicGroup, true);
            }
        }

        return min($results);
    }

    /**
     * @inheritdoc
     */
    public function determineOwner($object)
    {
        return false;
    }

    /**
     * Get top requestors.
     *
     * @param [[@doctodo param_type:accessingObject]] $accessingObject [[@doctodo param_description:accessingObject]]
     *
     * @return [[@doctodo return_type:getTopRequestors]] [[@doctodo return_description:getTopRequestors]]
     */
    public function getTopRequestors($accessingObject)
    {
        $individual = false;
        if ($accessingObject->modelAlias === 'cascade\models\User'
            && isset($accessingObject->object_individual_id)
        ) {
            $individual = Registry::getObject($accessingObject->object_individual_id, false);
            if ($individual) {
                $requestors[] = $individual->primaryKey;
            }
            $requestors[] = $accessingObject->primaryKey;
        } elseif ($accessingObject->modelAlias === ':Individual\\ObjectIndividual') {
            $requestors[] = $accessingObject->primaryKey;
        }
        if (empty($requestors)) {
            return false;
        }

        return $requestors;
    }

    /**
     * @inheritdoc
     */
    public function getRequestors($accessingObject, $firstLevel = true)
    {
        $individual = false;
        if (empty($accessingObject)) {
            return false;
        }
        if ($accessingObject->modelAlias === 'cascade\models\User'
            && isset($accessingObject->object_individual_id)
        ) {
            $individual = Registry::getObject($accessingObject->object_individual_id, false);
        } elseif ($accessingObject->modelAlias === ':Individual\\ObjectIndividual') {
            $individual = $accessingObject;
        }

        if ($individual) {
            $requestors = [$individual->primaryKey];
            foreach ($this->collectorItem->parents as $parentType) {
                if ($parentType->parent->getBehavior('Authority') !== null) {
                    if (($parentRequestors = $parentType->parent->getRequestors($individual, false)) && !empty($parentRequestors)) {
                        $requestors = array_merge($requestors, $parentRequestors);
                    }
                }
            }

            return $requestors;
        }

        return false;
    }

    /**
     * Get requestor types.
     */
    public function getRequestorTypes()
    {
    }

    /**
     * @inheritdoc
     */
    public function parents()
    {
        return [
            'Account' => [
                'temporal' => true,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function children()
    {
        return [
            'PostalAddress' => [],
            'EmailAddress' => [],
            'PhoneNumber' => [],
            'WebAddress' => [],
        ];
    }

    /**
     * @inheritdoc
     */
    public function taxonomies()
    {
        return [];
    }
}
