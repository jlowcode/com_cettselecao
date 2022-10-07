<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cettselecao
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the CettSelect Component
 *
 * @since  0.0.1
 */
class CettSelecaoViewCettSelecao extends JViewLegacy
{
	/**
	 * Display the Cett Select view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		$this->addDependences();

		// Display the view
		parent::display($tpl);
	}

	function addDependences()
	{
		$document = JFactory::getDocument();

		// everything's dependent upon JQuery
		JHtml::_('jquery.framework');

		// we need the Openlayers JS and CSS libraries
		$document->addScript("https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js");
		$document->addScript("https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js");

		$document->addStyleSheet("https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css");
		$document->addStyleSheet("https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css");
	}
}