class VamtamHrScrolling extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.elementor-widget-container',
				products: '.products.elementor-grid, .elementor-posts-container',
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );
		return {
			$container: this.$element.find( selectors.container ),
			$products: this.$element.find( selectors.products ),
		};
	}

	onInit( ...args ) {
		super.onInit( ...args );
		this.checkHandleHrLayout();
	}

	checkHandleHrLayout() {
		const isTouchDevice = window.VAMTAM.CUSTOM_ANIMATIONS.VamtamCustomAnimations.utils.isTouchDevice();

		// We don't want to add the navigation on touch devices.
		if ( isTouchDevice ) {
			return;
		}

		const hasHrLayout = this.$element.hasClass( 'vamtam-has-hr-layout' ),
			hasNav = this.$element.hasClass( 'vamtam-has-nav' );

		// No need to add the navigation if the element doesn't have the hr layout.
		if ( ! hasHrLayout ) {
			return;
		}

		// Has the hr layout and is not a touch device.
		// Add the navigation.
		if ( hasNav ) {
			this.handleHrLayoutNavigation();
		}

	}

	handleHrLayoutNavigation() {

		// Create and insert the nav elements.
		const addNavElements = () => {
			const $navigation = jQuery(
				`<div class="vamtam-nav">
					<span class="vamtam-nav-btn vamtam-nav-btn-prev">
						<i class="vamtamtheme- vamtam-theme-arrow-left"></i>
					</span>
					<span class="vamtam-nav-btn vamtam-nav-btn-next">
						<i class="vamtamtheme- vamtam-theme-arrow-right"></i>
					</span>
				</div>` );

			this.elements.$products.after( $navigation );
		};

		// Add the event listeners & handlers.
		const bindNavEvents = () => {
			const onNavBtnClick = ( e ) => {
				e.preventDefault();

				const $products = this.elements.$products,
					visibleWidth = $products.width(),
					isNext = jQuery( e.target ).hasClass( 'vamtam-nav-btn-next' );

				if ( isNext ) {
					// Scrll to the right.
					$products.scrollLeft( $products.scrollLeft() + visibleWidth );
				} else {
					// Scroll to the left.
					$products.scrollLeft( $products.scrollLeft() - visibleWidth );
				}
			};

			// Add the navigation btn events.
			jQuery( this.$element.find( '.vamtam-nav-btn' ) ).off( 'click' ).on( 'click', onNavBtnClick );

			const onProductsScroll = ( e ) => {
				const products = this.elements.$products[0],
					$products = this.elements.$products;

				if ( products.scrollLeft === 0 ) {
					// Not scrolled.
					this.$element.find( '.vamtam-nav-btn-prev' ).addClass( 'disabled' );
					this.$element.find( '.vamtam-nav-btn-next' ).removeClass( 'disabled' );
				} else if ( products.scrollLeft === ( products.scrollWidth - $products.width() ) ) {
					// Fully scrolled.
					this.$element.find( '.vamtam-nav-btn-prev' ).removeClass( 'disabled' );
					this.$element.find( '.vamtam-nav-btn-next' ).addClass( 'disabled' );
				} else {
					// In-between scroll.
					this.$element.find( '.vamtam-nav-btn-prev' ).removeClass( 'disabled' );
					this.$element.find( '.vamtam-nav-btn-next' ).removeClass( 'disabled' );
				}
			};
			const onProductsScrollDebounced200 = window.VAMTAM.debounce( onProductsScroll, 200 );
			const onProductsScrollDebounced500 = window.VAMTAM.debounce( onProductsScroll, 500 );

			// Add the scroll event listener.
			this.elements.$products.off( 'scroll', onProductsScrollDebounced200 );
			this.elements.$products.on( 'scroll', onProductsScrollDebounced200 );

			// Add the resize event listener.
			jQuery( window ).off( 'resize', onProductsScrollDebounced500 );
			jQuery( window ).on( 'resize', onProductsScrollDebounced500 );

			// Trigger on render in case a previous (remembered by browser) scroll position is used.
			this.elements.$products.trigger( 'scroll' );
		};

		addNavElements();
		bindNavEvents();
	}

}

jQuery( window ).on( 'elementor/frontend/init', () => {

	const attachTo = [
		{ name: 'woocommerce-products', skin: 'default' },
		{ name: 'woocommerce-product-related', skin: 'default' },
		{ name: 'woocommerce-product-upsell', skin: 'default' },
		{ name: 'posts', skin: 'classic' },
		{ name: 'posts', skin: 'vamtam_classic' },
		{ name: 'archive-posts', skin: 'archive_classic' },
		{ name: 'archive-posts', skin: 'vamtam_classic' },
	];

	if ( ! elementorFrontend.elementsHandler || ! elementorFrontend.elementsHandler.attachHandler ) {
		const addHandler = ( $element ) => {
			elementorFrontend.elementsHandler.addHandler( VamtamHrScrolling, {
				$element,
			} );
		};

		attachTo.forEach( widget => {
			elementorFrontend.hooks.addAction( `frontend/element_ready/${widget.name}.${widget.skin}`, addHandler, 100 );
		} );
	} else {
		attachTo.forEach( widget => {
			elementorFrontend.elementsHandler.attachHandler( widget.name, VamtamHrScrolling, widget.skin );
		} );
	}
} );
