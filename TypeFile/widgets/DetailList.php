<?php
/**
 * @link http://www.infinitecascade.com/
 * @copyright Copyright (c) 2014 Infinite Cascade
 * @license http://www.infinitecascade.com/license/
 */

namespace cascade\modules\core\TypeFile\widgets;

/**
 * DetailList [@doctodo write class description for DetailList]
 *
 * @author Jacob Morrison <email@ofjacob.com>
 */
class DetailList extends \cascade\components\web\widgets\base\DetailList
{
	/**
	* @inheritdoc
	 */
	public function contentTemplate($model)
	{
		if ($model->can('read')) {
			return [
				'downloadLink' => ['class' => 'list-group-item-heading', 'tag' => 'h5']
			];
		} else {
			return [
				'descriptor' => ['class' => 'list-group-item-heading', 'tag' => 'h5']
			];
		}
		
	}
}
