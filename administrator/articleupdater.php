<?php
/**
 * @version     1.0.0
 * @package     com_articleupdater
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      aldo praherda <aldopraherda@gmail.com> - http://ujianku.com
 */


// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_articleupdater')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JController::getInstance('Articleupdater');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
