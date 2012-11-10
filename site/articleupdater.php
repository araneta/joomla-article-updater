<?php
/**
 * @version     1.0.0
 * @package     com_articleupdater
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      aldo praherda <aldopraherda@gmail.com> - http://ujianku.com
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
$controller	= JController::getInstance('Articleupdater');
$controller->execute(JRequest::getVar('task',''));
$controller->redirect();
