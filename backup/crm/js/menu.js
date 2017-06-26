var old_menu = '';
var old_cell = '';
function showsubmenu(submenu ,cellbar) {
	if( old_menu != submenu ) {
		if( old_menu !='' )
			old_menu.style.display = 'none';

		submenu.style.display = 'block';
		old_menu = submenu;
		old_cell = cellbar;
		//alert("aa");
	} else {
		submenu.style.display = 'none';
		old_menu = '';
		old_cell = '';
	}
	//alert(cellbar);
}