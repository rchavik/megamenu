<?php

class MegamenuHelper extends LayoutHelper {

	public function __construct(View $view) {
		parent::__construct($view);
		if (!isset($this->params['admin']) && !$this->request->is('ajax')) {
			$this->Html->css('/megamenu/css/menu', array(), array('inline' => false));
		}
	}

/**
 * Show Menu by Alias
 *
 * @param string $menuAlias Menu alias
 * @param array $options (optional)
 * @return string
 */
	public function menu($menuAlias, $options = array()) {
		$options = Set::merge($options, array(
			'element' => 'Megamenu.menu',
			'dropdownClass' => 'megamenu',
			));
		return parent::menu($menuAlias, $options);
	}

/**
 * Nested Links
 *
 * @param array $links model output (threaded)
 * @param array $options (optional)
 * @param integer $depth depth level
 * @return string
 */
	public function nestedLinks($links, $options = array(), $depth = 1) {
		$_options = array();
		$options = array_merge($_options, $options);

		$output = '';
		foreach ($links AS $link) {
			$linkAttr = array(
				'id' => 'link-' . $link['Link']['id'],
				'rel' => $link['Link']['rel'],
				'target' => $link['Link']['target'],
				'title' => $link['Link']['description'],
				'class' => $link['Link']['class'],
			);

			foreach ($linkAttr AS $attrKey => $attrValue) {
				if ($attrValue == null) {
					unset($linkAttr[$attrKey]);
				}
			}

			// if link is in the format: controller:contacts/action:view
			if (strstr($link['Link']['link'], 'controller:')) {
				$link['Link']['link'] = $this->linkStringToArray($link['Link']['link']);
			}

			// Remove locale part before comparing links
			if (!empty($this->params['locale'])) {
				$currentUrl = substr($this->_View->request->url, strlen($this->params['locale']));
			} else {
				$currentUrl = $this->_View->request->url;
			}

			if (Router::url($link['Link']['link']) == Router::url('/' . $currentUrl)) {
				if (!isset($linkAttr['class'])) {
					$linkAttr['class'] = '';
				}
				$linkAttr['class'] .= ' ' . $options['selected'];
			}

			$linkOutput = $this->Html->link($link['Link']['title'], $link['Link']['link'], $linkAttr);
			if (isset($link['children']) && count($link['children']) > 0) {
				$linkOutput .= $this->nestedLinks($link['children'], $options, $depth + 1);
			}
			$linkOutput = $this->Html->tag('li', $linkOutput);
			$output .= $linkOutput;
		}
		if ($output != null) {
			$tagAttr = $options['tagAttributes'];
			if ($options['dropdown'] && $depth == 1) {
				$tagAttr['class'] = $options['dropdownClass'];
			}
			$output = $this->Html->tag($options['tag'], $output, $tagAttr);
		}

		return $output;
	}

}
