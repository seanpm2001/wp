/** spits out some ad code.
 * config accepts
 * desktop, boolean, if it shows up on desktop or not
 * placement, string, the identifier for the ad
 * height, string, tells us how tall to make it. optional
 * yieldmo, boolean, let us know it's meant to be a yieldmo ad
 * doc_write, boolean, tells us to doc write it instead of placing it
 * element, jquery element, tells us where to put it. optional. 
 *   places after current script if not specified
 **/
var desktop_ads = (screen.width > 630);

var ad_code = function(config) {
  if (   (!config.desktop && desktop_ads)
      || (config.desktop && !desktop_ads) 
    ) { 
    return false;
  } //do not do desktop ads if we're not on desktop, do not do mobile ads if we're not on mobile
  config.ad = create_ad(config);
  place_ad_iframe(config, config)
  if ( config.yieldmo || config.placement.match(/ym_/) ) {
    ym_script();
  } 
}

var create_ad = function(config) {
  if ( config.yieldmo || config.placement.match(/ym_/) ) {
    return yieldmo_code(config.placement);
  } else if (config.doc_write) { //doc write is only on adtech fyi
    return adtech_script(config);
  } else {
    return adtech_code(config);
  }
}

var place_ad = function(ad, element) {
  if (config.doc_write) {
    document.write(config.ad);
  } else if (element) { 
    config.element.after(config.ad); 
  } else { 
    $('script').last().after(config.ad);
  }
}

var yieldmo_code = function( placement ) {
  return $('<div id="' + placement + '" class="ym"></div>');
}

var ym_script = function() {
  (function(e,t){if(t._ym===void 0){t._ym="";var m=e.createElement("script");m.type="text/javascript",m.async=!0,m.src="//static.yieldmo.com/ym.m5.js",(e.getElementsByTagName("head")[0]||e.getElementsByTagName("body")[0]).appendChild(m)}else t._ym instanceof String||void 0===t._ym.chkPls||t._ym.chkPls()})(document,window);
}

var adtech_code = function(config) {
  var script_tag = adtech_script(config);
  return $('<iframe>' + script_tag + '</iframe>');
}

var adtech_script = function(config) {
  var curDateTime = new Date(); 
  var offset = -(curDateTime.getTimezoneOffset()); 
  if (offset > 0) { offset = "+" + offset; }
  return '<script language="javascript1.1" src="http://adserver.adtechus.com/addyn/3.0/5443.1/0/0/' +
    escape(config.height)+'/ADTECH;loc=100;target=_blank' + 
    ';alias=' + escape(config.placement) +
    ';key=' + escape(window.ad_keywords) +
    ';grp=' + escape(window.groupid) +
    ';kvuri=' + escape(window.location.pathname) +
    ';misc=' + curDateTime.getTime() +
    ';aduho=' + offset + '"></script>'
    ; 
}

var is_post;
var is_fullwidth;
(function($) {
  $('document').ready(function() {
    if (   (!is_post) 
        || (!desktop_ads && !is_fullwidth) 
        || (typeof MJ_HideInContentAds === "undefined") ) {
      return;
    }

    if (is_fullwidth) {
      fullwidth_inline_ads();
    } else {
      inline_ads();
    }
   
  });

  var fullwidth_inline_ads = function() {
      var subhed_selector = 'h3.subhed';
      var section_lead_selector = 'span.section-lead';
      var pgs = $('.node .node-content > p, .node .node-content > p,' 
                + '.node .node-content > h3, .node .node-content > h3');
      var wordcount = 0;
      var words_before_ad_can_be_placed = 650;
      var ads_placed = 0;
      var ads_desired = 10; 
      var placement_prefix = 'InContentMob300x250_BB';
      var ad_height = 250;


      for (var i = 0; i < pgs.length; i++) {
        if (wordcount > words_before_ad_can_be_placed
          && (
                $(pgs[i]).is(subhed_selector)
             || $(pgs[i]).find(section_lead_selector).length
             )
          ) {

            ads_placed++;
						ad_code({
              element: $(pgs[i-1]),
              placement: placement_prefix + ads_placed,
              height: ad_height,
              desktop: desktop_ads,
						});
            if (ads_placed >= ads_desired) {
              break;
            }
          } else {
            var words = $(pgs[i]).text().split(' ').length;
            wordcount += words;
          }
      }

  }

  var inline_ads = function() {
		var ads_placed = 0;
		var ym_codes = ["ym_1210153280377042966","ym_1368917604586339309","ym_1368918492646325230","ym_1368918756996529135"];
		var min_ps_for_ad = 3;
		var separating_ps = 5;
		var min_ps_before_end = 2;
		var max_ads = 4;
		var pgs = $(".node-content > p").length;

		var count_till_ad = 0;
		for (var i = (min_ps_for_ad - 1); i < (pgs - min_ps_before_end); i++ ) {
		 if (count_till_ad < 1) {
				ad_code({
					element: $($(".node-content > p")[placement]) ,
					placement: ym_codes[ads_placed],
					desktop: false, //yea mobile only 
				});
			 count_till_ad = separating_ps;
			 ads_placed++;
			 if ( ads_placed === max_ads || ads_placed === ym_codes.length ) { break; }
		 }
		 count_till_ad--;
		}
  }
})(jQuery);
