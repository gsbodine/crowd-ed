<?php $tags = $item -> getTags(); ?>
        <input type="hidden" name="tags-to-delete" size="30" id="tags-to-delete" value="" />
        <div id="add-tags">
            <h5><?php echo __('Add Tags'); ?></h5>           
            <input type="text" name="tags" size="20" id="tags" class="textinput" value="" />
            <input type="button" name="add-tags-button" id="add-tags-button" value="<?php echo __('Add Tags'); ?>" />
            <p id="add-tags-explanation"><?php echo __('Separate tags with %s', settings('tag_delimiter')); ?></p>
        </div>
        <?php if ($tags): ?>
            <div id="tags">
                <h5><?php echo __('Current Tags'); ?></h5>
                <ul id="tags-list">
                    <?php foreach( $tags as $tag ): ?>
                        <li>
                            <?php if (has_permission('Items','untagOthers')): ?>
                                <?php echo __v()->formImage('undo-remove-tag-' . $tag->id, 
                                                            $tag->name,
                                                            array(
                                                                'src'   => img('silk-icons/add.png'),
                                                                'class' => 'undo_remove_tag')); 
                                      echo __v()->formImage('remove-tag-' . $tag->id,
                                                            $tag->name,
                                                            array(
                                                                'src'   => img('silk-icons/delete.png'),
                                                                'class' => 'remove_tag')); ?>
                            <?php endif; ?>
                            <?php echo html_escape($tag->name); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
   