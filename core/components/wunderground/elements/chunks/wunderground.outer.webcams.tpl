<script type="text/javascript">
         $('#slideshow').cycle({
          fx:      'fade',
          timeout:  0,
          prev:    '#prev',
          next:    '#next'
      });
</script>
<div class="webcams clearfix">
<div class="slide-nav">
     <a href="#" class="slide-nav-link" id="prev"><span>[[%prev? &namespace=`wunderground`]]</span></a> 
     &nbsp;|&nbsp; <a href="#" class="slide-nav-link" id="next"><span>[[%next? &namespace=`wunderground`]]</span></a>
</div><!--e.slide-nav-->
<div id="slideshow" class="pics">
  [[+content]]
</div>
</div>