<?php namespace Flynsarmy\Menu\MenuItemTypes;

use URL;
use Flynsarmy\Menu\Models\MenuItem;
use Backend\Widgets\Form;
use Cms\Classes\Page as Pg;
use Cms\Classes\Theme;
use Flynsarmy\Menu\MenuItemTypes\ItemTypeBase;

/**
 * Rich Editor
 * Renders a rich content editor field.
 *
 * @package october\backend
 * @author Alexey Bobkov, Samuel Georges
 */
class Page extends ItemTypeBase
{
	public $pageList;

	public function __construct()
	{
		$theme = Theme::getEditTheme();
		$this->pageList = Pg::listInTheme($theme, true);
	}

	/**
	 * Add fields to the MenuItem form
	 *
	 * @param  Form   $form
	 *
	 * @return void
	 */
	public function formExtendFields(Form $form)
	{
		$context = $form->getContext();

		$options = [];
		foreach ( $this->pageList as $page )
			$options[$page->baseFileName] = $page->title . ' ('.$page->url.')';

		asort($options);

		$form->addFields([
			'master_object_id' => [
				'label' => 'Page',
				'comment' => 'Select the page you wish to link to.',
				'type' => 'dropdown',
				'options' => $options,
			],
		]);
	}

	/**
	 * Returns the URL for the master object of given ID
	 *
	 * @param  MenuItem  $item Master object iD
	 *
	 * @return string
	 */
	public function getUrl(MenuItem $item)
	{
		return URL::to(Pg::find($item->master_object_id)->url);
	}

	/**
	 * Adds any validation rules to $item->rules array that are required
	 * by the ItemType's extended fields. If necessary, add custom messages
	 * to $item->customMessages.
	 *
	 * For example:
	 * $item->rules['master_object_id'] = 'required';
	 * $item->customMessages['master_object_id.required'] = 'The Blog Post field is required.';
	 *
	 *
	 * @param MenuItem $item
	 *
	 * @return void
	 */
	public function addValidationRules(MenuItem $item)
	{
		$item->rules['master_object_id'] = 'required';
		$item->customMessages['master_object_id.required'] = 'The Page field is required.';
	}
}