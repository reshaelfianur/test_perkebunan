const modCode = 'module_perkebunan'
const sideMenu = $(`#sidebarnav #${modCode}`)

let route = $('body').data('route')
let pageName = $('body').data('title')

let language = []

transaksi_detail = {
	__construct() {
		sideMenu.addClass('active')
			.find('[data-sub-module="transaksi-detail"]').addClass('active')

		$('.select-picker').selectpicker()
	},
	index: {
		init() {
			let addDefs = [{
				className: 'text-center',
				targets: [4]
			}]

			FUNC._dataTable('#transaksi-detail-list', `${route}fetch`, 10, addDefs)

			this._create()
			this._update()
			this._delete()
		},
		_validation() {
			let t = this

			FUNC._formValidation('#transaksi-detail-form', {
					notrans: {
						required: true,
					},
					idbuah: {
						required: true,
						number: true
					},
					jumlah: {
						required: true,
						number: true
					},
				}, {},
				(form) => {
					t._submitForm(form)
				}
			)
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
							$('#transaksi-detail-form').submit()
						}
					},
					doSomething: () => {
						$('.select-picker').selectpicker()

						t._validation()
					}
				})
			})
		},
		_update() {
			let t = this

			$('#transaksi-detail-list tbody').on('click', '.update-control', function (e) {
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
							$('#transaksi-detail-form').submit()
						}
					},
					doSomething: () => {
						$('.select-picker').selectpicker()
						
						t._validation()
					}
				})
			})
		},
		_delete() {
			$('#transaksi-detail-list tbody').on('click', '.delete-control', function (e) {
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