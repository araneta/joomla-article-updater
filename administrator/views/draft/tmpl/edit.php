<?php
/**
 * @version     1.0.0
 * @package     com_articleupdater
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      aldo praherda <aldopraherda@gmail.com> - http://ujianku.com
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_articleupdater/assets/css/articleupdater.css');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'draft.cancel' || document.formvalidator.isValid(document.id('draft-form'))) {
			Joomla.submitform(task, document.getElementById('draft-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
 
<form action="<?php echo JRoute::_('index.php?option=com_articleupdater&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="draft-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_ARTICLEUPDATER_LEGEND_DRAFT'); ?></legend>
			<ul class="adminformlist">

            
			<li><?php echo $this->form->getLabel('id'); ?>
			<?php echo $this->form->getInput('id'); ?></li>

            
			<li><?php //echo $this->form->getLabel('article_id'); ?>
			<?php //echo $this->form->getInput('article_id'); ?></li>

            <li><?php echo $this->form->getLabel('article_id'); ?>
            <?php echo $this->form->getInput('article_id'); ?></li>
            
			<li><?php echo $this->form->getLabel('content'); ?>
			<?php echo $this->form->getInput('content'); ?></li>

            
			<li><?php echo $this->form->getLabel('publish_date'); ?>
			<?php echo $this->form->getInput('publish_date'); ?></li>

            

            <li><?php echo $this->form->getLabel('checked_out'); ?>
                    <?php echo $this->form->getInput('checked_out'); ?></li><li><?php echo $this->form->getLabel('checked_out_time'); ?>
                    <?php echo $this->form->getInput('checked_out_time'); ?></li>

            </ul>
		</fieldset>
	</div>


	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
</form>
