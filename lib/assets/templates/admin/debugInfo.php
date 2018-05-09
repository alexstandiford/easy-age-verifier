<strong>Basic Info:</strong>
<ul>
  <?php foreach($this->wpInfo as $info_type => $info): ?>
    <li><strong><?php echo $info_type; ?>:</strong> <?php echo $info; ?></li>
  <?php endforeach; ?>
  <li><strong>Browser Info: </strong> <?php echo $this->browserAgent; ?></li>
</ul>


<strong>Plugins: </strong>
<ul>
  <?php foreach($this->plugins as $path => $plugin): ?>
    <li><strong><?php echo $plugin['Name']; ?>:</strong> <?php echo $path; ?></li>
  <?php endforeach; ?>
</ul>


<strong>Basic Verifier Info: </strong>
<ul>
  <li><strong>Easy Age Verifier Version: </strong><?php echo $this->eavVersion; ?></li>
  <li><strong>Verifier CSS is accessible: </strong><?php echo $this->cssIsLoadable ? 'true' : 'false'; ?></li>
  <li><strong>Verifier JS is accessible: </strong><?php echo $this->scriptIsLoadable ? 'true' : 'false'; ?></li>
</ul>


<strong>Verifier Template Info: </strong>
<ul>
  <li><strong>Legacy Override Is Being Used: </strong><?php echo $this->verifier->hasLegacyOverride ? 'true' : 'false'; ?></li>
  <li><strong>Verifier Template Path: </strong><?php echo $this->verifier->templatePath ? $this->verifier->templatePath : 'false'; ?></li>
</ul>


<strong>Verification Info: </strong>
<ul>
  <li><strong>Minimum Age:</strong> <?php echo $this->verification->minAge; ?></li>
  <?php foreach($this->verification->getVerifications() as $verification => $result): ?>
    <?php if(is_array($result)): ?>
      <li><strong><?php echo $verification; ?></strong>
        <ul>

          <?php foreach($result as $sub_verification => $sub_result): ?>
            <li><strong><?php echo $sub_verification; ?>:</strong> <?php echo $sub_result ? 'true' : 'false'; ?></li>
          <?php endforeach; ?>
        </ul>
      </li>
    <?php else: ?>
      <li><strong><?php echo $verification; ?>:</strong> <?php echo $result ? 'true' : 'false'; ?></li>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>


<strong>Verifier Display Info: </strong>
<ul>
  <?php foreach($this->verifier->passData() as $data => $value): ?>
    <?php if(is_string($value) || is_bool($value)): ?>
      <li><strong><?php echo $data; ?>:</strong> <?php echo $value ? $value === true ? 'true' : htmlspecialchars($value) : 'false'; ?></li>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>