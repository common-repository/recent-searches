(function($) {
RecentSearches = {	
	init : function(){
		var t = this;

		$(".recent-searches a.delete").click( function(e){
			t.deleteSearch(e);
		});

		this.load = false
	},
	
	deleteSearch: function(e) {
		e.preventDefault();
		var t = this;
		
		if ( t.load )
			return;
		
		t.load = true;
		
		$(e.currentTarget).closest(".search-keyword").addClass("wait");
		
		$.post( recentsearches.ajaxurl, { 
			action: recentsearches.action, 
			keyword: $(e.currentTarget).attr("data-keyword"), 
			nonce: recentsearches.nonce }, function( data ) {
				$(e.currentTarget).closest(".search-keyword").remove();
				t.load = false;
			}
		);
	}
};

$(document).ready(function(){RecentSearches.init();});
})(jQuery);