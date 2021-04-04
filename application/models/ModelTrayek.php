<?php

class ModelTrayek extends CI_Model
{
    var $table = 't_trayek';
    var $column_order = array('kode', 'posawal', 'poslewat', 'posakhir', 'kelas', null); //set column field database for datatable orderable     
    var $column_search = array('kode', 'posawal', 'poslewat', 'posakhir', 'kelas',); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_trayek($kelas)
    {
        $this->db->from($this->table);
        $this->db->where('kelas', $kelas);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_id_trayek($kode)
    {
        $this->db->select('id');
        $this->db->from($this->table);
        $this->db->where('kode', $kode);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_pos_by_id($id)
    {
        $this->db->from('t_tarif');
        $this->db->where('trayek_id', $id);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_tarif_array($id)
    {
        $this->db->from($this->table);
        $this->db->join('t_tarif', $this->table . '.id = t_tarif.trayek_id');
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_pos_array($id)
    {

        $this->db->distinct();
        $this->db->select('posnaik');
        $this->db->from($this->table);
        $this->db->join('t_tarif', $this->table . '.id = t_tarif.trayek_id');
        $this->db->where('trayek_id', $id);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_single_tarif($id, $id_tarif)
    {
        //$this->db->select('`t_tarif`.`tarif`');
        $this->db->from($this->table);
        $this->db->join('t_tarif', $this->table . '.id = t_tarif.trayek_id');
        $where = '`t_trayek`.`id` = ' . $id . ' AND `t_tarif`.`id_tarif` = ' . $id_tarif;
        $this->db->where($where);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_by_id_array($id)
    {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_by_kode_array($kode)
    {
        // here we select just the age column
        $this->db->select('kode');
        $this->db->where('kode', $kode);
        $q = $this->db->get($this->table);
        $data = $q->row_array();

        if (isset($data)) {
            return ($data['kode']);
        }
    }

    public function count_get_by_id_join($id)
    {
        $this->db->from($this->table);
        $this->db->join('t_dinas', $this->table . '.id = t_dinas.trayek_id');
        $this->db->where('t_trayek.id', $id);
        $this->db->where('t_dinas.status_jalan = 1');
        $query = $this->db->get();

        return $query->num_rows();
    }

    function tambahDataTrayek($data)
    {
        $result = $this->db->insert($this->table, $data);
        return $result;
    }

    function tambahSusunanPos($data)
    {
        $result = $this->db->insert('t_tarif', $data);
        return $result;
    }

    public function editDataTrayek($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function updateSusunanPos($where, $data)
    {
        $this->db->update('t_tarif', $data, $where);
        return $this->db->affected_rows();
    }

    public function deleteTarif($trayek_id)
    {
        $this->db->where('trayek_id', $trayek_id);
        $this->db->delete('t_tarif');
        return $this->db->affected_rows();
    }
}
