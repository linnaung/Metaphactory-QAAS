$(window).on('load', function(){
       	
	Core = new Core();
	Core.init();
});

function Core(){}
Core.prototype = (function() {
              
	var _xhr = [];
 
	var _abort = function() {

		for(let i=0; i < _xhr.length; i++) {
                                
			if (_xhr[i].hasOwnProperty("abort")) {
				_xhr[i].abort();
			}
		}
		_xhr = [];

	};

      	var _setEvent = function(){

		  
		$('#create-new-concept').on("click", function(e) {

			var label = $('#search.assign-phrase').val();
			if(label) {

	
				var postdata = {
                                        label : label,
					action: 'create'
                                };
                                $.ajax({
                                        url: "jump.php",
                                        type: "POST",
                                        data: postdata,		
					statusCode: {

                                                400: function(xhr) {
                                                        var info = JSON.parse(xhr.responseText);
                                                }
                                        }
                                }).done(function(data) {

					var response = JSON.parse(data);
console.log(response);
					if(response.success) {

						var span = $('<span/>', {
							'class': 'badge badge-info',
							'style': 'margin-left: 4px; margin-right: 4px;',
							html: label
						});
				 		$('.accordion .card #append-result').append(span);

					}


				});
			} 
		});
	
		
		$(".accordion .card #search").typeahead({
                        
			source: function(keywords, process) {

				return $.ajax({
					url: "jump.php",
					type: "POST",
					beforeSend: function(xhr) {
						_abort();
						_xhr.push(xhr);
					},
					data: {
						keywords: keywords
                                        },
                                }).done(function(data) {

					var response = JSON.parse(data);
					
					var concepts = []; 

					if(response.hasOwnProperty('results')) {

						for(let i=0; i < response.results.bindings.length; i++) {

							var span = $('<span/>', {
								'class': 'badge badge-info',
								'style': 'margin-left: 4px; margin-right: 4px;',
								html: response.results.bindings[i].label.value,
							});

							concepts.push({
								concept: response.results.bindings[i].concept.value,
								conceptLabel: response.results.bindings[i].label.value,
								name: span.get(0).outerHTML,
							});

						}
					} 

				
					return process(concepts);
				});

			},
			autoSelect: true,
			minLength: 2,
			delay: 3,
			highlighter: function(hi){
				return hi;
			},
			matcher: function(ma){
				if(ma.hasOwnProperty('name')){
					return true;
				}
			},
			afterSelect: function(item) {
                      
				 $('.accordion .card #search').val(item.conceptLabel);

				 $('.accordion .card #append-result').append(item.name);
				 
			}

		});
		
	};
       
	return {

		init: function(){
			_setEvent();
		},
        }

})();
