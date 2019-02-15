	// Function for Timer send in GlslCanvas for Transition Stars->Planet
	var easing = 0;

	function timerJsToGlsl(easing, t, end,  u_name){
		setTimeout(function(){
			var timer_easing = setInterval(function(){
				easing += t;
				if(easing > end){
					clearInterval(timer_easing);
				};
				sandbox.setUniform(u_name,easing);
			}, 20);
		},2000);
	}

	timerJsToGlsl(easing,0.01, 0.9,"u_easing");