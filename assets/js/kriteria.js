const modCode = 'module_perkebunan'
const sideMenu = $(`#sidebarnav #${modCode}`)

let route = $('body').data('route')
let pageName = $('body').data('title')

let language = []

kriteria = {
	__construct() {
		sideMenu.addClass('active')
			.find('[data-sub-module="kriteria"]').addClass('active')

		$('.select-picker').selectpicker()
	},
	index: {
		init() {
			let addDefs = [{
				className: 'text-center',
				targets: [2]
			}]

			FUNC._dataTable('#kriteria-list', `${route}fetch`, 10, addDefs)

			this._create()
			this._update()
			this._delete()
		},
		_validation() {
			let t = this

			FUNC._formValidation('#kriteria-form', {
					name: {
						required: true,
						maxlength: 15
					},
				}, {},
				(form) => {
					t._submitHandler(form)
				}
			)
		},
		_submitHandler(form) {
			let t = this

			let args = {
				id: $('input[name="id"]').val(),
				name: $('input[name="name"]').val(),
			}

			FUNC._ajax(`${route}before-saving`, FUNC._jsonToQueryString(args)).done(res => {

				if (res.success) {
					FUNC._toastr(res.message, 'warning')
					return false
				} else {
					t._submitForm(form)
				}
			})
		},
		_create() {
			let t = this

			$('.btn-add').on('click', function (e) {
				e.preventDefault()
				let el = $(this)

				FUNC._modal('open', {
					title: `Add ${pageName}`,
					body: {
						url: el.attr('href')
					},
					btnAction: {
						cssClass: 'btn-outline-custom',
						text: 'Save',
						onPress: () => {
							$('#kriteria-form').submit()
						}
					},
					doSomething: () => {
						t._validation()
					}
				})
			})
		},
		_update() {
			let t = this

			$('#kriteria-list tbody').on('click', '.update-control', function (e) {
				e.preventDefault()
				let el = $(this)

				FUNC._modal('open', {
					title: `Edit ${pageName}`,
					body: {
						url: el.attr('href')
					},
					btnAction: {
						cssClass: 'btn-outline-custom',
						text: 'Save Changes',
						onPress: () => {
							$('#kriteria-form').submit()
						}
					},
					doSomething: () => {
						t._validation()
					}
				})
			})
		},
		_delete() {
			$('#kriteria-list tbody').on('click', '.delete-control', function (e) {
				e.preventDefault()
				let el = $(this)

				FUNC._confirmation('Do you really want to delete this record?', {
					doOk: () => {
						FUNC._ajax(el.attr('href')).done(res => {

							if (res.success) {
								FUNC._toastr(res.message)
								table.ajax.reload(null, false)
							} else {
								FUNC._toastr(res.message, 'error')
							}
						})
					}
				}, `Delete ${pageName}`, 'Ok', 'Cancel')
			})
		},
		_submitForm(form) {
			FUNC._ajax($(form).attr('action'), $(form).serialize()).done(res => {

				if (res.success) {
					FUNC._toastr(res.message)
					table.ajax.reload(null, false)

					$('#global-modal').modal('hide')
				} else {
					FUNC._toastr(res.message, 'error')
				}
			})
		}
	}

}