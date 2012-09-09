<?php

/*
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

    head(); 
?>

<h1>Items to Review</h1>
<div id="primary">
    <?php echo flash(); ?>
    <?php // echo total_results(); ?>
    
    <div id="browse-meta" class="group">
        <div id="browse-meta-lists">
            <ul id="items-sort" class="navigation">
                <li><strong><?php echo __('Quick Filter'); ?></strong></li>
            <?php
                echo nav(array(
                    __('All') => uri('items'),
                    __('Public') => uri('items/browse?public=1'),
                    __('Private') => uri('items/browse?public=0'),
                    __('Featured') => uri('items/browse?featured=1')
                ));
            ?>
            </ul>
    </div>

<form id="items-browse" action="<?php echo html_escape(uri('items/batch-edit')); ?>" method="post" accept-charset="utf-8">
    <div class="group">
    <?php if (has_permission('Items', 'edit')): ?>
        <div class="batch-edit-option">
            <input type="submit" class="submit" name="submit" value="<?php echo __('Edit Selected Items'); ?>" />
        </div>
    <?php endif; ?>
        <div class="pagination"><?php echo pagination_links(); ?></div>
    </div>
    <table id="items" class="simple" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <?php if (has_permission('Items', 'edit')): ?>
                <th id="batch-edit-heading"><?php echo __('Select'); ?></th>
                <?php endif; ?>
            <?php
            $browseHeadings[__('Title')] = 'Dublin Core,Title';
            $browseHeadings[__('Creator')] = 'Dublin Core,Creator';
            $browseHeadings[__('Type')] = null;
            $browseHeadings[__('Public')]  = 'public';
            $browseHeadings[__('Featured')] = 'featured';
            $browseHeadings[__('Date Added')] = 'added';
            echo browse_headings($browseHeadings); ?>
            </tr>
        </thead>
        <tbody>
    <?php $key = 0; ?>
    <?php while($item = loop_items()): ?>
    <tr class="item <?php if(++$key%2==1) echo 'odd'; else echo 'even'; ?>">
        <?php $id = item('id'); ?>
        <?php if (has_permission($item, 'edit') || has_permission($item, 'tag')): ?>
        <td class="batch-edit-check" scope="row"><input type="checkbox" name="items[]" value="<?php echo $id; ?>" /></td>
        <?php endif; ?>
        <td class="item-info">
            <span class="title"><?php echo link_to_item(); ?></span>
            <ul class="action-links group">
                <?php if (has_permission($item, 'edit')): ?>
                <li><?php echo link_to_item(__('Edit'), array(), 'edit'); ?></li>
                <?php endif; ?>
                <?php if (has_permission($item, 'delete')): ?>
                <li><?php echo link_to_item(__('Delete'), array('class' => 'delete-confirm'), 'delete-confirm'); ?></li>
                <?php endif; ?>
            </ul>
            <?php fire_plugin_hook('admin_append_to_items_browse_simple_each'); ?>
            <div class="item-details">
                <?php
                if (item_has_thumbnail()) {
                    echo link_to_item(item_square_thumbnail(), array('class'=>'square-thumbnail'));
                }
                ?>
                <?php echo snippet_by_word_count(strip_formatting(item('Dublin Core', 'Description')), 40); ?>
                <ul>
                    <li><strong><?php echo __('Collection'); ?>:</strong> <?php if (item_belongs_to_collection()) echo link_to_collection_for_item(); else echo __('No Collection'); ?></li>
                    <li><strong><?php echo __('Tags'); ?>:</strong> <?php if ($tags = item_tags_as_string()) echo $tags; else echo __('No Tags'); ?></li>
                </ul>
                <?php fire_plugin_hook('admin_append_to_items_browse_detailed_each'); ?>
            </div>
        </td>
        <td><?php echo strip_formatting(item('Dublin Core', 'Creator')); ?></td>
        <td><?php echo ($typeName = item('Item Type Name'))
                    ? $typeName
                    : '<em>' . item('Dublin Core', 'Type', array('snippet' => 35)) . '</em>'; ?></td>
        <td>
        <?php if($item->public): ?>
        <img src="<?php echo img('silk-icons/tick.png'); ?>" alt="<?php echo __('Public'); ?>"/>
        <?php endif; ?>
        </td>
        <td>
        <?php if($item->featured): ?>
        <img src="<?php echo img('silk-icons/star.png'); ?>" alt="<?php echo __('Featured'); ?>"/>
        <?php endif; ?>
        </td>
        <td><?php echo format_date(item('Date Added')); ?></td>
    </tr>
    <?php endwhile; ?>

<?php foot(); ?>