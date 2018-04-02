// JavaScript Document
/**
*
*  Simple Context Menu
*  http://www.webtoolkit.info/
*
**/
/* global reference to currently clicked element */
var currentElement;


var SimpleContextMenu = {
 
	// private attributes
	SimpleContextMenu : new Array,
	_openMenu : null,	
	_prntDefault : true,
	_prntForms : true,
	_submenus : new Array,
	
 
 
	// public method. Sets up whole context menu stuff..
	setup : function (conf) {
 
		if ( document.all && document.getElementById && !window.opera ) {
			SimpleContextMenu.IE = true;
		}
 
		if ( !document.all && document.getElementById && !window.opera ) {
			SimpleContextMenu.FF = true;
		}
 
		if ( document.all && document.getElementById && window.opera ) {
			SimpleContextMenu.OP = true;
		}
 
		if ( SimpleContextMenu.IE || SimpleContextMenu.FF ) {
 
			document.oncontextmenu = SimpleContextMenu._show;
			document.onclick = SimpleContextMenu._hide;
 
			if (conf && typeof(conf.prntDefault) != "undefined") {
				SimpleContextMenu._prntDefault = conf.prntDefault;
			}
 
			if (conf && typeof(conf.prntForms) != "undefined") {
				SimpleContextMenu._prntForms = conf.prntForms;
			}
 
		}
 
	},
 
 
	// public method. Attaches context menus to specific class names
	attach : function (classNames, menuId, onOpen) {
		
		if (typeof(classNames) == "string") {
			SimpleContextMenu.SimpleContextMenu[classNames] = [ menuId, onOpen ];
		}
 
		if (typeof(classNames) == "object") {
			for (x = 0; x < classNames.length; x++) {
				SimpleContextMenu.SimpleContextMenu[classNames[x]] = [ menuId, onOpen ];
			}
		}
	},
	
	detach : function (classNames) {
		
		if (typeof(classNames) == "string") {
			SimpleContextMenu.SimpleContextMenu[classNames] = [];
		}
 
		if (typeof(classNames) == "object") {
			for (x = 0; x < classNames.length; x++) {
				SimpleContextMenu.SimpleContextMenu[classNames[x]] = [];
			}
		}
	},
	
	attachSubmenu : function(parentSelector, submenuId, onOpen) {
				
		$(parentSelector).each(function(index)
		{			
			//$('#'+parentEltId).mouseover(function(event) {
			$(this).mouseover(function(event) {				
				parent = $(this).parent();
				menuRoot = parent.parent();
				submenu = $('#'+submenuId);	
				submenu.css('left', parseFloat(menuRoot.css('left')) + menuRoot.width());
				submenu.css('top', parseFloat(menuRoot.css('top')) + (parent.index() * parent.height()));
				submenu.css('display', 'block');						
				onOpen();
			});
		});
					
		
		
		//TODO: this method does not allow for third-tier menus.
		$('#'+submenuId).mouseleave(function(event)
		{			
			$('#'+submenuId).css('display', 'none');
		});			
		
		$('#'+submenuId).click(function(event)
		{			
			$('#'+submenuId).css('display', 'none');
		});		
		
		SimpleContextMenu._submenus.push(submenuId);	//for hiding the submenus when the parent is hidden.		
	}, 
 
	// private method. Get which context menu to show
	_getMenuElementId : function (e) {
 
		if (SimpleContextMenu.IE) {
			currentElement = window.event.srcElement;
		} else {
			currentElement = e.target;
		}

		while(currentElement != null) {
			var className = currentElement.className;
 
			if (typeof(className) != "undefined") {
				className = className.replace(/^\s+/g, "").replace(/\s+$/g, "")
				var classArray = className.split(/[ ]+/g);
 
				for (i = 0; i < classArray.length; i++) {
					if (SimpleContextMenu.SimpleContextMenu[classArray[i]]) {						
						return SimpleContextMenu.SimpleContextMenu[classArray[i]];
					}
				}
			}
			
			if (currentElement) {			
				if (SimpleContextMenu.IE) {
					currentElement = currentElement.parentElement;
				} else {
					currentElement = currentElement.parentNode;
				}
			}
		}
 
		return null;
 
	},
 
 
	// private method. Shows context menu
	_getReturnValue : function (e) {
 
		var returnValue = true;
		var evt = SimpleContextMenu.IE ? window.event : e;
 
		if (evt.button != 1) {
			if (evt.target) {
				var el = evt.target;
			} else if (evt.srcElement) {
				var el = evt.srcElement;
			}
 
			var tname = el.tagName.toLowerCase();
 
			if ((tname == "input" || tname == "textarea")) {
				if (!SimpleContextMenu._prntForms) {
					returnValue = true;
				} else {
					returnValue = false;
				}
			} else {
				if (!SimpleContextMenu._prntDefault) {
					returnValue = true;
				} else {
					returnValue = false;
				}
			}
		}
 
		return returnValue;
 
	},
 
 
	// private method. Shows context menu
	_show : function (e) {
	
		SimpleContextMenu._hide();
		var menuFields = SimpleContextMenu._getMenuElementId(e);
		var menuElementId = menuFields[0];
		var menuOnOpen = menuFields[1];
				
		if (menuElementId) {
			var m = SimpleContextMenu._getMousePosition(e);
			var s = SimpleContextMenu._getScrollPosition(e);
 
 			if (menuOnOpen)
			{
 				SimpleContextMenu._hide();
 				menuOnOpen();
			}
			SimpleContextMenu._openMenu = document.getElementById(menuElementId);
			SimpleContextMenu._openMenu.style.left = m.x + s.x + 'px';
			SimpleContextMenu._openMenu.style.top = m.y + s.y + 'px';
			SimpleContextMenu._openMenu.style.display = 'block';
			return false;
		}
 
		return SimpleContextMenu._getReturnValue(e);
 
	},

 
	// private method. Hides context menu
	_hide : function () {
 		
		if (SimpleContextMenu._openMenu) {
			SimpleContextMenu._openMenu.style.display = 'none';
		}				
		
		$.each(SimpleContextMenu._submenus, function(index, submenu) 
		{									
			$('#'+submenu).css('display', 'none');;		
		});
 
	},
 
 
	// private method. Returns mouse position
	_getMousePosition : function (e) {
 
		e = e ? e : window.event;
		var position = {
			'x' : e.clientX,
			'y' : e.clientY
		}
 
		return position;
 
	},
 
 
	// private method. Get document scroll position
	_getScrollPosition : function () {
 
		var x = 0;
		var y = 0;
 
		if( typeof( window.pageYOffset ) == 'number' ) {
			x = window.pageXOffset;
			y = window.pageYOffset;
		} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
			x = document.documentElement.scrollLeft;
			y = document.documentElement.scrollTop;
		} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
			x = document.body.scrollLeft;
			y = document.body.scrollTop;
		}
 
		var position = {
			'x' : x,
			'y' : y
		}
 
		return position;
	}
}