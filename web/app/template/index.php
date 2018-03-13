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
        Template: /web/app/template/index.php
      </div>
      <p>myvar data from controller in /web/app/controller/index.php:

        <?php
        if (isset($data['myvar']['error']) and $data['myvar']['error'][0] !== '00000') {
            echo '<pre>';
            print_r($data['myvar']['error']);
            echo '</pre>';
        } else {
            foreach ($data['myvar']['data'] as $value) {
                echo "<div>${value['id']}: ${value['text']}</div>";
            }
        }

        ?>

      </p>
    </div>

</div>
</div>
