if(jQuery(function(a){function d(){var b={onclose:function(){c.removeClass("active").appendTo(document.body)}},d=a(mui.overlay("on",b));c.appendTo(d),setTimeout(function(){c.addClass("active")},20)}function e(){b.toggleClass("hide-sidedrawer")}var b=a("body"),c=a("#sidedrawer");a(".js-show-sidedrawer").on("click",d),a(".js-hide-sidedrawer").on("click",e);var f=a("strong",c);f.next().hide(),f.on("click",function(){a(this).next().slideToggle(200)})}),"undefined"===typeof jQuery)throw new Error("MDTimePicker: This plugin requires jQuery");+function(a){var b="mdtimepicker",c=120,d=90,e=360,f=30,g=6,h=[9,112,113,114,115,116,117,118,119,120,121,122,123],i=function(b,c){this.hour=b,this.minute=c,this.format=function(b,c){var d=this,e=(b.match(/h/g)||[]).length>1;return a.trim(b.replace(/(hh|h|mm|ss|tt|t)/g,function(a){switch(a.toLowerCase()){case"h":var b=d.getHour(!0);return c&&b<10?"0"+b:b;case"hh":return d.hour<10?"0"+d.hour:d.hour;case"mm":return d.minute<10?"0"+d.minute:d.minute;case"ss":return"00";case"t":return e?"":d.getT().toLowerCase();case"tt":return e?"":d.getT()}}))},this.setHour=function(a){this.hour=a},this.getHour=function(a){return a?0===this.hour||12===this.hour?12:this.hour%12:this.hour},this.invert=function(){this.setHour("AM"===this.getT()?this.getHour()+12:this.getHour()-12)},this.setMinutes=function(a){this.minute=a},this.getMinutes=function(){return this.minute},this.getT=function(){return this.hour<12?"AM":"PM"}},j=function(b,c){var d=this;this.visible=!1,this.activeView="hours",this.hTimeout=null,this.mTimeout=null,this.input=a(b),this.config=c,this.time=new i(0,0),this.selected=new i(0,0),this.timepicker={overlay:a('<div class="mdtimepicker hidden"></div>'),wrapper:a('<div class="mdtp__wrapper"></div>'),timeHolder:{wrapper:a('<section class="mdtp__time_holder"></section>'),hour:a('<span class="mdtp__time_h">12</span>'),dots:a('<span class="mdtp__timedots">:</span>'),minute:a('<span class="mdtp__time_m">00</span>'),am_pm:a('<span class="mdtp__ampm">AM</span>')},clockHolder:{wrapper:a('<section class="mdtp__clock_holder"></section>'),am:a('<span class="mdtp__am">AM</span>'),pm:a('<span class="mdtp__pm">PM</span>'),clock:{wrapper:a('<div class="mdtp__clock"></div>'),dot:a('<span class="mdtp__clock_dot"></span>'),hours:a('<div class="mdtp__hour_holder"></div>'),minutes:a('<div class="mdtp__minute_holder"></div>')},buttonsHolder:{wrapper:a('<div class="mdtp__buttons">'),btnOk:a('<span class="mdtp__button ok">Ok</span>'),btnCancel:a('<span class="mdtp__button cancel">Cancel</span>')}}};var e=d.timepicker;if(d.setup(e).appendTo("body"),e.clockHolder.am.click(function(){"AM"!==d.selected.getT()&&d.setT("am")}),e.clockHolder.pm.click(function(){"PM"!==d.selected.getT()&&d.setT("pm")}),e.timeHolder.hour.click(function(){"hours"!==d.activeView&&d.switchView("hours")}),e.timeHolder.minute.click(function(){"minutes"!==d.activeView&&d.switchView("minutes")}),e.clockHolder.buttonsHolder.btnOk.click(function(){d.setValue(d.selected);var b=d.getFormattedTime();d.input.trigger(a.Event("timechanged",{time:b.time,value:b.value})).trigger("onchange"),d.hide()}),e.clockHolder.buttonsHolder.btnCancel.click(function(){d.hide()}),d.input.on("keydown",function(a){return 13===a.keyCode&&d.show(),!(h.indexOf(a.which)<0&&d.config.readOnly)}).on("click",function(){d.show()}).prop("readonly",d.config.readOnly),""!==d.input.val()){var f=d.parseTime(d.input.val(),d.config.format);d.setValue(f)}else{var f=d.getSystemTime();d.time=new i(f.hour,f.minute)}d.resetSelected(),d.switchView(d.activeView)};j.prototype={constructor:j,setup:function(b){if("undefined"===typeof b)throw new Error("Expecting a value.");var h=this,i=b.overlay,j=b.wrapper,k=b.timeHolder,l=b.clockHolder;k.wrapper.append(k.hour).append(k.dots).append(k.minute).append(k.am_pm).appendTo(j);for(var m=0;m<12;m++){var n=m+1,o=(c+m*f)%e,p=a('<div class="mdtp__digit rotate-'+o+'" data-hour="'+n+'"><span>'+n+"</span></div>");p.find("span").click(function(){var b=parseInt(a(this).parent().data("hour")),c=h.selected.getT(),d=(b+("PM"===c||"AM"===c&&12===b?12:0))%24;h.setHour(d),h.switchView("minutes")}),l.clock.hours.append(p)}for(var m=0;m<60;m++){var q=m<10?"0"+m:m,o=(d+m*g)%e,r=a('<div class="mdtp__digit rotate-'+o+'" data-minute="'+m+'"></div>');m%5===0?r.addClass("marker").html("<span>"+q+"</span>"):r.html("<span></span>"),r.find("span").click(function(){h.setMinute(a(this).parent().data("minute"))}),l.clock.minutes.append(r)}switch(l.clock.wrapper.append(l.am).append(l.pm).append(l.clock.dot).append(l.clock.hours).append(l.clock.minutes).appendTo(l.wrapper),l.buttonsHolder.wrapper.append(l.buttonsHolder.btnCancel).append(l.buttonsHolder.btnOk).appendTo(l.wrapper),l.wrapper.appendTo(j),h.config.theme){case"red":case"blue":case"green":case"purple":case"indigo":case"teal":j.attr("data-theme",h.config.theme);break;default:j.attr("data-theme",a.fn.mdtimepicker.defaults.theme)}return j.appendTo(i),i},setHour:function(b){if("undefined"===typeof b)throw new Error("Expecting a value.");var c=this;this.selected.setHour(b),this.timepicker.timeHolder.hour.text(this.selected.getHour(!0)),this.timepicker.clockHolder.clock.hours.children("div").each(function(b,d){var e=a(d),f=e.data("hour");e[f===c.selected.getHour(!0)?"addClass":"removeClass"]("active")})},setMinute:function(b){if("undefined"===typeof b)throw new Error("Expecting a value.");this.selected.setMinutes(b),this.timepicker.timeHolder.minute.text(b<10?"0"+b:b),this.timepicker.clockHolder.clock.minutes.children("div").each(function(c,d){var e=a(d),f=e.data("minute");e[f===b?"addClass":"removeClass"]("active")})},setT:function(a){if("undefined"===typeof a)throw new Error("Expecting a value.");this.selected.getT()!==a.toUpperCase()&&this.selected.invert();var b=this.selected.getT();this.timepicker.timeHolder.am_pm.text(b),this.timepicker.clockHolder.am["AM"===b?"addClass":"removeClass"]("active"),this.timepicker.clockHolder.pm["PM"===b?"addClass":"removeClass"]("active")},setValue:function(a){if("undefined"===typeof a)throw new Error("Expecting a value.");var b="string"===typeof a?this.parseTime(a,this.config.format):a;this.time=new i(b.hour,b.minute);var c=this.getFormattedTime();this.input.val(c.value).attr("data-time",c.time).attr("value",c.value)},resetSelected:function(){this.setHour(this.time.hour),this.setMinute(this.time.minute),this.setT(this.time.getT())},getFormattedTime:function(){var a=this.time.format(this.config.timeFormat,!1),b=this.time.format(this.config.format,this.config.hourPadding);return{time:a,value:b}},getSystemTime:function(){var a=new Date;return new i(a.getHours(),a.getMinutes())},parseTime:function(a,b){var c=this,d="undefined"===typeof b?c.config.format:b,e=(d.match(/h/g)||[]).length,f=e>1,h=((d.match(/m/g)||[]).length,(d.match(/t/g)||[]).length),j=a.length,k=d.indexOf("h"),l=d.lastIndexOf("h"),m="",n="",o="";if(c.config.hourPadding||f)m=a.substr(k,2);else{var p=d.substring(k-1,k),q=d.substring(l+1,l+2);m=l===d.length-1?a.substring(a.indexOf(p,k-1)+1,j):0===k?a.substring(0,a.indexOf(q,k)):a.substring(a.indexOf(p,k-1)+1,a.indexOf(q,k+1))}d=d.replace(/(hh|h)/g,m);{var r=d.indexOf("m"),s=d.lastIndexOf("m"),t=d.indexOf("t"),u=d.substring(r-1,r);d.substring(s+1,s+2)}n=s===d.length-1?a.substring(a.indexOf(u,r-1)+1,j):0===r?a.substring(0,2):a.substr(r,2),o=f?parseInt(m)>11?h>1?"PM":"pm":h>1?"AM":"am":a.substr(t,2);var w="pm"===o.toLowerCase(),x=new i(parseInt(m),parseInt(n));return(w&&parseInt(m)<12||!w&&12===parseInt(m))&&x.invert(),x},switchView:function(a){var b=this,c=this.timepicker,d=350;("hours"===a||"minutes"===a)&&(b.activeView=a,c.timeHolder.hour["hours"===a?"addClass":"removeClass"]("active"),c.timeHolder.minute["hours"===a?"removeClass":"addClass"]("active"),c.clockHolder.clock.hours.addClass("animate"),"hours"===a&&c.clockHolder.clock.hours.removeClass("hidden"),clearTimeout(b.hTimeout),b.hTimeout=setTimeout(function(){"hours"!==a&&c.clockHolder.clock.hours.addClass("hidden"),c.clockHolder.clock.hours.removeClass("animate")},"hours"===a?20:d),c.clockHolder.clock.minutes.addClass("animate"),"minutes"===a&&c.clockHolder.clock.minutes.removeClass("hidden"),clearTimeout(b.mTimeout),b.mTimeout=setTimeout(function(){"minutes"!==a&&c.clockHolder.clock.minutes.addClass("hidden"),c.clockHolder.clock.minutes.removeClass("animate")},"minutes"===a?20:d))},show:function(){var b=this;if(""===b.input.val()){var c=b.getSystemTime();this.time=new i(c.hour,c.minute)}b.resetSelected(),a("body").attr("mdtimepicker-display","on"),b.timepicker.wrapper.addClass("animate"),b.timepicker.overlay.removeClass("hidden").addClass("animate"),setTimeout(function(){b.timepicker.overlay.removeClass("animate"),b.timepicker.wrapper.removeClass("animate"),b.visible=!0,b.input.blur()},10)},hide:function(){var b=this;b.timepicker.overlay.addClass("animate"),b.timepicker.wrapper.addClass("animate"),setTimeout(function(){b.switchView("hours"),b.timepicker.overlay.addClass("hidden").removeClass("animate"),b.timepicker.wrapper.removeClass("animate"),a("body").removeAttr("mdtimepicker-display"),b.visible=!1,b.input.focus()},300)},destroy:function(){var a=this;a.input.removeData(b).unbind("keydown").unbind("click").removeProp("readonly"),a.timepicker.overlay.remove()}},a.fn.mdtimepicker=function(c){return a(this).each(function(){var f=this,g=a(this),h=a(this).data(b);options=a.extend({},a.fn.mdtimepicker.defaults,g.data(),"object"===typeof c&&c),h||g.data(b,h=new j(f,options)),"string"===typeof c&&h[c](),a(document).on("keydown",function(a){27===a.keyCode&&h.visible&&h.hide()})})},a.fn.mdtimepicker.defaults={timeFormat:"hh:mm:ss.000",format:"h:mm tt",theme:"blue",readOnly:!0,hourPadding:!1}}(jQuery);

