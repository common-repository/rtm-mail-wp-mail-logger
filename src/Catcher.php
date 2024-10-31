<?php

namespace RtmMail;

use PHPMailer\PHPMailer\PHPMailer;
use RtmMail\Helpers\LogHelper;


class Catcher
{
    
    public function catch_mail($args)
    {
                if (empty($args['headers']) || substr($args["headers"][0], 0, 2) !== 'Id') {
                        if (is_string($args['to'])) {
                                $args['to'] = str_replace(',', ';', $args['to']);
                                $recipients = [];
                foreach (explode(';', $args['to']) as $recipient) {
                    $recipients[] = str_replace(' ', '', $recipient);
                }
                                $args['to'] = $recipients;
            }
            $args['headers'] = $args['headers'] ?? [];
            $args['headers'] = is_string($args['headers']) ? explode("\n", str_replace("\\r\\n", "\n", $args['headers'])) : $args['headers'];
            $args['backtrace'] = $this->get_backtrace();

                        $mail_data = $this->format($args);

                        $log_id = LogHelper::save($mail_data);

            $smtp_settings = get_option('rtm_mail_smtp_settings');
            $smtp_enabled = isset($smtp_settings['smtp_enabled']) && filter_var($smtp_settings['smtp_enabled'], FILTER_VALIDATE_BOOLEAN);

            if ($smtp_enabled) {
                self::send_smtp($log_id, $mail_data);
            } else {
                LogHelper::update($log_id, ['status' => 'sent', 'sent' => date('Y-m-d H:i:s', time())]);
                                array_unshift($args['headers'], "Id: " . $log_id);
            }
        }

        return $args;
    }

    
    public function mail_error($error)
    {
                if (!empty($error->error_data['wp_mail_failed']['headers'])) {
            $log_id = (int)$error->error_data['wp_mail_failed']['headers']['Id'];
            LogHelper::update($log_id, ['status' => 'failed', 'sent' => null]);
            do_action('rtmmail_send_failed', $log_id, $error->errors['wp_mail_failed'][0]);
        }
    }

    
    public static function send_mail($log_id)
    {
        $mail_log = LogHelper::get([
            'post_per_page' => null,
            'where' => [
                'id' => [
                    'type' => '=',
                    'value' => $log_id,
                ]
            ]
        ]);

        $mail_log = $mail_log[0] ?? null;

                if ($mail_log == null) {
            return;
        }

        $smtp_settings = get_option('rtm_mail_smtp_settings');
        $smtp_enabled = isset($smtp_settings['smtp_enabled']) && filter_var($smtp_settings['smtp_enabled'], FILTER_VALIDATE_BOOLEAN);
        if ($smtp_enabled) {
            return self::send_smtp($log_id, $mail_log);
        } else {
            $mail_data = [];
                        $mail_data['subject'] = $mail_log['subject'];
                        $mail_data['to'] = $mail_log['receiver'];
                        $mail_data['message'] = $mail_log['body'];
                        $mail_data['headers'] = [];

                        if (!empty($mail_log['cc'])) {
                foreach ($mail_log['cc'] as $cc_address) {
                    if (!empty($cc_address)) {
                        $mail_data['headers'][] = 'Cc: ' . $cc_address;
                    }
                }
            }
                        if (!empty($mail_log['bcc'])) {
                foreach ($mail_log['bcc'] as $bcc_address) {
                    if (!empty($bcc_address)) {
                        $mail_data['headers'][] = 'Bcc: ' . $bcc_address;
                    }
                }
            }

                        if (!empty($mail_log['headers'])) {
                foreach ($mail_log['headers'] as $header_key => $header_value) {
                    if (!empty($header_value)) {
                        $mail_data['headers'][] = ucfirst($header_key) . ': ' . $header_value;
                    }
                }
            }

                        array_unshift($mail_data['headers'], "Id: " . $log_id);
                        $mail_data['attachments'] = [];
            if (!empty($mail_log['attachments'])) {
                foreach ($mail_log['attachments'] as $attachment) {
                    if (!empty($attachment)) {
                        $mail_data['attachments'][] = $attachment;
                    }
                }
            }
            $success = wp_mail($mail_data['to'], $mail_data['subject'], $mail_data['message'], $mail_data['headers'], $mail_data['attachments']);
                        if ($success) {
                                LogHelper::update($log_id, ['status' => 'sent', 'sent' => date('Y-m-d H:i:s', time())]);
                do_action('rtmmail_send_success', $log_id);
                return 'success';

            } else {
                                LogHelper::update($log_id, ['status' => 'failed', 'sent' => null]);
                return __('Failed sending email, check the debug logs for more info!', 'rtm-mail');
            }
        }
    }

