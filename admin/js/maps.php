<script src="http://maps.google.co.id/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript">
    var center = null;
    var map = null;
    var currentPopup;
    var icon = '../maps/home.png';
    var tempat = '1';
    var bujur = -6.975545089232602;
    var lintang = 107.6327373512039;
    var markersArray = [];
    
    function addMarker(data,setCenter) {
        var pt = new google.maps.LatLng(data.bujur, data.lintang);
        var marker = new google.maps.Marker({
            id : data.id,
            position: pt,
            icon: '../maps/'+data.icon,
            map: map,
            title : data.nama
        });
        
        if(setCenter == true){
            map.setCenter(marker.getPosition());
        }
                
        var popup = new google.maps.InfoWindow({
            content: data.info,
            maxWidth: 400
        });

        google.maps.event.addListener(marker, "click", function() {
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
    }
            
    function initMap() { 
        map = new google.maps.Map(document.getElementById("maps"), {
            center: new google.maps.LatLng(bujur, lintang),
            zoom: 17,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        
        google.maps.event.addListener(map,'click',function(event){
            var center = map.getCenter();
            bujur = center.lat();
            lintang = center.lng();
            deleteOverlays();
            addLocation(event.latLng);
        });
        
        getLocation();
    }
    
    function deleteOverlays() {
      if (markersArray) {
        for (i in markersArray) {
          markersArray[i].setMap(null);
        }
        markersArray.length = 0;
      }
    }
    
    function initOnlyMap(data){
        return true;
    }
    
    function getLocation(){
        if($('#id_tempat').val()){
            $.ajax({
                url: "../action/get_location.php?id_tempat="+$('#id_tempat').val(),
                dataType: 'json',
                success: function(data){
                    var init = initOnlyMap(data);
                    if(init){
                        for(i=0;i<data.length;i++){
                            addMarker(data[i],true);
                        }
                    }
                }
            });
        }else{
            $.ajax({
                url: "../action/get_location.php?id=false",
                dataType: 'json',
                success: function(data){
                    for(i=0;i<data.length;i++){
                        addMarker(data[i])
                    }
                }
            });
        }
    }
    
    function addLocation(event){
        marker = new google.maps.Marker({
            position: event,
            map: map,
            icon: icon
        });
        
        markersArray.push(marker);
        
        $("#bujur").val(event.lat());
        $("#lintang").val(event.lng());
    }
    
    $(document).ready(function(){
        $('.check-maps').click(function(){
            icon = $(this).val();
            tempat = $(this).attr('data-id');
        });
        
        $('#simpan').click(function(){
            var act = 'input';
            if($('#id_tempat').val()){
                act = 'update';
            }
            if($('#nama').val() != ''){
                $.ajax({
                    url:'modul/tempat/aksi.php?act='+act+'&bujur='+$('#bujur').val()+'&lintang='+$('#lintang').val()+'&nama='+$('#nama').val()+'&keterangan='+$('#ket').val()+'&alamat='+$('#alamat').val()+'&kost_id='+$('#kost').val()+'&type_tempat_id='+tempat+'&id='+$('#id_tempat').val(),
                    success : function(data){
                        window.history.back();
                    }
                });
            }else{
                alert("Anda belum mengisikan Nama.");
            }
        });
    });
    
    function setIcon(id, gambar){
        icon = '../maps/'+gambar;
        tempat = id;
    }
</script>
