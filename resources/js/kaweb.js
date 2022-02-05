$(document).ready(function()
	{
		$('#formCalc').on('click', function(e){
			
				e.preventDefault();
 							 	
				GUI_Status_Busy();
				
 				$(".results").animate({opacity:1});
				
				$.ajax(
					{
						url:  'ajaxurl', 
						data: $('input[name="calc"]').closest("form").serialize() ,
						dataType: 'JSON',  // what to expect back from the PHP script, if anything
						cache: false,
						type: "POST",
						 
						success: function(data)
						{

							if(data.error)
							{
								$(".results").html(" <!-- #formSave Complete! -->" + data.msg);
								$(".status").addClass("error_msg");
								
							}else
							{
								$(".results").html(" <!-- #formSave Complete! -->" + data.msg);
								 $(".status").html("Complete");
								$(".status").addClass("success_msg");
								$(".success_msg").fadeIn("fast");
							}

							$(".status").removeClass("loader");
						
							setTimeout(function()
							{
								$(".success_msg").fadeOut("fast");
								 
							}, 1000);

							return false;
						},
						error: function(XMLHttpRequest, textStatus, errorThrown)
						{
							$(".status").html("Failed! "+textStatus);
							$(".status").removeClass("loader");
						}
					});
				
				return false;

			}); // End button click submit
			
		function GUI_Status_Busy()
		{
			$(".status").html("");
			$(".status").addClass("loader");
			$(".status").removeClass("error_msg");
			$(".status").removeClass("success_msg");
		}

} ); // End doc ready