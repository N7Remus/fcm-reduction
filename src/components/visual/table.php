<table class="table table-striped table-bordered" id="init">
  <thead>
    <tr>
      <th scope="col">Megnevezés</th>
      <?php
      foreach ($init_state as $key => $value) {
        echo '<th scope="col">' . $key . '</th>';
      }
      ?>
      
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">Kezdeti érték</th>
      <?php

      foreach ($init_state as $key => $value) {
      ?>
        <td><?= $value ?></td>
      <?php

      } /* <td>
      <svg style="width: 20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
        <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
      </svg>
    </td> */ ?>
      
    </tr>
  </tbody>
</table>
<hr>
<table class="table table-striped table-bordered" id="view">
  <thead>
    <tr>
      <th scope="col">#</th>
      <?php
      foreach ($init_state as $key => $value) {
        echo '<th scope="col">' . $key . '</th>';
      }

      ?>
    </tr>
  </thead>
  <tbody>
    <?php
    $is_keys = array_keys($init_state);
    foreach ($connection_matrix as $key => $value) {
    ?>
      <tr>
        <th scope="row"><?= $is_keys[$key] ?></th>
        <?php
        foreach ($connection_matrix[$key] as $k => $v) {
        ?>
          <td>
            <?= $v ?>
          </td>
        <?php
        } ?>
      </tr>
    <?php

    } ?>
  </tbody>
</table>

<hr>