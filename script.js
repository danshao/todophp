$(document).ready(function(){
	$(".todoList").sortable({
		axis		: 'y',				
		containment	: 'window',			
		update		: function(){		
							var arr = $(".todoList").sortable('toArray');
							
							arr = $.map(arr,function(val,key){
								return val.replace('todo-','');
							});
							
							$.get('ajax.php',{action:'rearrange',positions:arr});
						}
	});
	
	// Actions
	var currentTODO;

	// Find element that action is performed on
	$('.todo a').live('click',function(e){				   
		currentTODO = $(this).closest('.todo');
		currentTODO.data('id',currentTODO.attr('id').replace('todo-',''));
		
		e.preventDefault();
	});

	$('.completed a').live('click',function(e){				   
		currentTODO = $(this).closest('.completed');
		currentTODO.data('id',currentTODO.attr('id').replace('todo-',''));
		
		e.preventDefault();
	});

	// Listen for delete button
	$('.todo a.delete').live('click',function(){
		$.get("ajax.php",{"action":"delete","id":currentTODO.data('id')},function(msg){
					currentTODO.fadeOut('fast');
				})
	});
	
	// Listen for edit button
	$('.todo a.edit').live('click',function(){
		var container = currentTODO.find('.text');
		
		if(!currentTODO.data('origText'))
		{
			currentTODO.data('origText',container.text());
		}
		else
		{
			return false;
		}
		
		$('<input type="text">').val(container.text()).appendTo(container.empty());
		
		container.append(
			'<div class="edit">'+
				'<a href="#" class="save"></a>'+
			'</div>'
		);
		
	});

	// Listen for complete button
	$('.todo a.complete').live('click',function(){
		$.get("ajax.php",{"action":"complete","id":currentTODO.data('id')},function(msg){
			currentTODO.fadeOut('fast');
			currentTODO.attr('class', 'completed');
			currentTODO.find('.actions').remove();
			currentTODO.append('<div class="actions"><a href="#" class="undo">Undo</a></div>');
			currentTODO.appendTo('.completedList').fadeIn();
		});
	});

	// Listen for undo button
	$('.completed a.undo').live('click',function(){
		$.get("ajax.php",{"action":"undo","id":currentTODO.data('id')},function(msg){
			currentTODO.fadeOut('fast');
			currentTODO.attr('class', 'todo');
			currentTODO.find('.actions').remove();
			currentTODO.append('<div class="actions"><a href="#" class="edit">Edit</a><a href="#" class="complete">Complete</a><a href="#" class="delete">Delete</a></div>');
			currentTODO.appendTo('.todoList').fadeIn();
		});
	});

	// Listen for save button
	$('.todo a.save').live('click',function(){
		var text = currentTODO.find("input[type=text]").val();
		
		$.get("ajax.php",{'action':'edit','id':currentTODO.data('id'),'text':text});
		
		currentTODO.removeData('origText')
					.find(".text")
					.text(text);
	});
	
	// Listen for add button
	$('#addButton').click(function(e){
		
		$.get("ajax.php",{'action':'new','text':'New Todo Item.'},function(msg){
			$(msg).hide().appendTo('.todoList').fadeIn();
		});
		
		e.preventDefault();
	});
	
});