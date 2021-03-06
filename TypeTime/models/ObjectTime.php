<?php
/**
 * @link http://psesd.org/
 *
 * @copyright Copyright (c) 2015 Puget Sound ESD
 * @license http://psesd.org/license/
 */

namespace cascade\modules\core\TypeTime\models;

use cascade\models\Registry;

/**
 * ObjectTime is the model class for table "object_time".
 *
 * @property string $id
 * @property string $description
 * @property string $hours
 * @property string $log_date
 * @property binary $billable
 * @property string $created
 * @property string $created_user_id
 * @property string $modified
 * @property string $modified_user_id
 * @property User $createdUser
 * @property User $archivedUser
 * @property User $modifiedUser
 * @property Registry $registry
 *
 * @author Jacob Morrison <email@ofjacob.com>
 */
class ObjectTime extends \cascade\components\types\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $descriptorField = 'hoursWithUnit';

    /**
     * Get hours with unit.
     *
     * @return [[@doctodo return_type:getHoursWithUnit]] [[@doctodo return_description:getHoursWithUnit]]
     */
    public function getHoursWithUnit()
    {
        if ($this->hours == 1) {
            $postfix = ' hour';
        } else {
            $postfix = ' hours';
        }

        return $this->hours . $postfix . ' on ' . $this->log_date . '';
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_time';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), []);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['hours'], 'number'],
            [['log_date', 'billable'], 'safe'],
            [['id', 'created_user_id', 'modified_user_id'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function fieldSettings()
    {
        return [
            'description' => [],
            'hours' => [
                'format' => [],
                'formField' => ['fieldConfig' => ['inputGroupPostfix' => 'hours']],
            ],
            'log_date' => [],
            'parent:Individual' => ['alias' => 'parent:Individual::contributor'],
            'parent:Individual::contributor' => ['formField' => ['lockFields' => ['taxonomy_id']], 'attributes' => ['taxonomy_id' => [['systemId' => 'contributor', 'taxonomyType' => 'ic_time_individual_role']]]],
            'parent:Individual::requestor' => ['formField' => ['lockFields' => ['taxonomy_id']], 'attributes' => ['taxonomy_id' => [['systemId' => 'requestor', 'taxonomyType' => 'ic_time_individual_role']]]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function formSettings($name, $settings = [])
    {
        if (!isset($settings['fields'])) {
            $settings['fields'] = [];
        }
        $settings['fields'][] = ['hours', 'log_date'];
        $settings['fields'][] = ['description'];
        $settings['fields'][] = ['parent:Individual::contributor', 'parent:Individual::requestor'];

        return $settings;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'hours' => 'Hours',
            'log_date' => 'Log Date',
            'billable' => 'Billable',
            'created' => 'Created Date',
            'created_user_id' => 'Created by User',
            'modified' => 'Modified Date',
            'modified_user_id' => 'Modified by User',
            'parent:Individual::contributor' => 'Contributor',
            'parent:Individual::requestor' => 'Requestor',
        ];
    }

    /**
     * @inheritdoc
     */
    public function additionalFields()
    {
        return array_merge(parent::additionalFields(), [
            'completedStatus' => [],
            'parent:Individual::contributor' => 'parent:Individual',
            'parent:Individual::requestor' => 'parent:Individual',
        ]);
    }

    /**
     * Get registry.
     *
     * @return \yii\db\ActiveRelation
     */
    public function getRegistry()
    {
        return $this->hasOne(Registry::className(), ['id' => 'id']);
    }

    /**
     * Get created user.
     *
     * @return \yii\db\ActiveRelation
     */
    public function getCreatedUser()
    {
        return $this->hasOne(Yii::$app->classes['User'], ['id' => 'created_user_id']);
    }

    /**
     * Get modified user.
     *
     * @return \yii\db\ActiveRelation
     */
    public function getModifiedUser()
    {
        return $this->hasOne(Yii::$app->classes['User'], ['id' => 'modified_user_id']);
    }
}
