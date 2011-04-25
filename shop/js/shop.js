function add_value_textbox()
{
    jQuery(".add_value").live('click', function(){
        var options_counter = jQuery("ol#options").children("li").length;
        var counter = jQuery(this).parent().parent().children().length;
        var option_value = jQuery("ol.option_values li span").html();
        var html_to_paste = '<li><label for="value">' + option_value + ' #'
                            + counter + '</label><input type="text" name="option'
                            + options_counter + '_value[' + counter + ']"></li>';

        jQuery(this).parent().before(html_to_paste);
        return false;
    });
}

function add_option()
{
    jQuery("#add_option").click(function(){
        var counter = jQuery("ol#options").children("li").length + 1;
        var add_class = "";
        var option_val  = jQuery("ol.option_values li span").html();
        var option_name = jQuery("ol#options li span").html();

        if (counter%2 == 0) add_class = "class=even";
        var option_to_paste = "<li "+ add_class +" ><h3>Option No."
            + counter + "</h3><label for=option_name["
            + counter + "]>" + option_name + "</label><input type=text name=option_name["
            + counter + "]><ol class=option_values><li><label for=option"
            + counter +"_value[1]>"+ option_val +" #1</label><input type=text name=option"
            + counter + "_value[1]></li><li>[ <a href=# class =add_value>Add Value Textbox</a> ] | [ <a href=# class=delete_value>Delete Value Textbox</a> ]</li></ol></li>";


        jQuery("ol#options").append(option_to_paste);
        return false;
    });
}

function delete_option()
{
    jQuery("#delete_option").click(function(){
        if (jQuery("ol#options").children("li").length > 1) {
            jQuery("ol#options li").has("ol").last().remove();
        }
        return false;
    });
}

jQuery(function(){
    add_value_textbox();
    add_option();
    delete_option();
});