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
use Joomla\CMS\Date\Date;

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
	 * @param  object $module  The module object.
	 * @param  array  $attribs The render atriputs
	 *
	 * @return  boolean
	 *
	 * @since   0.5.0
	 */
	function onRenderModule(&$module, &$attribs)
	{
		$params = new Registry ($module->params);
		if ($params->get('onetime', 0) && $params->get('onetime_impressions', 0))
		{
			$app         = Factory::getApplication();
			$name        = 'onetimemodule_' . $module->id;
			$cookie      = $app->input->cookie->get($name, 0);
			$impressions = $params->get('onetime_impressions', 0);
			if ($cookie >= $impressions)
			{
				$module = false;
			}

			$rememder = $params->get('onetime_remember', 'always');
			if ($cookie < $impressions && $rememder == 'temporarily')
			{
				$rememder_number = $params->get('onetime_remember_number', 1);
				$rememder_value  = $params->get('onetime_remember_value', 'day');
				$time            = new Date('now +' . $rememder_number . ' ' . $rememder_value);
				$value           = $cookie + 1;
				$expire          = $time->toUnix();
				$app->input->cookie->set($name, $value, $expire, '/');
			}
			elseif ($rememder == 'always')
			{
				$time   = new Date('now +1 year');
				$value  = ($cookie < $impressions)? $cookie + 1 : $cookie;
				$expire = $time->toUnix();
				$app->input->cookie->set($name, $value, $expire, '/');
			}
		}
		return true;
	}
}
