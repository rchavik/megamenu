<div id="menu-<?php echo $menu['Menu']['id']; ?>" class="megamenu">
<?php
	echo $this->Megamenu->nestedLinks($menu['threaded'], $options);
?>
</div>
