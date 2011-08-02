<?php echo @$place_breadcrumbs ?>

<h1><?php echo $title ?></h1>

<table class="items">
    <thead>
        <div><tr>
            <!--<th></th>-->
            <?php foreach($fields as $field=>$title): ?>
            
            <?php if (isset($sorter['current_field']) && $field == $sorter['current_field']): ?>
            <th class="sort-<?php echo $sorter['current_order'] ?>">
            <?php else: ?>
            <th>
            <?php endif; ?>
            
            <?php echo anchor($sorter['fields'][$field], '<span>'.$title.'</span><span class="sorter-indicator"></span>', $title) ?></th>
            <?php echo "\n"; endforeach; unset($fields[$field_show]) ?>
            
        </tr></div>
    </thead>
    <tbody>
        
        
        <?php foreach($inners as $inner): ?>   
        <tr>
            <td><?php echo anchor(url(array('controller'=>$this->input->get('controller'), 
                'action'=>'explore', 'area'=>$inner['id'])), $inner[$field_show], $inner[$field_show],
                array('class'=>'icon-text areas-mini')) ?></td>
            <?php foreach($fields as $f=>$f_name): ?>   
            <td><?php echo $inner[$f] ?></span></td>
            <?php endforeach;?>
        </tr>
        <?php echo "\n"; endforeach; ?>
        
        
        <?php foreach($leaves as $leaf): ?>   
        <tr>
            <td><?php echo anchor(url(array('controller'=>$this->input->get('controller'), 
                'action'=>'show', 'id'=>$leaf['id'])), $leaf[$field_show], $leaf[$field_show],
                array('class'=>'icon-text risks-mini')) ?></td>
            <?php foreach($fields as $f=>$f_name): ?>   
            <td><?php echo $item[$f] ?></td>
            <?php endforeach;?>
        </tr>
        <?php echo "\n"; endforeach; ?>
        
        <?php if(empty($inners) && empty($leaves)): ?>
        <tr><td class="ui-border-radius-bottom no-items" colspan="<?php echo count($fields)+1?>">
            <?php echo lang('list_no_items') ?>
        </td></tr>
        <?php endif; ?>
        
        <tfoot>
            <tr><th class="ui-border-radius-bottom" colspan="<?php echo count($fields)+1?>">&nbsp;</th></tr>
        </tfoot>
    </tbody>
</table>
