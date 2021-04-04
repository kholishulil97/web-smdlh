<?php

class ModelBus extends CI_Model
{
    var $table = 't_bus';
    var $column_order = array('nopol', 'mesin', 'tahun', 'kelas', 'trayek_id', 'url', 'status', null); //set column field database for datatable orderable     
    var $column_search = array('nopol', 'mesin', 'tahun', 'kelas', 'trayek_id', 'url', 'status'); //set column field database for datatable searchable
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

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_by_kelas($kelas)
    {
        $this->db->from($this->table);
        $this->db->where('kelas', $kelas);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_by_id_join($id)
    {
        $this->db->from($this->table);
        $this->db->join('t_trayek', $this->table . '.trayek_id = t_trayek.id');
        $this->db->where('t_trayek.id', $id);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_by_nopol_array($nopol)
    {
        // here we select just the nopol column
        $this->db->select('nopol');
        $this->db->where('nopol', $nopol);
        $q = $this->db->get($this->table);
        $data = $q->row_array();

        if (isset($data)) {
            return ($data['nopol']);
        }
    }

    function tambahDataBus($data)
    {
        $result = $this->db->insert($this->table, $data);
        return $result;
    }

    function stubTambahDataBus($data)
    {
        return $data;
    }

    public function editDataBus($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function updateTrayekIdle($id)
    {
        $this->db->set('trayek_id', 0);
        $this->db->set('status', 0);
        $this->db->where('id', $id);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }

    public function updateTrayekDinas($id, $trayek_id)
    {
        $this->db->set('trayek_id', $trayek_id);
        $this->db->set('status', 1);
        $this->db->where('id', $id);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }

    public function updateTrayekNA($id)
    {
        $this->db->set('trayek_id', 0);
        $this->db->set('status', 3);
        $this->db->where('id', $id);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }
}
