jQuery(document).ready(function(){  
    jQuery(".tab_menu > li").click(function(e){
        jQuery(".tab_menu > li").removeClass("active");
        jQuery("#"+e.target.id).addClass("active");
        jQuery(".tab_content > div.content").css("display", "none");
        jQuery("div."+e.target.id).fadeIn(); 
        return false;  
    });  
    
    $('.imgthumb > img').click(function() {
        $("a[rel='group1']").colorbox({open:true, transition:"fade"});
    });

});  
