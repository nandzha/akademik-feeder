<?php
namespace Models;

use Libraries;
use Resources;

class LoginValidation extends Resources\Validation
{

    private $rule = [];

    public function __construct()
    {
        parent::__construct();

        $this->session = new Resources\Session;
        $this->request = new Resources\Request;
        $this->user = new Users;
        $this->main = new Main;
        $this->epsbed = new Libraries\Epsbed;
    }

    public function setRules()
    {
        return $this->rule;
    }

    public function validateValues($rule)
    {
        $this->rule = $this->myRules($rule);

        $validate = $this->validate();

        if ($rule == 'signin') {

            if ($validate) {
                return $this->validateSignin();
            }
        }

        if ($rule == 'mhsSignin') {

            if ($validate) {
                return $this->validateMhsSignin();
            }
        }

        if ($rule == 'signup') {

            if ($validate) {
                return $this->addUserAndSignin();
            }
        }

        return $validate;
    }

    private function myRules($key)
    {
        $rules = [
            'signin' => [
                'smt' => [
                    'rules' => ['required'],
                ],
                'username' => [
                    'rules' => [
                        'required',
                        'min' => 3,
                        'regex' => '/^([-a-z0-9_-])+$/i',
                    ],
                    'label' => 'Username',
                    'filter' => ['trim', 'strtolower'],
                ],
                'password' => [
                    'rules' => [
                        'required',
                        'min' => 3,
                    ],
                    'label' => 'Password',
                ],
            ],
            'mhsSignin' => [
                'nim' => [
                    'rules' => [
                        'required',
                        'min' => 3,
                        'regex' => '/^([-a-z0-9_-])+$/i',
                    ],
                    'label' => 'Nim',
                    'filter' => ['trim', 'strtolower'],
                ],
                'password' => [
                    'rules' => [
                        'required',
                        'min' => 4,
                    ],
                    'label' => 'Password',
                ],
            ],
            'post' => [
                'post' => [
                    'rules' => [
                        'required',
                        'max' => 140,
                        'min' => 3,
                    ],
                    'label' => 'Post',
                    'filter' => ['trim', [$this, 'sanitizeString']],
                ],
            ],
        ];

        $rules['signup']['rUsername'] = $rules['signin']['username'];
        $rules['signup']['rPassword'] = $rules['signin']['password'];
        $rules['signup']['rUsername']['rules'] = array_merge($rules['signup']['rUsername']['rules'], ['callback' => 'isUsernameExists']);

        return $rules[$key];
    }

    private function validateSignin()
    {
        $value = $this->value();

        $user = $this->user->getOne(['user_login' => $value['username']]);

        if ($user && md5($value['password']) == $user->user_pass) {

            $this->session->setValue(
                [
                    'penggunaId' => $user->id_user,
                    'namaPengguna' => $user->user_login,
                    'grupId' => $user->group_id,
                    'idsmt' => $value['smt'],
                    'desc' => $user->user_desc,
                ]
            );

            if (!$next = $this->request->get('next', FILTER_SANITIZE_URL, FILTER_VALIDATE_URL)) {
                $next = 'dashboard';
            }

            return $next;
        } else {
            $this->setErrorMessage('username', 'Wrong username/password.');
            return false;
        }
    }

    private function validateMhsSignin()
    {
        $value = $this->value();

        $user = $this->user->getOneMhs(['nim' => $value['nim']]);

        if ($user && md5($value['password']) == $user->pass) {

            $this->session->setValue(
                [
                    'userId' => $user->nim,
                    'namaPengguna' => $user->nmmhs,
                    'prodi' => $user->jur,
                    'thsmst' => $this->epsbed->getTahunSmt(),
                    'grupId' => 2, //mahasiswa
                ]
            );

            if (!$next = $this->request->get('next', FILTER_SANITIZE_URL, FILTER_VALIDATE_URL)) {
                $next = 'home';
            }

            return $next;
        } else {
            $this->setErrorMessage('username', 'Wrong username/password.');
            return false;
        }
    }

    public function isUsernameExists($field, $value, $label)
    {

        if (!$user = $this->user->getOne(['user_login' => $value])) {
            return true;
        }

        $this->setErrorMessage($field, 'Username already exists.');

        return false;
    }

    public function addUserAndSignin()
    {

        $value = $this->value();

        $userId = $this->user->insert(['user_login' => $value['rUsername'], 'user_pass' => md5($value['rPassword'])]);

        $this->connections->addFollowing($userId, $userId);

        $this->session->setValue(
            [
                'userId' => $userId,
                'username' => $value['rUsername'],
            ]
        );

        return 'home';
    }

    public function sanitizeString($string)
    {
        return filter_var($string, FILTER_SANITIZE_STRING);
    }
}
