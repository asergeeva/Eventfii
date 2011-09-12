$(function(){
    $('#tour img:gt(0)').hide();
    setInterval(function(){
      $('#tour figure :first-child').fadeOut()
         .next().fadeIn()
         .end().appendTo('#tour figure');}, 
      5000);
});