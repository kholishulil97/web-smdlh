<?php

class ModelSP extends CI_Model
{
    var $table = 't_sp';
    var $column_order = array('nip', 'nopol', 'kode', 'mengganti_id', 'pengatur_id', 'tanggal_sp', null); //set column field database for datatable orderable     
    var $column_search = array('nip', 'nama', 'nopol', 't_bus.kelas', 'kode', 'posawal', 'posakhir', 'tanggal_sp'); //set column field database for datatable searchable
    var $order = array('t_sp.tanggal_sp' => 'desc'); // default order

    private function _get_datatables_query()
    {
        $this->db->select('t_sp.id, t_sp.dinas_id, t_sp.kru_id, t_sp.mengganti_id, t_sp.nomor_sp, t_sp.tanggal_sp, t_sp.pengatur_id, t_dinas.bus_id, t_dinas.trayek_id, t_dinas.kru_dinas, t_dinas.status_jalan, t_bus.nopol, t_bus.mesin, t_bus.kelas, t_bus.status, t_trayek.kode, t_trayek.posawal, t_trayek.posakhir, t_user.nama, t_user.nip, t_user.role_id');
        $this->db->from($this->table);
        $this->db->join('t_dinas', 't_dinas.id = ' . $this->table . '.dinas_id');
        $this->db->join('t_user', 't_user.id = ' . $this->table . '.kru_id');
        $this->db->join('t_bus', 't_dinas.bus_id = t_bus.id');
        $this->db->join('t_trayek', 't_dinas.trayek_id = t_trayek.id');
        //$this->db->order_by('t_sp.tanggal_sp', 'desc');

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
        $this->db->join('t_dinas', 't_dinas.id = ' . $this->table . '.dinas_id');
        $this->db->join('t_user', 't_user.id = ' . $this->table . '.kru_id');
        $this->db->join('t_bus', 't_dinas.bus_id = t_bus.id');
        $this->db->join('t_trayek', 't_dinas.trayek_id = t_trayek.id');
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->select('t_sp.id, t_sp.dinas_id, t_sp.kru_id, t_sp.mengganti_id, t_sp.nomor_sp, t_sp.tanggal_sp, t_sp.pengatur_id, t_dinas.bus_id, t_dinas.trayek_id, t_dinas.kru_dinas, t_dinas.status_jalan, t_bus.nopol, t_bus.mesin, t_bus.kelas, t_bus.status, t_trayek.kode, t_trayek.posawal, t_trayek.posakhir, t_user.nama, t_user.nip, t_user.role_id');
        $this->db->from($this->table);
        $this->db->join('t_dinas', 't_dinas.id = ' . $this->table . '.dinas_id');
        $this->db->join('t_user', 't_user.id = ' . $this->table . '.kru_id');
        $this->db->join('t_bus', 't_dinas.bus_id = t_bus.id');
        $this->db->join('t_trayek', 't_dinas.trayek_id = t_trayek.id');

        $this->db->where($this->table . '.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    function tambahSP($data)
    {
        $result = $this->db->insert($this->table, $data);
        return $result;
    }
}
