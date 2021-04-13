<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_transaksi_detail extends MY_Model
{
    private $table;
    private $primary;

    public function __construct()
    {
        parent::__construct();

        $this->table   = 'transaksi_detail';
        $this->primary = 'id';

        $this->kriteria  = 'kriteria';
        $this->transaksi = 'transaksi';
    }

    public function find($id)
    {
        return $this->db->where($this->primary, $id)->get($this->table)->row();
    }

    public function get($where = [], $args = [], $deleted = false)
    {
        return $this->read($this->table, $this->primary, $where, $args, $deleted);
    }

    public function insert($data, $lastId = false)
    {
        return $this->created($this->table, $data, true, $lastId);
    }

    public function update($data, $where, $update = false)
    {
        return $this->updated($this->table, $data, $where, $update);
    }

    public function delete($data, $where, $delete = false)
    {
        return $this->deleted($this->table, $data, $where, $delete);
    }

    public function hard_delete($where)
    {
        return $this->hard_deleted($this->table, $where);
    }

    public function fetch($where = [])
    {
        if (!empty($where))
            $this->db->where($where);

        return $this->db->select('a.*, b.name')
            ->join($this->kriteria . ' b', 'a.idbuah = b.id')
            ->get($this->table . ' a');
    }

    public function get_report_a()
    {
        return $this->db->select('t.tanggal, k.name, k.id AS idkriteria')
            ->select_sum('td.jumlah')
            ->join($this->kriteria . ' k', 'td.idbuah = k.id')
            ->join($this->transaksi . ' t', 't.notrans = td.notrans')
            ->group_by(['tanggal', 'name', 'idkriteria'])
            ->order_by('tanggal ASC', 'name ASC')
            ->get($this->table . ' td')
            ->result();
    }
}
