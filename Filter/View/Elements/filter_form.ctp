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
<?php echo $this->Form->create(null, array('action' => 'filter')); ?>
<fieldset>
	<?php if (!empty($options['legend'])): ?>
	<legend><?php echo $options['legend']; ?></legend>
	<?php endif; ?>

<?php
	if (!empty($fields)) {
		foreach ($fields as $key => $val) {
			if (is_array($val)) {
				$field = $key;
				$options = $val;
			} else {
				$field = $val;
				$options = array();
			}
			$options = array_merge(array(
				'required' => false,
				'empty' => true
			), $options);
			echo $this->Form->input($field, $options);
		}
	}
?>
</fieldset>
<?php echo $this->Form->end(__('Filter')); ?>
