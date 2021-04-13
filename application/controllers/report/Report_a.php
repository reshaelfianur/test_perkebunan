<?php defined('BASEPATH') or exit('No direct script access allowed');

class Report_a extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->validate_session();

        $this->data = array_merge($this->data, [
            'title'       => 'Report A',
            'route'       => 'report/report-a',
            'pageContent' => 'report/report-a/index',
        ]);

        $this->load->model('m_kriteria');
        $this->load->model('m_transaksi');
        $this->load->model('m_transaksi_detail');
    }

    public function index()
    {
        $kriteria = $this->m_kriteria->get()->result();
        $transaksiDetail = $this->m_transaksi_detail->get_report_a();

        $data = [];

        foreach ($transaksiDetail as $key => $row) {
            if (!array_key_exists($row->tanggal, $data)) {
                $data[$row->tanggal][$row->idkriteria] = (array) $row;

                continue;
            }

            $data[$row->tanggal][$row->idkriteria] = (array) $row;
        }
        // dd($data);
        return $this->load->view('templates/reporting', [
            'title'             => $this->data['title'],
            'route'             => $this->data['route'],
            'pageReportContent' => 'report/report_a',
            'kriteria'          => $kriteria,
            'fetch'             => $data,
        ]);
    }
}
