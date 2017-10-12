<?php
/**
 * @package    System - One Time Module Plugin
 * @version    0.5.0
 * @author     JoomlaZen - www.joomlazen.com
 * @copyright  Copyright (c) 2016 - 2017 JoomlaZen. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link       https://www.joomlazen.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\Registry\Registry;

class plgSystemOneTimeModule extends CMSPlugin
{

	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Adds additional fields to forms with personal data
	 *
	 * @param  Form  $form The form to be altered.
	 * @param  mixed $data The associated data for the form.
	 *
	 * @return  boolean
	 *
	 * @since   0.5.0
	 */
	function onContentPrepareForm($form, $data)
	{
		$app       = Factory::getApplication();
		$component = $app->input->get('option', '');
		if ($app->isAdmin() && $component == 'com_modules')
		{
			Form::addFormPath(__DIR__);
			$form->loadFile('form', false);
		}

		return true;
	}
	/**
	 * Adds additional fields to forms with personal data
	 *
	 * @param  object  $module The module object.
	 * @param  array $attribs The render atriputs
	 *
	 * @return  boolean
	 *
	 * @since   0.5.0
	 */
	function onRenderModule (&$module, &$attribs) {
		$params = new Registry ($module->params);
		if ($params->get('onetime' , 0)) {
			echo '<pre>', print_r($params, true), '</pre>';
			//$module = null;
		}

		return true;
	}
}
