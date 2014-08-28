$(function() {

$('#support-form').hide();
$('#shade').hide();

$('#support-want').on('click',function(){
        $('#shade').show(200);
        $('#support-form').fadeIn(100);
        $("HTML").css({ overflow: 'hidden' });
        $('#support-want').hide();
        return false;
      });

$('#close-support-form').on('click',function(){
        $('#shade').hide();
        $('#support-form').hide();
        $("HTML").css({ overflow: 'visible' });
        $('#support-want').show();
        return false;
      });
}) 