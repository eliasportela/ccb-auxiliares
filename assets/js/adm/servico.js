var idGaleriaImovel = 0;

jQuery(document).ready(function(){

	jQuery('#gerarServicos').submit(function(){
			
		var dadosDetalhesImovel = new FormData(this);
		pageurl = 'http://127.0.0.1/edsa-rodizio/api/gerar';

		$.ajax({
		    url: pageurl,
		    type: 'POST',
		    data:  dadosDetalhesImovel,
		    mimeType:"multipart/form-data",
		    contentType: false,
		    cache: false,
		    processData:false,
		    success: function(data, textStatus, jqXHR)
		        {
		             // Em caso de sucesso faz isto...
		             alert('Inserido');
		        },
		    error: function(jqXHR, textStatus, errorThrown) 
		        {
		        	console.log(textStatus);
		          	alert('Erro');  
		        }          
		    });
			
		return false;
	});


});