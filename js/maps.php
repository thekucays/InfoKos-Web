<script src="http://maps.google.co.id/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript">
    var id = 'false';
    var center = null;
    var map = null;
    var currentPopup;
    var bujur = -6.975545089232602;
    var lintang = 107.6327373512039;
    var zoom = 17;
    function addMarker(data) {
        var pt = new google.maps.LatLng(data.bujur, data.lintang);
        var marker = new google.maps.Marker({
            id : data.id,
            position: pt,
            icon: 'maps/'+data.icon,
            map: map,
            title : data.nama,
            id_kost : data.id_kost
        });
                
        if($('#detail-info').val() == 'default'){
            var popup = new google.maps.InfoWindow({
                content: data.info,
                maxWidth: 400
            });

            google.maps.event.addListener(marker, "click", function() {
                map.setCenter(marker.getPosition());
                centerLocation(map.getCenter());
                
                if (currentPopup != null) {
                    currentPopup.close();
                    currentPopup = null;
                }
                popup.open(map, marker);
                currentPopup = popup;
            });
        
            google.maps.event.addListener(popup, "closeclick", function() {
                currentPopup = null;
            });
        }else{
            google.maps.event.addListener(marker, "click", function() {
                map.setCenter(marker.getPosition());
                centerLocation(map.getCenter());
                
                $('.pagination').remove();
                var tab1 = data.info;
                $('#tabs-1').html(tab1);
                $('#tabs-3').html('');
                $('.slides_container').html('');
                $.ajax({
                    url: "action/get_image.php?id="+marker.id,
                    dataType: 'json',
                    success: function(image){
                        var slider = '';
                        if(image['image']){
                            for(i=0;i<image['image'].length;i++){
                                slider = slider
                                    +'<div class="slide">'
                                    +'<a href="images/medium_'+image['image'][i].lokasi+'" target="_blank"><img src="images/medium_'+image['image'][i].lokasi+'" width="570" height="270"></a>'
                                    +'<div class="caption" style="bottom:0">'
                                    +'<p>'+image['image'][i].keterangan+'</p>'
                                    +'</div>'
                                    +'</div>';
                            }
                            if(image['image'].length > 0){
                                tab1 = '<img src="images/medium_'+image['image'][0].lokasi+'" style="width:230px;float:left;margin:0 20px 20px 0;"/>'+data.info+'<div style="clear:both"></div>';
                                $('#tabs-1').html(tab1);
                            }
                            
                            $('.slides_container').html(slider);
                                        
                            $(function(){
                                $('#slides').slides({
                                    preload: true,
                                    preloadImage: 'img/loading.gif',
                                    play: 5000,
                                    pause: 2500,
                                    hoverPause: true,
                                    animationStart: function(current){
                                        $('.caption').animate({
                                            bottom:-35
                                        },100);
                                    },
                                    animationComplete: function(current){
                                        $('.caption').animate({
                                            bottom:0
                                        },200);
                                    },
                                    slidesLoaded: function() {
                                        $('.caption').animate({
                                            bottom:0
                                        },200);
                                    }
                                });
                            });
                        }
                        $('#tabs-3').html(image['detail']);
                        
                    }
                });
                $(".hide").show();
            });            
        }
    }
            
    function initMap(id) { 
        map = new google.maps.Map(document.getElementById("maps"), {
            center: new google.maps.LatLng(bujur, lintang),
            zoom: zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
                
        google.maps.event.addListener(map, 'zoom_changed', function() {
            zoom = map.getZoom();
            centerLocation(map.getCenter());
        });

        google.maps.event.addListener(map, 'dragend', function() {
            centerLocation(map.getCenter());
        });
        
        getLocation(id);
    }
    
    function centerLocation(ctr){
        bujur = ctr.lat();
        lintang = ctr.lng();
    }
    
    function getLocation(id){
        if(typeof id == 'undefined'){
            id = 'false';
        }
        $.ajax({
            url: "action/get_location.php?id="+id,
            dataType: 'json',
            success: function(data){
                for(i=0;i<data.length;i++){
                    addMarker(data[i])
                }
            }
        });
    }
    
    $(document).ready(function(){
        $("#close-tabs").click(function(){
            $(".hide").hide();
        });
        
        $('#detail-info').change(function(){
            var center = map.getCenter();
            bujur = center.lat();
            lintang = center.lng();
            initMap(id);
        });
        
        $('.maps-icon').click(function(){
            id = $(this).attr('id');
            initMap(id);
        });
        
    });
</script>

<link type="text/css" rel="stylesheet" href="css/global.css"/>
<script src="js/slides.min.jquery.js" type="text/javascript"></script>
