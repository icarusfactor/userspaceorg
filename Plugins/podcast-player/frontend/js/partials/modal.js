class Modal {

	/**
	 * Currently clicked list item.
	 */
	modal;

	/**
	 * Create and manager podcast player modal window.
	 * 
	 * @since 2.0 
	 */
	constructor() {

		this.settings = window.ppmejsSettings || {};
		this.mediaObj = false;

		// Scrolling specific
		this.bodyScrollDisabled = false;
		this.scrollPosition = 0;
		this.scrollingElem = document.scrollingElement || document.documentElement || document.body;

		// Create modal markup.
		this.setup();

		// Run methods.
		this.events();

		this.pauseMedia = this.mediaPaused.bind(this);
	}

	// Setup modal markup.
	setup() {
		const { ppClose, ppMiniScrnBtn } = this.settings;
		const close = jQuery('<button />', { class: 'pp-modal-close' }).html( ppClose + ppMiniScrnBtn );
		const modal = `
		<div id="pp-modal-window" class="pp-modal-window">
			<div class="pp-modal-wrapper"></div>
			${close[0].outerHTML}
		</div>`;

		jQuery('body').append(modal);
		this.modal = jQuery('#pp-modal-window');
	}

	// Event handling.
	events() {
		const _this = this;
		this.modal.on('click', '.pp-modal-close', function() {
			if (_this.modal.hasClass('modal-view') && !_this.mediaObj.isVideo) {
				_this.modal.removeClass('modal-view').addClass('inline-view');
				_this.scrollEnable();
			} else {
				_this.returnElem();
				_this.modal.removeClass().addClass('pp-modal-window');
				_this.scrollEnable();
				if (_this.mediaObj) {
					_this.mediaObj.pause();
					_this.mediaObj = false;
				}
			}
		});

		this.modal.on('click', '.ppjs__playpause-button > button', function() {
			const btn = jQuery(this);
			if (! btn.hasClass('playing')) {
				_this.modal.addClass('media-paused');
				jQuery('#pp-modal-placeholder').parent().find('.activeEpisode').removeClass('media-playing');
			} else {
				_this.modal.removeClass('media-paused');
				jQuery('#pp-modal-placeholder').parent().find('.activeEpisode').addClass('media-playing');
			}
		});
	}

	// Create & display modal markup.
	create(elem, mediaObj, isModalView) {
		const placeHolder = jQuery('<div />', { id: 'pp-modal-placeholder' });
		const wrapper = this.modal.find('.pp-modal-wrapper');
		const id = elem.closest('.pp-podcast').attr('id');
		const inst = id.replace( 'pp-podcast-', '' );

		placeHolder.insertBefore(elem);
		wrapper.empty().append(elem.find('.episode-single__header').clone());
		wrapper.append(elem);
		wrapper.children().wrapAll('<div class="modal-' + inst +'">');
		if (isModalView) {
			this.modal.addClass('modal-view pp-modal-open');
			this.scrollDisable();
		} else {
			this.modal.addClass('inline-view pp-modal-open');
		}

		if (this.mediaObj) {
			this.mediaObj.pause();
		}
		this.mediaObj = mediaObj;
		const media = this.mediaObj.media;
		media.addEventListener('ended', this.pauseMedia);
	}

	// Setup modal markup.
	mediaPaused() {
		this.modal.addClass('media-paused');
	}

	// Return element to its original position.
	returnElem() {
		const wrapper = this.modal.find('.pp-modal-wrapper');
		const elem = wrapper.find('.pp-podcast__single');
		const placeHolder = jQuery('#pp-modal-placeholder');
		const media = this.mediaObj.media;

		// No element available to return.
		if (! elem.length || ! placeHolder.length) return;

		media.removeEventListener('ended', this.pauseMedia);

		// remove activeEpisode.
		placeHolder.parent().find('.activeEpisode').removeClass('activeEpisode media-playing');

		// Remove active class from the element.
		elem.removeClass('activePodcast');

		// Reset modal class.
		this.modal.removeClass().addClass('pp-modal-window');

		//Returning elem to its original position.
		elem.insertAfter(placeHolder);

		// Removing temporary items.
		wrapper.empty();
		placeHolder.remove();
	}

	/**
	 * Disable scroll on the element that scrolls the document.
	 * 
	 * @since 1.3.5
	 */
	scrollDisable() {

		// Return if scroll is already disabled.
		if (this.bodyScrollDisabled) {
			return;
		}

		this.scrollPosition = this.scrollingElem.scrollTop;
		this.bodyScrollDisabled = true;
		setTimeout(() => {
			this.scrollingElem.scrollTop = 0;
			this.scrollingElem.classList.add('no-scroll');
		}, 250);
	}

	/**
	 * Enable scroll on the element that scrolls the document.
	 * 
	 * @since 1.3.5
	 */
	scrollEnable() {

		// Return if scroll is already Enabled.
		if (! this.bodyScrollDisabled) {
			return;
		}

		this.scrollingElem.classList.remove('no-scroll');
		this.scrollingElem.scrollTop = this.scrollPosition;
		this.bodyScrollDisabled = false;
	}
}

export default Modal;
