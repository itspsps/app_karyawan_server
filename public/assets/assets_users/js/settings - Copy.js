var JobieSettings = function(){	

    "use strict"
        
    
	// Theme Version
    var handleThemeVersion = function() {
        jQuery('.theme-btn').on('click',function(){
            jQuery('body').toggleClass('theme-dark');
        });
        jQuery('.theme-btn').on('click',function(){
            jQuery('.theme-btn').toggleClass('active');
        });
	}
    
    
    
	/* Function ============ */
	return {
		init:function(){
            handleThemeVersion();
		},

		load:function(){
			
		},
		
	}
	
}();

/* Document.ready Start */	
jQuery(document).ready(function() {
    'use strict';
	JobieSettings.init();
	
});
/* Document.ready END */

/* Window Load START */
jQuery(window).on('load',function () {
	'use strict'; 
	JobieSettings.load();
	
});