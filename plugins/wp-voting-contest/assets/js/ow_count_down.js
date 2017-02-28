/************* Ohio web tech ************/
(function($){

	$.fn.ow_vote_countDown = function (options) {
		config = {};

		$.extend(config, options);

		diffSecs = this.setCountDown(config);
		if (config.onComplete)
		{
			$.data($(this)[0], 'callback', config.onComplete);
		}
		if (config.omitWeeks)
		{
			$.data($(this)[0], 'omitWeeks', config.omitWeeks);
		}

		$('#' + $(this).attr('id') + ' .digit').html('<div class="top"></div><div class="bottom"></div>');
		$(this).votingdoCountDown($(this).attr('id'), diffSecs, 500);

		return this;

	};

	$.fn.stopCountDown = function () {
		clearTimeout($.data(this[0], 'timer'));
	};

	$.fn.startCountDown = function () {
		this.votingdoCountDown($(this).attr('id'),$.data(this[0], 'diffSecs'), 500);
	};

	$.fn.setCountDown = function (options) {
		var targetTime = new Date();
		if (options.targetDate)
		{
			targetTime = new Date(options.targetDate.month + '/' + options.targetDate.day + '/' + options.targetDate.year + ' ' + options.targetDate.hour + ':' + options.targetDate.min + ':' + options.targetDate.sec + (options.targetDate.utc ? ' UTC' : ''));
		}
		else if (options.targetOffset)
		{
			targetTime.setFullYear(options.targetOffset.year + targetTime.getFullYear());
			targetTime.setMonth(options.targetOffset.month + targetTime.getMonth());
			targetTime.setDate(options.targetOffset.day + targetTime.getDate());
			targetTime.setHours(options.targetOffset.hour + targetTime.getHours());
			targetTime.setMinutes(options.targetOffset.min + targetTime.getMinutes());
			targetTime.setSeconds(options.targetOffset.sec + targetTime.getSeconds());
		}

		//var nowTime = new Date();
		
		//var nowTime = servDate;
		var nowTime = new Date();
		
		if (options.currentDate){
			var nowTime = new Date(options.currentDate.month + '/' + options.currentDate.day + '/' + options.currentDate.year + ' ' + options.currentDate.hour + ':' + options.currentDate.min + ':' + options.currentDate.sec + (options.currentDate.utc ? ' UTC' : ''));
		}else if (options.currentOffset)
		{
			nowTime.setFullYear(options.currentOffset.year + nowTime.getFullYear());
			nowTime.setMonth(options.currentOffset.month + nowTime.getMonth());
			nowTime.setDate(options.currentOffset.day + nowTime.getDate());
			nowTime.setHours(options.currentOffset.hour + nowTime.getHours());
			nowTime.setMinutes(options.currentOffset.min + nowTime.getMinutes());
			nowTime.setSeconds(options.currentOffset.sec + nowTime.getSeconds());
		}
		
		diffSecs = Math.floor((targetTime.valueOf()-nowTime.valueOf())/1000);
		$.data(this[0], 'diffSecs', diffSecs);

		return diffSecs;
	};

	$.fn.votingdoCountDown = function (id, diffSecs, duration) {
		$this = $('#' + id);
		if (diffSecs <= 0)
		{
			diffSecs = 0;
			if ($.data($this[0], 'timer'))
			{
				clearTimeout($.data($this[0], 'timer'));
			}
		}

		secs = diffSecs % 60;
		mins = Math.floor(diffSecs/60)%60;
		hours = Math.floor(diffSecs/60/60)%24;
		if ($.data($this[0], 'omitWeeks') == true)
		{
			days = Math.floor(diffSecs/60/60/24);
			weeks = Math.floor(diffSecs/60/60/24/7);
		}
		else 
		{
			days = Math.floor(diffSecs/60/60/24)%7;
			weeks = Math.floor(diffSecs/60/60/24/7);
		}

		$this.votingdashChangeTo(id, 'seconds_dash', secs, duration ? duration : 800);
		$this.votingdashChangeTo(id, 'minutes_dash', mins, duration ? duration : 1200);
		$this.votingdashChangeTo(id, 'hours_dash', hours, duration ? duration : 1200);
		$this.votingdashChangeTo(id, 'days_dash', days, duration ? duration : 1200);
		$this.votingdashChangeTo(id, 'weeks_dash', weeks, duration ? duration : 1200);

		$.data($this[0], 'diffSecs', diffSecs);
		if (diffSecs > 0)
		{
			e = $this;
			t = setTimeout(function() { e.votingdoCountDown(id, diffSecs-1) } , 1000);
			$.data(e[0], 'timer', t);
		} 
		else if (cb = $.data($this[0], 'callback')) 
		{
			$.data($this[0], 'callback')();
		}

	};

	$.fn.votingdashChangeTo = function(id, dash, n, duration) {
		  $this = $('#' + id);
		 
		  for (var i=($this.find('.' + dash + ' .digit').length-1); i>=0; i--)
		  {
				var d = n%10;
				n = (n - d) / 10;
				$this.digitChangeTo('#' + $this.attr('id') + ' .' + dash + ' .digit:eq('+i+')', d, duration);
		  }
	};

	$.fn.digitChangeTo = function (digit, n, duration) {
		if (!duration)
		{
			duration = 800;
		}
		if ($(digit + ' div.top').html() != n + '')
		{

			$(digit + ' div.top').css({'display': 'none'});
			$(digit + ' div.top').html((n ? n : '0')).slideDown(duration);

			$(digit + ' div.bottom').animate({'height': ''}, duration, function() {
				$(digit + ' div.bottom').html($(digit + ' div.top').html());
				$(digit + ' div.bottom').css({'display': 'block', 'height': ''});
				$(digit + ' div.top').hide().slideUp(10);

			
			});
		}
	};

})(jQuery);

/*!
 * jQuery Countdown plugin v1.0
 * http://www.littlewebthings.com/projects/countdown/
 *
 * 
 */
