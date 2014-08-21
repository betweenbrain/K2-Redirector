<?php defined('_JEXEC') or die;

/**
 * File       k2redirector.php
 * Created    10/28/13 4:48 PM
 * Author     Matt Thomas
 * Website    http://betweenbrain.com
 * Email      matt@betweenbrain.com
 * Support    https://github.com/betweenbrain/K2-Redirector/issues
 * Copyright  Copyright (C) 2013-2014 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v2 or later
 */
class plgSystemK2redirector extends JPlugin
{

	function plgSystemK2redirector(&$subject, $params)
	{
		parent::__construct($subject, $params);

		$this->app = JFactory::getApplication();
		$this->db  = JFactory::getDBO();
		$this->doc = JFactory::getDocument();
	}

	function onAfterRoute()
	{

		if ($this->app->isAdmin())
		{
			return true;
		}

		if (JRequest::getCmd('option') === "com_k2")
		{

			$categories       = $this->params->get('categories');
			$categoryRedirect = $this->params->get('categoryRedirect');
			$dateRedirect     = $this->params->get('dateRedirect');
			$itemRedirect     = $this->params->get('itemRedirect');
			$searchRedirect   = $this->params->get('searchRedirect');
			$tagRedirect      = $this->params->get('tagRedirect');
			$task             = JRequest::getWord('task');
			$userRedirect     = $this->params->get('userRedirect');
			$view             = JRequest::getWord('view');

			// Ensure that categories is always an array
			if (!is_array($categories))
			{
				$categories = str_split($categories, strlen($categories));
			}

			switch (true)
			{

				case ($task === 'category' && in_array(JRequest::getVar('id'), $categories)) :

					header('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . $this->getUrl($categoryRedirect));

					break;

				case ($task === 'user' && $userRedirect) :

					header('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . $this->getUrl($userRedirect));

					break;

				case ($task === 'tag' && $tagRedirect) :

					header('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . $this->getUrl($tagRedirect));

					break;

				case ($task === 'search' && $searchRedirect) :

					header('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . $this->getUrl($searchRedirect));

					break;

				case ($task === 'date' && $dateRedirect) :

					header('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . $this->getUrl($dateRedirect));

					break;

				case($view === 'item') :

					if (in_array($this->getItemCategory(), $categories))
					{
						header('HTTP/1.1 301 Moved Permanently');
						header('Location: ' . $this->getUrl($itemRedirect));
					}

					break;
			}
		}
	}

	/**
	 * Gets the link associated with a menu item
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	private function getUrl($id)
	{

		$query = 'SELECT ' . $this->db->nameQuote('link') . '
              FROM ' . $this->db->nameQuote('#__menu') . '
              WHERE ' . $this->db->nameQuote('id') . ' = ' . $this->db->quote($id) . '
              AND ' . $this->db->nameQuote('published') . ' = 1';

		$this->db->setQuery($query);
		$link = $this->db->loadResult();

		return JRoute::_($link . '&Itemid=' . $id, false);
	}

	/**
	 * Method to get a K2 item's category
	 *
	 * @return mixed
	 */
	private function getItemCategory()
	{
		$id = JRequest::getVar('id', '', 'get', 'INT');

		$query = 'SELECT ' . $this->db->nameQuote('catid') . '
		              FROM ' . $this->db->nameQuote('#__k2_items') . '
		              WHERE ' . $this->db->nameQuote('id') . ' = ' . $this->db->quote($id);

		$this->db->setQuery($query);

		return $this->db->loadResult();
	}
}
