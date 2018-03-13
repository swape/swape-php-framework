<?php include 'header.php'; ?>
<div class="ui inverted vertical masthead center aligned segment">
  <div class="ui text container">
  <h1>Swape PHP Framework</h1>
  </div>
</div>

<div class="ui vertical aligned segment">
  <div class="ui text container">

    <div class="ui message">
      <div class="header">
        template: /template/index.php
      </div>
      <p>myvar data from controller:

        <?php
        foreach ($data['myvar'] as $value) {
            echo "<div>${value['id']}: ${value['text']}</div>";
        }
        ?>

      </p>
    </div>

</div>
</div>