!function(i){i.fn.faqGenerator=function(e){var n=i.extend({theme:null,limitOne:!1,startOpen:!1,icon:!1},e);return this.each(function(){var e=i(this);e.addClass("faqgen"),e.find("li").addClass("faqgen-item"),e.find("li > div:first-child").addClass("faqgen-question"),e.find("li > div:last-child").addClass("faqgen-answer");var a=(e.find(".faqgen-item"),i(this).find(".faqgen-question")),s=i(this).find(".faqgen-answer");null!=n.theme&&e.addClass(n.theme),n.icon&&a.each(function(){i(this).prepend("<div class='faqgen-icon'></div>")}),!n.startOpen||n.limitOne?s.hide():(s.addClass("active"),e.find(".faqgen-icon").addClass("active")),a.click(function(){$sibling=i(this).siblings(".faqgen-answer"),$icon=i(this).find(".faqgen-icon"),$sibling.hasClass("active")?(i(this).siblings(".faqgen-answer").slideToggle(300).toggleClass("active"),$icon.toggleClass("active")):(n.limitOne&&(e.find(".faqgen-answer.active").slideToggle(300).toggleClass("active"),e.find(".faqgen-icon.active").toggleClass("active")),$sibling.slideToggle(300).toggleClass("active"),$icon.toggleClass("active"))})})}}(jQuery);