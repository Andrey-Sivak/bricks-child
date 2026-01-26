'use strict';

import { __ } from '@wordpress/i18n';

( function () {
	class Header {
		constructor() {
			this.header = document.querySelector( '.brxe-block.header' );
			this.mobBurgerBtn = null;

			if ( ! this.header ) {
				return;
			}

			this.menus = [
				...this.header.querySelectorAll( '.nav__wrapper-items' ),
			];
			this.boundScrollHandler = this.scrollHandler.bind( this );
			this.boundDisplayMobMenuHandler = this.displayMobMenu.bind( this );
			this.boundMenuClickHandler = this.handleMenuClick.bind( this );
			this.mobMenuButton = `<button class="mob-burger-btn"
											type="button"
											aria-expanded="false"
											aria-controls="mobile-menu"
											title="${ __( 'Show/hide mobile menu', 'bricks-child' ) }">
									<span class="ft-visually-hidden">${ __( 'Menu', 'bricks-child' ) }</span>
									<span class="mob-burger-btn-top"></span>
									<span class="mob-burger-btn-center"></span>
									<span class="mob-burger-btn-bottom"></span>
								</button>`;

			this.init();
		}

		init() {
			console.log( 'Header init' );

			this.addMobileMenuButton();

			this.boundScrollHandler();
			window.addEventListener( 'scroll', this.boundScrollHandler );

			if ( this.menus.length ) {
				this.menus.forEach( ( menu ) =>
					menu.addEventListener( 'click', this.boundMenuClickHandler )
				);
			}
		}

		displayMobMenu() {
			document.documentElement.classList.toggle( 'mob-menu-active' );

			this.mobBurgerBtn.setAttribute(
				'aria-expanded',
				document.documentElement.classList.contains( 'mob-menu-active' )
			);
		}

		handleMenuClick( e ) {
			const item = e.target.closest( 'li' );
			const isDropdown = item.classList.contains( 'nav__dropdown' );

			if ( ! isDropdown ) {
				return;
			}

			this.toggleSubmenu( item );
		}

		isTouchDevice() {
			return window.matchMedia( '(hover: none)' ).matches;
		}

		toggleSubmenu( menuItem ) {
			menuItem.classList.toggle( 'ft-active' );
		}

		addMobileMenuButton() {
			const insertTo = this.header.querySelector( '#brxe-kifgrw' );

			if ( ! insertTo ) {
				return;
			}

			insertTo.insertAdjacentHTML( 'afterbegin', this.mobMenuButton );
			this.mobBurgerBtn = document.querySelector( '.mob-burger-btn' );

			this.mobBurgerBtn.addEventListener(
				'click',
				this.boundDisplayMobMenuHandler
			);
		}

		isHeaderHide( scrolled ) {
			return scrolled > 100 && scrolled > this.scrollPrev;
		}

		isHeaderScrolled( scrolled ) {
			return scrolled > 100;
		}

		scrollHandler() {
			const scrolled = window.scrollY;

			if ( this.isHeaderHide( scrolled ) ) {
				this.header.classList.add( 'ft-out' );
			} else {
				this.header.classList.remove( 'ft-out' );
			}

			if ( this.isHeaderScrolled( scrolled ) ) {
				this.header.classList.add( 'ft-scrolled' );
			} else {
				this.header.classList.remove( 'ft-scrolled' );
			}

			this.scrollPrev = scrolled;
		}
	}

	new Header();
} )();
