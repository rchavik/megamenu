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
	public function nestedLinks($links, $options = array(), $depth = 1, $parent = null) {
		$_options = array('dropClass' => 'drop');
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

			if ($depth == 1) {
				if (!empty($link['children'])) {
					$linkAttr['class'] = trim($linkAttr['class'] . ' ' .$options['dropClass']);
				}
			}

			if (isset($link['Params']['link']) && $link['Params']['link'] == 'none') {
				$linkOutput = $link['Link']['title'];
			} else {
				$linkOutput = $this->Html->link($link['Link']['title'], $link['Link']['link'], $linkAttr);
			}
			if (isset($link['Params']['heading'])) {
				if (in_array(strtolower(trim($link['Params']['heading'])), array('h2', 'h3', 'h4', 'h5', 'h6'))) {
					$linkOutput = $this->Html->tag($link['Params']['heading'], $linkOutput);
				}
			}
			if (isset($link['children']) && count($link['children']) > 0) {
				if (isset($link['Params']['list'])) {
					$savedClass = $options['tagAttributes'];
					$options['tagAttributes']['class'] = $link['Params']['list'];
				}
				$linkOutput .= $this->nestedLinks($link['children'], $options, $depth + 1, $link);
				if (isset($savedClass)) {
					$options['tagAttributes'] = $savedClass;
					unset($savedClass);
				}
			}
			if (isset($parent['Params']['container'])) {
				if (isset($link['Params']['div'])) {
					$divOptions = array('class' => $link['Params']['div']);
				} else {
					$divOptions = array('class' => 'col_1');
				}
				if (isset($link['Params']['blackbox'])) {
					if (isset($link['Params']['link']) && trim($link['Params']['link']) == 'none') {
						$linkOutput = $this->Html->tag('p', $link['Link']['description'], array('class' => 'black_box'));
					} else {
						$linkOutput = $linkOutput .= $this->Html->tag('p', $link['Link']['description'], array('class' => 'black_box'));
					}
				} elseif (isset($link['Params']['description']) || !empty($link['Link']['description'])) {
					if (!empty($link['Params']['description'])) {
						$position = $link['Params']['description'];
					} else {
						$position = 'before';
					}
					if (isset($link['Params']['description']) && trim($link['Params']['description']) == 'none') {
						$position = false;
					}
					if ($position == 'before') {
						$linkOutput = $this->Html->tag('p', $link['Link']['description'] . $linkOutput);
					} elseif ($position == 'after') {
						$linkOutput = $this->Html->tag('p', $linkOutput . $link['Link']['description']);
					} else {
						$linkOutput = $this->Html->tag('p', $link['Link']['description']);
					}

				}
				if (isset($link['Params']['imgpath'])) {
					$imgclass = '';
					if (isset($link['Params']['imgclass'])) {
						$imgclass = $link['Params']['imgclass'];
					}
					$linkOutput = $this->Html->tag('p',
						$this->Html->image($link['Params']['imgpath'], array(
							'class' => $imgclass
							)) .
						$linkOutput
						);
				}
				$linkOutput = $this->Html->tag('div', $linkOutput, $divOptions);
			} else {
				$tagAttr = empty($link['children']) ? array() : array('class' => $options['dropClass']);
				$linkOutput = $this->Html->tag('li', $linkOutput, $tagAttr);
			}
			$output .= $linkOutput;
		}
		if ($output != null) {
			$tagAttr = $options['tagAttributes'];
			if ($options['dropdown'] && $depth == 1) {
				$tagAttr['class'] = $options['dropdownClass'];
			}
			if (isset($parent['Params']['container'])) {
				$options['tag'] = 'div';
				$tagAttr['class'] = $parent['Params']['container'];
			}
			$output = $this->Html->tag($options['tag'], $output, $tagAttr);
		}

		return $output;
	}

}
