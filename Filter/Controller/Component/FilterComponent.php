<?php
/**
 * CakePHP Filter Plugin
 *
 * Copyright (C) 2014 Kamal Dyouri
 *
 * Licensed under:
 *   GPL <http://www.gnu.org/licenses/gpl.html>
 */
class FilterComponent extends Component {

	public function initialize(Controller $controller) {
		if (in_array($controller->action, array('search', 'filter'))) $this->_dataToUrl($controller);
	}

	public function startup(Controller $controller) {
		if (!empty($controller->params['named']['q']) || !empty($controller->params['named']['filter'])) {
			// Extraire les données de recherche/filtres depuis l'URL
			$params = array();
			if (!empty($controller->params['named']['q'])) {
				$params['q'] = $controller->params['named']['q'];
			}
			if (!empty($controller->params['named']['filter'])) {
				$filters = explode(';', base64_decode($controller->params['named']['filter']));
				foreach ($filters as $filter) {
					list($field, $value) = explode(':', $filter);
					$params[$field] = $value;
				}
			}
			// Lier le modèle avec `FilterBehavior`
			$controller->{$controller->modelClass}->Behaviors->attach('Filter.Filter', $params);
			
			// Remettre les données du formulaire de recherche/filtres
			foreach ($params as $key => $val) {
				$alias = null;
				$field = $key;
				if (strpos($key, '.') !== false) list($alias, $field) = explode('.', $key);
				
				if (empty($alias))
					$controller->request->data[$field] = $val;
				else
					$controller->request->data[$alias][$field] = $val;
			}
		}
	}

/**
 * Transformer les données du formulaire de recherche et filtres 
 * en paramètres URL
 *
 * @access private
 * @return void
 */
	private function _dataToUrl(Controller $controller) {
		// Extraire l'URL du référent
		$base = Router::url('/', true);
		$referer = substr($controller->referer(), strlen($base));
		$params = Router::parse($referer);
		
		$url = $params;
		unset($url['named']);
		$url = array_merge($url, $params['named']);
		unset($url['pass']);
		$url += $params['pass'];
		if (isset($url['q'])) unset($url['q']);
		if (isset($url['filter'])) unset($url['filter']);
		
		if ($controller->request->is('post')) {
			$args = array();
			$filters = array();
			foreach ($controller->request->data as $key => $val) {
				if (is_array($val)) {
					foreach ($val as $field => $value) {
						if (!empty($value)) $filters[] = "$key.$field:$value";
					}
				} else {
					if (!empty($val)) $args[$key] = $val;
				}
			}
			if (!empty($filters)) $args['filter'] = base64_encode(implode(';', $filters));
			
			if (!empty($args)) $url = array_merge($url, $args);
		}
		// Redirection
		$controller->redirect($url);
	}

}
