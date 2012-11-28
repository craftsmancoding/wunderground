<div class="more-forecast-container clearfix">
	<div class="more-forecast-header">
		[[+observation_location.full]]
	</div>
	<div class="location-info">
		<p>Today, here in [[+display_location.city]],[[+display_location.state]]</p>
	</div>
	<div class="forecast-content forecast-first">
		<div class="forecast-content-inner">
			<div class="forecast-title">Condition</div>
			<div class="thumb">
				<img src="[[+icon_url]]" alt="">
			</div>
			<div class="forecast-weather">
				[[+weather]]
			</div>
		</div>

		<div class="forecast-content-inner">
			<div class="forecast-title">Temperature</div>
			<div class="forecast-temp">
				[[+temp_f]]&#8457;([[+temp_c]]&#8451;)
			</div>
		</div>

		<div class="forecast-content-inner">
			<div class="forecast-title">Wind(km/h)</div>
			<div class="forecast-wind">
				[[+wind_mph]]
			</div>
			<div class="wind-direction">
				Direction: [[+wind_dir]]
			</div>
		</div>
		<div class="forecast-content-inner">
			<div class="forecast-title">Visibility</div>
			<div class="visibility">
				[[+visibility_km]] km.
			</div>
		</div>
		
	</div>
</div>