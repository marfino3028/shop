<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Margin PPOB</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart('administrator/edit_ppob_margin',$attributes); 
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$rows[id_ppob_margin]'>
                    <tr><th width='120px' scope='row'>Operator</th>    <td>
                    <select name='operator' class='form-control' id='operator' required>";
                    foreach($operator->data as $item){
                      if ($item->pembeliankategori_id=='1'){
                        if ($rows['id_ppob']==$item->id){
                          echo "<option>".$item->product_name."</option>";
                        }
                      }
                    }
                    echo "</select></td></tr>
                    <tr><th scope='row'>Produk</th>                 <td>
                    <select name='produk' class='form-control' id='produk' required>
                      <option value='$rows[harga]' selected>$rows[nama_ppob] (Rp ".rupiah($rows['harga']).")</option>
                    </select></td></tr>
                    <tr><th scope='row'>Jual (Rp)</th>    <td><input type='number' class='form-control' value='".($rows['margin']+$rows['harga'])."' name='margin'></td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Update</button>
                    <a href='#'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div>";