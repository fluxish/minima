<!--
See http://developer.yahoo.com/ypatterns/pattern.php?pattern=searchpagination
-->

<?php $this->language->load('paginator'); ?>


<?php if($paginator): ?>
<div id="paginator_container">
    <ul class="simple-squared-pagination">
    
        <!-- First page link -->
        <?php if(isset($paginator['first'])): ?>
            <li class="first"><?php echo anchor($paginator['first'], lang('paginator_first')); ?></li>
        <?php else: ?>
            <li class="first"><span class="disabled"><?php echo lang('paginator_first') ?></span></li>
        <?php endif; ?>
        
        <!-- Previous page link -->
        <?php if(isset($paginator['previous'])): ?>
            <li class="previous"><?php echo anchor($paginator['previous'], lang('paginator_previous')); ?></li>
        <?php else: ?>
            <li class="previous"><span class="disabled"><?php echo lang('paginator_previous') ?></span></li>
        <?php endif; ?>

        <!-- Numbered page links -->
        
        <?php foreach($paginator['pages'] as $key=>$url): ?>
            <?php if ($key != $paginator['current']): ?>
            <li class="page"><?php echo anchor($url, $key); ?></li>
            <?php else: ?>
            <li class="active"><?php echo $key; ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
        
        
        <!-- Next page link -->
        <?php if(isset($paginator['next'])): ?>
            <li class="next"><?php echo anchor($paginator['next'], lang('paginator_next')); ?></li>
        <?php else: ?>
            <li class="next"><span class="disabled"><?php echo lang('paginator_next') ?></span></li>
        <?php endif; ?>
        
        <!-- Last page link -->
        <?php if(isset($paginator['last'])): ?>
            <li class="last"><?php echo anchor($paginator['last'], lang('paginator_last')); ?></li>
        <?php else: ?>
            <li class="last"><span class="disabled"><?php echo lang('paginator_last') ?></span></li>
        <?php endif; ?>
    
    </ul>
</div>
<?php endif; ?>
