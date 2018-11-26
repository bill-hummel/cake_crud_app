<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SectionsStudent $sectionsStudent
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Sections Students'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="sectionsStudents form large-9 medium-8 columns content">
    <?= $this->Form->create($sectionsStudent) ?>
    <fieldset>
        <legend><?= __('Add Sections Student') ?></legend>
        <?php
            //echo $this->Form->control('sectionid');
            echo $this->Form->control('sectionid', ['type'=>'select','options' => $sections]);
            echo $this->Form->control('studentid', ['type'=>'select','options' => $students]);

        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
