$(function() {

$('#data-grid').hide();
//$('#data-graphs').hide();

$('#open-detailed-data').on('click',function(){
        $('#data-graphs').hide();
        $('#data-grid').show(200);
        $( document ).trigger( "appear-grid" );
        return false;
      });

$('#close-detailed-data').on('click',function(){
        $('#data-grid').hide();
        $('#data-graphs').show(400);
        return false;
      });
}) 