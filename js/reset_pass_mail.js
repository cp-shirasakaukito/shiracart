/**
 * Created by ukito on 16/01/06.
 */
$(document).ready(function(){
    $("#reset_pass_mail").validate({
            onkeyup: false,
            rules: {
                email: {
                    required: true,
                    email: true,
                    maxlength: 255
                }
            }
        }

    );
});
