main = {

	init () {
		this._print()
	},
	_print () {
		$('.print').on('click', () => {
			window.print()
		})
	}

}

$(main.init())