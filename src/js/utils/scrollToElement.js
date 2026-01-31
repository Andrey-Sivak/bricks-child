'use strict';

/**
 * Smooth scroll to an element with easing animation
 * @param {string} targetId - id of a target element (without #)
 * @param {number} offset - offset from top in pixels (default: 50)
 * @param {number} duration - animation duration in ms (default: 500)
 */
const scrollToElement = ( targetId, offset = 50, duration = 500 ) => {
	if ( ! targetId ) {
		return;
	}

	const targetElement = document.getElementById( targetId );

	if ( ! targetElement ) {
		return;
	}

	const targetPosition =
		targetElement.getBoundingClientRect().top + window.scrollY - offset;

	const startPosition = window.pageYOffset;
	const distance = targetPosition - startPosition;
	let startTime = null;

	function easeInOutCubic( t ) {
		return t < 0.5 ? 4 * t * t * t : 1 - Math.pow( -2 * t + 2, 3 ) / 2;
	}

	function animationStep( currentTime ) {
		if ( startTime === null ) {
			startTime = currentTime;
		}

		const timeElapsed = currentTime - startTime;
		const progress = Math.min( timeElapsed / duration, 1 );

		window.scrollTo(
			0,
			startPosition + distance * easeInOutCubic( progress )
		);

		if ( timeElapsed < duration ) {
			requestAnimationFrame( animationStep );
		}
	}

	requestAnimationFrame( animationStep );
};

export default scrollToElement;
