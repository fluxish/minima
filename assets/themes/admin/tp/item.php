<h1><?php echo $title ?></h1>

<div class="reduced">
<table class="item ui-border-radius">
    <tbody>
        <?php foreach($item['fields'] as $field=>$field_title): ?>
        <tr>
            <th><?php echo $field_title ?></th>
            <td><?php echo $item['data'][$field] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
                <ul class="menu h_basic_menu">
                    <li>
                        <?php echo anchor(abs(array('controller'=>$this->input->get('controller'),'action'=>'delete','id'=>$item['data']['id'],'tab'=>$this->input->get('tab'))), lang('form_remove'), lang('form_remove'), 
                        array('class'=>'item confirm-link', 'onclick'=>'javascript:return confirm(\''.lang($this->input->get('controller').'_delete_confirm').'\')')); ?>
                    </li>
                </ul>
            </td>
        </tr>
    </tfoot>
</table>
</div>


<div class="reduced">
<?php if(isset($sections)) foreach($sections as $section_name=>$section): ?>
<?php if(is_array($section['data'])): ?>
<h2><?php echo $section['title'] ?></h2>
<table class="items">
    <thead>
        <div><tr>
            <!--<th></th>-->
            <?php foreach($section['fields'] as $field=>$field_title): ?>
            
            <?php if (isset($section['sorter']['current_field']) && $field == $section['sorter']['current_field']): ?>
            <th class="sort-<?php echo $section['sorter']['current_order'] ?>">
            <?php else: ?>
            <th>
            <?php endif; ?>
            
            <?php echo anchor($section['sorter']['fields'][$field], '<span>'.$field_title.'</span><span class="sorter-indicator"></span>', $field_title) ?></th>
            <?php echo "\n"; endforeach; unset($section['fields'][$section['field_show']]) ?>
            <th></th>
        </tr></div>
    </thead>
    <tbody>
        <?php if(empty($section['data'])): ?>
        <tr><td class="ui-border-radius-bottom no-items" colspan="<?php echo count($section['fields'])+2?>">
            <?php echo lang('list_no_items') ?>
        </td></tr>
        <?php else: ?>
        <?php foreach($section['data'] as $section_item): ?>   
        <tr>
            <td><?php echo anchor(abs(array('controller'=>$section_name,'action'=>'show','id'=>$section_item['id'])), 
                $section_item[$section['field_show']], lang('form_show')) ?></td>
            <?php foreach($section['fields'] as $field=>$field_title): ?>   
            <td><?php echo $section_item[$field] ?></td>
            <?php endforeach;?>
            
            <td><?php echo anchor(abs(array('controller'=>$this->input->get('controller'),'action'=>'remove_'.$section_name,'id'=>$section_item['id'],'target'=>$item['data']['id'],'tab'=>$this->input->get('tab'))), lang('form_remove'), lang('form_remove'), 
                        array('class'=>'item confirm-link floatRight', 'onclick'=>'javascript:return confirm(\''
                        .lang($this->input->get('controller').'_remove_'.$section_name.'_confirm').'\')')); ?></td>
        </tr>
        <?php echo "\n"; endforeach; endif; ?>
        <tfoot>
            <tr><th class="ui-border-radius-bottom" colspan="<?php echo count($section['fields'])+2?>">&nbsp;</th></tr>
        </tfoot>
    </tbody>
</table>

<?php else: ?>
<table class="item" class="ui-border-radius">
    <tbody>
        <?php foreach($section['fields'] as $field=>$field_title): ?>
        <tr>
            <th><?php echo $field_title ?></th>
            <td><?php echo $section['data'][$name] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
                <ul class="menu h_basic_menu">
                    <li>
                        <?php echo anchor(abs($this->input->get('controller').'/delete/id/'.$section['data']['id']), lang('form_remove'), lang('form_remove'), 
                        array('class'=>'item confirm-link', 'onclick'=>'javascript:return confirm(\''.lang($this->input->get('controller').'_delete_confirm').'\')')); ?>
                    </li>
                </ul>
            </td>
        </tr>
    </tfoot>
</table>

<?php endif; ?>
<?php endforeach; ?>

<?php echo @$place_sections ?>

</div>

