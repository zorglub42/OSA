			function loadDoc(){
				$.get( "resources/templates/apidoc.php", function( data ) {
					$("#content").html(data);
				});
			}
			$(			
				function (){
					$('#apiDoc').click(loadDoc);
				}
			);
