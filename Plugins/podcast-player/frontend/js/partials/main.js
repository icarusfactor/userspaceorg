import props from './variables';
import Podcast from './podcast';
import Modal from './modal';

( $ => {

	'use strict';

	const podcasts = $( '.pp-podcast' );
	const settings = window.ppmejsSettings || {};

	const modal = settings.isPremium ? new Modal() : '';
	podcasts.each( function() {

		const podcast = $(this);
		const id = podcast.attr('id');
		const mediaObj = new MediaElementPlayer( id + '-player', settings );
		const list = podcast.find('.pod-content__list');
		const episode = podcast.find('.pod-content__episode');
		const episodes = list.find('.episode-list__wrapper');
		const single = episode.find('.episode-single__wrapper');
		const singleWrap = podcast.find('.pp-podcast__single');
		const player = podcast.find('.pp-podcast__player');
		props[id] = {
			podcast, mediaObj, settings, list, episode,
			episodes, single, player, modal, singleWrap,
			instance: id.replace( 'pp-podcast-', '' ),
		};

		new Podcast(id);
	} );
})(jQuery);
