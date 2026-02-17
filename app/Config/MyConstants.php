<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class MyConstants extends BaseConfig
{
    public const REJECTED = 'REJECTED';
    public const ACCEPTED = 'ACCEPTED';
    public const UNDER_REVIEW = 'UNDER_REVIEW';
    public const DRAFT = 'DRAFT';
    public const ADMIN = 'Admin';
    public const SUPERADMIN = 'Superadmin';
    public const DOSEN = 'Dosen';
    public const MAHASISWA = 'Mahasiswa';
    public const VALID = 'VALID';
    public const INVALID = 'INVALID';
    public const FIRST_SUPERVISOR = 'FIRST SUPERVISOR';
    public const SECOND_SUPERVISOR = 'SECOND SUPERVISOR';
    public const FIRST_AUTHOR = 'FIRST AUTHOR';
    public const CORRESPONDING_AUTHOR = 'CORRESPONDING AUTHOR';
    public const CO_AUTHOR = 'CO-AUTHOR';
    public const STATUS_OPEN = 'OPEN';
    public const STATUS_CLOSED = 'CLOSED';
    public const STATEMENT_LETTER = 'statement_letter';
    public const REFERENCE_FILE = 'reference';
    public const NUMBER_ROW = 15;
    public const MEMBER_STATUS = [ 0 => 'Tidak Aktif', 1 => 'Aktif'];
    public const MODE_INSERT = 'insert';
    public const MODE_UPDATE = 'update';
    public const VISIBLE_TO = [
        ['value' => 'anyone', 'label' => 'Semua'],
        ['value' => 'member-only', 'label' => 'Hanya Anggota'],
        ['value' => 'staff-only', 'label' => 'Hanya Staff'],
    ];
    public const FILE_TYPE = [
        ['value' => 'text', 'label' => 'Teks'],
        ['value' => 'image', 'label' => 'Gambar'],
        ['value' => 'video', 'label' => 'Video'],
        ['value' => 'audio', 'label' => 'Audio'],
        ['value' => 'archive', 'label' => 'Arsip'],
        ['value' => 'others', 'label' => 'Lainnya'],
    ];

    public const LICENSE = [
        [ 'value' => 'CC BY (Attribution)', 'label' => 'CC BY (Attribution)', 'desc' => 'Mengizinkan orang lain untuk mendistribusikan, mengadaptasi, dan membangun karya Anda, bahkan secara komersial, selama mereka mencantumkan nama Anda.'],
        [ 'value' => 'CC BY-SA (Attribution-ShareAlike)', 'label' => 'CC BY-SA (Attribution-ShareAlike)', 'desc' => 'Mengizinkan penggunaan komersial dan adaptasi, selama hasil karya baru didistribusikan di bawah lisensi yang sama.'],
        [ 'value' => 'CC BY-ND (Attribution-NoDerivatives)', 'label' => 'CC BY-ND (Attribution-NoDerivatives)', 'desc' => 'Mengizinkan distribusi dan penggunaan komersial, tetapi tidak boleh diadaptasi atau diubah.'],
        [ 'value' => 'CC BY-NC (Attribution-NonCommercial)', 'label' => 'CC BY-NC (Attribution-NonCommercial)', 'desc' => 'Mengizinkan adaptasi dan distribusi non-komersial, dengan pencantuman nama.'],
        [ 'value' => 'CC BY-NC-SA (Attribution-NonCommercial-ShareAlike)', 'label' => 'CC BY-NC-SA (Attribution-NonCommercial-ShareAlike)', 'desc' => 'Mengizinkan adaptasi dan distribusi non-komersial, selama hasil karya baru didistribusikan di bawah lisensi yang sama, dengan pencantuman nama.'],
        [ 'value' => 'CC BY-NC-ND (Attribution-NonCommercial-NoDerivatives)', 'label' => 'CC BY-NC-ND (Attribution-NonCommercial-NoDerivatives)', 'desc' => 'Ini adalah lisensi CC yang paling ketat. Mengizinkan distribusi non-komersial tanpa adaptasi, dengan pencantuman nama.'],
        [ 'value' => 'CC0 (No Rights Reserved – Public Domain Dedication)', 'label' => 'CC0 (No Rights Reserved – Public Domain Dedication)', 'desc' => 'Ini bukan lisensi, melainkan dedikasi untuk melepaskan karya ke domain publik sepenuhnya, menyerahkan semua hak cipta yang terkait dengan karya tersebut.'],
        [ 'value' => 'Unspesified', 'label' => 'Unspesified']
    ];
}

?>