/**
 * Created by ukito on 16/01/11.
 */
$(document).ready(function(){
    //jqueryValidationPluginを使用する（http://jqueryvalidation.org/documentation/）
    $("#buy").validate({
        onkeyup: false,
        rules: {
            cn: {
                required: true,
                digits: true,
                credit_card: true
            },
            ed: {
                required: true,
                digits: true,
                rangelength: [4,4]
            }
        }
    });
});

