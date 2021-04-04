<?php

class ModelPembayaranPremi extends CI_Model
{
    var $table = 't_bayar_premi';
    var $column_order = array('nama', 'tanggal_setor', 'tanggal_ambil', 'status_ambil', null); //set column field database for datatable orderable     
    var $column_search = array('nama', 'nip', 'tanggal_setor', 'status_ambil', 'tanggal_ambil'); //set column field database for datatable searchable
    var $order = array('t_laporan_harian.tanggal_setor' => 'desc'); // default order

    private function _get_datatables_query()
    {
        $this->db->select('t_bayar_premi.id, t_bayar_premi.kru_id, t_bayar_premi.laporan_harian_id, t_bayar_premi.status_ambil, t_bayar_premi.kasir_id, t_bayar_premi.tanggal_ambil, t_user.nip, t_user.nama, t_user.role_id, t_user.fotoprofil, t_laporan_harian.tanggal_setor, t_dinas.kru_dinas, t_bus.kelas');
        $this->db->from($this->table);
        $this->db->join('t_user', 't_user.id = ' . $this->table . '.kru_id');
        $this->db->join('t_laporan_harian', 't_laporan_harian.id = ' . $this->table . '.laporan_harian_id');
        $this->db->join('t_dinas', 't_dinas.id = t_laporan_harian.dinas_id');
        $this->db->join('t_bus', 't_bus.id = t_dinas.bus_id');
        // $this->db->order_by('t_laporan_harian.tanggal_setor', 'desc');

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
        $this->db->join('t_laporan_harian', 't_laporan_harian.id = ' . $this->table . '.laporan_harian_id');
        $this->db->join('t_dinas', 't_dinas.id = t_laporan_harian.dinas_id');
        $this->db->join('t_bus', 't_bus.id = t_dinas.bus_id');
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->select('t_bayar_premi.id, t_bayar_premi.kru_id, t_bayar_premi.laporan_harian_id, t_bayar_premi.status_ambil, t_bayar_premi.kasir_id, t_bayar_premi.tanggal_ambil, t_bayar_premi.nominal_premi, t_user.nip, t_user.nama, t_user.role_id, t_user.fotoprofil, t_laporan_harian.tanggal_setor, t_dinas.kru_dinas, t_bus.kelas');
        $this->db->from($this->table);
        $this->db->join('t_user', 't_user.id = ' . $this->table . '.kru_id');
        $this->db->join('t_laporan_harian', 't_laporan_harian.id = ' . $this->table . '.laporan_harian_id');
        $this->db->join('t_dinas', 't_dinas.id = t_laporan_harian.dinas_id');
        $this->db->join('t_bus', 't_bus.id = t_dinas.bus_id');
        $this->db->where('t_bayar_premi.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_id_kru()
    {
        $this->db->distinct();
        $this->db->select('kru_id');
        $this->db->from($this->table);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_bulan($kru_id)
    {
        $this->db->distinct();
        $this->db->select('DATE_FORMAT(`t_laporan_harian`.`tanggal_setor`,\'%Y-%m\')');
        $this->db->join('`t_laporan_harian`', '`t_laporan_harian`.`id` = `t_bayar_premi`.`laporan_harian_id`');
        $this->db->from($this->table);
        $this->db->where('`t_bayar_premi`.`status_ambil`', 0);
        $this->db->where('`t_bayar_premi`.`kru_id`', $kru_id);
        $this->db->order_by('`t_laporan_harian`.`tanggal_setor`', 'ASC');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_by_kru_id($kru_id, $status_setor)
    {
        $this->db->select('`t_laporan_harian`.`status_setor`,`t_laporan_harian`.`tanggal_setor`,`t_bayar_premi`.`id`,`t_bayar_premi`.`kru_id`,`t_bayar_premi`.`laporan_harian_id`,`t_bayar_premi`.`status_ambil`,`t_bayar_premi`.`nominal_premi`,`t_bayar_premi`.`tanggal_ambil`');
        $this->db->from($this->table);
        $this->db->join('`t_laporan_harian`', '`t_laporan_harian`.`id` = `t_bayar_premi`.`laporan_harian_id`');
        $this->db->where('kru_id', $kru_id);
        $this->db->where('status_ambil', $status_setor);
        $this->db->order_by('`t_laporan_harian`.`tanggal_setor`', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    function tambahPremi($data)
    {
        $result = $this->db->insert($this->table, $data);
        return $result;
    }

    public function updatePremi(array $where, array $data)
    {
        // $this->db->join('`t_laporan_harian`', '`t_laporan_harian`.`id` = `t_bayar_premi`.`laporan_harian_id`', 'INNER');
        // $this->db->set($data);
        // $this->db->where($where);
        // $this->db->update($this->table);
        // return $this->db->affected_rows();

        $sql = "
        UPDATE `t_bayar_premi` 
        INNER JOIN `t_laporan_harian` ON `t_laporan_harian`.`id` = `t_bayar_premi`.`laporan_harian_id` 
        SET `t_bayar_premi`.`status_ambil` = " . $data['status_ambil'] . ", `t_bayar_premi`.`kasir_id` = " . $data['kasir_id'] . ", `t_bayar_premi`.`tanggal_ambil` = '" . strval($data['tanggal_ambil']) . "'
        WHERE `t_bayar_premi`.`kru_id` = " . $where['kru_id'] . " AND DATE_FORMAT(`t_laporan_harian`.`tanggal_setor`,'%Y-%m') = '" . $where['setor'] . "'";
        return $this->db->query($sql);
    }
}
