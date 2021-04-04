<?php

class ModelUser extends CI_Model
{
    private $user;

    var $table = '`t_user`';
    var $column_order = array('`nip`', '`alamat`', '`nama`', '`nomor_hp`', '`role_id`', '`fotoprofil`', '`status_dinas`',  null); //set column field database for datatable orderable     
    var $column_search = array('`nip`', '`alamat`', '`nama`', '`nomor_hp`', '`role_id`', '`fotoprofil`', '`status_dinas`'); //set column field database for datatable searchable
    var $order = array('`id`' => 'desc'); // default order


    public function __construct()
    {
        parent::__construct();
    }


    public function getUserLogin($nip)
    {

        return $this->db->get_where($this->table, [
            'nip' => $nip
        ])->row_array();
    }

    public function setSessionUser($data)
    {

        return $this->session->set_userdata($data);
    }

    public function getSessionUserLogin()
    {
        return $this->db->get_where($this->table, [
            'nip' => $this->session->userdata('nip')
        ])->row_array();
    }

    public function getPasswordUserLogin()
    {
        // here we select just the nopol column
        $this->db->select('password');
        $this->db->where('nip', $this->session->userdata('nip'));
        $q = $this->db->get($this->table);
        $data = $q->row();

        if (isset($data)) {
            return ($data['password']);
        }
    }


    private function _get_datatables_query($where)
    {
        $this->db->from($this->table);
        $where;

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

    function get_datatables($ambil)
    {
        if ($ambil == 'kru') {
            $where = $this->db->where("role_id=31 OR role_id=32 OR role_id=33");
            $this->_get_datatables_query($where);
        } else {
            $where = $this->db->where("role_id=4");
            $this->_get_datatables_query($where);
        }
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($ambil)
    {
        $this->_get_datatables_query($ambil);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($ambil)
    {
        if ($ambil == 'kru') {
            $this->db->from($this->table);
            $this->db->where("role_id=31 OR role_id=32 OR role_id=33");
        } else {
            $this->db->from($this->table);
            $this->db->where("role_id=4");
        }
        return $this->db->get()->num_rows();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_by_status()
    {
        $this->db->from($this->table);
        $where = "(role_id=31 OR role_id=32 OR role_id=33) AND status_dinas=0";
        $this->db->where($where);

        $query = $this->db->get();

        return $query->result();
    }

    public function get_by_nip_array($nip)
    {
        // here we select just the nip column
        $this->db->select('nip');
        $this->db->where('nip', $nip);
        $q = $this->db->get($this->table);
        $data = $q->row_array();

        if (isset($data)) {
            return ($data['nip']);
        }
    }

    public function getLastUserbyTipe()
    {
        $this->db->from($this->table);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->row();
    }

    function tambahDataUser($data)
    {
        $result = $this->db->insert($this->table, $data);
        return $result;
    }

    public function editDataUser($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function updateStatusKru($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }
}
