<h1><?php echo $title ?></h1>
<?php echo form_open(url($action)) ?>
    <table class="items">
        <thead>
            <div><tr>
                <th style="width:10px"></th>
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
            <?php if(empty($list)): ?>
            <tr><td class="ui-border-radius-bottom no-items" colspan="<?php echo count($fields)+2?>">
                <?php echo lang('list_no_items') ?>
            </td></tr>
            <?php else: ?>
            <?php foreach($list as $item): ?>   
            <tr>
                <td><?php echo form_checkbox('ids[]',$item['id'],'') ?></td>
                <td><?php echo anchor(url($this->input->get('controller').'/show/id/'.$item['id']), $item[$field_show], lang('form_show')) ?></td>
                <?php foreach($fields as $f=>$f_name): ?>   
                <td><?php echo $item[$f] ?></td>
                <?php endforeach;?>
            </tr>
            <?php echo "\n"; endforeach; endif; ?>
            <tfoot>
                <tr><th class="ui-border-radius-bottom" colspan="<?php echo count($fields)+2?>">&nbsp;</th></tr>
            </tfoot>
        </tbody>
    </table>
    <br/>
    <ul>
        <li>
            <?php echo form_hidden('target', $target) ?>
            <?php echo form_hidden('tab', $this->input->get('tab')) ?>
            <?php echo form_submit('submit', lang('form_submit')) ?>
            <?php echo anchor(url($cancel), lang('form_cancel'), lang('form_cancel')) ?>
        </li>
    </ul>
</form>

