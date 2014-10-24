<?php
/**
 * CakePHP Filter Plugin
 *
 * Copyright (C) 2014 Kamal Dyouri
 *
 * Licensed under:
 *   GPL <http://www.gnu.org/licenses/gpl.html>
 */
?>
<?php
	if (!isset($options)) $options = array();
	$options = array_merge(array(
		'label' => false,
		'placeholder' => __('Search...')
	), $options);

	echo $this->Form->create(false, array('action' => 'search'));
	echo $this->Form->input('q', $options);
	echo $this->Form->end(__('Search'));
?>
