<?php defined('_JEXEC') or die;

/**
 * File       k2redirector.php
 * Created    10/28/13 4:48 PM
 * Author     Matt Thomas
 * Website    http://betweenbrain.com
 * Email      matt@betweenbrain.com
 * Support    https://github.com/betweenbrain/K2-Redirector/issues
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

class plgSystemK2redirector extends JPlugin {

	function plgSystemK2redirector(&$subject, $params) {
		parent::__construct($subject, $params);

		$this->app = JFactory::getApplication();
		$this->db  = JFactory::getDBO();
		$this->doc = JFactory::getDocument();
	}

	function onAfterRoute() {

		if ($this->app->isAdmin()) {
			return TRUE;
		}

		if (JRequest::getCmd('option') === "com_k2") {

			$categoryRedirect = $this->params->get('categoryRedirect');
			$dateRedirect     = $this->params->get('dateRedirect');
			$searchRedirect   = $this->params->get('searchRedirect');
			$tagRedirect      = $this->params->get('tagRedirect');
			$task             = JRequest::getWord('task');
			$userRedirect     = $this->params->get('userRedirect');

			switch (TRUE) {

				case ($task === 'category' && $categoryRedirect) :

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
			}
		}
	}

	private function getUrl($id) {

		$query = 'SELECT ' . $this->db->nameQuote('link') . '
              FROM ' . $this->db->nameQuote('#__menu') . '
              WHERE ' . $this->db->nameQuote('id') . ' = ' . $this->db->quote($id) . '
              AND ' . $this->db->nameQuote('published') . ' = 1';

		$this->db->setQuery($query);
		$link = $this->db->loadResult();

		return JRoute::_($link . '&Itemid=' . $id);
	}
}
