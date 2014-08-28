$(function() {

$('#data-grid').hide();
//$('#data-graphs').hide();

$('#open-detailed-data').on('click',function(){
        $('#data-graphs').hide();
        $('#data-grid').show(200);
        return false;
      });

$('#close-detailed-data').on('click',function(){
        $('#data-grid').hide();
        $('#data-graphs').show(400);
        return false;
      });
}) 