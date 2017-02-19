<?php
/**
 * Default Easy Age Verifier Template
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */
?>
<div id='taseav-age-verify' class='<?php echo $this->wrapperClass; ?>'>
  <?php echo $this->beforeForm; ?>
  <form class='<?php echo $this->formClass; ?>'>
    <h2><?php echo $this->formTitle ?></h2>
    <?php
    //If the settings call to enter the age, do this
    if($this->formType == 'eav_enter_age'): ?>
      <?php echo $this->beforeMonth; ?>
      <div class='<?php echo $this->monthClass; ?>'>
        <label for="month">Month</label>
        <input tabindex="1" title="month" name='month' type='number' min='1' max='12' required>
      </div>
      <?php echo $this->beforeDay; ?>
      <div class='<?php echo $this->dayClass; ?>'>
        <label for="day">Day</label>
        <input tabindex="2" title="day" name='day' type='number' min='1' max='31' required>
      </div>
      <?php echo $this->beforeYear; ?>
      <div class='<?php echo $this->yearClass; ?>'>
        <label for="year">Year</label>
        <input tabindex="3" title="year" name='year' type='number' min='<?php echo $this->minYear; ?>'
               max='<?php echo date("Y"); ?>' required>
      </div>
      <?php echo $this->beforeButton; ?>
      <input tabindex="4" type='submit' value='<?php echo $this->buttonValue; ?>'>
    <?php endif; ?>
    <?php if($this->formType == 'eav_confirm_age'): ?>
      <input name='overAge' type='submit' value='<?php echo $this->overAge; ?>'>
      <input name='underAge' type='submit' value='<?php echo $this->underAge; ?>'>
    <?php endif; ?>
  </form>
  <?php $this->afterForm; ?>
</div>
