<?php defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_detail extends My_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->validate_session();

		$this->data = array_merge($this->data, [
			'title'		  => 'Transaksi Detail',
			'route'		  => 'transaksi_detail/',
			'pageContent' => 'transaksi_detail/index',
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

		$data 	= $this->m_td->fetch();
		$x 		= 0;

		$update = 'Update ' . $this->data['title'];
		$delete = 'Delete' . $this->data['title'];

		foreach ($data->result() as $key => $value) {
			$buttons = "<a href='" . base_url($this->data['route'] . 'update/' . $value->id) . "' class='update-control font-16' data-toggle='tooltip' data-placement='top' title='" . $update . "'><i class='ti-pencil'></i></a>
						<a href='" . base_url($this->data['route'] . 'delete/' . $value->id) . "' class='delete-control ml-3 font-16' data-toggle='tooltip' data-placement='top' title='" . $delete . "'><i class='ti-trash'></i></a>";

			$result['data'][$key] = [
				++$x,
				$value->notrans,
				$value->name,
				$value->jumlah,
				$buttons
			];
		}
		return $this->response_json($result);
	}

	public function create()
	{
		$this->data += [
			'kriteria' => $this->m_kriteria->get()->result()
		];

		return $this->load->view($this->data['route'] . 'create', $this->data);
	}

	public function store()
	{
		$create = $this->m_td->insert([
			'notrans' 		=> trim($this->input->post('notrans')),
			'idbuah' 		=> trim($this->input->post('idbuah')),
			'jumlah' 		=> trim($this->input->post('jumlah')),
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
		$this->data['row'] = $this->m_td->find($id);

		return $this->load->view($this->data['route'] . 'update', $this->data);
	}

	public function save()
	{
		$update = $this->m_td->update([
			'notrans' 		=> trim($this->input->post('notrans')),
			'idbuah' 		=> trim($this->input->post('idbuah')),
			'jumlah' 		=> trim($this->input->post('jumlah')),
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
		$delete = $this->m_td->hard_delete(['id' => $id]);

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

		return $this->response_json($response);
	}
}
