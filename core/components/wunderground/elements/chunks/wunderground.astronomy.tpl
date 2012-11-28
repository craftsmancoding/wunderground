<div class="astronomy clearfix">
  <div class="sun">
    <div class="astro-title">[[%sunrise? &namespace=`wunderground`]] / [[%sunset? &namespace=`wunderground`]]</div>
    <div class="astro-info">
      <div class="sunrise">
        <span class="rise"></span> [[+moon_phase.sunrise.hour]]:[[+moon_phase.sunrise.minute]]
      </div>
      <div class="sunset">
        <span class="set"></span> [[+moon_phase.sunset.hour]]:[[+moon_phase.sunset.minute]]
      </div>
    </div>
  </div>

   <div class="moon">
    <div class="astro-title">[[%moon? &namespace=`wunderground`]]</div>
    <div class="astro-info">
       <img src="http://www.wunderground.com/graphics/moonpictsnew/moon[[+moon_phase.ageOfMoon]].gif" alt="">
    </div>
  </div>

</div>