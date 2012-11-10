<?php
/**
 * @version     1.0.0
 * @package     com_articleupdater
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      aldo praherda <aldopraherda@gmail.com> - http://ujianku.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Drafts list controller class.
 */
class ArticleupdaterControllerDrafts extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'draft', $prefix = 'ArticleupdaterModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}