<?php

$output = __("No active contests found.");
$output = <<<ROW_OUTPUT
<tr>
    <th scope = "row">
        <label for=""></label>
    </th>
    <td>
$output
    </td>
</tr>
ROW_OUTPUT;

$args = array(
    'meta_query' => array(
        array(
            'key' => 'developer-contest',
        )
    )
);
$my_secondary_loop = new \WP_Query($args);
if( $my_secondary_loop->have_posts() ) {
    $output = "";
    while ($my_secondary_loop->have_posts()){
        $my_secondary_loop->the_post();
        $postID = get_the_ID();
        $AdminRole = new \DeveloperContest\AdminRole;
        $adminActionButtons = $AdminRole->returnActionButtons($postID);
        $DateTimePicker = new \DeveloperContest\DateTimePicker();
        $DtPOutput = $DateTimePicker->returnDateTimePickerUI($postID);
        $output = $output . "<tr><th>$adminActionButtons</th><td>" . "<a href = '/wp-admin/post.php?post=" . $postID . "&action=edit' target = '_blank'>"  . get_the_title() . "</a><td>$DtPOutput</td>  </tr></td>"; // your custom-post-type post's title
    }
}
wp_reset_postdata();
echo $output;