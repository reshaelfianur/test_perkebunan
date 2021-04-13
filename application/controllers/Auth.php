<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends My_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('bcrypt');

        $this->controller = 'auth';
        $this->secretKey  = 'R3$7!n';
    }

    public function index()
    {
        $this->validate_session();

        return $this->load->view('auth/login');
    }

    public function validate_username()
    {
        $checkUsername = $this->m_user->get(['name' => $this->input->post('username')]);

        if ($checkUsername->num_rows() > 0) {
            $response = [
                'success' => true,
                'message' => 'The Username field is valid.'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'The Username field does not exist!'
            ];
        }

        return $this->response_json($response);
    }

    public function authenticate()
    {
        $username   = $this->input->post('username');
        $password   = $this->input->post('password');

        $checkUsername  = $this->m_user->get(['name' => $username]);

        if ($checkUsername->num_rows() > 0) {
            $row            = $checkUsername->row();

            $checkPassword  = $this->bcrypt->validate($password, $this->secretKey , $row->password);

            if ($checkPassword) {
                $session = [
                    'session_id'        => session_id(),
                    'logged_in'         => true,
                    'user_id'           => $row->id,
                    'user_name'         => $row->name,
                    'user_email'        => $row->email,
                    'user_last_request' => dateTimeNow(),
                ];
                $this->session->set_userdata($session);

                $response = [
                    'current_url' => 'dashboard',
                    'message'     => 'Login Success',
                    'success'     => true
                ];
            } else {
                $response = [
                    'message' => 'These credentials do not match our record!',
                    'success' => false
                ];
            }
        } else {
            $response = [
                'message' => 'The Username field does not exist!',
                'success' => false
            ];
        }

        return $this->response_json($response);
    }

    public function logout()
    {
        $this->session->set_userdata([
            'logged_in'   => false,
            'current_url' => null
        ]);

        $this->session->set_flashdata('data', [
            'type'    => 'info',
            'message' => $this->lang->line('info_logout')
        ]);

        return redirect('auth');
    }
}
