!function(t){var e={};function s(i){if(e[i])return e[i].exports;var a=e[i]={i:i,l:!1,exports:{}};return t[i].call(a.exports,a,a.exports,s),a.l=!0,a.exports}s.m=t,s.c=e,s.d=function(t,e,i){s.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:i})},s.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},s.t=function(t,e){if(1&e&&(t=s(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var i=Object.create(null);if(s.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var a in t)s.d(i,a,function(e){return t[e]}.bind(null,a));return i},s.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return s.d(e,"a",e),e},s.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},s.p="",s(s.s=5)}([function(t,e,s){"use strict";e.a={}},function(t,e,s){"use strict";var i=s(0);function a(t,e){for(var s=0;s<e.length;s++){var i=e[s];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}var n=function(){function t(e){var s,a,n;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),n=null,(a="resizeTimeout")in(s=this)?Object.defineProperty(s,a,{value:n,enumerable:!0,configurable:!0,writable:!0}):s[a]=n,this.id=e,this.podcast=i.a[e].podcast,this.infoToggle=this.podcast.find(".pod-info__toggle"),this.podInfo=this.podcast.find(".pod-info__header"),this.events()}var e,s,n;return e=t,(s=[{key:"events",value:function(){this.infoToggle.click(function(){this.podInfo.slideToggle("fast"),this.infoToggle.toggleClass("toggled-on").attr("aria-expanded",this.infoToggle.hasClass("toggled-on"))}.bind(this))}}])&&a(e.prototype,s),n&&a(e,n),t}();function o(t,e){for(var s=0;s<e.length;s++){var i=e[s];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}function r(t,e,s){return e in t?Object.defineProperty(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}var d=function(){function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),r(this,"plBtn",void 0),r(this,"forBtn",void 0),r(this,"bckBtn",void 0),r(this,"ttBtn",void 0),r(this,"ssBtn",void 0),this.podcast=i.a[e].podcast,this.mediaObj=i.a[e].mediaObj,this.controls=jQuery(this.mediaObj.controls),this.layers=this.controls.prev(".ppjs__layers"),this.media=this.mediaObj.media,this.modalObj=i.a[e].modal,this.settings=i.a[e].settings,this.transcript=i.a[e].episode,this.list=i.a[e].list,this.props=i.a[e],this.modControlMarkup(),this.modLayersMarkup(),this.plBtn=this.controls.find(".ppjs__playpause-button > button"),this.forBtn=this.controls.find(".ppjs__jump-forward-button > button"),this.bckBtn=this.controls.find(".ppjs__skip-backward-button > button"),this.ttBtn=this.controls.find(".ppjs__script-button > button"),this.ssBtn=this.controls.find(".ppjs__share-button > button"),this.pbrBtn=this.controls.find(".ppjs__play-rate-button > button"),this.events()}var e,s,a;return e=t,(s=[{key:"events",value:function(){this.media.addEventListener("loadedmetadata",this.condbtnPauseMedia.bind(this)),this.media.addEventListener("play",this.btnPlayMedia.bind(this)),this.media.addEventListener("playing",this.btnPlayMedia.bind(this)),this.media.addEventListener("pause",this.btnPauseMedia.bind(this)),this.plBtn.click(this.playPause.bind(this)),this.forBtn.click(this.forwardMedia.bind(this)),this.bckBtn.click(this.skipbackMedia.bind(this)),this.ttBtn.click(this.showtranscript.bind(this)),this.ssBtn.click(this.showsocialshare.bind(this)),this.pbrBtn.click(this.mediaPlayRate.bind(this)),this.podcast.find(".episode-single__close").click(this.hidetranscript.bind(this))}},{key:"playPause",value:function(){this.mediaObj.paused?(this.mediaObj.play(),this.plBtn.addClass("playing")):(this.mediaObj.pause(),this.plBtn.removeClass("playing"))}},{key:"forwardMedia",value:function(){var t,e;e=isNaN(this.media.duration)?15:this.media.duration,t=this.media.currentTime===1/0?0:this.media.currentTime,this.media.setCurrentTime(Math.min(t+15,e)),this.forBtn.blur()}},{key:"skipbackMedia",value:function(){var t;t=this.media.currentTime===1/0?0:this.media.currentTime,this.media.setCurrentTime(Math.max(t-15,0)),this.bckBtn.blur()}},{key:"mediaPlayRate",value:function(){var t=this.pbrBtn.find(".current"),e=t.next(".pp-rate"),s=this.pbrBtn.find(".pp-times");0===e.length&&(e=this.pbrBtn.find(".pp-rate").first());var i=parseFloat(e.text());t.removeClass("current"),e.addClass("current"),e.hasClass("withx")?s.show():s.hide(),this.media.playbackRate=i,this.pbrBtn.blur()}},{key:"btnPlayMedia",value:function(){this.plBtn.addClass("playing"),this.podcast.hasClass("postview")||this.modalObj.modal&&this.modalObj.modal.hasClass("pp-modal-open")&&this.modalObj.returnElem()}},{key:"btnPauseMedia",value:function(){this.plBtn.removeClass("playing")}},{key:"showtranscript",value:function(){this.transcript.slideToggle("fast"),this.ttBtn.parent().toggleClass("toggled-on")}},{key:"hidetranscript",value:function(){this.transcript.slideUp("fast"),this.ttBtn.parent().removeClass("toggled-on")}},{key:"showsocialshare",value:function(){this.ssBtn.closest(".pp-podcast__player").siblings(".pod-content__social-share").slideToggle("fast"),this.ssBtn.parent().toggleClass("toggled-on")}},{key:"condbtnPauseMedia",value:function(){-1===this.media.rendererName.indexOf("flash")&&this.plBtn.removeClass("playing")}},{key:"modControlMarkup",value:function(){var t,e;this.mediaObj.isVideo?(this.controls.prepend(this.settings.ppPlayPauseBtn),this.controls.find(".ppjs__fullscreen-button > button").html(this.settings.ppMaxiScrnBtn+this.settings.ppMiniScrnBtn)):(this.controls.find(".ppjs__time").wrapAll('<div class="ppjs__audio-timer" />'),this.controls.find(".ppjs__time-rail").wrap('<div class="ppjs__audio-time-rail" />'),(t=jQuery("<div />",{class:"ppjs__audio-controls"})).html(this.settings.ppAudioControlBtns),this.controls.prepend(t),this.props.isWide&&(e=this.transcript.find(".episode-single__title").text(),this.controls.find(".ppjs__episode-title").text(e)))}},{key:"modLayersMarkup",value:function(){this.layers.find(".ppjs__overlay-play > .ppjs__overlay-button").html(this.settings.ppPlayCircle),this.layers.find(".ppjs__overlay > .ppjs__overlay-loading").html(this.settings.ppVidLoading)}}])&&o(e.prototype,s),a&&o(e,a),t}();function l(t,e){for(var s=0;s<e.length;s++){var i=e[s];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}var c=function(){function t(e){var s,a,n;!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),n=void 0,(a="listItem")in(s=this)?Object.defineProperty(s,a,{value:n,enumerable:!0,configurable:!0,writable:!0}):s[a]=n,this.id=e,this.podcast=i.a[e].podcast,this.list=i.a[e].list,this.episode=i.a[e].episode,this.player=i.a[e].player,this.mediaObj=i.a[e].mediaObj,this.instance=i.a[e].instance,this.modalObj=i.a[e].modal,this.singleWrap=i.a[e].singleWrap,this.data=window.podcastPlayerData||{},this.events()}var e,s,a;return e=t,(s=[{key:"events",value:function(){var t=this;t.podcast.hasClass("postview")?t.list.on("click",".pod-entry__title a, .pod-entry__featured",function(e){var s=jQuery(this);if(!s.hasClass("fetch-post-title")){var i="pp-podcast-".concat(t.instance),a=t.data[i].load_info,n=!!a&&!!a.args&&a.args.hddesc;n=n||!1;var o=!s.hasClass("pod-entry__featured")&&!n||t.mediaObj.isVideo;e.preventDefault(),t.listItem=s.closest(".pod-entry"),t.playModal(o)}}):t.list.on("click",".episode-list__entry, .episode-list__search-entry",function(e){e.preventDefault(),t.listItem=jQuery(this),t.play()})}},{key:"common",value:function(){var t,e,s,i,a="pp-podcast-".concat(this.instance),n=this.listItem.attr("id"),o=this.singleWrap.find(".pod-content__social-share");0<(t=this.list.find(".activeEpisode")).length&&t.removeClass("activeEpisode media-playing"),e=this.listItem.hasClass("episode-list__search-entry")?this.data.search[n]:this.data[a][n];var r="https://www.facebook.com/sharer.php?u="+(s=encodeURIComponent(e.link)),d="https://twitter.com/intent/tweet?url="+s+"&text="+(i=encodeURIComponent(e.title)),l="https://www.linkedin.com/shareArticle?mini=true&url="+s,c="mailto:?subject="+i+"&body=Link:"+s;return this.listItem.addClass("activeEpisode media-playing"),this.episode.find(".episode-single__title").html(e.title),this.episode.find(".episode-single__author > .single-author").html(e.author),this.player.find(".ppjs__episode-title").html(e.title),this.episode.find(".episode-single__description").html(e.description),o.find(".ppsocial__facebook").attr("href",r),o.find(".ppsocial__twitter").attr("href",d),o.find(".ppsocial__linkedin").attr("href",l),o.find(".ppsocial__email").attr("href",c),o.find(".ppshare__download").attr("href",e.src),this.mediaObj.setSrc(e.src),this.mediaObj.load(),!0}},{key:"play",value:function(){if(this.listItem.hasClass("activeEpisode")&&!this.mediaObj.paused)return this.listItem.removeClass("activeEpisode"),void this.mediaObj.pause();this.modalObj.modal&&this.modalObj.modal.hasClass("pp-modal-open")&&this.modalObj.returnElem(),this.common(),this.mediaObj.play(),jQuery("html, body").animate({scrollTop:this.player.offset().top-200},400)}},{key:"playModal",value:function(t){this.modalObj&&(this.listItem.hasClass("activeEpisode")?t?(this.modalObj.modal.removeClass("inline-view").addClass("modal-view"),this.modalObj.scrollDisable(),this.mediaObj.play(),this.modalObj.modal.removeClass("media-paused"),this.listItem.addClass("media-playing")):this.mediaObj.paused?(this.mediaObj.play(),this.modalObj.modal.removeClass("media-paused"),this.listItem.addClass("media-playing")):(this.mediaObj.pause(),this.modalObj.modal.addClass("media-paused"),this.listItem.removeClass("media-playing")):(this.common(),this.singleWrap.hasClass("activePodcast")||(this.modalObj.modal.hasClass("pp-modal-open")&&this.modalObj.returnElem(),this.modalObj.create(this.singleWrap,this.mediaObj,t),this.singleWrap.addClass("activePodcast")),this.mediaObj.play()))}}])&&l(e.prototype,s),a&&l(e,a),t}();function h(t,e){for(var s=0;s<e.length;s++){var i=e[s];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}var p=function(){function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.podcast=i.a[e].podcast,this.settings=i.a[e].settings,this.instance=i.a[e].instance,this.data=window.podcastPlayerData||{},this.loadbtn=this.podcast.find(".episode-list__load-more"),this.events()}var e,s,a;return e=t,(s=[{key:"events",value:function(){this.loadbtn.click(this.loadEpisodes.bind(this))}},{key:"loadEpisodes",value:function(){var t="pp-podcast-".concat(this.instance),e=this.data[t].rdata.from,s=this.data[t].load_info,i=this.data[t].rdata.elen,a=Math.min(s.displayed+s.step,s.loaded),n=s.displayed+1,o=jQuery("<div />"),r="";for("posts"===e&&(r="fetch-post-title");n<=a;n++){var d="ppe-".concat(this.instance,"-").concat(n),l=this.data[t][d];if(void 0!==l){var c=l.title,h=l.description,p=l.author,u=l.date,f=l.link,m=l.featured;m||(m=(m=s.args.imgurl)||"");var y=jQuery("<a />",{href:f,class:r}).html(c),v=jQuery("<div />",{class:"pod-entry__title"}).html(y),b=jQuery("<div />",{class:"pod-entry__date"}).text(u),_=jQuery("<div />",{class:"pod-entry__author"}).html(p),j=void 0;if(this.podcast.hasClass("postview")){var g=h?jQuery(h).text():"",w=jQuery("<img />",{class:"pod-entry__image",src:m}),k=g?g.split(" ").splice(0,i).join(" "):"",O=k?jQuery("<div />",{class:"pod-entry__excerpt"}).text(k+"..."):"",C=O?O[0].outerHTML:"",T=jQuery("<div />",{class:"pod-entry__play"}).html(this.settings.ppPlayCircle+this.settings.ppPauseBtn);j='\n\t\t\t\t\t<div id="'.concat(d,'" class="episode-list__entry pod-entry" data-search-term="').concat(c.toLowerCase(),'">\n\t\t\t\t\t\t<div class="pod-entry__featured">\n\t\t\t\t\t\t\t').concat(T[0].outerHTML,'\n\t\t\t\t\t\t\t<div class="pod-entry__thumb">').concat(w[0].outerHTML,'</div>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t\t<div class="pod-entry__content">\n\t\t\t\t\t\t\t').concat(v[0].outerHTML).concat(C).concat(b[0].outerHTML).concat(_[0].outerHTML,"\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>\n\t\t\t\t\t")}else j='\n\t\t\t\t\t<div id="'.concat(d,'" class="episode-list__entry pod-entry" data-search-term="').concat(c.toLowerCase(),'">\n\t\t\t\t\t\t<div class="pod-entry__content">\n\t\t\t\t\t\t\t').concat(v[0].outerHTML).concat(b[0].outerHTML).concat(_[0].outerHTML,"\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>\n\t\t\t\t\t");o.append($(j))}}this.loadbtn.parent().before(o.html()),s.displayed=a,this.fetchEpisodes()}},{key:"fetchEpisodes",value:function(){var t="pp-podcast-".concat(this.instance),e=this.data[t].rdata;"feedurl"===e.from?this.fetchFromFeed():"posts"===e.from&&this.fetchFromPosts()}},{key:"fetchFromFeed",value:function(){var t=this,e="pp-podcast-".concat(this.instance),s=this.data[e].load_info,i=this.data.ajax_info,a={action:"pp_fetch_episodes",security:i.security,instance:this.instance,loaded:s.loaded,maxItems:s.maxItems,feedUrl:s.src,step:s.step,sortby:s.sortby,filterby:s.filterby};s.loaded>=s.maxItems?s.displayed>=s.loaded&&this.loadbtn.slideUp("slow"):jQuery.ajax({url:i.ajaxurl,data:a,type:"POST",timeout:4e3,success:function(i){var a=jQuery.parseJSON(i);s.loaded=a.loaded,jQuery.extend(!0,t.data[e],a.episodes)},error:function(){t.loadbtn.hide()}})}},{key:"fetchFromPosts",value:function(){var t=this,e="pp-podcast-".concat(this.instance),s=this.data[e].load_info,i=this.data.ajax_info,a={action:"pp_fetch_posts",security:i.security,instance:this.instance,offset:s.offset,loaded:s.loaded,args:s.args};0!==s.offset?jQuery.ajax({url:i.ajaxurl,data:a,type:"POST",timeout:4e3,success:function(i){var a=jQuery.parseJSON(i);jQuery.isEmptyObject(a)?(s.offset=0,t.loadbtn.slideUp("slow")):(console.log(a),s.loaded=a.loaded,jQuery.extend(!0,t.data[e],a.episodes),s.offset+=s.step)},error:function(){t.loadbtn.hide()}}):this.loadbtn.slideUp("slow")}}])&&h(e.prototype,s),a&&h(e,a),t}();function u(t,e){for(var s=0;s<e.length;s++){var i=e[s];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}function f(t,e,s){return e in t?Object.defineProperty(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}var m=function(){function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),f(this,"isSrchOpen",!1),f(this,"localTimeOut",null),f(this,"serverTimeOut",null),f(this,"hasLoading",!1),f(this,"prevSearchTerm",""),this.podcast=i.a[e].podcast,this.instance=i.a[e].instance,this.data=window.podcastPlayerData||{},this.list=i.a[e].list,this.episodes=i.a[e].episodes,this.settings=i.a[e].settings,this.loadbtn=this.podcast.find(".episode-list__load-more"),this.searchBox=this.list.find(".episode-list__search > input"),this.searchResults=this.list.find(".episode-search"),this.searchClose=this.list.find(".episode-list__clear-search"),this.events()}var e,s,a;return e=t,(s=[{key:"events",value:function(){this.searchBox.on("keyup",this.initSearch.bind(this)),this.searchClose.click(this.clearSearch.bind(this))}},{key:"initSearch",value:function(){var t="";!1===this.isSrchOpen&&(this.podcast.addClass("search-opened"),this.searchResults.show(),this.loadbtn.hide(),this.searchClose.show(),this.isSrchOpen=!0),clearTimeout(this.localTimeOut),this.localTimeOut=setTimeout(function(){(t=jQuery.trim(this.searchBox.val().toLowerCase()))&&this.filterEpisodes(t)}.bind(this),100),clearTimeout(this.serverTimeOut),this.serverTimeOut=setTimeout(function(){if(t){if(this.prevSearchTerm===t)return;this.liveSearch(t)}else this.clearSearch()}.bind(this),500)}},{key:"filterEpisodes",value:function(t){this.episodes.find(".episode-list__entry").each(function(){jQuery(this).filter("[data-search-term *= "+t+"]").length>0||t.length<1?jQuery(this).show():jQuery(this).hide()})}},{key:"liveSearch",value:function(t){if(!1===this.hasLoading){var e=jQuery("<div />",{class:"episode-search__loading"}).html(this.settings.ppVidLoading);this.searchResults.html(e),this.hasLoading=!0}this.fetchResults(t)}},{key:"fetchResults",value:function(t){var e="pp-podcast-".concat(this.instance),s=this.data[e].rdata;"feedurl"===s.from?this.fetchFromFeed(t):"posts"===s.from&&this.fetchFromPosts(t)}},{key:"fetchFromFeed",value:function(t){var e=this,s="pp-podcast-".concat(this.instance),i=this.data[s].load_info,a=this.data.ajax_info,n={action:"pp_search_episodes",security:a.security,instance:"s",loaded:i.displayed,maxItems:i.maxItems,feedUrl:i.src,sortby:i.sortby,filterby:i.filterby,search:t};i.displayed>=i.maxItems?this.flushSearchResults():jQuery.ajax({url:a.ajaxurl,data:n,type:"POST",timeout:4e3,success:function(t){var s=jQuery.parseJSON(t);jQuery.isEmptyObject(s)?e.flushSearchResults():(e.data.search=s.episodes,e.showSearchResults(s.episodes))},error:function(){e.flushSearchResults()}})}},{key:"fetchFromPosts",value:function(t){var e=this,s="pp-podcast-".concat(this.instance),i=this.data[s].load_info,a=this.data.ajax_info,n={action:"pp_search_posts",security:a.security,offset:i.displayed,args:i.args,instance:"s",search:t};jQuery.ajax({url:a.ajaxurl,data:n,type:"POST",timeout:4e3,success:function(t){var s=jQuery.parseJSON(t);jQuery.isEmptyObject(s)?e.flushSearchResults():(e.data.search=s.episodes,e.showSearchResults(s.episodes))},error:function(){e.flushSearchResults()}})}},{key:"clearSearch",value:function(){var t="pp-podcast-".concat(this.instance),e=this.data[t].load_info;this.searchBox.val(""),this.prevSearchTerm="",this.isSrchOpen=!1,this.podcast.removeClass("search-opened"),this.searchResults.hide(),this.searchClose.hide(),this.episodes.find(".episode-list__entry").show(),this.flushSearchResults(),e.displayed<e.loaded&&this.loadbtn.show()}},{key:"flushSearchResults",value:function(){this.searchResults.empty(),this.hasLoading=!1}},{key:"showSearchResults",value:function(t){for(var e="pp-podcast-".concat(this.instance),s=this.data[e].load_info,i=Object.getOwnPropertyNames(t),a=i.length-1,n=this.data[e].rdata.elen,o=0,r=jQuery("<div />");o<=a;o++){var d=i[o],l=t[d],c=l.title,h=l.description,p=l.author,u=l.date,f=l.link,m=l.featured;m||(m=(m=s.args.imgurl)||"");var y=jQuery("<a />",{href:f}).html(c),v=jQuery("<div />",{class:"pod-entry__title"}).html(y),b=jQuery("<div />",{class:"pod-entry__date"}).text(u),_=jQuery("<div />",{class:"pod-entry__author"}).html(p),j=void 0;if(this.podcast.hasClass("postview")){var g=h?$(h).text():"",w=jQuery("<img />",{class:"pod-entry__image",src:m}),k=g?g.split(" ").splice(0,n).join(" "):"",O=k?jQuery("<div />",{class:"pod-entry__excerpt"}).text(k+"..."):"",C=O?O[0].outerHTML:"",T=jQuery("<div />",{class:"pod-entry__play"}).html(this.settings.ppPlayCircle+this.settings.ppPauseBtn);j='\n\t\t\t\t<div id="'.concat(d,'" class="episode-list__search-entry pod-entry" data-search-term="').concat(c.toLowerCase(),'">\n\t\t\t\t\t<div class="pod-entry__featured">\n\t\t\t\t\t\t').concat(T[0].outerHTML,'\n\t\t\t\t\t\t<div class="pod-entry__thumb">').concat(w[0].outerHTML,'</div>\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class="pod-entry__content">\n\t\t\t\t\t\t').concat(v[0].outerHTML).concat(C).concat(b[0].outerHTML).concat(_[0].outerHTML,"\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t\t")}else j='\n\t\t\t\t<div id="'.concat(d,'" class="episode-list__search-entry pod-entry" data-search-term="').concat(c.toLowerCase(),'">\n\t\t\t\t\t<div class="pod-entry__content">\n\t\t\t\t\t\t').concat(v[0].outerHTML).concat(b[0].outerHTML).concat(_[0].outerHTML,"\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t\t");r.append($(j)),this.searchResults.html(r.html()),this.hasLoading=!1}}}])&&u(e.prototype,s),a&&u(e,a),t}();function y(t,e){for(var s=0;s<e.length;s++){var i=e[s];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}function v(t,e,s){return e in t?Object.defineProperty(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}var b=function(){function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),v(this,"width",void 0),v(this,"resizeTimeout",null),this.id=e,this.playerStyleUpdate(),this.managerHeader(),this.mediaElements(),this.entryEpisodes(),this.loadMore(),this.searchEpisodes(),this.events()}var e,s,a;return e=t,(s=[{key:"events",value:function(){var t=this;clearTimeout(this.resizeTimeout),jQuery(window).on("resize",function(){t.resizeTimeout=setTimeout(t.playerStyleUpdate.bind(t),100)})}},{key:"playerStyleUpdate",value:function(){var t=i.a[this.id].podcast,e="";this.width=t.width(),window.matchMedia("(max-width: 640px)").matches?i.a.isLrgScrn=!1:i.a.isLrgScrn=!0,t.removeClass("wider-player wide-player narrow-player"),i.a[this.id].isWide=!1,this.width>720?(e="wider-player wide-player",i.a[this.id].isWide=!0):this.width>600?(e="wide-player",i.a[this.id].isWide=!0):this.width>300&&(e="narrow-player"),t.addClass(e)}},{key:"managerHeader",value:function(){new n(this.id)}},{key:"mediaElements",value:function(){new d(this.id)}},{key:"entryEpisodes",value:function(){new c(this.id)}},{key:"loadMore",value:function(){new p(this.id)}},{key:"searchEpisodes",value:function(){new m(this.id)}}])&&y(e.prototype,s),a&&y(e,a),t}();e.a=b},,,,function(t,e,s){"use strict";s.r(e);var i=s(0),a=s(1);!function(t){var e=window.ppmejsSettings||{};function s(s){if("playerAdded"===s.animationName&&t(s.target).hasClass("pp-podcast")&&!t(s.target).hasClass("pp-podcast-added")){var n=t(s.target),o=n.attr("id"),r=new MediaElementPlayer(o+"-player",e),d=n.find(".pod-content__list"),l=n.find(".pod-content__episode"),c=d.find(".episode-list__wrapper"),h=l.find(".episode-single__wrapper"),p=n.find(".pp-podcast__player");i.a[o]={podcast:n,mediaObj:r,settings:e,list:d,episode:l,episodes:c,single:h,player:p,instance:o.replace("pp-podcast-","")},n.addClass("pp-podcast-added"),new a.a(o)}}document.addEventListener("animationstart",s,!1),document.addEventListener("webkitAnimationStart",s,!1)}(jQuery)}]);