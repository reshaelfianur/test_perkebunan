<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_transaksi extends MY_Model
{
    private $table;
    private $primary;

    public function __construct()
    {
        parent::__construct();

        $this->table   = 'transaksi';
        $this->primary = 'notrans';
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
}
