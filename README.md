# CakePHP Filter Plugin #

## About ##

Filter is a plugin for [CakePHP-2.x][] that helps you to make data searching and filtering forms in a 
simple and fast way, without loosing the paging, sorting or any other parameter effect.
It send the filter conditions as URL parameters, witch give the users the ability to 
bookmark there pages with the desired filted data.

## Installation ##

- Visit <http://github.com/kdyouri/cakephp-2.x_filter-plugin> and download the plugin.
- Put the files to your `app/Plugin/Filter/` or `plugins/Filter/` folder.
- Don't forget to load the plugin. <http://book.cakephp.org/2.0/en/plugins/how-to-use-plugins.html>
- Add the `FilterComponent` to your controller.

## Usage ##

For search form, put in the view file:
```php
<?php echo $this->element('Filter.search_form'); ?>
```
For filter form, put something like:
```php
	<?php echo $this->element('Filter.filter_form', array(
		'fields' => array(
			'name' => array('placeholder' => __('first or last name')),
			'job_id',
			'department_id'
		),
		'options' => array('legend' => __('Filter'))
	)); ?>
```
If you want to customize your filter conditions, see the example bellow:
```php
	public function filter($params, $conditions) {
		if (!empty($params['Employee.name'])) {
			$conditions['OR'] = array(
				'Employee.first_name LIKE' => '%' . $params['Employee.name'] . '%',
				'Employee.last_name LIKE' => '%' . $params['Employee.name'] . '%'
			);
		}
		return $conditions;
	}
```

## Licence ##

Licensed under:

* GPL <http://www.gnu.org/licenses/gpl.html>

[CakePHP]: http://cakephp.org/
