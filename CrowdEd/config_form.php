<div class="field">
    <div class="two columns alpha">
        <label for="tos">Require Terms of Service?</label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation">Whether or not to include a Terms of Service agreement upon account creation.</p>
        <div class="input-block">
            <?php echo get_view()->formCheckbox('crowded_require_terms_of_service', true, 
            array('checked'=>(boolean)get_option('crowded_require_terms_of_service'))); ?>        
        </div>
    </div>
</div>
<div class="field">
    <div class="two columns alpha">
        <label for="tos">Terms of Service (Full Text)</label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation">The full Terms of Service as presented for the User's consent prior to account being created.</p>
        <div class="input-block">
            <?php echo get_view()->formTextarea('crowded_terms_of_service',get_option('crowded_terms_of_service')) ?>        
        </div>
    </div>
</div>