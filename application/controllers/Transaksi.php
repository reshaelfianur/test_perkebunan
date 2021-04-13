<?php defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends My_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->validate_session();

		$this->data = array_merge($this->data, [
			'title'		  => 'Transaksi',
			'route'		  => 'transaksi/',
			'pageContent' => 'transaksi/index',
		]);

		$this->load->model('m_transaksi');
		$this->load->model('m_transaksi_detail', 'm_td');
	}

	public function index()
	{
		$this->data = array_merge($this->data, [
			'css' => [
				'assets/plugins/datatables/css/datatables.min',
				'assets/plugins/bootstrap-select/bootstrap-select.min',
				'assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min',
			],
			'js'  => [
				'assets/plugins/datatables/js/datatables.min',
				'assets/plugins/bootstrap-select/bootstrap-select.min',
				'assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min',
			],
		]);

		return $this->load->view('templates/base', $this->data);
	}

	public function fetch()
	{
		$result = ['data' => []];

		$data 	= $this->m_transaksi->get();
		$x 		= 0;

		$update = 'Update ' . $this->data['title'];
		$delete = 'Delete' . $this->data['title'];

		foreach ($data->result() as $key => $value) {
			$buttons = "<a href='" . base_url($this->data['route'] . 'update/' . $value->notrans) . "' class='update-control font-16' data-toggle='tooltip' data-placement='top' title='" . $update . "'><i class='ti-pencil'></i></a>
						<a href='" . base_url($this->data['route'] . 'delete/' . $value->notrans) . "' class='delete-control ml-3 font-16' data-toggle='tooltip' data-placement='top' title='" . $delete . "'><i class='ti-trash'></i></a>";

			$result['data'][$key] = [
				++$x,
				date('d-m-Y', strtotime($value->tanggal)),
				$value->divisi,
				$value->totalbuah,
				$buttons
			];
		}
		return $this->response_json($result);
	}

	public function before_saving()
	{
		$duplicate = [];

		if ($this->input->post('update') == 'undefined') {
			$duplicate = $this->m_transaksi->find($this->input->post('notrans'));
		}

		$response = [];

		if (count((array) $duplicate) > 0) {
			$response['success'] = true;
			$response['message'] = 'No ' . $this->data['title']  . ' is Exists';
		} else {
			$response['success'] = false;
		}

		return $this->response_json($response);
	}

	public function create()
	{
		return $this->load->view($this->data['route'] . 'create', $this->data);
	}

	public function store()
	{
		$create = $this->m_transaksi->insert([
			'notrans' 		=> trim($this->input->post('notrans')),
			'tanggal' 		=> $this->input->post('tanggal'),
			'divisi' 		=> $this->input->post('divisi'),
			'totalbuah' 	=> $this->input->post('totalbuah'),
			'created_by' 	=> $this->_user->user_id
		]);

		if ($create) {
			$response = [
				'message' => 'Succesfully created the ', $this->data['title'],
				'success' => true
			];
		} else {
			$response = [
				'message' => 'Error in the database while created the ' . $this->data['title'],
				'success' => false
			];
		}

		return $this->response_json($response);
	}

	public function update($notrans)
	{
		$this->data['row'] = $this->m_transaksi->find($notrans);

		return $this->load->view($this->data['route'] . 'update', $this->data);
	}

	public function save()
	{
		$update = $this->m_transaksi->update([
			// 'notrans' 		=> $this->input->post('notrans'),
			'tanggal' 		=> $this->input->post('tanggal'),
			'divisi' 		=> $this->input->post('divisi'),
			'totalbuah' 	=> $this->input->post('totalbuah'),
			'updated_by'    => $this->_user->user_id
		], ['notrans' => $this->input->post('notrans')]);

		if ($update) {
			$response = [
				'message' => 'Succesfully updated the ' . $this->data['title'],
				'success' => true
			];
		} else {
			$response = [
				'message' => 'Error in the database while updated the ' . $this->data['title'],
				'success' => false
			];
		}

		return $this->response_json($response);
	}

	public function delete($notrans)
	{
		$transaksiDetail = $this->m_td->get(['notrans' => $notrans])->row();

		if (!$transaksiDetail) {

			$delete = $this->m_transaksi->hard_delete(['notrans' => $notrans]);

			if ($delete) {
				$response = [
					'message' => 'Succesfully deleted the ' . $this->data['title'],
					'success' => true
				];
			} else {
				$response = [
					'message' => 'Error in the database while deleted the ' . $this->data['title'],
					'success' => false
				];
			}
		} else {
			$response = [
				'message' => 'This record can not be deleted because it is still used in other data',
				'success' => false
			];
		}

		return $this->response_json($response);
	}
}
