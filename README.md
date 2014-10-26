# CakePHP Filter Plugin #

## About ##

Filter is a plugin for [CakePHP-v2.x](http://cakephp.org/) that helps you to make data searching and filtering forms in a 
simpler and faster way, without loosing the paging, sorting or any other parameter effect.
It send the filter conditions as URL parameter, witch gives the users the possibility to 
bookmark there pages with there desired filted data.

## Installation ##

- Visit [the github repository](http://github.com/kdyouri/cakephp-2.x_filter-plugin) and download the plugin.
- Put the files into your `app/Plugin/Filter/` or `plugins/Filter/` folder.
- Don't forget to load the plugin. [How to?](http://book.cakephp.org/2.0/en/plugins/how-to-use-plugins.html)
- Add the `FilterComponent` to your controller:

	```php
	public $components = array('Filter.Filter');
	```

## Usage ##

- For search form, put in the view file:
	```php
	<?php echo $this->element('Filter.search_form'); ?>
	```

- For filter form, put something like:
	```php
		<?php echo $this->element('Filter.filter_form', array(
			'fields' => array(
				'name' => array('placeholder' => __('first or last name')),
				'job_id',
				'Job.min_salary',
				'department_id'
			),
			'options' => array('legend' => __('Filter'))
		)); ?>
	```
	Notice: Don't forget to send the dropdown lists data from the controller. For our example :
	```php
	$this->set('jobs', $this->Employee->Job->find('list'));
	$this->set('departments', $this->Employee->Department->find('list'));
	```

- If you want to customize your filter conditions, put the `filter()` callback in your model. 
Like the example bellow:
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

