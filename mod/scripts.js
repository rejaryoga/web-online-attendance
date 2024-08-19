
    $(document).on('click', '.btn-modal', function(){
        $('#modal-location').modal();
        var latitude  = $(this).attr("data-latitude");
        var longitude = $(this).attr("data-longitude");
        var name = $('.employees_name').html();
        $(".modal-title-name").html(name);
        document.getElementById("iframe-map").innerHTML ='<iframe src="map.php?latitude='+latitude+'&longitude='+longitude+'&name='+name+'" frameborder="0" width="100%" height="400px" marginwidth="0" marginheight="0" scrolling="no">';
    });
    



