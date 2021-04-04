<?php

function is_logged_in()
{
    $ci = get_instance();
    if (!$ci->session->userdata('nip')) {
        redirect('auth');
    } else {
        $role_id = $ci->session->userdata('role_id');
        $controller = $ci->uri->segment(1); //mendapatkan controllerpengaturdinas atau controllerkasir
        $subMenu = $ci->uri->segment(2); //mendapatkan lihatdaftarbus, lihatdaftartrayek, dsb


        // $queryMenu = $ci->db->get_where('user_menu', ['controller' => $controller])->row_array();
        // $menu_id = $queryMenu['id']; // id controller

        // $querySubMenu = $ci->db->get_where('user_sub_menu', ['menu_id' => $menu_id])->row_array();
        // $subMenu_id = $querySubMenu['id_sm'];

        $queryDropdown = $ci->db->get_where('user_sub_menu_dropdown', ['method' => $subMenu])->row_array();
        $dropdown_id = $queryDropdown['id_dr'];

        $userAccess = $ci->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'dr_id' => $dropdown_id
            // 'menu_id' => $subMenu_id
        ]);

        if ($userAccess->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}
