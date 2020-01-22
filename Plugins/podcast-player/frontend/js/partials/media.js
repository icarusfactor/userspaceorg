import props from './variables';

class MediaElements {

	/**
	 * Media play/pause button.
	 */
	plBtn;

	/**
	 * Media fast-forward button.
	 */
	forBtn;

	/**
	 * Media skipback button.
	 */
	bckBtn;

	/**
	 * Media transcript toggle button.
	 */
	ttBtn;

	/**
	 * Social share toggle button.
	 */
	ssBtn;

	/**
	 * Media Elements JS display control.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} id Podcast player ID.
	 */
	constructor(id) {

		this.podcast = props[id].podcast;
		this.mediaObj = props[id].mediaObj;
		this.controls = jQuery(this.mediaObj.controls);
		this.layers = this.controls.prev('.ppjs__layers');
		this.media = this.mediaObj.media;
		this.modalObj = props[id].modal;
		this.settings = props[id].settings;
		this.transcript = props[id].episode;
		this.list = props[id].list;
		this.props = props[id];

		this.modControlMarkup();
		this.modLayersMarkup();

		this.plBtn = this.controls.find( '.ppjs__playpause-button > button' );
		this.forBtn = this.controls.find( '.ppjs__jump-forward-button > button' );
		this.bckBtn = this.controls.find( '.ppjs__skip-backward-button > button' );
		this.ttBtn = this.controls.find( '.ppjs__script-button > button' );
		this.ssBtn = this.controls.find( '.ppjs__share-button > button' );
		this.pbrBtn = this.controls.find( '.ppjs__play-rate-button > button' );

		this.events();
	}

	/**
	 * PodcastTabs event handling.
	 * 
	 * @since 1.3
	 */
	events() {

		// Toggle play button class on play or pause events.
		this.media.addEventListener('loadedmetadata', this.condbtnPauseMedia.bind(this));
		this.media.addEventListener('play', this.btnPlayMedia.bind(this));
		this.media.addEventListener('playing', this.btnPlayMedia.bind(this));
		this.media.addEventListener('pause', this.btnPauseMedia.bind(this));
		this.plBtn.click(this.playPause.bind(this));
		this.forBtn.click(this.forwardMedia.bind(this));
		this.bckBtn.click(this.skipbackMedia.bind(this));
		this.ttBtn.click(this.showtranscript.bind(this));
		this.ssBtn.click(this.showsocialshare.bind(this));
		this.pbrBtn.click(this.mediaPlayRate.bind(this));
		this.podcast.find('.episode-single__close').click(this.hidetranscript.bind(this));
	}

	/**
	 * Play/pause media on button click.
	 * 
	 * @since 1.3
	 */
	playPause() {

		if (this.mediaObj.paused) {
			this.mediaObj.play();
			this.plBtn.addClass( 'playing' );
		} else {
			this.mediaObj.pause();
			this.plBtn.removeClass( 'playing' );
		}
	}

	/**
	 * Forward audio by specified amount of time.
	 */
	forwardMedia() {

		const interval = 15;
		let currentTime;
		let duration;

		duration = !isNaN(this.media.duration) ? this.media.duration : interval;
		currentTime = ( this.media.currentTime === Infinity ) ? 0 : this.media.currentTime;
		this.media.setCurrentTime(Math.min(currentTime + interval, duration));
		this.forBtn.blur();
	}

	/**
	 * Skip back media by specified amount of time.
	 * 
	 * @since 1.3
	 */
	skipbackMedia() {

		const interval = 15;
		let currentTime;

		currentTime = ( this.media.currentTime === Infinity ) ? 0 : this.media.currentTime;
		this.media.setCurrentTime(Math.max(currentTime - interval, 0));
		this.bckBtn.blur();
	}

	/**
	 * Change media play back rate.
	 * 
	 * @since 2.0
	 */
	mediaPlayRate() {
		const curItem = this.pbrBtn.find('.current');
		let nxtItem = curItem.next('.pp-rate');
		const times   = this.pbrBtn.find('.pp-times');
		if (0 === nxtItem.length ) nxtItem = this.pbrBtn.find('.pp-rate').first();
		const num = parseFloat(nxtItem.text());
		curItem.removeClass('current');
		nxtItem.addClass('current');
		if (nxtItem.hasClass('withx')) {
			times.show();
		} else {
			times.hide();
		}
		this.media.playbackRate = num;
		this.pbrBtn.blur();
	}

	/**
	 * Manage button class for playing media.
	 */
	btnPlayMedia() {

		this.plBtn.addClass('playing');
		if (!this.podcast.hasClass('postview')) {
			if (this.modalObj.modal && this.modalObj.modal.hasClass('pp-modal-open')) {
				this.modalObj.returnElem();
			}
		}
	}

	/**
	 * Manage button class for pausing media.
	 */
	btnPauseMedia() {

		this.plBtn.removeClass('playing');
	}

	/**
	 * Show podcast transcript.
	 */
	showtranscript() {

		this.transcript.slideToggle('fast');
		this.ttBtn.parent().toggleClass('toggled-on');
	}

	/**
	 * Hide podcast transcript.
	 */
	hidetranscript() {

		this.transcript.slideUp('fast');
		this.ttBtn.parent().removeClass('toggled-on');
	}

	/**
	 * Show podcast transcript.
	 */
	showsocialshare() {
		const player = this.ssBtn.closest('.pp-podcast__player');
		const socialWrapper = player.siblings('.pod-content__social-share');

		socialWrapper.slideToggle('fast');
		this.ssBtn.parent().toggleClass('toggled-on');
	}

	/**
	 * Conditionally manage button for media.
	 */
	condbtnPauseMedia() {

		if (this.media.rendererName.indexOf('flash') === -1) {
			this.plBtn.removeClass( 'playing' );
		}
	}

	/**
	 * Modify media controls markup
	 * 
	 * @since 1.3
	 */
	modControlMarkup() {

		let tempMarkup;
		let episodeTitle;

		if (this.mediaObj.isVideo) {

			// Add SVG icons to video control section.
			this.controls.prepend(this.settings.ppPlayPauseBtn);
			this.controls.find('.ppjs__fullscreen-button > button').html(this.settings.ppMaxiScrnBtn + this.settings.ppMiniScrnBtn);
		} else {
			
			// Add forward and backward buttons to audio control section.
			this.controls.find('.ppjs__time').wrapAll('<div class="ppjs__audio-timer" />');
			this.controls.find('.ppjs__time-rail').wrap('<div class="ppjs__audio-time-rail" />');
			tempMarkup = jQuery('<div />', { class: 'ppjs__audio-controls' });
			tempMarkup.html(this.settings.ppAudioControlBtns);
			this.controls.prepend(tempMarkup);
			if ( this.props.isWide ) {
				episodeTitle = this.transcript.find('.episode-single__title').text();
				this.controls.find('.ppjs__episode-title').text(episodeTitle);
			}
		}
	}

	/**
	 * Modify mediaelement layers markup
	 * 
	 * @since 1.3
	 */
	modLayersMarkup() {

		// Add SVG icon markup to media layers elements.
		this.layers.find( '.ppjs__overlay-play > .ppjs__overlay-button' ).html( this.settings.ppPlayCircle );
		this.layers.find( '.ppjs__overlay > .ppjs__overlay-loading' ).html( this.settings.ppVidLoading );
	}
}

export default MediaElements;
