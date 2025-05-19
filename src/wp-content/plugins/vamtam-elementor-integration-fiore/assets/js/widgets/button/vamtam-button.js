class VamtamButton extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				btn: '.elementor-button',
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );
		return {
			$btn: this.$element.find( selectors.btn ),
		};
	}

	onInit( ...args ) {
		super.onInit( ...args );
		this.handleBtnUnderlineAnimation();
	}

	handleBtnUnderlineAnimation() {
		if ( ! this.$element.hasClass( 'vamtam-has-underline-anim' ) ) {
			return;
		}

		const _this = this;

		// Add class on hover to trigger the animation.
		this.elements.$btn.on( 'mouseenter', () => {
			if ( _this.elements.$btn.hasClass( 'hovered' ) ) {
				return;
			}

			_this.elements.$btn.addClass( 'hovered' );
			setTimeout(() => {
				_this.elements.$btn.removeClass( 'hovered' );
			}, 600 );
		} );
	}
}


jQuery( window ).on( 'elementor/frontend/init', () => {
	if ( ! elementorFrontend.elementsHandler || ! elementorFrontend.elementsHandler.attachHandler ) {
		const addHandler = ( $element ) => {
			elementorFrontend.elementsHandler.addHandler( VamtamButton, {
				$element,
			} );
		};

		elementorFrontend.hooks.addAction( 'frontend/element_ready/button.default', addHandler, 100 );
	} else {
		elementorFrontend.elementsHandler.attachHandler( 'button', VamtamButton );
	}
} );
