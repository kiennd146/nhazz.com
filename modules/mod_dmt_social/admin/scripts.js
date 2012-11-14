window.addEvent('load', function(){
	
	var addBtns = $$('.button-add');
	
	addBtns.each(function(btn,idx){
		// Add a new item when clicked
		btn.addEvent('click', function(event){
			var template = $('dmt-item-template'),
				container = btn.getParent(),
				newItem = template.clone(),
				newName = 'jform[params]['+container.getProperty('id')+']',
				itemCount = container.getChildren('.dmt-item').length; // Count BEFORE adding the new item

			newItem.getChildren('.alt').setProperty('name', newName+'['+itemCount+'][alt]').setProperty('value','');
			newItem.getChildren('.url').setProperty('name', newName+'['+itemCount+'][url]').setProperty('value','');
			newItem.getChildren('select').setProperty('name', newName+'['+itemCount+'][icon]');

			newItem.inject(btn,'before').addClass('dmt-item');

			updateIcons();
			updateRemoveBtns();
			event.preventDefault();
		} );
	} );
	
	// Update the id for the websites to save
	var updateID = function(container) {
		var i = 0;
		container.getChildren('.dmt-item').each(function(cont){
			cont.getChildren().each(function(el){
				var name = el.getProperty('name');
				if(name !== null){
					var regex = new RegExp("\[[0-9]+\]"),
						newName = name.replace(regex,'['+i+']');
					el.setProperty('name', newName);
				}
			});
		i++;
		});
	}
	
	var updateRemoveBtns = function(){
		var removeBtns = $$('.icon-remove');
			
		removeBtns.each(function(btn){
			// Remove the item
			btn.addEvent('click',function(){
				var item = btn.getParent(),
					container = item.getParent();
				item.destroy();
				updateID(container);
			});
		});
	};
	updateRemoveBtns();
});
