            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Sub Third Kategori Produk</h3>
                  <a class='pull-right btn btn-primary btn-sm' href='<?php echo base_url(); ?>administrator/tambah_kategori_produk_sub_third'>Tambahkan Data</a>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-condensed">
                    <thead>
                      <tr>
                        <th style='width:30px'>No</th>
                        <th>Nama Kategori Sub</th>
                        <th>Nama Sub Third Kategori Produk</th>
                        <th>Icon Kode</th>
                        <th>Icon Image</th>
                        <th style='width:70px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record->result_array() as $row){
                    echo "<tr><td>$no</td>
                              <td style='color:green'>$row[nama_kategori_sub]</td>
                              <td>$row[nama_kategori_sub_third]</td>
                              <td>$row[icon_kode]</td>
                              <td>$row[icon_image]</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='".base_url()."administrator/edit_kategori_produk_sub_third/$row[id_kategori_produk_sub_third]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='".base_url()."administrator/delete_kategori_produk_sub_third/$row[id_kategori_produk_sub_third]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>