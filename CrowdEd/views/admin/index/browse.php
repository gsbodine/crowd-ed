<?php

  //  $head = array('bodyclass' => 'crowd-ed-admin primary', 'title' => html_escape(__('Crowd Ed Configuration')));
  //  head($head);
    head();
    $navArray = array();
    $navArray[__('Review Flagged Items')] = uri('crowd-ed/index/review');
    $navArray = apply_filters('admin_navigation_tags', $navArray);
?>

<h1>CrowdEd Plug-in</h1>
<h3>Crowdsourcing Metadata</h3>
<div>
    <ul id="section-nav" class="navigation">
    <?php echo nav($navArray); ?>
    </ul>
</div>

<?php foot(); ?>