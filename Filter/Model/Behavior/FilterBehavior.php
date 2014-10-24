<?php
/**
 * CakePHP Filter Plugin
 *
 * Copyright (C) 2014 Kamal Dyouri
 *
 * Licensed under:
 *   GPL <http://www.gnu.org/licenses/gpl.html>
 */
class FilterBehavior extends ModelBehavior {

	protected $_filterParams = array();

	public function setup(Model $model, $settings = array()) {
		// Sauvegarder les paramètres de filtres
		$this->_filterParams[$model->alias] = $settings;
	}

	public function beforeFind(Model $model, $query) {
		// Récupérer les paramètres de filtres
		$params = $this->_filterParams[$model->alias];
		
		// Transformer ces paramètres en expression de filtres
		$conditions = $this->_getFilterExp($model, $params);
		
		// Permettre au modèle de modifier ces filtres
		if (method_exists($model, 'filter')) {
			$conditions = (array)$model->filter($params, $conditions);
		}
		
		// Transmettre cette expression au modèle
		$query['conditions'] = $conditions;
		return $query;
	}

	protected function _getFilterExp(Model $model, $params = array()) {
		// Charger la liste des champs du modèle principal
		$allFields[$model->alias] = $model->schema();
		$searchFields = $allFields;
		
		// Ajouter les champs des modèles associés
		$relations = array_merge($model->belongsTo, $model->hasOne);
		foreach ($relations as $alias => $details) {
			$schema = $model->{$alias}->schema();
			$searchField = $model->{$alias}->displayField;
			
			$allFields[$alias] = $schema;
			$searchFields[$alias][$searchField] = $schema[$searchField];
		}
		
		$conditions = array();
		foreach ($params as $key => $val) {
			if ($key == 'q') {
				// Si recherche globale, rechercher dans tout les champs
				foreach ($searchFields as $alias => $fields) {
					foreach ($fields as $field => $properties) {
						if (!in_array($field, array('id', 'created', 'modified'))) {
							$conditions['OR'][$alias.'.'.$field.' LIKE'] = "%$val%";
						}
					}
				}
			} else {
				// Sinon, formuler l'expression de filtre
				$alias = $model->alias;
				$field = $key;
				if (strpos($key, '.') !== false) list($alias, $field) = explode('.', $key);
				if (!isset($allFields[$alias][$field])) continue;
				if (in_array($allFields[$alias][$field]['type'], array('string', 'text', 'date'))) {
					$key .= ' LIKE';
					$val = "%$val%";
				}
				$conditions[$key] = $val;
			}
		}
		return $conditions;
	}
}
