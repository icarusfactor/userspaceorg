!function(e){var t={};function i(n){if(t[n])return t[n].exports;var s=t[n]={i:n,l:!1,exports:{}};return e[n].call(s.exports,s,s.exports,i),s.l=!0,s.exports}i.m=e,i.c=t,i.d=function(e,t,n){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var s in e)i.d(n,s,function(t){return e[t]}.bind(null,s));return n},i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="",i(i.s=2)}({2:function(e,t){var i,n,s;i=jQuery,(s=i(document)).on("click",".podcast-player-widget-img-uploader",function(e){var t=i(this);e.preventDefault(),(n=wp.media.frames.fileFrame=wp.media({title:podcastplayerImageUploadText.uploader_title,button:{text:podcastplayerImageUploadText.uploader_button_text},multiple:!1})).on("select",function(){var e=n.state().get("selection").first().toJSON(),i=e.url,s=e.id,o=document.createElement("img");o.src=i,o.className="custom-widget-thumbnail",t.html(o),t.addClass("has-image"),t.nextAll(".podcast-player-widget-img-id").val(s).trigger("change"),t.nextAll(".podcast-player-widget-img-instruct, .podcast-player-widget-img-remover").removeClass("podcast-player-hidden")}),n.open()}),s.on("click",".podcast-player-widget-img-remover",function(e){e.preventDefault(),i(this).prevAll(".podcast-player-widget-img-uploader").html(podcastplayerImageUploadText.set_featured_img).removeClass("has-image"),i(this).prev(".podcast-player-widget-img-instruct").addClass("podcast-player-hidden"),i(this).next(".podcast-player-widget-img-id").val("").trigger("change"),i(this).addClass("podcast-player-hidden")}),s.on("click",".pp-settings-toggle",function(e){var t=i(this);e.preventDefault(),t.next(".pp-settings-content").slideToggle("fast"),t.toggleClass("toggle-active")}),i(document).on("ready widget-added widget-updated",function(e,t){var n={change:function(e,t){i(e.target).val(t.color.toString()),i(e.target).trigger("change")}};i(".pp-accent-color").not('[id*="__i__"]').wpColorPicker(n)}),i("#widgets-right").on("change","select.podcast-player-pp-display-style",function(){var e,t=i(this),n=t.val(),s=t.parent().parent(),o=s.prevAll(".pp-settings-content"),a=o.find(".header-default"),p=o.find(".excerpt-length");n?a.hide():a.show(),["lv1","gv1"].includes(n)?p.show():p.hide(),["gv1"].includes(n)?s.find(".grid-columns").show():s.find(".grid-columns").hide(),["lv1","gv1"].includes(n)?(s.find(".aspect-ratio").show(),(e=s.find("select.podcast-player-pp-aspect-ratio")).length&&e.val()&&s.find(".crop-method").show()):(s.find(".aspect-ratio").hide(),s.find(".crop-method").hide())}),i("#widgets-right").on("change","select.podcast-player-pp-aspect-ratio",function(){var e=i(this),t=e.parent();e.val()?t.siblings(".crop-method").show():t.siblings(".crop-method").hide()}),i("#widgets-right").on("change",'.hide_header input[type="checkbox"]',function(){var e=i(this),t=e.parent().nextAll(".hide-cover-img, .hide-title, .hide-description, .hide-subscribe");e.is(":checked")?t.hide():t.show()}),i("#widgets-right").on("change","select.podcast-player-pp-fetch-method",function(){var e=i(this),t=e.parent(),n=t.siblings(".pp-options-wrapper"),s=n.find(".podcast-addinfo-toggle"),o=n.find(".pp-settings-content");"feed"===e.val()?(t.siblings(".feed-url").show(),t.siblings(".post-type-fetch").hide(),s.find(".is-premium-post").hide(),s.find(".is-feed").show(),o.find(".podcast-title").hide(),t.siblings(".pp-options-wrapper").show(),o.find(".hide-content").show(),t.siblings(".single-audio-fetch").hide()):"post"===e.val()?(t.siblings(".feed-url").hide(),t.siblings(".post-type-fetch").show(),s.find(".is-premium-post").show(),s.find(".is-feed").hide(),o.find(".podcast-title").show(),t.siblings(".pp-options-wrapper").show(),o.find(".hide-content").hide(),t.siblings(".single-audio-fetch").hide()):"link"===e.val()&&(t.siblings(".single-audio-fetch").show(),t.siblings(".feed-url").hide(),t.siblings(".post-type-fetch").hide(),t.siblings(".pp-options-wrapper").hide())}),i("#widgets-right").on("change","select.podcast-player-pp-post-type",function(){var e=i(this),t=e.val(),n=e.parent(),s=n.siblings(".pp-taxonomies").find(".podcast-player-pp-taxonomy");n.siblings(".pp-terms-panel").hide(),s.find("option").hide(),s.find("."+t).show(),s.find(".always-visible").show(),s.val("")}),i("#widgets-right").on("change","select.podcast-player-pp-taxonomy",function(){var e=i(this);e.val()?(e.parent().next(".pp-terms-panel").show(),e.parent().next(".pp-terms-panel").find(".pp_terms-checklist li").hide(),e.parent().next(".pp-terms-panel").find(".pp_terms-checklist ."+e.val()).show()):e.parent().next(".pp-terms-panel").hide()})}});