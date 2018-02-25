<style>
    li.list-img{
        float:left;
        width:50%;
        height:64px;
        list-style: none; 
        vertical-align: middle;
    }
    li.list-img a{
        text-decoration: none;
        color: #000;
        cursor: pointer;
    }
    li.list-img a:hover{
        color: #5E9901;
        font-weight: bold;
    }
    div.hide{
        z-index: 999;
        width: 700px;
        height: auto;
        position: absolute;
        top: 500px;
        left: 200px;
        font-size: 12px;
        display: none;
    }
</style>
<div style="clear:both;height: 30px;"></div>
<div style="margin: 20px auto;width: 90%;height: 600px; border: 2px #5E9901 solid;border-radius: 10px;-moz-border-radius: 10px;-webkit-border-radius: 10px; background: #5E9901;box-shadow: 0 0 5px #000;">
    <div id="maps" style="float: left;width: 70%;height: 100%;border-bottom-left-radius: 10px;border-top-left-radius: 10px;-moz-border-bottom-left-radius: 10px;-moz-border-top-left-radius: 10px;-webkit-border-top-left-radius: 10px;-webkit-border-bottom-left-radius: 10px; background: #5E9901;"></div>
    <div style="float: right;width: 30%;height: 100%;border-bottom-right-radius: 10px;border-top-right-radius: 10px;-moz-border-bottom-right-radius: 10px;-moz-border-top-right-radius: 10px;-webkit-border-top-right-radius: 10px;-webkit-border-bottom-right-radius: 10px; background-color: #F4F3F0;">
        <div style="border-left: 3px #5E9901 solid;height: 100%">
            <h2 style="margin: 0 auto 10px auto;text-align: center;font-size: 20px;font-weight: bold">Keterangan</h2>
            <b style="padding-left:50px;font-size: 14px">Detail Info</b> 
            <select id="detail-info">
                <option value="default">Default</option>
                <option value="detail">Detail Info</option>
            </select>
            <div style="height: 85%;overflow-y: auto;">
                <ul style="margin: 10px">
                    <li class="list-img"><a class="maps-icon" id="false"><img src="maps/condominium.png"/>Semua Tempat</a></li>
                    <?php
                    $type = mysql_query('SELECT * FROM type_tempat ORDER BY nama');
                    while ($r = mysql_fetch_array($type)) {
                        echo "<li class='list-img'><a class='maps-icon' id='".$r[id]."'><img src='maps/$r[gambar]'/> $r[nama]</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div style="clear:both;height: 30px;"></div>

<div class="hide">
    <div class="tabs">
        <span style="position: absolute; height:20px;width: 20px;background-position: 50% 50%;background: url(images/cancel.png) no-repeat;;z-index: 1;right:5px;top: 10px;cursor: pointer;" id="close-tabs"></span>
        <ul>
            <li><a href="#tabs-1">Info</a></li>
            <li><a href="#tabs-2">Photo</a></li>
            <li><a href="#tabs-3">Detail</a></li>
        </ul>
        <div id="tabs-1"></div>
        <div id="tabs-2" style="height:320px">
            <div id="slides" style="margin:40px 0 10px 60px">
                <div class="slides_container">
                </div>
                <a href="#" class="prev"><img src="img/arrow-prev.png" width="24" height="43" alt="Arrow Prev"></a>
                <a href="#" class="next"><img src="img/arrow-next.png" width="24" height="43" alt="Arrow Next"></a>
            </div>

        </div>
        <div id="tabs-3"></div>
    </div>
</div>
