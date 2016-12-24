function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    return !(charCode > 31 && (charCode < 48 || charCode > 57));
}

function hideDownline(uID)
{
	$('#downlineTR_'+uID).remove();
	$('#downlineBtn_'+uID).html('<a href="javascript:void(0);" alt="Downline" title="View Downline"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></a');
	$('#downlineBtn_'+uID).removeAttr('onClick');	
	$('#downlineBtn_'+uID).attr('onClick','viewDownline('+uID+')');
}

  var bannerurl_fields_rows_selected = []; 
$('.rowCheck_all').bind('change',function(){
			var allInputs = document.getElementsByName("rowselected[]");
			var index   = 0;
			var chkbox_elem	=	document.getElementsByName("rowCheck_all");
			if($(this).is(':checked')){
				for (var i = 0, max = allInputs.length; i < max; i++){
					if (allInputs[i].type === 'checkbox'){
						allInputs[i].checked = true;
						index   = $.inArray(allInputs[i].value, bannerurl_fields_rows_selected);						
						$('#field_row_'+allInputs[i].value).addClass('selected');
						if(index === -1)
         				bannerurl_fields_rows_selected.push(allInputs[i].value);
					}
				}					
				for (var i = 0, max = chkbox_elem.length; i < max; i++){
					if (chkbox_elem[i].type === 'checkbox'){
						chkbox_elem[i].checked = true;
					}
				}
			}
			else{
				for (var i = 0, max = allInputs.length; i < max; i++){
					if (allInputs[i].type === 'checkbox'){
						allInputs[i].checked = false;
						index   = $.inArray(allInputs[i].value, bannerurl_fields_rows_selected);						
						$('#field_row_'+allInputs[i].value).removeClass('selected');
						if(index !== -1)
         				bannerurl_fields_rows_selected.splice(index, 1);
					}
				}				
				for (var i = 0, max = chkbox_elem.length; i < max; i++){
					if (chkbox_elem[i].type === 'checkbox'){
						chkbox_elem[i].checked = false;
					}
				}	
			}
	});
	
$('input[name="rowselected[]"]').bind('change',function(){
			var allInputs = document.getElementsByName("rowselected[]");
			var index   = 0, count=0;
			var chkbox_elem	=	document.getElementsByName("rowCheck_all");
			if($(this).is(':checked')){
					index   = $.inArray($(this).val(), bannerurl_fields_rows_selected);
					$('#field_row_'+$(this).val()).addClass('selected');
					if(index === -1)
					bannerurl_fields_rows_selected.push($(this).val());					
			}
			else{
					index   = $.inArray($(this).val(), bannerurl_fields_rows_selected);
					$('#field_row_'+$(this).val()).removeClass('selected');
					if(index !== -1)
					bannerurl_fields_rows_selected.splice(index, 1);
			}
			
			for (var i = 0, max = allInputs.length; i < max; i++){
					if (allInputs[i].type === 'checkbox' && allInputs[i].checked == true)
					count++;
			}			
			if(allInputs.length == count)
			{
				for (var i = 0, max = chkbox_elem.length; i < max; i++){
					if (chkbox_elem[i].type === 'checkbox'){
						chkbox_elem[i].checked = true;
					}
				}	
			}
			else{
				for (var i = 0, max = chkbox_elem.length; i < max; i++){
					if (chkbox_elem[i].type === 'checkbox'){
						chkbox_elem[i].checked = false;
					}
				}	
			}
						
	});
	
	
	$('.editTD').bind('click',function(){
			var id_elements		=	$(this).attr('id').split('_');
			$('#lbl_'+id_elements[1]+'_'+id_elements[2]).hide();
			$('#edit_'+id_elements[1]+'_'+id_elements[2]).hide();	
			$('#dp_'+id_elements[1]+'_'+id_elements[2]).show();
			$('#save_'+id_elements[1]+'_'+id_elements[2]).show();
	});
	
// function to generate message	
function maketoast ()
{	
	var options =
	{
		priority : $('#toastPriority').val() || null,
		title    : $('#toastTitle').val() || null,
		message  : $('#toastMessage').val() || 'A message is required'
	};

	var codeobj = [];
	var codestr = [];

	var labels = ['message', 'title', 'priority'];
	for (var i = 0, l = labels.length; i < l; i += 1)
	{
		if (options[labels[i]] !== null)
		{
			codeobj.push([labels[i], "'" + options[labels[i]] + "'"].join(' : '));
		}

		codestr.push((options[labels[i]] !== null) ? "'" + options[labels[i]] + "'" : 'null');
	}
	$.toaster(options);
}
	