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
$FreelancerRole = new \DeveloperContest\Role_Freelancer();
if( $my_secondary_loop->have_posts() ) {
    $output = "";
    $SettingsPage = new \DeveloperContest\SettingsPage;
    while ($my_secondary_loop->have_posts()){
        $my_secondary_loop->the_post();
        $postID = get_the_ID();
        $meta_data = get_post_custom($postID);
        if (isset($meta_data['developer-contest-entry'][0])){
            if ($meta_data['developer-contest-entry'][0] != ""){
                continue;
            }
        }
        $output = $output . "<tr><th>" . $FreelancerRole->returnActionButtons($postID) . "</th><td>" . "<a href = '" . get_the_permalink() . "' target = '_blank'>"  . get_the_title() . "</a></tr></td>"; // your custom-post-type post's title
        $output = $output . ($SettingsPage->activeContestEntriesForCurrentUserTableRowsHTML($postID));
    }
}
wp_reset_postdata();
echo $output;