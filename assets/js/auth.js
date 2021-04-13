let language = []

auth = {
	init() {
		this._imageSlide()
		this._ajaxAnimation()
		this._showPassword()
		this._loginForm()
		this._showNotification()
	},
	_imageSlide() {
		$.backstretch([
			baseUrl('assets/images/background/img1.jpg'),
			baseUrl('assets/images/background/img2.jpg'),
			baseUrl('assets/images/background/img3.jpg')
		], {
			fade: 750,
			duration: 4000
		})
	},
	_ajaxAnimation() {
		$(document).on({
			ajaxStart: () => {
				$('.preloader').fadeIn(200)
			},
			ajaxStop: () => {
				$('.preloader').fadeOut(200)
			},
		})
	},
	_showPassword() {
		$('.border-icon').on('click', function () {
			let password = $(this).closest('.input-group').find('#password')
			if (password.attr('type') == 'password') {
				password.attr('type', 'text')
				$(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye')
			} else {
				password.attr('type', 'password')
				$(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash')
			}
		})
	},
	_formValidation(el, rules, submitHandler) {
		$(el).validate({
			errorClass: 'invalid-feedback d-block',
			focusInvalid: true,
			rules: rules,
			highlight: (e) => {
				$(e).addClass('invalid').removeClass('valid')
			},
			unhighlight: (e) => {
				$(e).closest('.input-group').find('.invalid-feedback').remove()
				$(e).addClass('valid').removeClass('invalid')
			},
			errorPlacement: (error, element) => {
				if (element.hasClass('with-icon')) {
					element.closest('.input-group').append(error)
				} else {
					error.insertAfter(element)
				}
			},
			submitHandler: (form) => {
				submitHandler(form)
			},
		})
	},
	_formValidationCustom(
		el,
		message,
		success = true,
		delay = false,
		header = false
	) {
		if (header) FUNC._notif(message, success, delay)
		else {
			$(el).closest('.form-group').find('.invalid-feedback').remove()
			$(el)
				.after(
					'<span class="invalid-feedback d-block">' + message + '</span>'
				)
		}

		if (!success) {
			$(el)
				.closest('.form-group')
				.removeClass('is-invalid')
				.removeClass('is-valid')
				.addClass('is-invalid')
			$(el).addClass('invalid')
		}
	},
	_loginForm() {
		let t = this

		t._formValidation(
			'#login-form',
			{
				username: {
					required: true,
				},
				password: {
					required: true,
				},
			},
			(form) => {
				t._submitLoginForm(form)
			}
		)
	},
	_submitLoginForm(form) {
		let t = this
		let lf = $(form)

		lf.find('#btn-submit-login')
			.html(
				`<span class="spinner-grow spinner-grow-lg" role="status" aria-hidden="true"></span> Loading...`
			)
			.attr('disabled', true)

		if (lf.hasClass('check-username')) {
			FUNC._ajax(lf.data('first-action'), lf.serialize()).done((res) => {
				lf.find('#btn-submit-login').html('Log In').attr('disabled', false)

				if (res.success) {
					setTimeout(() => {
						lf.removeClass('check-username')
						lf.find('.hide-password').slideDown().removeClass('hide')
						lf.find('#password').focus()
					}, 300)
				} else {
					t._formValidationCustom('#username', res.message, res.success)
				}
			})
		} else {
			FUNC._ajax(lf.attr('action'), lf.serialize()).done((res) => {
				if (res.success) {
					if (res.row) {
						t._showChangeForm(res.row)
						return
					}

					FUNC._toastr(res.message, 'success', {
						timeOut: '3000',
						onHidden: () => {
							window.location.assign(baseUrl(res.current_url))
						},
					})
				} else {
					FUNC._clearForm(lf)
					t._formValidationCustom('#username', res.message, res.success)

					lf.addClass('check-username')
					lf.find('#username').focus()
					lf.find('#btn-submit-login').html('Log In').attr('disabled', false)

					lf.find('.hide-password').slideUp().addClass('hide')
				}
			})
		}
	},
	_showNotification() {
		let flashType = $('body').data('flash-type')

		if (flashType != "") {
			FUNC._toastr($('body').data('flash-message'), flashType)
		}
	},
}

$(auth.init())
