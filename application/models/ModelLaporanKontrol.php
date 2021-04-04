<?php

class ModelLaporanKontrol extends CI_Model
{
    var $table = 't_laporan_kontrol';
    var $column_order = array('nama', 'nopol', 'laporan_harian_id', 'naik_kontrol', 'turun_kontrol', null); //set column field database for datatable orderable     
    var $column_search = array('nama', 'nip', 'mesin', 'nopol', 'kode', 'posawal', 'posakhir', 'naik_kontrol', 'turun_kontrol', 'laporan_harian_id', 'naik_kontrol', 'turun_kontrol',); //set column field database for datatable searchable
    var $order = array('id' => 'desc'); // default order

    private function _get_datatables_query()
    {
        $this->db->select('t_laporan_kontrol.id, t_laporan_kontrol.laporan_harian_id, t_laporan_kontrol.jenis_pelanggaran, t_laporan_kontrol.keterangan, t_laporan_kontrol.naik_kontrol, t_laporan_kontrol.status_turun, t_laporan_kontrol.turun_kontrol, t_laporan_kontrol.tanggal_naik_kontrol, t_laporan_kontrol.tanggal_turun_kontrol, t_laporan_kontrol.jumlah_penumpang, t_laporan_kontrol.pendapatan_kontrol, t_laporan_harian.dinas_id, t_laporan_harian.tanggal_jalan, t_dinas.kru_dinas, t_bus.nopol, t_bus.kelas, t_trayek.posawal, t_trayek.posakhir, t_trayek.kode, t_user.nama, t_user.nip');
        $this->db->from($this->table);
        $this->db->join('t_user', 't_user.id = ' . $this->table . '.petugas_id');
        $this->db->join('t_laporan_harian', 't_laporan_harian.id = ' . $this->table . '.laporan_harian_id');
        $this->db->join('t_dinas', 't_dinas.id = t_laporan_harian.dinas_id');
        $this->db->join('t_bus', 't_dinas.bus_id = t_bus.id');
        $this->db->join('t_trayek', 't_dinas.trayek_id = t_trayek.id');

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
        $this->db->join('t_user', 't_user.id = ' . $this->table . '.petugas_id');
        $this->db->join('t_laporan_harian', 't_laporan_harian.id = ' . $this->table . '.laporan_harian_id');
        $this->db->join('t_dinas', 't_dinas.id = t_laporan_harian.dinas_id');
        $this->db->join('t_bus', 't_dinas.bus_id = t_bus.id');
        $this->db->join('t_trayek', 't_dinas.trayek_id = t_trayek.id');
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);

        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }
}
