<?php
/**
 * Forms Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Forms {
    
    private static $instance = null;
    private $table;
    private $shares_table;
    private $submissions_table;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'spfm_forms';
        $this->shares_table = $wpdb->prefix . 'spfm_form_shares';
        $this->submissions_table = $wpdb->prefix . 'spfm_form_submissions';
    }
    
    public function get_all($args = array()) {
        global $wpdb;
        
        $defaults = array(
            'per_page' => 50,
            'page' => 1,
            'orderby' => 'created_at',
            'order' => 'DESC',
            'status' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        $offset = ($args['page'] - 1) * $args['per_page'];
        
        $where = "WHERE 1=1";
        
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
    
    public function get_by_id($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id));
    }
    
    public function create($data) {
        global $wpdb;
        
        $insert_data = array(
            'name' => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field($data['description'] ?? ''),
            'available_themes' => $data['available_themes'] ?? '[]',
            'allow_theme_selection' => isset($data['allow_theme_selection']) ? 1 : 0,
            'allow_color_customization' => isset($data['allow_color_customization']) ? 1 : 0,
            'header_text' => sanitize_text_field($data['header_text'] ?? ''),
            'footer_text' => sanitize_text_field($data['footer_text'] ?? ''),
            'success_message' => sanitize_textarea_field($data['success_message'] ?? ''),
            'notify_admin' => isset($data['notify_admin']) ? 1 : 0,
            'status' => isset($data['status']) ? intval($data['status']) : 1
        );
        
        $result = $wpdb->insert($this->table, $insert_data);
        
        return $result === false ? false : $wpdb->insert_id;
    }
    
    public function update($id, $data) {
        global $wpdb;
        
        $update_data = array(
            'name' => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field($data['description'] ?? ''),
            'available_themes' => $data['available_themes'] ?? '[]',
            'allow_theme_selection' => isset($data['allow_theme_selection']) ? 1 : 0,
            'allow_color_customization' => isset($data['allow_color_customization']) ? 1 : 0,
            'header_text' => sanitize_text_field($data['header_text'] ?? ''),
            'footer_text' => sanitize_text_field($data['footer_text'] ?? ''),
            'success_message' => sanitize_textarea_field($data['success_message'] ?? ''),
            'notify_admin' => isset($data['notify_admin']) ? 1 : 0,
            'status' => isset($data['status']) ? intval($data['status']) : 1
        );
        
        return $wpdb->update($this->table, $update_data, array('id' => $id)) !== false;
    }
    
    public function delete($id) {
        global $wpdb;
        
        // Delete submissions
        $wpdb->delete($this->submissions_table, array('form_id' => $id));
        
        // Delete shares
        $wpdb->delete($this->shares_table, array('form_id' => $id));
        
        // Delete form
        return $wpdb->delete($this->table, array('id' => $id));
    }
    
    public function get_submission_count($form_id) {
        global $wpdb;
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->submissions_table} WHERE form_id = %d",
            $form_id
        ));
    }
    
    public function get_share_count($form_id) {
        global $wpdb;
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->shares_table} WHERE form_id = %d",
            $form_id
        ));
    }
    
    // Get submissions
    public function get_submissions($args = array()) {
        global $wpdb;
        
        $defaults = array(
            'per_page' => 50,
            'page' => 1,
            'form_id' => '',
            'status' => '',
            'orderby' => 'created_at',
            'order' => 'DESC'
        );
        
        $args = wp_parse_args($args, $defaults);
        $offset = ($args['page'] - 1) * $args['per_page'];
        
        $where = "WHERE 1=1";
        
        if (!empty($args['form_id'])) {
            $where .= $wpdb->prepare(" AND s.form_id = %d", $args['form_id']);
        }
        
        if (!empty($args['status'])) {
            $where .= $wpdb->prepare(" AND s.status = %s", $args['status']);
        }
        
        $sql = "SELECT s.*, f.name as form_name, t.name as theme_name, t.primary_color, t.secondary_color
                FROM {$this->submissions_table} s
                LEFT JOIN {$this->table} f ON s.form_id = f.id
                LEFT JOIN {$wpdb->prefix}spfm_themes t ON s.selected_theme_id = t.id
                $where
                ORDER BY s.{$args['orderby']} {$args['order']}
                LIMIT %d OFFSET %d";
        
        return $wpdb->get_results($wpdb->prepare($sql, $args['per_page'], $offset));
    }
    
    public function get_submission_by_id($id) {
        global $wpdb;
        
        $sql = "SELECT s.*, f.name as form_name, t.name as theme_name, t.primary_color, t.secondary_color
                FROM {$this->submissions_table} s
                LEFT JOIN {$this->table} f ON s.form_id = f.id
                LEFT JOIN {$wpdb->prefix}spfm_themes t ON s.selected_theme_id = t.id
                WHERE s.id = %d";
        
        return $wpdb->get_row($wpdb->prepare($sql, $id));
    }
    
    // Save submission
    public function save_submission($data) {
        global $wpdb;
        
        $insert_data = array(
            'form_id' => intval($data['form_id']),
            'share_id' => intval($data['share_id'] ?? 0),
            'customer_id' => intval($data['customer_id'] ?? 0),
            'selected_theme_id' => intval($data['selected_theme_id']),
            'page_contents' => is_string($data['page_contents']) ? $data['page_contents'] : json_encode($data['page_contents']),
            'color_customizations' => is_string($data['color_customizations']) ? $data['color_customizations'] : json_encode($data['color_customizations']),
            'uploaded_files' => is_string($data['uploaded_files'] ?? '[]') ? ($data['uploaded_files'] ?? '[]') : json_encode($data['uploaded_files'] ?? array()),
            'customer_info' => is_string($data['customer_info'] ?? '{}') ? ($data['customer_info'] ?? '{}') : json_encode($data['customer_info'] ?? array()),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'status' => 'new'
        );
        
        $result = $wpdb->insert($this->submissions_table, $insert_data);
        
        return $result === false ? false : $wpdb->insert_id;
    }
    
    public function update_submission_status($id, $status) {
        global $wpdb;
        return $wpdb->update(
            $this->submissions_table,
            array('status' => sanitize_text_field($status)),
            array('id' => $id)
        );
    }
    
    public function delete_submission($id) {
        global $wpdb;
        return $wpdb->delete($this->submissions_table, array('id' => $id));
    }
    
    public function get_submission_stats($form_id = null) {
        global $wpdb;
        
        $where = $form_id ? $wpdb->prepare(" WHERE form_id = %d", $form_id) : "";
        
        $total = $wpdb->get_var("SELECT COUNT(*) FROM {$this->submissions_table} $where");
        
        $where_new = $form_id ? $wpdb->prepare(" WHERE form_id = %d AND status = 'new'", $form_id) : " WHERE status = 'new'";
        $new = $wpdb->get_var("SELECT COUNT(*) FROM {$this->submissions_table} $where_new");
        
        $where_completed = $form_id ? $wpdb->prepare(" WHERE form_id = %d AND status = 'completed'", $form_id) : " WHERE status = 'completed'";
        $completed = $wpdb->get_var("SELECT COUNT(*) FROM {$this->submissions_table} $where_completed");
        
        return array(
            'total' => (int) $total,
            'new' => (int) $new,
            'completed' => (int) $completed
        );
    }
}
