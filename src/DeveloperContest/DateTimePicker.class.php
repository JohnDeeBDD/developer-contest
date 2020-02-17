<?php

namespace DeveloperContest;

class DateTimePicker{

        public function returnDateTimePickerUI($postID){
            $output = <<<OUTPUT
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#date-$postID').datepicker({
            dateFormat: 'dd-mm-yy'
        });
        jQuery(".dateTimeUpdate").click(function(){
           jQuery("#developer-contest-form").submit(); // Submit
        })
    });
</script>
DATE: <input id='date-$postID' name='date' /> TIME: <input id = 'time-$postID' name = 'time' /> <input type = "button" value = "Update" class = "dateTimeUpdate" />
OUTPUT;
        return $output;
        }
}