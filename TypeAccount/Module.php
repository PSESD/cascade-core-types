<?php
/**
 * @link http://psesd.org/
 *
 * @copyright Copyright (c) 2015 Puget Sound ESD
 * @license http://psesd.org/license/
 */

namespace cascade\modules\core\TypeAccount;

use cascade\components\types\Module as TypeModule;
use canis\helpers\ArrayHelper;
use Yii;

/**
 * Module [[@doctodo class_description:cascade\modules\core\TypeAccount\Module]].
 *
 * @author Jacob Morrison <email@ofjacob.com>
 */
class Module extends TypeModule
{
    /**
     * @inheritdoc
     */
    protected $_title = 'Account';
    /**
     * @inheritdoc
     */
    public $icon = 'fa fa-building-o';
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
    public $priority = 105;
    /**
     * @inheritdoc
     */
    public $parentSearchWeight = .2;
    /**
     * @inheritdoc
     */
    public $widgetNamespace = 'cascade\modules\core\TypeAccount\widgets';
    /**
     * @inheritdoc
     */
    public $modelNamespace = 'cascade\modules\core\TypeAccount\models';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->registerMigrationAlias('@cascade/modules/core/TypeAccount/migrations');
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
    public function getPrimaryAsParent(TypeModule $child)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function determineOwner($object)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getRequestors($accessingObject, $firstLevel = true)
    {
        if (!$firstLevel) {
            $parentAccounts = $accessingObject->parents($this->primaryModel, [], ['disableAccessCheck' => true]);
            if (!empty($parentAccounts)) {
                return ArrayHelper::getColumn($parentAccounts, 'id', false);
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function widgets()
    {
        $widgets = parent::widgets();
        $widgets['ParentAccountBrowse']['section'] = Yii::$app->collectors['sections']->getOne('_side');

        return $widgets;
    }

    /**
     * @inheritdoc
     */
    public function parents()
    {
        return [
            'Account' => [],
        ];
    }

    /**
     * @inheritdoc
     */
    public function children()
    {
        return [
            'Account' => [],
            'Individual' => [
                'temporal' => true,
            ],
            'PhoneNumber' => [],
            'PostalAddress' => [],
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
