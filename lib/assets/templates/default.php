<?php
/**
 * Default Easy Age Verifier Template
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */

$verifier = $_POST;
?>
<div id='taseav-age-verify' class='<?php echo $verifier['wrapperClass']; ?>'>
  <?php echo $verifier['beforeForm']; ?>
  <form class='<?php echo $verifier['formClass']; ?>'>
    <h2><?php echo $verifier['formTitle']?></h2>
    <?php
    //If the settings call to enter the age, do this
    if($verifier['formType'] == 'eav_enter_age'): ?>
      <?php echo $verifier['beforeMonth']; ?>
      <div class='<?php echo $verifier['monthClass']; ?>'>
        <label for="month">Month</label>
        <input title="month" name='month' type='number' min='1' max='12' required>
      </div>
      <?php echo $verifier['beforeDay']; ?>
      <div class='<?php echo $verifier['dayClass']; ?>'>
        <label for="day">Day</label>
        <input title="day" name='day' type='number' min='1' max='31' required>
      </div>
      <?php echo $verifier['beforeYear']; ?>
      <div class='<?php echo $verifier['yearClass']; ?>'>
        <label for="year">Year</label>
        <input title="year" name='year' type='number' min='<?php echo $verifier['minYear']; ?>' max='<?php echo date("Y"); ?>' required>
      </div>
      <?php echo $verifier['beforeButton']; ?>
      <input type='submit' value='<?php echo $verifier['buttonValue']; ?>'>
    <?php endif; ?>
  </form>
  <?php $verifier['afterForm']; ?>
</div>
