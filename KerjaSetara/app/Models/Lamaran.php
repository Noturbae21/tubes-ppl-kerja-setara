<?php

namespace App\Models;

/**
 * Model Lamaran (Model representasi domain bahasa Indonesia untuk JobApplication)
 * Terhubung dengan tabel 'job_applications' untuk manajemen riwayat lamaran kandidat.
 */
class Lamaran extends JobApplication
{
    protected $table = 'job_applications';
}

