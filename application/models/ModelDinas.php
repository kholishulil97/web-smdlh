<?php

class ModelDinas extends CI_Model
{
    var $table = 't_dinas';
    var $column_order = array('nopol', 'posawal', null, null, null, 'status_jalan', null); //set column field database for datatable orderable     
    var $column_search = array('posawal', 'posakhir', 'kode', 't_bus.kelas', 't_trayek.kelas', 'url', 'nopol', 'status_jalan'); //set column field database for datatable searchable
    var $order = array('t_dinas.status_jalan' => 'desc'); // default order

    private function _get_datatables_query()
    {
        $this->db->select('`t_dinas`.`id`, `t_dinas`.`bus_id`, `t_dinas`.`trayek_id`, `t_dinas`.`kru_dinas`, `t_dinas.status_jalan`, `nopol`, `mesin`, `tahun`, `t_bus`.`kelas`, `url`, `status`, `kode`, `posawal`, `posakhir`');
        $this->db->from($this->table);
        $this->db->join('t_bus', 't_bus.id = ' . $this->table . '.bus_id');
        $this->db->join('t_trayek', 't_trayek.id = ' . $this->table . '.trayek_id');
        // $this->db->order_by('t_dinas.status_jalan', 'desc');

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
        $this->db->join('t_bus', 't_bus.id = ' . $this->table . '.bus_id');
        $this->db->join('t_trayek', 't_trayek.id = ' . $this->table . '.trayek_id');

        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->select('`t_dinas`.`id`, `t_dinas`.`bus_id`, `t_dinas`.`trayek_id`, `t_dinas`.`kru_dinas`, `nopol`, `mesin`, `tahun`, `t_bus`.`kelas`, `url`, `status`, `kode`, `posawal`, `posakhir`');
        $this->db->from($this->table);
        $this->db->join('t_bus', 't_bus.id = ' . $this->table . '.bus_id');
        $this->db->join('t_trayek', 't_trayek.id = ' . $this->table . '.trayek_id');
        $this->db->where($this->table . '.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_single_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_by_bus($id)
    {
        $this->db->from($this->table);
        $this->db->where('bus_id', $id);
        $this->db->where('status_jalan', 1);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_kru_dinas_by_id($id)
    {
        $this->db->select('kru_dinas');
        $this->db->from($this->table);
        $this->db->where($this->table . '.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_id_dinas()
    {
        $this->db->select('id');
        $this->db->from($this->table);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function getLastDinas()
    {
        $this->db->from($this->table);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->row();
    }


    function tambahDinas($data)
    {
        $result = $this->db->insert($this->table, $data);
        return $result;
    }

    public function editDataBus($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function updateStatusJalan($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }
}
