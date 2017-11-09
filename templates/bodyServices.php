<p>
  <kbd style="font-size:17px;"><kbd>
    <span class="badge" id="<?php echo $deta->metsin;?>">
      <b><?php echo $deta->method;?></b>
    </span>
    <code><?php echo $deta->servic;?> </code> 
  </kbd></kbd> 
  <span class="label label-info" style="float:right; font-size:12px ">
    <?php 
    echo '<code>Versión: '.$common->getTabsVersion($item).'</code> ';
    ?>
  </span>
  <br/><br/> <label>Parameter</label>
  <table class="table table-bordered">
    <tr>
      <th width="20%">Fiel</th>
      <th width="5%">Type</th>
      <th>Description</th>
    </tr>
    <?php 
    $parameter=$common->getTabsParameter($item);
    if(count($parameter)>0){
      foreach ($parameter as $value){
        ?>
        <tr>
          <td><?php  echo $value['param']; ?></td>
          <td><?php  echo $value['type'];?></td>
          <td><?php  echo $value['detalles'];?></td>
        </tr>
        <?php 
      }
    } ?>
  </table>
  <br/><label>Success 200</label>
  <table class="table table-bordered">
    <tr>
      <th width="20%">Fiel</th>
      <th width="5%">Type</th>
      <th>Description</th>
    </tr>
    <?php 
    $parameter=$common->getTabsParameterSuccess($item);
    foreach ($parameter as $value){
      ?>
      <tr>
        <td><?php  echo $value['param']; ?></td>
        <td><?php  echo $value['type'];?></td>
        <td><?php  echo $value['detalles'];?></td>
      </tr>
      <?php } ?>
    </table>
    <br/><label>Success-Response:</label>
    <code><br/>
      <kbd style="font-size:17px;"><kbd>
        <?php 
        $parameter=$common->getTabsParameterSuccessResponse($item);
        echo ($parameter->response);
        ?>
      </kbd></kbd><br/><br/>
    </code><br/>
    <br/><label>Error 4XX</label>
    <table class="table table-bordered">
      <tr>
        <th width="20%">Fiel</th>
        <th width="5%">Type</th>
        <th>Description</th>
      </tr>
      <?php 
      $parameter=$common->getTabsParameterError($item);
      foreach ($parameter as $value){
        ?>
        <tr>
          <td><?php  echo $value['param']; ?></td>
          <td><?php  echo $value['type'];?></td>
          <td><?php  echo $value['detalles'];?></td>
        </tr>
        <?php } ?>
      </table>
      <br/><label>Error-Response:</label>
      <code><br/>
        <kbd style="font-size:17px;"><kbd>
          <?php 
          $parameter=$common->getTabsParameterErrorResponse($item);
          echo ($parameter->response);
          ?>
        </kbd></kbd><br/><br/>
      </code><br/>
    </p>