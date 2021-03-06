<div class="ps-breadcrumb">
        <div class="ps-container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <?php 
                    if (cetak($this->input->get('s'))!=''){
                        echo "<li><a href='#'>Product</a></li>
                              <li>$title</li>";
                    }else{
                        if (isset($_POST['cari'])){
                            echo "<li>Product</li>";
                            echo "<li>$judul</li>";
                        }else{
                            echo "<li>Product</li>";
                        }
                    }
                ?>
                
            </ul>
        </div>
    </div>
    <div class="ps-page--shop" id="shop-sidebar">
        <div class="container">
            <div class="ps-layout--shop">
                <?php include "sidebar-produk.php"; ?>
                <div class="ps-layout__right">
                    <?php 
                        if (!isset($_GET['s'])){ 
                    ?>
                    <div class="ps-block--shop-features">
                    <?php echo $this->session->flashdata('message'); 
                        $this->session->unset_userdata('message'); ?>
                        <div class="ps-block__header">
                            <h3>Best Selling Product</h3>
                            <div class="ps-block__navigation"><a class="ps-carousel__prev" href="#bestsale"><i class="icon-chevron-left"></i></a><a class="ps-carousel__next" href="#bestsale"><i class="icon-chevron-right"></i></a></div>
                        </div>
                        <div class="ps-block__content">
                            <div class="owl-slider" id="bestsale" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="false" data-owl-dots="false" data-owl-item="4" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="2" data-owl-item-lg="3" data-owl-item-xl="4" data-owl-duration="1000" data-owl-mousedrag="on">
                                <?php 
                                    $produk_terlaris = $this->model_reseller->produk_terlaris(0,0,10);
                                    foreach ($produk_terlaris->result_array() as $row){
                                        $ex = explode(';', $row['gambar']);
                                        if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                                        if (strlen($row['nama_produk']) > 38){ $judul = substr($row['nama_produk'],0,38).',..';  }else{ $judul = $row['nama_produk']; }
                                        $jual = $this->model_reseller->jual_reseller($row['id_reseller'],$row['id_produk'])->row_array();
                                        $beli = $this->model_reseller->beli_reseller($row['id_reseller'],$row['id_produk'])->row_array();

                                        $disk = $this->model_app->view_where("rb_produk_diskon",array('id_produk'=>$row['id_produk']))->row_array();
                                        $diskon = rupiah(($disk['diskon']/$row['harga_konsumen'])*100,0)." %";

                                        if ($beli['beli']-$jual['jual']<=0){ 
                                            $stok = "<div class='ps-product__badge out-stock'>Sold Out</div>"; 
                                            $diskon_persen = ''; 
                                        }else{ 
                                            $stok = ""; 
                                            if ($diskon>0){ 
                                                $diskon_persen = "<div class='ps-product__badge'>$diskon</div>"; 
                                            }else{
                                                $diskon_persen = ''; 
                                            }
                                        }
                            
                                        if ($diskon>=1){ 
                                            $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon'])." <del>".rupiah($row['harga_konsumen'])."</del>";
                                            $base_price = $row['harga_konsumen'] - $disk['diskon'];
                                            //$del = " <del>".idr2hkd($row['harga_konsumen'],0)."</del>";
                                            $del = "";
                                        }else{
                                            $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                                            $base_price = $row['harga_konsumen'];
                                        }
                    
                                        if(cekmatauang() == "hkd"){
                                            $harga_produk = "HK$ ". idr2hkd($base_price,$row['harga_dollar']);
                                        }
                    
                                        $harga_produk .= $del;

                                        $sold = $this->model_reseller->produk_terjual($row['id_produk'],2);
                                        $persentase = ($sold->num_rows()/$beli['beli'])*100;
                                        $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                                        
                                        echo "<div class='ps-product'>
                                                <div class='ps-product__thumbnail'>
                                                <a href='".base_url()."asset/foto_produk/$foto_produk' class='progressive replace'><img class='preview' loading='lazy' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
                                                    $diskon_persen
                                                    $stok
                                                    <ul class='ps-product__actions produk-$row[id_produk]'>
                                                        <li><a href='".base_url()."produk/detail/$row[produk_seo]' data-toggle='tooltip' data-placement='top' title='Read More'><i class='icon-bag2'></i></a></li>
                                                        <li><a href='#' data-toggle='tooltip' data-placement='top' title='Quick View' class='quick_view' data-id='$row[id_produk]'><i class='icon-eye'></i></a></li>";
                                                        if ($cek_save>='1'){
                                                            echo "<li><a data-toggle='tooltip' data-placement='top' title='Add to Whishlist'><i style='color:red' class='icon-heart'></i></a></li>";
                                                        }else{
                                                            echo "<li><a data-toggle='tooltip' data-placement='top' id='save-$row[id_produk]' title='Add to Whishlist'><i class='icon-heart' onclick=\"save('$row[id_produk]',this.id)\"></i></a></li>";
                                                        }
                                                    echo "</ul>
                                                </div>
                                                <div class='ps-product__container'><a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>".cek_paket_icon($row['id_reseller'])." $row[nama_reseller]</a>
                                                    <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                                        <div class='ps-product__rating'>
                                                            <select class='ps-rating' data-read-only='true'>".rate_bintang($row['id_produk'])."</select><span>".rate_jumlah($row['id_produk'])."</span>
                                                        </div>
                                                        <p class='ps-product__price'>$harga_produk</p>
                                                    </div>
                                                        <div class='ps-product__content hover'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>";
                                                        if (stok($row['id_reseller'],$row['id_produk'])<=0){ 
                                                            echo "<a style='margin-top:10px; color:#a7a7a7; background-color:#e2e2e2' class='ps-btn ps-btn--fullwidth add-to-cart-empty'>Add to Cart</a>";
                                                        }else{
                                                            echo "<a style='margin-top:10px; color:#fff' id='$row[id_produk]' class='ps-btn ps-btn--fullwidth add-to-cart'>Add to Cart</a>";
                                                        }
                                                        echo "</div>
                                                </div>
                                            </div>";
                                    }
                                ?>

                            </div>
                        </div>
                    </div>
                    <?php 
                    }elseif (cetak($this->input->get('s'))!='' AND cetak($this->input->get('dari'))!=''){ 
                    if ($rekomendasi->num_rows()>=1){
                    ?>
                        <div class="ps-block--shop-features">
                            <div class="ps-block__header">
                                <h3>Rekomendasi Produk</h3>
                                <div class="ps-block__navigation"><a class="ps-carousel__prev" href="#bestsale"><i class="icon-chevron-left"></i></a><a class="ps-carousel__next" href="#bestsale"><i class="icon-chevron-right"></i></a></div>
                            </div>
                            <div class="ps-block__content">
                                <div class="owl-slider" id="bestsale" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="false" data-owl-dots="false" data-owl-item="4" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="2" data-owl-item-lg="3" data-owl-item-xl="4" data-owl-duration="1000" data-owl-mousedrag="on">
                                    <?php 
                                        foreach ($rekomendasi->result_array() as $row){
                                            $ex = explode(';', $row['gambar']);
                                            if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                                            if (strlen($row['nama_produk']) > 38){ $judul = substr($row['nama_produk'],0,38).',..';  }else{ $judul = $row['nama_produk']; }
                                            $jual = $this->model_reseller->jual_reseller($row['id_reseller'],$row['id_produk'])->row_array();
                                            $beli = $this->model_reseller->beli_reseller($row['id_reseller'],$row['id_produk'])->row_array();

                                            $disk = $this->model_app->view_where("rb_produk_diskon",array('id_produk'=>$row['id_produk']))->row_array();
                                            $diskon = rupiah(($disk['diskon']/$row['harga_konsumen'])*100,0)." %";

                                            if ($beli['beli']-$jual['jual']<=0){ 
                                                $stok = "<div class='ps-product__badge out-stock'>Habis Terjual</div>"; 
                                                $diskon_persen = ''; 
                                            }else{ 
                                                $stok = ""; 
                                                if ($diskon>0){ 
                                                    $diskon_persen = "<div class='ps-product__badge'>$diskon</div>"; 
                                                }else{
                                                    $diskon_persen = ''; 
                                                }
                                            }
                                
                                            if ($diskon>=1){ 
                                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon'])." <del>".rupiah($row['harga_konsumen'])."</del>";
                                                $base_price = $row['harga_konsumen'] - $disk['diskon'];
                                                //$del = " <del>".idr2hkd($row['harga_konsumen'],0)."</del>";
                                                $del = "";
                                            }else{
                                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                                                $base_price = $row['harga_konsumen'];
                                            }
                        
                                            if(cekmatauang() == "hkd"){
                                                $harga_produk = "HK$ ". idr2hkd($base_price,$row['harga_dollar']);
                                            }
                        
                                            $harga_produk .= $del;

                                            $sold = $this->model_reseller->produk_terjual($row['id_produk'],2);
                                            $persentase = ($sold->num_rows()/$beli['beli'])*100;
                                            $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                                            
                                            echo "<div class='ps-product'>
                                                    <div class='ps-product__thumbnail'>
                                                    <a href='".base_url()."asset/foto_produk/$foto_produk' class='progressive replace'><img class='preview' loading='lazy' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
                                                        $diskon_persen
                                                        $stok
                                                        <ul class='ps-product__actions produk-$row[id_produk]'>
                                                            <li><a href='".base_url()."produk/detail/$row[produk_seo]' data-toggle='tooltip' data-placement='top' title='Read More'><i class='icon-bag2'></i></a></li>
                                                            <li><a href='#' data-toggle='tooltip' data-placement='top' title='Quick View' class='quick_view' data-id='$row[id_produk]'><i class='icon-eye'></i></a></li>";
                                                            if ($cek_save>='1'){
                                                                echo "<li><a data-toggle='tooltip' data-placement='top' title='Add to Whishlist'><i style='color:red' class='icon-heart'></i></a></li>";
                                                            }else{
                                                                echo "<li><a data-toggle='tooltip' data-placement='top' id='save-$row[id_produk]' title='Add to Whishlist'><i class='icon-heart' onclick=\"save('$row[id_produk]',this.id)\"></i></a></li>";
                                                            }
                                                        echo "</ul>
                                                    </div>
                                                    <div class='ps-product__container'><a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>".cek_paket_icon($row['id_reseller'])." $row[nama_reseller]</a>
                                                        <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                                            <div class='ps-product__rating'>
                                                                <select class='ps-rating' data-read-only='true'>".rate_bintang($row['id_produk'])."</select><span>".rate_jumlah($row['id_produk'])."</span>
                                                            </div>
                                                            <p class='ps-product__price'>$harga_produk</p>
                                                        </div>
                                                            <div class='ps-product__content hover'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>";
                                                            if (stok($row['id_reseller'],$row['id_produk'])<=0){ 
                                                                echo "<a style='margin-top:10px; color:#a7a7a7; background-color:#e2e2e2' class='ps-btn ps-btn--fullwidth add-to-cart-empty'>Add to Cart</a>";
                                                            }else{
                                                                echo "<a style='margin-top:10px; color:#fff' id='$row[id_produk]' class='ps-btn ps-btn--fullwidth add-to-cart'>Add to Cart</a>";
                                                            }
                                                            echo "</div>
                                                    </div>
                                                </div>";
                                        }
                                    ?>

                                </div>
                            </div>
                        </div>
                    <?php } } ?>
                    <div class="ps-shopping ps-tab-root">
                        <div class="ps-shopping__header">
                            <p><strong><?php echo $jumlah; ?></strong> Products Shown</p>
                            <div class="ps-shopping__actions">
                            </div>
                        </div>
                        <div class="ps-tabs">
                            <div class="ps-tab active" id="tab-1">
                                <div class="ps-shopping-product">
                                    <?php 
                                        if ($jumlah=='0'){ 
                                            echo "<center>
                                            <img style='width:250px' src='".base_url()."asset/images/no-product.png'>
                                            <h3><br>Oops, Product Is Not Found</h3>
                                                  Try Other Keywords to Find The Product You Are Looking for...</center>";
                                        }
                                    ?>
                                    <div class="row">
                                    <?php 
                                        foreach ($record->result_array() as $row){
                                            $ex = explode(';', $row['gambar']);
                                            if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                                            if (strlen($row['nama_produk']) > 38){ $judul = substr($row['nama_produk'],0,38).',..';  }else{ $judul = $row['nama_produk']; }
                                            $jual = $this->model_reseller->jual_reseller($row['id_reseller'],$row['id_produk'])->row_array();
                                            $beli = $this->model_reseller->beli_reseller($row['id_reseller'],$row['id_produk'])->row_array();

                                            $disk = $this->model_app->view_where("rb_produk_diskon",array('id_produk'=>$row['id_produk']))->row_array();
                                            $diskon = rupiah(($disk['diskon']/$row['harga_konsumen'])*100,0)." %";

                                            if ($beli['beli']-$jual['jual']<=0){ 
                                                $stok = "<div class='ps-product__badge out-stock'>Sold Out</div>"; 
                                                $diskon_persen = ''; 
                                            }else{ 
                                                $stok = ""; 
                                                if ($diskon>0){ 
                                                    $diskon_persen = "<div class='ps-product__badge'>$diskon</div>"; 
                                                }else{
                                                    $diskon_persen = ''; 
                                                }
                                            }
                                
                                            if ($diskon>=1){ 
                                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon'])." <del>".rupiah($row['harga_konsumen'])."</del>";
                                                $base_price = $row['harga_konsumen'] - $disk['diskon'];
                                                //$del = " <del>".idr2hkd($row['harga_konsumen'],0)."</del>";
                                                $del = "";
                                            }else{
                                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                                                $base_price = $row['harga_konsumen'];
                                            }
                        
                                            if(cekmatauang() == "hkd"){
                                                $harga_produk = "HK$ ". idr2hkd($base_price,$row['harga_dollar']);
                                            }
                        
                                            $harga_produk .= $del;

                                            $sold = $this->model_reseller->produk_terjual($row['id_produk'],2);
                                            $persentase = ($sold->num_rows()/$beli['beli'])*100;
                                            $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();

                                            echo "<div class='col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 '>
                                                    <div class='ps-product'>
                                                        <div class='ps-product__thumbnail'><a href='".base_url()."asset/foto_produk/$foto_produk' class='progressive replace'><img class='preview' loading='lazy' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
                                                        $diskon_persen
                                                        $stok
                                                        <ul class='ps-product__actions produk-$row[id_produk]'>
                                                            <li><a href='".base_url()."produk/detail/$row[produk_seo]' data-toggle='tooltip' data-placement='top' title='Read More'><i class='icon-bag2'></i></a></li>
                                                            <li><a href='#' data-toggle='tooltip' data-placement='top' title='Quick View' class='quick_view' data-id='$row[id_produk]'><i class='icon-eye'></i></a></li>";
                                                            if ($cek_save>='1'){
                                                                echo "<li><a data-toggle='tooltip' data-placement='top' title='Add to Whishlist'><i style='color:red' class='icon-heart'></i></a></li>";
                                                            }else{
                                                                echo "<li><a data-toggle='tooltip' data-placement='top' id='save-$row[id_produk]' title='Add to Whishlist'><i class='icon-heart' onclick=\"save('$row[id_produk]',this.id)\"></i></a></li>";
                                                            }
                                                        echo "</ul>
                                                        </div>
                                                        <div class='ps-product__container'><a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>".cek_paket_icon($row['id_reseller'])." $row[nama_reseller]</a>
                                                            <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                                                <p class='ps-product__price'>$harga_produk</p>
                                                            </div>
                                                            <div class='ps-product__content hover'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>";
                                                            if (stok($row['id_reseller'],$row['id_produk'])<=0){ 
                                                                echo "<a style='margin-top:10px; color:#a7a7a7; background-color:#e2e2e2' class='ps-btn ps-btn--fullwidth add-to-cart-empty'>Add to Cart</a>";
                                                            }else{
                                                                echo "<a style='margin-top:10px; color:#fff' id='$row[id_produk]' class='ps-btn ps-btn--fullwidth add-to-cart'>Add to Cart</a>";
                                                            }
                                                            echo "</div>
                                                        </div>
                                                    </div>
                                                </div>";
                                        }
                                    ?>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="ps-tab" id="tab-2">
                                <div class="ps-shopping-product">
                                <?php 
                                    foreach ($record->result_array() as $row){
                                        $ex = explode(';', $row['gambar']);
                                        if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                                        $judul = $row['nama_produk'];
                                        $jual = $this->model_reseller->jual_reseller($row['id_reseller'],$row['id_produk'])->row_array();
                                        $beli = $this->model_reseller->beli_reseller($row['id_reseller'],$row['id_produk'])->row_array();

                                        $disk = $this->model_app->view_where("rb_produk_diskon",array('id_produk'=>$row['id_produk']))->row_array();
                                        $diskon = rupiah(($disk['diskon']/$row['harga_konsumen'])*100,0)." %";

                                        if ($beli['beli']-$jual['jual']<=0){ 
                                            $stok = "<div class='ps-product__badge out-stock'>Sold Out</div>"; 
                                            $diskon_persen = ''; 
                                        }else{ 
                                            $stok = ""; 
                                            if ($diskon>0){ 
                                                $diskon_persen = "<div class='ps-product__badge'>$diskon</div>"; 
                                            }else{
                                                $diskon_persen = ''; 
                                            }
                                        }
                            
                                        if ($diskon>=1){ 
                                            $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon'])." <del>".rupiah($row['harga_konsumen'])."</del>";
                                            $base_price = $row['harga_konsumen'] - $disk['diskon'];
                                            //$del = " <del>".idr2hkd($row['harga_konsumen'],0)."</del>";
                                            $del = "";
                                        }else{
                                            $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                                            $base_price = $row['harga_konsumen'];
                                        }
                    
                                        if(cekmatauang() == "hkd"){
                                            $harga_produk = "HK$ ". idr2hkd($base_price,$row['harga_dollar']);
                                        }
                    
                                        $harga_produk .= $del;

                                        $sold = $this->model_reseller->produk_terjual($row['id_produk'],2);
                                        $persentase = ($sold->num_rows()/$beli['beli'])*100;
                                        $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                                        
                                        echo "<div class='ps-product ps-product--wide'>
                                            <div class='ps-product__thumbnail'><a href='".base_url()."asset/foto_produk/$foto_produk' class='progressive replace'><img class='preview' loading='lazy' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
                                            </div>
                                            <div class='ps-product__container'>
                                                <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                                    <p class='ps-product__vendor'>Penjual : <a href='".base_url()."u/".user_reseller($row['id_reseller'])."'>".cek_paket_icon($row['id_reseller'])." $row[nama_reseller]</a></p>
                                                    ".nl2br($row['tentang_produk'])."
                                                </div>
                                                <div class='ps-product__shopping'>
                                                    <p class='ps-product__price'>$harga_produk</p>";
                                                    if (stok($row['id_reseller'],$row['id_produk'])<=0){ 
                                                        echo "<a style='margin-top:10px; color:#a7a7a7; background-color:#e2e2e2' class='ps-btn ps-btn--fullwidth add-to-cart-empty'>Add to Cart</a>";
                                                    }else{
                                                        echo "<a style='margin-top:10px; color:#fff' id='$row[id_produk]' class='ps-btn ps-btn--fullwidth add-to-cart'>Add to Cart</a>";
                                                    }
                                                    echo "<ul class='ps-product__actions'>";
                                                        if ($cek_save>='1'){
                                                            echo "<li><a style='cursor:pointer'><i style='color:red' class='icon-heart'></i> Wishlist</a></li>";
                                                        }else{
                                                            echo "<li><a style='cursor:pointer' id='save-$row[id_produk]' onclick=\"save('$row[id_produk]',this.id)\"><i class='icon-heart'></i> Wishlist</a></li>";
                                                        }
                                                        echo "<li><a href='' class='quick_view' data-id='$row[id_produk]'><i class='icon-eye'></i> Quick</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>";
                                    }
                                ?>

                                </div>
                                
                            </div>
                                <div class="ps-pagination">
                                    <?php echo $this->pagination->create_links(); ?>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
