<?php defined('_JEXEC') or die;

/**
 * File       k2redirector.php
 * Created    10/28/13 4:48 PM
 * Author     Matt Thomas
 * Website    http://betweenbrain.com
 * Email      matt@betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

class plgSystemK2redirector extends JPlugin {

	function plgSystemK2redirector(&$subject, $params) {
		parent::__construct($subject, $params);

		$this->app    = JFactory::getApplication();
		$this->db     = JFactory::getDBO();
		$this->doc    = JFactory::getDocument();
		$this->task   = JRequest::getWord('task');
		$this->option = JRequest::getCmd('option');
	}

	function onAfterRoute() {

		if ($this->app->isAdmin()) {
			return TRUE;
		}

		if ($this->option === "com_k2") {

			switch ($this->task) {

				case 'category' :

					header('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . $this->getUrl($this->params->get('categoryRedirect')));

					break;

				case 'user' :

					header('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . $this->getUrl($this->params->get('userRedirect')));

					break;

				case 'tag' :

					header('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . $this->getUrl($this->params->get('tagRedirect')));

					break;

				case 'search' :

					header('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . $this->getUrl($this->params->get('searchRedirect')));

					break;

				case 'date' :

					header('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . $this->getUrl($this->params->get('dateRedirect')));

					break;
			}
		}
	}

	private function getUrl($id) {

		$query = 'SELECT ' . $this->db->nameQuote('link') . '
              FROM ' . $this->db->nameQuote('#__menu') . '
              WHERE ' . $this->db->nameQuote('id') . ' = ' . $this->db->quote($id) . '
              AND ' . $this->db->nameQuote('published') . ' = 1 ';

		$this->db->setQuery($query);
		$link = $this->db->loadResult();

		return $link . '&Itemid=' . $id;
	}
}
