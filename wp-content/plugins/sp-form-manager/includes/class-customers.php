<?php
/**
 * Customers Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Customers {
    
    private static $instance = null;
    private $table;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'spfm_customers';
    }
    
    public function get_all($args = array()) {
        global $wpdb;
        
        $defaults = array(
            'per_page' => 50,
            'page' => 1,
            'orderby' => 'name',
            'order' => 'ASC',
            'search' => '',
            'status' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        $offset = ($args['page'] - 1) * $args['per_page'];
        
        $where = "WHERE 1=1";
        
        if (!empty($args['search'])) {
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $where .= $wpdb->prepare(" AND (name LIKE %s OR email LIKE %s OR company LIKE %s)", $search, $search, $search);
        }
        
        if ($args['status'] !== '') {
            $where .= $wpdb->prepare(" AND status = %d", $args['status']);
        }
        
        $orderby = sanitize_sql_orderby($args['orderby'] . ' ' . $args['order']);
        if (!$orderby) {
            $orderby = 'name ASC';
        }
        
        $sql = "SELECT * FROM {$this->table} $where ORDER BY $orderby LIMIT %d OFFSET %d";
        
        return $wpdb->get_results($wpdb->prepare($sql, $args['per_page'], $offset));
    }
    
    public function get_by_id($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id));
    }
    
    public function get_by_email($email) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table} WHERE email = %s", $email));
    }
    
    public function get_total($status = '') {
        global $wpdb;
        
        $where = "";
        if ($status !== '') {
            $where = $wpdb->prepare(" WHERE status = %d", $status);
        }
        
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$this->table}" . $where);
    }
    
    public function create($data) {
        global $wpdb;
        
        $insert_data = array(
            'name' => sanitize_text_field($data['name']),
            'email' => sanitize_email($data['email']),
            'phone' => sanitize_text_field($data['phone'] ?? ''),
            'company' => sanitize_text_field($data['company'] ?? ''),
            'address' => sanitize_textarea_field($data['address'] ?? ''),
            'city' => sanitize_text_field($data['city'] ?? ''),
            'state' => sanitize_text_field($data['state'] ?? ''),
            'country' => sanitize_text_field($data['country'] ?? ''),
            'zip_code' => sanitize_text_field($data['zip_code'] ?? ''),
            'notes' => sanitize_textarea_field($data['notes'] ?? ''),
            'status' => isset($data['status']) ? intval($data['status']) : 1
        );
        
        $result = $wpdb->insert($this->table, $insert_data);
        
        return $result === false ? false : $wpdb->insert_id;
    }
    
    public function update($id, $data) {
        global $wpdb;
        
        $update_data = array(
            'name' => sanitize_text_field($data['name']),
            'email' => sanitize_email($data['email']),
            'phone' => sanitize_text_field($data['phone'] ?? ''),
            'company' => sanitize_text_field($data['company'] ?? ''),
            'address' => sanitize_textarea_field($data['address'] ?? ''),
            'city' => sanitize_text_field($data['city'] ?? ''),
            'state' => sanitize_text_field($data['state'] ?? ''),
            'country' => sanitize_text_field($data['country'] ?? ''),
            'zip_code' => sanitize_text_field($data['zip_code'] ?? ''),
            'notes' => sanitize_textarea_field($data['notes'] ?? ''),
            'status' => isset($data['status']) ? intval($data['status']) : 1
        );
        
        return $wpdb->update($this->table, $update_data, array('id' => $id)) !== false;
    }
    
    public function delete($id) {
        global $wpdb;
        return $wpdb->delete($this->table, array('id' => $id));
    }
    
    public function toggle_status($id) {
        global $wpdb;
        
        $current_status = $wpdb->get_var($wpdb->prepare("SELECT status FROM {$this->table} WHERE id = %d", $id));
        $new_status = $current_status ? 0 : 1;
        
        return $wpdb->update($this->table, array('status' => $new_status), array('id' => $id));
    }
}
