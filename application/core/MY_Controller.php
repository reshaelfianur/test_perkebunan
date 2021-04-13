<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set('Asia/Jakarta');

		$this->data = [];

		$this->_user = (object) $this->session->userdata();

		if (empty($this->session->userdata('logged_in'))) {
			$session_data = ['logged_in' => false];
			$this->session->set_userdata($session_data);
		} else {
			$this->data = [
				'modules' => []
			];
		}
	}

	public function get_module($moduleCode = null)
	{
		$where = [
			'module_status'	=> 1,
		];

		if (!empty($moduleCode)) {
			return $this->m_module->get(['module_code' => $moduleCode])->row();
		}

		return $this->m_module->get($where, ['orderBy' => 'module_order'])->result();
	}

	public function response_json($response)
	{
		echo json_encode($response);
		die();
	}

	public function get_settings()
	{
		$security   	  = [];
		$securitySettings = $this->m_ss->get();

		foreach ($securitySettings->result() as $row) {
			$security[$row->ss_name] = $row->ss_value;
		}

		return $security;
	}

	public function set_current_url()
	{
		if (!$this->input->is_ajax_request() && empty($this->input->post())) {
			$this->session->set_userdata([
				'current_url' => uri_string()
			]);
		}
	}

	public function get_current_url()
	{
		if (!empty($this->session->userdata('current_url'))) {
			redirect($this->session->userdata('current_url'));
		} else {
			redirect('dashboard');
		}
	}

	public function validate_session()
	{
		$state = $this->session->userdata('logged_in');

		if ($this->router->class == 'auth' && $this->router->method == 'index') {
			if ($state) {
				$this->get_current_url();
			}
		} else {
			$this->set_current_url();

			if (!$state) {
				if ($this->input->is_ajax_request()) {
					$response = [
						'message' => 'Session expired. Please re-login',
						'success' => false
					];
					$this->session->sess_sess_destroy();

					$this->response_json($response);
				} else {
					// $this->session->sess_destroy();
					$this->session->set_userdata(['logged_in' => false]);

					$data = [
						'type' 	  => 'info',
						'message' => 'Session expired. Please re-login'
					];
					$this->session->set_flashdata('data', $data);

					redirect('auth');
				}
			}else {
				#check expiry
				$last = $this->session->userdata('user_last_request');
				$time = DateTime::createFromFormat('Y-m-d H:i:s', $last);

				#end check expiry
				$idle 		  = (int) 60 * 60;

				$now 		= new DateTime();
				$lastSecond = $time->format('U');
				$nowSecond 	= $now->format('U');

				$diff       = $nowSecond - $lastSecond;

				if ($diff > $idle and $idle != 0) {
					if ($this->input->is_ajax_request()) {
						$response = [
							'message' => 'Session expired. Please re-login',
							'success' => false
						];
						$this->session->sess_destroy();

						$this->response_json($response);
					} else {
						// $this->session->sess_destroy();
						$this->session->set_userdata(['logged_in' => false]);

						$data = [
							'type' 	  => 'info',
							'message' => 'Session expired. Please re-login'
						];
						$this->session->set_flashdata('data', $data);

						redirect('auth');
					}
				} else {
					$this->session->unset_userdata('user_last_request');
					$this->session->set_userdata('user_last_request', dateTimeNow());
				}
			}
		}
	}

	public function show_error_custum($status = 500)
	{
		include(VIEWPATH . 'errors/pages-error-' . $status . '.php');
		exit($status);
	}

	public function guard_module($module)
	{
		$category = $this->m_user->get_group(['a.group_id' => $this->_user->group_id])->row()->user_category;

		if ($category == 1) return true;

		$get = $this->m_am->get_module([
			'group_id' 	  => $this->_user->group_id,
			'module_code' => $module
		]);

		if ($get->num_rows() <= 0) return $this->show_error_custum(403);

		$right = $get->row()->am_rights;
		return $right == 1 ? true : $this->show_error_custum(403);
	}

	public function guard_module_function($funcCode)
	{
		$category = $this->m_user->get_group(['a.group_id' => $this->_user->group_id])->row()->user_category;

		if ($category == 1) return json_decode('["1", "2", "3", "4", "5"]');

		return json_decode($this->m_amf->get_mf([
			'group_id' => $this->_user->group_id,
			'mf_code'  => $funcCode
		])->row()->amf_rights);
	}

	public function get_module_function($funcCode)
	{
		return $this->m_mf->get_module([
			'mf_code' => $funcCode
		])->row();
	}

	public function table_array($table, $arr)
	{
		$response = [];
		$fetch 	  = $this->db->get($table)->result();

		foreach ($fetch as $row) {
			foreach ($arr as $key => $val) {
				$response[$row->$key] = $row->$val;
			}
		}

		return $response;
	}

	public function rights_validation($rights)
	{
		if (!in_array($rights, $this->data['rights'])) {
			if ($this->input->is_ajax_request()) {
				return [
					'message' => $this->lang->line('info_not_allowed'),
					'success' => false
				];
			} else {
				$data = [
					'type' 	  => 'error',
					'message' => $this->lang->line('info_not_allowed')
				];
				$this->session->set_flashdata('data', $data);

				return redirect('dashboard');
			}
		}

		return ['success' => true];
	}

	public function rights_super_user($rights)
	{
		if (!$rights) {
			if ($this->input->is_ajax_request()) {
				return [
					'message' => $this->lang->line('info_not_allowed'),
					'success' => false
				];
			} else {
				$data = [
					'type' 	  => 'error',
					'message' => $this->lang->line('info_not_allowed')
				];
				$this->session->set_flashdata('data', $data);

				return redirect('dashboard');
			}
		}

		return ['success' => true];
	}

	public function is_super_user()
	{
		$category = $this->m_user->get_group(['a.group_id' => $this->_user->group_id])->row()->user_category;

		if ($category == 1) return true;

		return false;
	}

	public function upload_file($params)
	{
		makeDir($params['upload_path']);

		foreach ($params as $key => $value) {
			if ($key == 'upload_path') {
				$config['upload_path'] 	 = $value;
			} else if ($key == 'file_name') {
				$config['file_name'] 	 = $value;
			} elseif ($key == 'allowed_types') {
				$config['allowed_types'] = $value; // ex : 'bmp|jpg|png|doc|docx|pdf|xls|xlsx|ppt|pptx|txt|csv|jpeg|gif'
			} else if ($key == 'max_size') {
				$config['max_size'] 	 = $value;
			} else if ($key == 'max_width') {
				$config['max_width'] 	 = $value;
			} else if ($key == 'max_height') {
				$config['max_height'] 	 = $value;
			} else if ($key == 'encrypt_name') {
				$config['encrypt_name']  = $value; // default false
			} else if ($key == 'remove_spaces') {
				$config['remove_spaces'] = $value; // default false
			} else if ($key == 'overwrite') {
				$config['overwrite'] 	 = $value; // default false
			}
		}

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload($params['field_name'])) {
			return ['error' => $this->upload->display_errors()];
		} else {
			return $this->upload->data('file_name'); // file name with extension
		}
	}
}
