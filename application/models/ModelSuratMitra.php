<?php

class ModelSuratMitra extends CI_Model
{
    var $table = 't_suratmitra';
    var $column_order = array('nomor_sm', 'tanggal_sm', 'kru_id', 'personalia_id', 'tipe_surat', null); //set column field database for datatable orderable     
    var $column_search = array('nomor_sm', 'tanggal_sm', 'kru_id', 'personalia_id', 'tipe_surat', 'nama'); //set column field database for datatable searchable
    var $order = array('tanggal_sm' => 'desc'); // default order

    private function _get_datatables_query()
    {
        $this->db->select('t_suratmitra.id, t_suratmitra.kru_id, t_suratmitra.personalia_id, t_suratmitra.nomor_sm, t_suratmitra.tanggal_sm, t_suratmitra.tipe_surat, t_user.nama, t_user.nip, t_user.date_created, t_user.role_id');
        $this->db->from($this->table);
        $this->db->join('t_user', 't_user.id = ' . $this->table . '.kru_id');

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
        $this->db->join('t_user', 't_user.id = ' . $this->table . '.kru_id');
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->select('t_suratmitra.id, t_suratmitra.kru_id, t_suratmitra.personalia_id, t_suratmitra.nomor_sm, t_suratmitra.tanggal_sm, t_suratmitra.tipe_surat, t_user.nama, t_user.alamat, t_user.nip, t_user.date_created, t_user.role_id');
        $this->db->from($this->table);
        $this->db->join('t_user', 't_user.id = ' . $this->table . '.kru_id');
        $this->db->where('t_suratmitra.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function getLastSuratbyTipe($tipe_surat)
    {
        $this->db->from($this->table);
        $this->db->where('tipe_surat', $tipe_surat);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->row();
    }

    function tambahSuratMitra($data)
    {
        $result = $this->db->insert($this->table, $data);
        return $result;
    }
}
