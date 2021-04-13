<?php defined('BASEPATH') or exit('No direct script access allowed');

class Kriteria extends My_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->validate_session();

		$this->data = array_merge($this->data, [
			'title'		  => 'Kriteria',
			'route'		  => 'kriteria/',
			'pageContent' => 'kriteria/index',
		]);

		$this->load->model('m_kriteria');
		$this->load->model('m_transaksi_detail', 'm_td');
	}

	public function index()
	{
		$this->data = array_merge($this->data, [
			'css' => [
				'assets/plugins/datatables/css/datatables.min',
				'assets/plugins/bootstrap-select/bootstrap-select.min'
			],
			'js'  => [
				'assets/plugins/datatables/js/datatables.min',
				'assets/plugins/bootstrap-select/bootstrap-select.min'
			],
		]);

		return $this->load->view('templates/base', $this->data);
	}

	public function fetch()
	{
		$result = ['data' => []];

		$data 	= $this->m_kriteria->get();
		$x 		= 0;

		$update = 'Update ' . $this->data['title'];
		$delete = 'Delete' . $this->data['title'];

		foreach ($data->result() as $key => $value) {
			$buttons = "<a href='" . base_url($this->data['route'] . 'update/' . $value->id) . "' class='update-control font-16' data-toggle='tooltip' data-placement='top' title='" . $update . "'><i class='ti-pencil'></i></a>
						<a href='" . base_url($this->data['route'] . 'delete/' . $value->id) . "' class='delete-control ml-3 font-16' data-toggle='tooltip' data-placement='top' title='" . $delete . "'><i class='ti-trash'></i></a>";

			$result['data'][$key] = [
				++$x,
				$value->name,
				$buttons
			];
		}
		return $this->response_json($result);
	}

	public function before_saving()
	{
		if ($this->input->post('id') == 'undefined') {
			$duplicate = $this->m_kriteria->get(['name' => $this->input->post('name')]);
		} else {
			$duplicate = $this->m_kriteria->get([
				'name' 	=> $this->input->post('name'),
				'id <>' => $this->input->post('id')
			]);
		}

		$response = [];

		if ($duplicate->num_rows() > 0) {
			$response['success'] = true;
			$response['message'] = 'Name ' .$this->data['title'] . ' is Exists';
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
		$create = $this->m_kriteria->insert([
			'name' 			=> trim(ucwords($this->input->post('name'))),
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

	public function update($id)
	{
		$this->data['row'] = $this->m_kriteria->find($id);

		return $this->load->view($this->data['route'] . 'update', $this->data);
	}

	public function save()
	{
		$update = $this->m_kriteria->update([
			'name' 			=> trim(ucwords($this->input->post('name'))),
			'updated_by'    => $this->_user->user_id
		], ['id' => $this->input->post('id')]);

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

	public function delete($id)
	{
		$transaksiDetail = $this->m_td->get(['idbuah' => $id])->row();

		if (!$transaksiDetail) {

			$delete = $this->m_kriteria->hard_delete(['id' => $id]);

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
