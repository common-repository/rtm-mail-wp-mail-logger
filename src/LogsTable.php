<?php

namespace RtmMail;

use DateTime;
use Exception;
use RtmMail\Helpers\LogHelper;
use WP_List_Table;


class LogsTable extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct([
            'singular' => 'log',
            'plural' => 'logs',
            'ajax' => false
        ]);
    }

    
    public function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" class="log-select" />',
            'subject' => __('Subject (#id)', 'rtm-mail'),
            'sender' => __('Sender', 'rtm-mail'),
            'receiver' => __('Receiver', 'rtm-mail'),
            'status' => __('Status', 'rtm-mail'),
            'created' => __('Created on', 'rtm-mail'),
            'sent' => __('Sent on', 'rtm-mail'),
            'action' => __('Action', 'rtm-mail')
        ];

        return $columns;
    }

    
    public function get_sortable_columns()
    {
        $sortable_columns = [
            'subject' => ['id', false],
            'created' => ['created', false],
            'sent' => ['sent', false],
        ];

        return $sortable_columns;
    }

    
    function column_default($item, $column_name)
    {
        return esc_html($item[$column_name]);
    }

    
    public function column_cb($item)
    {
        return sprintf('<input type="checkbox" class="log-select" name="%1$s[]" value="%2$s" />', 'id', $item['id']);
    }

    
    public function column_subject($item)
    {
                return esc_attr($item['subject']) . ' (#' . $item['id'] . ")";
    }

    
    public function column_receiver($item)
    {
        $label = !empty($item['receiver']) && !empty($item['receiver'][0]) ? $item['receiver'][0] : __('No receivers..');
        $receiver_count = count($item['receiver']);
        if ($receiver_count > 1) {
            $label .= ' (' . ($receiver_count - 1) . ' ' . __('more', 'rtm-mail') . ')';
        }

        return $label;
    }

    
    public function column_status($item)
    {
                $label = __('Caught', 'rtm-mail');
        switch ($item['status']) {
            case 'caught':
                $label = __('Caught', 'rtm-mail');
                break;
            case 'failed':
                $label = __('Failed', 'rtm-mail');
                break;
            case 'sent':
                $label = __('Sent', 'rtm-mail');
                break;
        }
        return '<span class="badge badge-' . $item['status'] . '">' . $label . '</span>';
    }

    
    public function column_created($item)
    {
                $date = new DateTime($item['created']);
        return $date->format('d-m-Y H:i');
    }

    
    public function column_sent($item)
    {
                return !empty($item['sent']) ? (new DateTime($item['sent']))->format('d-m-Y H:i') : __('Not sent yet...', 'rtm-mail');
    }

    
    public function column_action($item)
    {
                $label = __('Details', 'rtm-mail');
        $html = '<div class="log-action-buttons">';
        $html .= '<a href="' . get_admin_url() . 'admin.php?page=rtm-mail-details&log_id=' . $item['id'] . '" class="button button-action">' . $label . '</a>';
        $html .= '<a href="#" data-id="' . $item['id'] . '" class="button button-action button-success" id="action-send">' . __('Send', 'rtm-mail') . '</a>';
        $html .= '<a href="#" data-id="' . $item['id'] . '" class="button button-action button-delete" id="action-delete">' . __('Delete', 'rtm-mail') . '</a>';
        $html .= '</div>';
        return $html;
    }

    
    public function column_mobile_action($item)
    {
                $label = __('Details', 'rtm-mail');
        $html = '<div class="action-list">';
        $html .= '<a class="dropdown-box toggle__dropdown" data-target="dropdown__options-' . $item['id'] . '"><i class="fas fa-ellipsis-h"></i></a>';
        $html .= '<div class="dropdown-menu dropdown__options-' . $item['id'] . '">';
        $html .= '<a href="' . get_admin_url() . 'admin.php?page=rtm-mail-details&log_id=' . $item['id'] . '" class="dropdown-item" style="color:#297da8!important;">' . $label . '</a>';
        $html .= '<a href="#" data-id="' . $item['id'] . '" class="dropdown-item" id="action-send" style="color:#05988a!important;">' . __('Send', 'rtm-mail') . '</a>';
        $html .= '<a href="#" data-id="' . $item['id'] . '" class="dropdown-item" id="action-delete" style="color:#bf0707!important;">' . __('Delete', 'rtm-mail') . '</a>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    
    public function search_box($text, $input_id)
    {
        if (empty($_REQUEST['s']) && !$this->has_items()) {
            return;
        }

        $input_id = $input_id . '-search-input';

        if (!empty($_REQUEST['orderby'])) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr($_REQUEST['orderby']) . '" />';
        }
        if (!empty($_REQUEST['order'])) {
            echo '<input type="hidden" name="order" value="' . esc_attr($_REQUEST['order']) . '" />';
        }
        if (!empty($_REQUEST['post_mime_type'])) {
            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr($_REQUEST['post_mime_type']) . '" />';
        }
        if (!empty($_REQUEST['detached'])) {
            echo '<input type="hidden" name="detached" value="' . esc_attr($_REQUEST['detached']) . '" />';
        }
        ?>
        <div class="search-filter">
            <div class="search-box">
                <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo esc_attr($text); ?>:</label>
                <input type="search" id="<?php echo esc_attr($input_id); ?>" name="s"
                       placeholder="<?php echo __('Search on content', 'rtm-mail'); ?>"
                       value="<?php _admin_search_query(); ?>"/>
                <?php submit_button($text, '', '', false, array('id' => 'search-submit')); ?>
            </div>
            <div class="filter-box">
                <div class="filter-label-box">
                    <p class="filter-label"><?php echo __('Filter by', 'rtm-mail'); ?><i
                                class="fas fa-caret-down filter__icon"></i></p>
                </div>
                <div class="filter-input">
                    <div class="filter-input-item">
                        <input type="checkbox" id="mail_subject" name="search_filter[subject]" checked>
                        <label for="mail_subject"><?php echo __('Subject', 'rtm-mail'); ?></label>
                    </div>
                    <div class="filter-input-item">
                        <input type="checkbox" id="mail_sender" name="search_filter[sender]" checked>
                        <label for="mail_sender"><?php echo __('Sender', 'rtm-mail'); ?></label>
                    </div>
                    <div class="filter-input-item">
                        <input type="checkbox" id="mail_receiver" name="search_filter[receiver]" checked>
                        <label for="mail_receiver"><?php echo __('Receiver', 'rtm-mail'); ?></label>
                    </div>
                    <div class="filter-input-item">
                        <input type="checkbox" id="mail_cc" name="search_filter[cc]" checked>
                        <label for="mail_cc"><?php echo __('CC', 'rtm-mail'); ?></label>
                    </div>
                    <div class="filter-input-item">
                        <input type="checkbox" id="mail_bcc" name="search_filter[bcc]" checked>
                        <label for="mail_bcc"><?php echo __('BCC', 'rtm-mail'); ?></label>
                    </div>
                    <div class="filter-input-item">
                        <input type="checkbox" id="mail_status" name="search_filter[status]" checked>
                        <label for="mail_status"><?php echo __('Status', 'rtm-mail'); ?></label>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    
    public function display_tablenav($which)
    {
                if ('top' === $which) {
            return;
        }
        ?>
        <div class="tablenav <?php echo esc_attr($which); ?>">

            <?php
            $this->extra_tablenav($which);
            $this->pagination($which);
            ?>

            <br class="clear"/>
        </div>
        <?php
    }

    
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [$columns, [], $sortable];

                $defaults = LogHelper::DEFAULT_ARGS;
                $total_items = LogHelper::get_total_rows($_GET);
        $post_per_page = $defaults['post_per_page'];

                $total_pages = ceil($total_items / $post_per_page);

                $this->set_pagination_args([
            "total_items" => $total_items,
            "total_pages" => $total_pages,
            "per_page" => $post_per_page,
        ]);
                $this->items = LogHelper::get($_GET);
    }

    
    public function display()
    {
        $singular = $this->_args['singular'];

        $this->display_tablenav('top');

        $this->screen->render_screen_reader_content('heading_list');
        
        ?>
        <table class="wp-list-table desktop__view <?php echo implode(' ', $this->get_table_classes()); ?>">
            <thead>
            <tr>
                <?php $this->print_column_headers(); ?>
            </tr>
            </thead>

            <tbody id="the-list"
                <?php
                if ($singular) {
                    echo " data-wp-lists='list:$singular'";
                }
                ?>
            >
            <?php $this->display_rows_or_placeholder(); ?>
            </tbody>

            <tfoot>
            <tr>
                <?php $this->print_column_headers(false); ?>
            </tr>
            </tfoot>

        </table>
        <?php
        $this->display_mobile();
        $this->display_tablenav('bottom');
    }

    
    public function display_mobile()
    {
        ?>
        <table class="mobile-grid mobile__view <?php echo implode(' ', $this->get_table_classes()); ?>">
            <tbody>
            <?php
            foreach ($this->items as $item) {
                echo '<tr class="mobile-grid-row">';
                $this->mobile_row_columns($item);
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
        <?php
    }

    public function mobile_row_columns($item)
    {
        list($columns, $hidden, $sortable, $primary) = $this->get_column_info();
        foreach ($columns as $column_name => $column_display_name) {
            if ('cb' === $column_name) {
                echo '<td class="mobile-grid-cell">';
                echo '<span class="cell-label">' . esc_attr($this->column_cb($item)) . '</span>';
                echo '<span class="cell-data"></span>';
                echo '</td>';
            } elseif (method_exists($this, 'column_mobile_' . $column_name)) {
                echo '<td class="mobile-grid-cell">';
                echo '<span class="cell-label">' . esc_attr($column_display_name) . '</span>';
                echo '<span class="cell-data">' . call_user_func(array($this, 'column_mobile_' . $column_name), $item) . '</span>';
                echo '</td>';
            } elseif (method_exists($this, 'column_' . $column_name)) {
                echo '<td class="mobile-grid-cell">';
                echo '<span class="cell-label">' . esc_attr($column_display_name) . '</span>';
                echo '<span class="cell-data">' . call_user_func(array($this, 'column_' . $column_name), $item) . '</span>';
                echo '</td>';
            } else {
                echo '<td class="mobile-grid-cell">';
                echo '<span class="cell-label">' . esc_attr($column_display_name) . '</span>';
                echo '<span class="cell-data">' . $this->column_default($item, $column_name) . '</span>';
                echo '</td>';
            }
        }
    }
}