    private static function send_smtp($log_id, $mail_data)
    {
        $settings = get_option('rtm_mail_settings');
        $sender_options = $settings['sender_options'] ?? null;

        $sender_mail = ($sender_options != null && !empty($sender_options['address'])) ? $sender_options['address'] : get_option('admin_email');
        $sender_title = ($sender_options != null && !empty($sender_options['title'])) ? $sender_options['title'] : get_bloginfo('name');

        $encryption_key = get_option('rtm_mail_smtp_key', false);
        $smtp_settings = get_option('rtm_mail_smtp_settings');
        $smtp_enabled = $smtp_settings['smtp_enabled'] ?? false;

        if (!$smtp_enabled) {
            return __('SMTP is not enabled');
        }

        $host = $smtp_settings['host'] ?? '';
        $encryption = $smtp_settings['encryption'] ?? 'none';
        $port = $smtp_settings['port'] ?? 0;
        $authentication = $smtp_settings['authentication'] ?? true;
        $username = $smtp_settings['username'] ?? '';
        $password = isset($smtp_settings['password']) ? Cryptor::Decrypt($smtp_settings['password'], $encryption_key) : '';

                require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
        require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
        require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = get_bloginfo('charset');
            $mail->IsSMTP();

                        $mail->ContentType = $mail_data['headers']['content-type'];
            $mail->IsHTML($mail_data['headers']['content-type'] === 'text/html');

                        $mail->Host = $host;
            $mail->Port = $port;

                        if ($encryption !== 'none') {
                $mail->SMTPSecure = $encryption;
            }

                        if ($authentication) {
                $mail->SMTPAuth = true;
                $mail->Username = $username;
                $mail->Password = $password;
            }

                        $mail->SetFrom($sender_mail, $sender_title);
                        foreach ($mail_data['receiver'] as $to_address) {
                if (!empty($to_address)) {
                    $mail->AddAddress($to_address);
                }
            }
                        foreach($mail_data['cc'] as $cc_address) {
                if (!empty($cc_address)) {
                    $mail->AddCC($cc_address);
                }
            }
                        foreach($mail_data['bcc'] as $bcc_address) {
                if (!empty($bcc_address)) {
                    $mail->AddBcc($bcc_address);
                }
            }

                        $mail->Subject = $mail_data['subject'];
            $mail->Body = $mail_data['body'];

                        foreach ($mail_data['attachments'] as $attachment) {
                $mail->addAttachment($attachment, basename($attachment));
            }

                        $mail->Timeout = 10;

            $mail->Send();

            LogHelper::update($log_id, ['status' => 'sent', 'sent' => date('Y-m-d H:i:s', time())]);
            do_action('rtmmail_send_success', $log_id);

        } catch(\Exception $ex) {
                        LogHelper::update($log_id, ['status' => 'failed', 'sent' => null]);
            do_action('rtmmail_send_failed', $log_id, $mail->ErrorInfo);
            return $mail->ErrorInfo;
        }

        return 'success';
    }

    
    private function format($args)
    {
                $formatted_data = [];
                $formatted_data['sender'] = get_option('admin_email');
        $sender_title = get_bloginfo('name');
        $formatted_data['receiver'] = [];
        $formatted_data['cc'] = [];
        $formatted_data['bcc'] = [];
        $formatted_data['subject'] = sanitize_text_field($args['subject']);
        $formatted_data['body'] = stripslashes(htmlspecialchars_decode($args['message']));
        $formatted_data['attachments'] = [];
        $formatted_data['headers'] = [];
        $formatted_data['backtrace'] = $args['backtrace'] ?? [];
        $formatted_data['created'] = date('Y-m-d H:i:s', time());

        $args['to'] = $args['to'] ?? [];
        $args['headers'] = $args['headers'] ?? [];
        $args['attachments'] = $args['attachments'] ?? [];

                foreach ($args['to'] as $receiver_mail) {
            if (!empty($receiver_mail)) {
                $formatted_data['receiver'][] = sanitize_text_field($receiver_mail);
            }
        }

                foreach ($args['headers'] as $header) {
            if (strpos(strtolower($header), 'from:') !== false) {
                                $from_email = trim(str_replace('from: ', '', strtolower($header)), '\'"');
                if (!empty($from_email)) {
                    $formatted_data['sender'] = sanitize_text_field($from_email);
                }
            } else if (strpos(strtolower($header), 'bcc:') !== false) {
                                $bcc_email = trim(str_replace('bcc: ', '', strtolower($header)), '\'"');
                if (!empty($bcc_email)) {
                    $formatted_data['bcc'][] = sanitize_text_field($bcc_email);
                }
            } else if (strpos(strtolower($header), 'cc:') !== false) {
                                $cc_email = trim(str_replace('cc: ', '', strtolower($header)), '\'"');
                if (!empty($cc_email)) {
                    $formatted_data['cc'][] = sanitize_text_field($cc_email);
                }
            } else {
                $header_data = explode(':', str_replace(' ', '', strtolower($header)));
                $formatted_data['headers'][$header_data[0]] = $header_data[1] ?? '';
            }
        }
                $formatted_data['headers']['from'] = $sender_title . ' <' . $formatted_data['sender'] . '>';

                $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'] . '/rtm-mail/attachments';
                if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755);
        }
                foreach ($args['attachments'] as $attachment) {
            $date = date('Y_m_d H_i_s', time());
            $file_name = substr($attachment, strrpos($attachment, '/'));
            $extension = explode('.', $file_name)[1];
                        $new_file = $upload_dir . str_replace('.' . $extension, '', $file_name) . '-' . str_replace(' ', '', $date) . '.' . $extension;
            if (copy($attachment, $new_file)) {
                $formatted_data['attachments'][] = $new_file;
            } else {
                printf(__('WP Mail Logger FATAL ERROR: Couldn\'t copy file %s to directory. Attachment is not added to logged mail', 'rtm-mail'), $new_file);
            }
        }

        return $formatted_data;
    }

    
    private function get_backtrace()
    {
        $result = [];
        $trace = array_reverse(debug_backtrace());
        array_pop($trace);                 foreach ($trace as $trace_line) {
            if (!isset($trace_line['class']) || $trace_line['class'] !== 'WP_Hook') {
                $call = isset($trace_line['class']) ? $trace_line['class'] . $trace_line['type'] . $trace_line['function'] : $trace_line['function'];
                if (strpos($call, 'require') === false && strpos($call, 'include') === false) {
                    $result[] = [
                        'call' => $call,
                        'line' => $trace_line['line'],
                        'file' => $trace_line['file']
                    ];
                }
            }
        }
        return $result;
    }
}
