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
            'per_page' => 20,
            'page' => 1,
            'orderby' => 'created_at',
            'order' => 'DESC',
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
            $orderby = 'created_at DESC';
        }
        
        $sql = "SELECT * FROM {$this->table} $where ORDER BY $orderby LIMIT %d OFFSET %d";
        
        return $wpdb->get_results($wpdb->prepare($sql, $args['per_page'], $offset));
    }
    
    public function get_total($args = array()) {
        global $wpdb;
        
        $where = "WHERE 1=1";
        
        if (!empty($args['search'])) {
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $where .= $wpdb->prepare(" AND (name LIKE %s OR email LIKE %s OR company LIKE %s)", $search, $search, $search);
        }
        
        if (isset($args['status']) && $args['status'] !== '') {
            $where .= $wpdb->prepare(" AND status = %d", $args['status']);
        }
        
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$this->table} $where");
    }
    
    public function get_by_id($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id));
    }
    
    public function create($data) {
        global $wpdb;
        
        $insert_data = array(
            'name' => sanitize_text_field($data['name']),
            'email' => sanitize_email($data['email']),
            'phone' => sanitize_text_field(isset($data['phone']) ? $data['phone'] : ''),
            'company' => sanitize_text_field(isset($data['company']) ? $data['company'] : ''),
            'address' => sanitize_textarea_field(isset($data['address']) ? $data['address'] : ''),
            'city' => sanitize_text_field(isset($data['city']) ? $data['city'] : ''),
            'state' => sanitize_text_field(isset($data['state']) ? $data['state'] : ''),
            'country' => sanitize_text_field(isset($data['country']) ? $data['country'] : ''),
            'zip_code' => sanitize_text_field(isset($data['zip_code']) ? $data['zip_code'] : ''),
            'notes' => sanitize_textarea_field(isset($data['notes']) ? $data['notes'] : ''),
            'status' => isset($data['status']) ? intval($data['status']) : 1,
            'created_by' => 0
        );
        
        // Get current user ID if available
        if (class_exists('SPFM_Auth') && method_exists('SPFM_Auth', 'get_current_user_id')) {
            $insert_data['created_by'] = SPFM_Auth::get_current_user_id();
        }
        
        $format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d');
        
        $result = $wpdb->insert($this->table, $insert_data, $format);
        
        if ($result === false) {
            // Log the error for debugging
            error_log('SPFM Customer Insert Error: ' . $wpdb->last_error);
            return false;
        }
        
        return $wpdb->insert_id;
    }
    
    public function update($id, $data) {
        global $wpdb;
        
        $update_data = array(
            'name' => sanitize_text_field($data['name']),
            'email' => sanitize_email($data['email']),
            'phone' => sanitize_text_field(isset($data['phone']) ? $data['phone'] : ''),
            'company' => sanitize_text_field(isset($data['company']) ? $data['company'] : ''),
            'address' => sanitize_textarea_field(isset($data['address']) ? $data['address'] : ''),
            'city' => sanitize_text_field(isset($data['city']) ? $data['city'] : ''),
            'state' => sanitize_text_field(isset($data['state']) ? $data['state'] : ''),
            'country' => sanitize_text_field(isset($data['country']) ? $data['country'] : ''),
            'zip_code' => sanitize_text_field(isset($data['zip_code']) ? $data['zip_code'] : ''),
            'notes' => sanitize_textarea_field(isset($data['notes']) ? $data['notes'] : ''),
            'status' => isset($data['status']) ? intval($data['status']) : 1
        );
        
        $format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d');
        
        $result = $wpdb->update($this->table, $update_data, array('id' => $id), $format, array('%d'));
        
        if ($result === false) {
            error_log('SPFM Customer Update Error: ' . $wpdb->last_error);
            return false;
        }
        
        return true;
    }
    
    public function delete($id) {
        global $wpdb;
        return $wpdb->delete($this->table, array('id' => $id), array('%d'));
    }
    
    public function bulk_delete($ids) {
        global $wpdb;
        
        $ids = array_map('intval', $ids);
        $ids_string = implode(',', $ids);
        
        return $wpdb->query("DELETE FROM {$this->table} WHERE id IN ($ids_string)");
    }
    
    public function toggle_status($id) {
        global $wpdb;
        
        $current_status = $wpdb->get_var($wpdb->prepare("SELECT status FROM {$this->table} WHERE id = %d", $id));
        $new_status = $current_status ? 0 : 1;
        
        return $wpdb->update($this->table, array('status' => $new_status), array('id' => $id), array('%d'), array('%d'));
    }
}
