var table;
var tableServerSide;

FUNC = {
	_confirmDelete(url, letTable = table) {
		Swal.fire({
			title: 'Are you sure?',
			text: 'You will not be able to recover this record!',
			type: 'warning',
			confirmButtonColor: '#DD6B55',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete it!',
			cancelButtonText: 'No, keep it'
		}).then((result) => {
			if (result.value) {
				this._ajax(url).done(res => {
					if (res.success) {
						Swal.fire({
							title: 'Deleted!',
							text: res.message,
							type: 'success',
							timer: 2000,
							showConfirmButton: false
						})
						letTable.ajax.reload(null, false)
					} else {
						Swal.fire("Cancelled!", res.message, "error")
					}
				})
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				Swal.fire({
					title: 'Cancelled',
					text: 'this record is safe :)',
					type: 'error',
					timer: 2000,
					showConfirmButton: false
				})
			}
		})
	},
	_generateCsrfToken(res) {
		$('meta[name="csrf_token"]').attr('content', res.csrfHash)
		$('input[name="csrf_token"]').val(res.csrfHash)
	},
	_month(param = '') {
		const month = [
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December'
		]

		if (param != '') {
			return month[param]
		} else {
			return month
		}
	},
	_daysName() {
		const daysName = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
		return daysName
	},
	_idrCurrency() {
		const idr = 69
		return idr
	},
	_ajax(toUrl, toData, type = 'post') {
		let finalUrl = (!/^(f|ht)tps?:\/\//i.test(toUrl) ? (baseUrl() + toUrl) : toUrl)

		let param = {
			url: finalUrl,
			data: 'csrf_token=' + $('meta[name="csrf_token"]').attr('content') + '&' + toData
		}

		if (type == 'post') {
			param = Object.assign(param, {
				type: 'post',
				dataType: 'json'
			})
		} else {
			param = Object.assign(param, {
				type: 'get',
			})
		}

		return $.ajax(param)
	},
	_ajaxFile(toUrl, toData) {
		let finalUrl = (!/^(f|ht)tps?:\/\//i.test(toUrl) ? (baseUrl() + toUrl) : toUrl)

		return $.ajax({
			url: finalUrl,
			type: 'post',
			data: toData,
			success(msg) {
				console.log(msg)
			},
			cache: false,
			contentType: false,
			processData: false
		})
	},
	_formValidation(el, rules, message, submitHandler, unhighlight = true) {
		$(el).validate({
			// debug: true,
			errorElement: 'span',
			errorClass: 'invalid-feedback d-block',
			focusInvalid: true,
			rules: rules,
			message: message,
			highlight: (e) => {
				if ($(e).is('select')) {
					$(e).closest('.bootstrap-select').addClass('is-invalid').removeClass('is-valid')
				}
				$(e).addClass('is-invalid').removeClass('is-valid')
				$(e).closest('.form-group').addClass('invalid').removeClass('valid')
			},
			unhighlight: (e) => {
				$(e).closest('.input-group').find('.invalid-feedback').remove()
				$(e).removeClass('is-invalid').closest('.form-group').removeClass('invalid')

				if ($(e).is('select')) {
					$(e).closest('.bootstrap-select').removeClass('is-invalid')
				}
				if (unhighlight) {
					if ($(e).is('select')) {
						$(e).closest('.bootstrap-select').addClass('is-valid')
					}
					$(e).addClass('is-valid').closest('.form-group').addClass('valid')
				}
			},
			errorPlacement: (error, element) => {
				if (element.hasClass('date-picker') || element.hasClass('with-icon')) {
					element.closest('.input-group').append(error)
				} else if (element.is('select')) {
					element.closest('.bootstrap-select').append(error)
				} else if (element.is('input[type=radio]')) {
					element.closest('div').append(error)
				} else {
					error.insertAfter(element)
				}
			},
			submitHandler: (form) => {
				submitHandler(form)
			}
		})
	},
	_modal(type, args, modalType) {
		let e = modalType == 'child' ? 'global-child-modal' : 'global-modal';
		let el = $(`#${e}`)

		if (type == 'close') {
			el.modal('hide')
		} else {
			this._ajax(args.body.url, args.body.params, 'get').done(res => {
				el.find('.modal-title').html(args.title)
				el.find('.modal-body').html(res)

				el.find('.btn-action')
					.removeClass('btn-primary btn-secondary btn-success btn-info btn-warning btn-danger btn-light btn-dark btn-megna')
					.addClass(args.btnAction.cssClass)
					.html(args.btnAction.text)

				if (typeof args.btnClose !== 'undefined') {
					el.find('.btn-close')
						.removeClass('btn-primary btn-secondary btn-success btn-info btn-warning btn-danger btn-light btn-dark btn-megna')
						.addClass(args.btnClose.cssClass)
						.html(args.btnClose.text)

					if (typeof args.btnClose.onPress !== 'undefined') {
						el.find('.btn-close').off().on('click', () => {
							args.btnClose.onPress()
						})
					}
				}

				if (typeof args.btnAction.onPress !== 'undefined') {
					el.find('.btn-action').off().on('click', () => {
						args.btnAction.onPress()
					})
				}

				if (typeof args.doSomething !== 'undefined') {
					args.doSomething()
				}

				el.modal('show')
			})
		}
	},
	_confirmation(message, args, title = 'Processing Data', labelOk = 'Ok', labelCancel = 'Cancel') {
		alertify.confirm(message)
			.set('onok', function (res) {
				if (typeof args.doOk !== 'undefined') {
					args.doOk()
				}
			}).set('oncancel', function (res) {
				if (typeof args.doCancel !== 'undefined') {
					args.doCancel()
				}
			})
			.set('labels', {
				ok: labelOk,
				cancel: labelCancel
			})
			.set({
				title: title
			})
			.set({
				closableByDimmer: false
			})
	},
	_loadingAnimation(show = true) {
		setInterval(() => {
			if (show) {
				$('.preloader').fadeIn()
			} else {
				$('.preloader').fadeOut()
			}
		}, 100);
	},
	_dateColumnOrder() {
		jQuery.extend(jQuery.fn.dataTableExt.oSort, {
			"date-uk-pre": (a) => {
				if (a == null || a == "") {
					return 0;
				}
				let ukDatea = a.split('/')
				return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
			},

			"date-uk-asc": (a, b) => {
				return ((a < b) ? -1 : ((a > b) ? 1 : 0))
			},

			"date-uk-desc": (a, b) => {
				return ((a < b) ? 1 : ((a > b) ? -1 : 0))
			}
		})
	},
	_dataTable(el, url = null, perPage = 10, addDefs = 0, isDateOrder = false, columnsParam = {}, rowCreated = null) {
		if (isDateOrder) {
			this._dateColumnOrder()
		}

		let defaultDefs = [{
			targets: 'no-sort',
			orderable: false
		}]
		let finalDefs = typeof addDefs == 'object' ? defaultDefs.concat(addDefs) : defaultDefs;

		let ajaxUrl = {
			ajax: {
				url: baseUrl(url),
				type: 'post',
				// data: { csrf_token: $('meta[name="csrf_token"]').attr('content') },
				dataSrc: (json) => {
					$('meta[name="csrf_token"]').attr('content', json.csrfHash)
					return json.data
				},
				data: (data) => {
					data.csrf_token = $('meta[name="csrf_token"]').attr('content')
				},
			},
		}
		ajaxUrl = Object.keys(columnsParam).length !== 0 && Array.isArray(columnsParam) ? Object.assign(ajaxUrl, { columns: columnsParam }) : ajaxUrl

		let args = {
			lengthChange: true,
			aaSorting: [],
			pageLength: perPage,
			fixedHeader: true,
			responsive: true,
			sDom: 'rtip',
			columnDefs: finalDefs,
			createdRow: (tr, row, dataIndex) => {
				if (rowCreated != null) {
					rowCreated(tr, row, dataIndex)
				}
			},
			order: []
		}
		let finalArgs = typeof url == 'string' ? Object.assign(args, ajaxUrl) : args;

		table = $(el).DataTable(finalArgs)

		// $('#global-modal').on('shown.bs.modal', function () {
		// 	table.columns.adjust()
		// })

		$('.key-search').each(function () {
			$(this).on('keyup', function () {
				table.search(this.value).draw()
			})
		})

		$(el).closest('.table-responsive').prevAll('.d-inline-block').find('select')
			.val(table.page.len())
			.on('change', function () {
				table.page.len(this.value).draw()
			})
	},
	_dataTableServerSide(id, url, columnsParam, columnDefsParam, params, rowCreated = null, stateSave = true) {
		let defaultDefs = [{
			targets: 'no-sort',
			orderable: false
		}];

		let finalDefs = typeof columnDefsParam == 'object' ? defaultDefs.concat(columnDefsParam) : defaultDefs;

		tableServerSide = $(id).DataTable({
			processing: true,
			serverSide: true,
			aaSorting: [],
			order: [
				// [0, 'asc']
			],
			pageLength: 10,
			sDom: 'rtip',
			stateSave: stateSave,
			stateDuration: -1,
			stateLoadParams: (settings, data) => {
				$('.key-search').val(data.search.search)
				$('#length-change').val(data.length).selectpicker('refresh')
			},
			ajax: {
				url: baseUrl(url),
				type: 'post',
				// data: { csrf_token: $('meta[name="csrf_token"]').attr('content'), param: params },
				dataSrc: (json) => {
					$('meta[name="csrf_token"]').attr('content', json.csrfHash)
					return json.data
				},
				data: (data) => {
					data.csrf_token = $('meta[name="csrf_token"]').attr('content')
					data.param = params
				},
				error: (err) => {
					console.log(err)
				}
			},
			columns: columnsParam,
			columnDefs: finalDefs,
			createdRow: (tr, row, dataIndex) => {
				if (rowCreated != null) {
					rowCreated(id, tr, row, dataIndex)
				}
			},

		})
		let typingTimer
		let doneTypingInterval = 500

		$('.key-search').each(function () {
			$(this).off().on('keyup', function () {
				let val = this.value
				clearTimeout(typingTimer)

				typingTimer = setTimeout(
					() => {
						tableServerSide.search(val).draw()
					},
					doneTypingInterval
				)
			})
		})

		$(id).closest('.table-responsive').prevAll().eq(1).find('select').on('change', function () {
			tableServerSide.page.len(this.value).draw()
		})
	},
	_jsonToQueryString(obj) {
		let str = []
		for (let p in obj) {
			if (obj.hasOwnProperty(p)) {
				str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]))
			}
		}

		return str.join("&")
	},
	_clearDate() {
		$('.clear-date').on('click', function () {
			$(this).closest('.input-group').find('input').val('')
		})
	},
	_clearForm(formId) {
		formId.find('.form-control').val('')
	},
	_datePickerId() {
		$.fn.datepicker.dates['id'] = {
			days: ["Minggu", "Senin", "Selasa", "Rabu", "kamis", "Jumat", "Sabtu"],
			daysShort: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
			daysMin: ["Mg", "Sn", "Sl", "Rb", "Km", "Ja", "St"],
			months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
			monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
			today: "Hari Ini",
			clear: "Bersihkan",
			format: "d MM yyyy",
			titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
			weekStart: 0
		};
	},
	_datePicker(langueage = 'en') {
		this._datePickerId()

		$('.date-picker').each(function () {
			$(this).datepicker({
				// daysOfWeekDisabled: weekendHolidaysDate,
				// datesDisabled: holidayDetail,
				langueage: langueage,
				format: 'd MM yyyy',
				todayBtn: 'linked',
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true,
				// startDate: '0d',
				// endDate: '+1y'
			}).on('changeDate', function (e) {
				$(this).parent('.input-group').find('input[type="hidden"]').val(e.format('yyyy-mm-dd'))
			}).on('blur', function (e) {
				if ($(this).val() == '') {
					$(this).parent('.input-group').find('input[type="hidden"]').val('')
				}
			})
		})
	},
	_customFile() {
		$('input[type="file"]').change((e) => {
			let fileName = e.target.files[0].name
			$('.custom-file-label').html(fileName)
		})
	},
	_numberFormat(strNumber, prefix = '') {
		return prefix + strNumber.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
	},
	_moneyFormat(number, prefix = '', decimal = 2) {
		return prefix + parseFloat(number.replace('.', null)).toFixed(decimal).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
	},
	_formatDate(date, format = 'Y-m-d') {
		let t = this
		let d = new Date(date)

		month = '' + (d.getMonth() + 1)
		day = '' + d.getDate()
		year = d.getFullYear()

		if (month.length < 2)
			month = '0' + month
		if (day.length < 2)
			day = '0' + day

		if (format == 'd/m/y') {
			year = year.toString().substr(-2)
			date = [day, month, year].join('/')
		} else if (format == 'd-m-y') {
			year = year.toString().substr(-2)
			date = [day, month, year].join('-')
		} else if (format == 'd/m/Y') {
			date = [day, month, year].join('/')
		} else if (format == 'd-m-Y') {
			date = [day, month, year].join('-')
		} else if (format == 'd F Y') {
			month = t._month(d.getMonth())
			date = [day, month, year].join(' ')
		} else if (format == 'd F Y, h:m') {
			month = t._month(d.getMonth())
			date = `${day} ${month} ${year}, ${d.getHours()}:${d.getMinutes()}`
		} else {
			date = [year, month, day].join('-')
		}

		return date
	},
	_formValidationCustom(el, message, success = true, delay = false, header = false) {
		if (header)
			this._notif(message, success, delay)
		else {
			$(el).closest('.input-group').find('.invalid-feedback').remove()
			$(el).closest('.input-group').append('<span class="invalid-feedback d-block">' + message + '</span>')
		}

		if (!success) {
			$(el).closest('.form-group').removeClass('valid').addClass('invalid')
			$(el).addClass('is-invalid')
		}

		$(el).focus()
	},
	_notif(message, success, delay = true) {
		if (success) {
			$("#messages").html('<div class="alert alert-success alert-rounded" role="alert">' +
				'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
				'<strong> <span class="fas fa-check-circle"></span> </strong>' + message +
				'</div>')

			if (delay) {
				$(".alert-success").delay(500).show(10, function () {
					$(this).delay(7000).hide(10, function () {
						$(this).remove()
					})
				})
			}
		} else {
			$("#messages").html('<div class="alert alert-danger alert-rounded" role="alert">' +
				'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
				'<strong> <span class="fas fa-check-circle"></span> </strong>' + message +
				'</div>')

			if (delay) {
				$(".alert-danger").delay(500).show(10, function () {
					$(this).delay(7000).hide(10, function () {
						$(this).remove()
					})
				})
			}
		}
	},
	_toastr(message, type = 'success', args = {}) {
		options = {
			closeButton: true,
			debug: false,
			newestOnTop: false,
			progressBar: true,
			positionClass: 'toast-top-right',
			preventDuplicates: false,
			onclick: null,
			showDuration: '300',
			hideDuration: '1000',
			timeOut: (args.timeOut) ? args.timeOut : '5000',
			extendedTimeOut: '1000',
			showEasing: 'swing',
			hideEasing: 'linear',
			showMethod: 'fadeIn',
			hideMethod: 'fadeOut'
		}
		// toastr[type](message)
		if (typeof args.onHidden !== 'undefined') {
			toastr.options.onHidden = () => {
				args.onHidden()
			}
		}
		if (typeof args.title === 'undefined') {
			title = type.charAt(0).toUpperCase() + type.slice(1)
		}

		if (type == 'success') {
			toastr.success(message, title, options)
		} else if (type == 'info') {
			toastr.info(message, title, options)
		} else if (type == 'warning') {
			toastr.warning(message, title, options)
		} else if (type == 'error') {
			toastr.error(message, title, options)
		}
	},
	_formatAmountNoDecimals(number) {
		let rgx = /(\d+)(\d{3})/

		while (rgx.test(number)) {
			number = number.replace(rgx, '$1' + '.' + '$2')
		}
		return number
	},
	_formatAmount(el) {
		let number = $(el).val().replace(/[^0-9]/g, '')

		if (number.length == 0) number = "0.00"
		else if (number.length == 1) number = "0.0" + number
		else if (number.length == 2) number = "0." + number
		else number = number.substring(0, number.length - 2) + '.' + number.substring(number.length - 2, number.length)

		number = new Number(number)
		number = number.toFixed(2)

		number = number.replace(/\./g, ',')

		x = number.split(',')
		x1 = x[0]
		x2 = x.length > 1 ? ',' + x[1] : ''

		noDecimal = this._formatAmountNoDecimals(x1) + x2

		$(el).next().val(noDecimal.replace(/\./g, '').replace(/\,/g, '.'))

		return noDecimal
	}

}