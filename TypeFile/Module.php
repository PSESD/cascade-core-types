<?php
/**
 * @link http://www.infinitecascade.com/
 * @copyright Copyright (c) 2014 Infinite Cascade
 * @license http://www.infinitecascade.com/license/
 */

namespace cascade\modules\core\TypeFile;

use Yii;

use cascade\components\types\Relationship;
use infinite\base\exceptions\HttpException;

/**
 * Module [@doctodo write class description for Module]
 *
 * @author Jacob Morrison <email@ofjacob.com>
**/
class Module extends \cascade\components\types\Module
{
	/**
	 * @inheritdoc
	 */
	protected $_title = 'File';
	/**
	 * @inheritdoc
	 */
	public $icon = 'fa fa-paperclip';
	/**
	 * @inheritdoc
	 */
	public $uniparental = false;
	/**
	 * @inheritdoc
	 */
	public $hasDashboard = false;
	/**
	 * @inheritdoc
	 */
	public $priority = 2300;

	/**
	 * @inheritdoc
	 */
	public $widgetNamespace = 'cascade\modules\core\TypeFile\widgets';
	/**
	 * @inheritdoc
	 */
	public $modelNamespace = 'cascade\modules\core\TypeFile\models';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		Yii::$app->registerMigrationAlias('@cascade/modules/core/TypeFile/migrations');
	}

	/**
	* @inheritdoc
	**/
	public function subactions()
	{
		return [
			'download' => [$this, 'actionDownload']
		];
	}

	/**
	 * __method_actionDownload_description__
	 * @param __param_event_type__ $event __param_event_description__
	 * @throws HttpException __exception_HttpException_description__
	 */
	public function actionDownload($event)
	{
		$results = $event->object->serve();
		if (!$results || !empty($results['error'])) {
			$errorMessage = isset($results['error']) ? $results['error'] : 'Unknown error';
			throw new HttpException(500, $errorMessage);
		}
		$event->handled = true;
	}

	/**
	 * @inheritdoc
	 */
	public function widgets()
	{
			return parent::widgets();
	}

	
	/**
	 * @inheritdoc
	 */
	public function parents()
	{
		return [
			'Project' => [],
			'Account' => [],
			'Individual' => [],
			'Time' => [],
			'Note' => [],
			'Task' => [],
		];
	}

	
	/**
	 * @inheritdoc
	 */
	public function children()
	{
		return [];
	}

	
	/**
	 * @inheritdoc
	 */
	public function taxonomies()
	{
		return [];
	}
}