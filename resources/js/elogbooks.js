$(document).ready(function()
	{
 
		$('#formCalc').on('click', function(e)
		{

				e.preventDefault();
 
			 			
				GUI_Status_Busy();


				$(window).scrollTop(0);
				
				 
				$.ajax(
					{
						url: "savelog", 
						data: $('input[name="calc"]').closest("form").serialize() ,
						dataType: 'JSON',  // what to expect back from the PHP script, if anything
						cache: false,
						type: "POST",
						success: function(data)
						{

							if(data.error)
							{
								$(".status").html(" <!-- #formSave Complete! -->" + data.msg);
								$(".status").addClass("error_msg");
							}else
							{
								$(".status").html(" <!-- #formSave Complete! -->" + data.msg);

								$(".status").addClass("success_msg");
							}

							$(".status").removeClass("loader");

							setTimeout(function()
								{
									if(data.redirect)
										window.location.href = data.redirect;
								}, 1000
							);

							return;
						},
						error: function(XMLHttpRequest, textStatus, errorThrown)
						{
							$(".status").html("Failed! "+textStatus);
							$(".status").removeClass("loader");
						}
					});

			}); // End button click submit
			
		function GUI_Status_Busy()
		{

			$( ".status" ).html("");
			$(".status").addClass("loader");
			$(".status").removeClass("error_msg");
			$(".status").removeClass("success_msg");
		}
 
		 
} ); // End doc ready