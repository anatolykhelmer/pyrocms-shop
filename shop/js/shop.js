jQuery(function(){
    var counter = 2;
    jQuery('#add_value').click(function(){
        jQuery('.add_value').before('<li><label for="value3">value #3</label><input type="text" id="value3" name="value3"><li>');
        return false;
    });
});