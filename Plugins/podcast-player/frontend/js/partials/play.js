import props from './variables';

class PlayEpisode {

	/**
	 * Currently clicked list item.
	 */
	listItem;

	/**
	 * Manage podcast tabs elements.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} id Podcast player ID.
	 */
	constructor(id) {

		this.id = id;
		this.podcast = props[id].podcast;
		this.list = props[id].list;
		this.episode = props[id].episode;
		this.player = props[id].player;
		this.mediaObj = props[id].mediaObj;
		this.instance = props[id].instance;
		this.modalObj = props[id].modal;
		this.singleWrap = props[id].singleWrap;
		this.data = window.podcastPlayerData || {};

		this.events();
	}

	/**
	 * PodcastTabs event handling.
	 * 
	 * @since 1.3
	 */
	events() {

		const _this = this;
		if (! _this.podcast.hasClass('postview')) {
			_this.list.on('click', '.episode-list__entry, .episode-list__search-entry', function(e) {
				e.preventDefault();
				_this.listItem = jQuery(this);
				_this.play();
			});
		} else {
			_this.list.on('click', '.pod-entry__title a, .pod-entry__featured', function(e) {
				const $this = jQuery(this);
				if ( $this.hasClass('fetch-post-title') ) return;
				const pid = `pp-podcast-${_this.instance}`;
				const info = _this.data[pid].load_info;
				let hideDescription = info ? (info.args ? info.args.hddesc : false) : false;
				hideDescription = hideDescription ? hideDescription : false;
				const isModalView = (! $this.hasClass('pod-entry__featured') && ! hideDescription) || _this.mediaObj.isVideo;
				e.preventDefault();
				_this.listItem = $this.closest('.pod-entry');
				_this.playModal(isModalView);
			});
		}
	}

	/**
	 * Common actions before plating podcast episode.
	 * 
	 * @since 2.0
	 */
	common() {
		const pid = `pp-podcast-${this.instance}`;
		const id = this.listItem.attr('id');
		let share = this.singleWrap.find('.pod-content__social-share');
		let active, details, ppurl, pptitle;

		// Remove active class from previously active episode.
		active = this.list.find('.activeEpisode')
		if ( 0 < active.length ) {
			active.removeClass( 'activeEpisode media-playing' );
		}

		// Update podcast data on single podcast wrapper.
		if ( this.listItem.hasClass( 'episode-list__search-entry' ) ) {
			details = this.data.search[id];
		} else {
			details = this.data[pid][id];
		}

		// Generate social sharing links.
		ppurl   = encodeURIComponent(details.link);
		pptitle = encodeURIComponent(details.title);

		const fburl = "https://www.facebook.com/sharer.php?u=" + ppurl;
		const twurl = "https://twitter.com/intent/tweet?url=" + ppurl + "&text=" + pptitle;
		const liurl = "https://www.linkedin.com/shareArticle?mini=true&url=" + ppurl;
		const mail  = "mailto:?subject=" + pptitle + "&body=Link:" + ppurl;

		this.listItem.addClass( 'activeEpisode media-playing' );
		this.episode.find( '.episode-single__title' ).html( details.title );
		this.episode.find( '.episode-single__author > .single-author' ).html( details.author );
		this.player.find('.ppjs__episode-title').html(details.title);
		this.episode.find( '.episode-single__description' ).html( details.description );
		share.find( '.ppsocial__facebook' ).attr( 'href', fburl );
		share.find( '.ppsocial__twitter' ).attr( 'href', twurl );
		share.find( '.ppsocial__linkedin' ).attr( 'href', liurl );
		share.find( '.ppsocial__email' ).attr( 'href', mail );
		share.find( '.ppshare__download' ).attr( 'href', details.src );
		this.mediaObj.setSrc( details.src );
		this.mediaObj.load();
		return true;
	}

	/**
	 * Play episode in player view.
	 * 
	 * @since 1.3
	 */
	play() {

		// If current episode is already playing. Let's pause it.
		if ( this.listItem.hasClass( 'activeEpisode' ) && ! this.mediaObj.paused ) {
			this.listItem.removeClass( 'activeEpisode' );
			this.mediaObj.pause();
			return;
		}

		if (this.modalObj.modal && this.modalObj.modal.hasClass('pp-modal-open')) {
			this.modalObj.returnElem();
		}

		// Perform common actions before plating podcast.
		this.common();

		// Auto play the media.
		this.mediaObj.play();

		// Scroll window to top of the single episode for better UX.
		jQuery( 'html, body' ).animate({ scrollTop: this.player.offset().top - 200 }, 400 );
	}

	/**
	 * Play episode in post view.
	 * 
	 * Episodes will be played in a Modal window.
	 * 
	 * @since 2.0
	 * 
	 * @param {bool} isModalView
	 */
	playModal(isModalView) {
		if (! this.modalObj) return;
		// If current episode is already playing. Let's pause it.
		if (this.listItem.hasClass('activeEpisode')) {
			if (isModalView) {
				this.modalObj.modal.removeClass('inline-view').addClass('modal-view');
				this.modalObj.scrollDisable();
				this.mediaObj.play();
				this.modalObj.modal.removeClass('media-paused');
				this.listItem.addClass('media-playing');
			} else {
				if (!this.mediaObj.paused) {
					this.mediaObj.pause();
					this.modalObj.modal.addClass('media-paused');
					this.listItem.removeClass('media-playing');
				} else {
					this.mediaObj.play();
					this.modalObj.modal.removeClass('media-paused');
					this.listItem.addClass('media-playing');
				}
			}
			return;
		}

		// Perform common actions before playing podcast.
		this.common();

		if (!this.singleWrap.hasClass('activePodcast')) {
			if (this.modalObj.modal.hasClass('pp-modal-open')) {
				this.modalObj.returnElem();
			}
			this.modalObj.create(this.singleWrap, this.mediaObj, isModalView);
			this.singleWrap.addClass('activePodcast');
		}

		// Auto play the media.
		this.mediaObj.play();
	}
}

export default PlayEpisode;
