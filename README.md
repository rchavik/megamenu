Megamenu Plugin Version 0.1
------------------------------

Megamenu plugin for Croogo

Based on:

	http://net.tutsplus.com/tutorials/html-css-techniques/how-to-build-a-kick-butt-css3-mega-drop-down-menu/

This is alpha software.

Configuration
-------------

Configuring the menu is a pain. You need to have the correct value in each link's params field.

**Link** parameter:

	`container`: first level menu items. Mandatory for first level menu items.
	Valid values: [dropdown_1column|dropdown_2columns|dropdown_3columns|dropdown_4_columns|dropdown_5columns]

	`heading`:
	Valid values: [h2|h3]

	`imgpath`: relative img path

	`imgclass`: image class
	Valid values: imgshadow, img_left, img_right

	`blackbox`: display description in blackbox
	Valid values: `true`

	`link`: Set to `none` to hide menu item link
	Valid values: `none`

	`description`: Description location
	Valid values: [before|after] link

	`div`: container class

	`list`: list class

Known Bugs
-----------

There are probably lots of them. Bug reports/pull requests welcome.

Requirements
-----------

Croogo 1.4 - http://croogo.org/

Good luck and have fun.
-- rchavik
