(function($){
	var _p="mPS2id",
		_o=mPS2id_params,
		shortcodeClass=_o.shortcode_class, //shortcode without suffix 
		_hash=location.hash || null,
		_validateLocHash=function(val){
			return $(val).length && $("a[href*='"+val+"']").filter(function(){return $(this).data(_p+"Element")==true}).length;
		},
		_offset=function(val){
			if(val.indexOf(",")!==-1){
				var arr=val.split(","),y=arr[0] || "0",x=arr[1] || "0";
				return {"y":y,"x":x};
			}else{
				return val;
			}
		},
		_screen=function(val){
			if(val.indexOf(",")!==-1){
				var arr=val.split(","),x=arr[0] || "0",y=arr[1] || "0";
				return [x,y];
			}else{
				return val;
			}
		};
	$(document).ready(function(){
		for(var k=0; k<_o.total_instances; k++){
			//scroll to location hash on page load
			if(_o.instances[_p+"_instance_"+k]["scrollToHash"]["value"]==="true" && _hash){
				$(_o.instances[_p+"_instance_"+k]["selector"]["value"]+",."+shortcodeClass).each(function(){
					$(this).data(_p+"Element",true);
				});
				if(_validateLocHash(_hash)){
					var href=window.location.href.replace(/#.*$/,"#"),
						layout=_o.instances[_p+"_instance_"+k]["layout"]["value"];
					if(layout!=="horizontal"){
						$(window).scrollTop(0); //stop jump to hash straight away
					}
					if(layout!=="vertical"){
						$(window).scrollLeft(0); //stop jump to hash straight away
					}
					if(window.history && window.history.pushState){
						window.history.pushState("","",href);
					}else{
						window.location.href=href;
					}
				}
			}
		}
	});
	$(window).load(function(){
		for(var i=0; i<_o.total_instances; i++){
			$(_o.instances[_p+"_instance_"+i]["selector"]["value"]+",."+shortcodeClass).mPageScroll2id({
				scrollSpeed:_o.instances[_p+"_instance_"+i]["scrollSpeed"]["value"],
				autoScrollSpeed:(_o.instances[_p+"_instance_"+i]["autoScrollSpeed"]["value"]==="true") ? true : false,
				scrollEasing:_o.instances[_p+"_instance_"+i]["scrollEasing"]["value"],
				scrollingEasing:_o.instances[_p+"_instance_"+i]["scrollingEasing"]["value"],
				pageEndSmoothScroll:(_o.instances[_p+"_instance_"+i]["pageEndSmoothScroll"]["value"]==="true") ? true : false,
				layout:_o.instances[_p+"_instance_"+i]["layout"]["value"],
				offset:_offset(_o.instances[_p+"_instance_"+i]["offset"]["value"].toString()),
				highlightSelector:_o.instances[_p+"_instance_"+i]["highlightSelector"]["value"],
				clickedClass:_o.instances[_p+"_instance_"+i]["clickedClass"]["value"],
				targetClass:_o.instances[_p+"_instance_"+i]["targetClass"]["value"],
				highlightClass:_o.instances[_p+"_instance_"+i]["highlightClass"]["value"],
				forceSingleHighlight:(_o.instances[_p+"_instance_"+i]["forceSingleHighlight"]["value"]==="true") ? true : false,
				keepHighlightUntilNext:(_o.instances[_p+"_instance_"+i]["keepHighlightUntilNext"]["value"]==="true") ? true : false,
				disablePluginBelow:_screen(_o.instances[_p+"_instance_"+i]["disablePluginBelow"]["value"].toString())
			});
			//scroll to location hash on page load
			if(_o.instances[_p+"_instance_"+i]["scrollToHash"]["value"]==="true" && _hash){
				if(_validateLocHash(_hash)){
					$.mPageScroll2id("scrollTo",_hash);
					if(window.history && window.history.pushState){
						window.history.pushState("","",_hash);
					}else{
						window.location.hash=_hash;
					}
				}
			}
		}
	});
})(jQuery);