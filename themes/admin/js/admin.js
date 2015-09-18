jQuery(document).ready(function(){
	Admin.init();
});

var Admin = {
	init: function(){
    Admin.checkBoxOperation();
	},
	checkBoxOperation:function(){
		$('#checkall').click(function(){
		 var checkAllChecked = this.checked;
		 $('input.cb[type=checkbox]').each(function () {
			this.checked = checkAllChecked;
		});
	 });
	 $("#series_approve_checked, #series_hide_checked").click(function(){
		 var idArr = [];
     var command = $(this).attr('data-command');
			$('input.cb[type=checkbox]').each(function () {
				if(this.checked){
					idArr.push($(this).val());
				}
			});
			if(idArr.length>0){
				var ids = idArr.join(",");
				$.post(base_url+"admin_series/update_status",{command:command, ids: ids}, function( data ) {
					 var currentLocation = window.location;
					 window.location = currentLocation;
				});
			}
	 });
   
   $("#video_approve_checked, #video_hide_checked").click(function(){
		 var idArr = [];
     var command = $(this).attr('data-command');
			$('input.cb[type=checkbox]').each(function () {
				if(this.checked){
					idArr.push($(this).val());
				}
			});
			if(idArr.length>0){
				var ids = idArr.join(",");
				$.post(base_url+"admin_video/update_status",{command:command, ids: ids}, function( data ) {
					 var currentLocation = window.location;
					 window.location = currentLocation;
				});
			}
	 });
	}	
}