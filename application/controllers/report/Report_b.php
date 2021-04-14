<?php defined('BASEPATH') or exit('No direct script access allowed');

class Report_b extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->validate_session();

        $this->data = array_merge($this->data, [
            'title'       => 'Report B',
            'route'       => 'report/report-b',
            'pageContent' => 'report/report-b/index',
        ]);

        $this->load->model('m_kriteria');
        $this->load->model('m_transaksi');
        $this->load->model('m_transaksi_detail');
    }

    public function index()
    {
        $kriteria = $this->m_kriteria->get()->result();
        $transaksiDetail = $this->m_transaksi_detail->get_report_b();

        $data = [];

        // foreach ($transaksiDetail as $key => $row) {
        //     if (!array_key_exists($row->divisi, $data)) {
        //         $data[$row->divisi][$row->tanggal][$row->idkriteria] = (array) $row;

        //         continue;
        //     }

        //     $data[$row->divisi][$row->tanggal][$row->idkriteria] = (array) $row;
        // }

        foreach ($transaksiDetail as $key => $row) {
            $custumKey = $row->divisi . "_" . $row->tanggal;

            if (!array_key_exists($custumKey, $data)) {
                $data[$custumKey][$row->idkriteria] = (array) $row;

                continue;
            }

            $data[$custumKey][$row->idkriteria] = (array) $row;
        }
        // dd($data);
        return $this->load->view('templates/reporting', [
            'title'             => $this->data['title'],
            'route'             => $this->data['route'],
            'pageReportContent' => 'report/report_b',
            'kriteria'          => $kriteria,
            'fetch'             => $data,
        ]);
    }
}
