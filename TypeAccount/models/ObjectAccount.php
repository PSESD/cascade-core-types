<?php
/**
 * @link http://psesd.org/
 *
 * @copyright Copyright (c) 2015 Puget Sound ESD
 * @license http://psesd.org/license/
 */

namespace cascade\modules\core\TypeAccount\models;

use cascade\models\Registry;
use cascade\models\User;

/**
 * ObjectAccount is the model class for table "object_account".
 *
 * @property string $id
 * @property string $name
 * @property string $alt_name
 * @property string $created
 * @property string $created_user_id
 * @property string $modified
 * @property string $modified_user_id
 * @property string $archived
 * @property string $archived_user_id
 * @property User $createdUser
 * @property User $archivedUser
 * @property User $modifiedUser
 * @property Registry $registry
 *
 * @author Jacob Morrison <email@ofjacob.com>
 */
class ObjectAccount extends \cascade\components\types\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $descriptorField = 'name';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_account';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'Photo' => 'cascade\components\db\behaviors\Photo',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['photo_storage_id'], 'safe'],
            [['name'], 'required'],
            [['id', 'created_user_id', 'modified_user_id', 'archived_user_id'], 'string', 'max' => 36],
            [['name', 'alt_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSubdescriptorFields()
    {
        return [['parent:_', 'child:PostalAddress:citySubnational']];
    }

    /**
     * @inheritdoc
     */
    public function fieldSettings()
    {
        return [
            'name' => [],
            'alt_name' => [],
        ];
    }

    /**
     * @inheritdoc
     */
    public function formSettings($name, $settings = [])
    {
        if (!array_key_exists('title', $settings)) {
            $settings['title'] = false;
        }
        $settings['fields'] = [];
        $settings['fields'][] = ['name' => ['columns' => 8], 'alt_name' => ['columns' => 4]];
        if ($this->isNewRecord) {
            $settings['fields'][] = ['parent:Account'];
        }

        return $settings;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'alt_name' => 'Alternative Name',
            'created' => 'Created Date',
            'created_user_id' => 'Created by User',
            'modified' => 'Modified Date',
            'modified_user_id' => 'Modified by User',
            'archived' => 'Archived Date',
            'archived_user_id' => 'Archived by User',
        ];
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
     * Get archived user.
     *
     * @return \yii\db\ActiveRelation
     */
    public function getArchivedUser()
    {
        return $this->hasOne(Yii::$app->classes['User'], ['id' => 'archived_user_id']);
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
     * @inheritdoc
     */
    public static function searchFields()
    {
        $fields = parent::searchFields();
        $fields[] = 'alt_name';

        return $fields;
    }
}
