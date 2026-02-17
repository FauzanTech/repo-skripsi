<?php

namespace App\Controllers;
use Config\MyConstants;
use App\Models\UserModel;
use App\Models\MemberModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    
    public function __construct()
    {
        $this->db = db_connect();
    }
    public function login()
    {
        // Menampilkan form login
        return view('pages/Auth');
    }

    public function loginProcess()
    {
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userModel = new UserModel();

        $user = $userModel->where('username', $username)->first();

        if ($user) {
            echo($user['user_id']);
            if (password_verify($password, $user['password'])) {
                
                $session = session();
                $session->set('logged_in', true);
                $session->set('user_id', $user['user_id']);
                $session->set('fullname', $user['fullname']);
                $session->set('email', $user['email']);
                $session->set('identity_number', $user['identity_number']);
                $session->set('role', $user['role']);
                $session->set('profil_pict', $user['profil_pict']);

                $query = $this->db->query('select t1.*, t2.prodi_name from user as t1 join prodi as t2 on t2.prodi_id = t1.prodi_id where t1.user_id = "'.$user['user_id'].'"');
                foreach ($query->getResultArray() as $member) {
                    $session->set('prodi_id', $member['prodi_id']);
                    $session->set('prodi_name', $member['prodi_name']);
                }
                if ($user['role'] == MyConstants::ADMIN || $user['role'] == MyConstants::SUPERADMIN) {
                    return redirect()->to('/dashboard');
                } else {
                    return redirect()->to('/list');
                }
            } else {
                return redirect()->back()->withInput()->with('error', 'Password salah!');
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Username tidak ditemukan');
        }
    }

    public function logout()
    {
        // Hapus session
        session()->destroy();
        return redirect()->to('/auth');
    }

    public function register()
    {
        // Menampilkan halaman register
        return view('pages/register');
    }

    public function registerProcess()
    {
        // Konfigurasi validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|is_unique[user.username]',  // Username harus unik
            'password' => 'required|min_length[8]',  // Password minimal 6 karakter
            'confirm_password' => 'required|matches[password]'  // Password konfirmasi harus sama
        ]);

        // Mengecek validasi
        if (!$this->validate($validation->getRules())) {
            // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Jika validasi berhasil, lanjutkan proses penyimpanan data
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Hash password sebelum disimpan
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $user_id = bin2hex(random_bytes(16));
        // Data yang akan dimasukkan ke database
        $data = [
            'user_id' => $user_id,
            'username' => $username,
            'password' => $passwordHash,
            'account_start_date' => date('Y-m-d H:i:s'),
            'status' => 1,  // Status aktif
            'role' => 'user'  // Default role user
        ];

        // Insert ke database
        $userModel = new \App\Models\UserModel();
        $userModel->insert($data);

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->to('/')->with('success', 'Registrasi berhasil');
    }

    public function changePasswordView()
    {
        $data = [
            'hideSideMenu' => true
        ];
        return view('pages/changePassword', $data);
    }

    public function changePassword()
    {
        // Validasi input form
        $validation = \Config\Services::validation();
        $validation->setRules([
            'old_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $session = session();
        $userId = $session->get('user'); 
        $userModel = new UserModel();
        $user = $userModel->find($userId['user_id']);
        // Cek apakah user ditemukan
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Cek apakah password lama yang dimasukkan benar
        $oldPassword = $this->request->getPost('old_password');
        if (!password_verify($oldPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama salah.');
        }

        // Hash password baru dan simpan ke database
        $newPassword = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
        $userModel->update($userId, ['password' => $newPassword]);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }

}

