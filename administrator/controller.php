<?php
/**
 * @version     1.0.0
 * @package     com_articleupdater
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      aldo praherda <aldopraherda@gmail.com> - http://ujianku.com
 */


// No direct access
defined('_JEXEC') or die;

class ArticleupdaterController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/articleupdater.php';

		// Load the submenu.
		ArticleupdaterHelper::addSubmenu(JRequest::getCmd('view', 'drafts'));

		$view		= JRequest::getCmd('view', 'drafts');
        JRequest::setVar('view', $view);

		parent::display();

		return $this;
	}
}
