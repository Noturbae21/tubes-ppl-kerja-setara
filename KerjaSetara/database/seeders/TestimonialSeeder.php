<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Siti Rahmawati',
                'position' => 'UX Designer',
                'company' => 'Tokopedia',
                'role_type' => 'kandidat',
                'avatar_initials' => 'SR',
                'avatar_color' => 'blue',
                'content' => 'Saya akhirnya menemukan tempat kerja yang benar-benar memahami kebutuhan saya. KerjaSetara membantu saya menemukan perusahaan yang sudah menyediakan akses kursi roda dan tools kolaborasi yang screen-reader friendly. Proses lamarnya pun sangat mudah!',
            ],
            [
                'name' => 'Dian Kusuma',
                'position' => 'Data Analyst',
                'company' => 'Gojek',
                'role_type' => 'kandidat',
                'avatar_initials' => 'DK',
                'avatar_color' => 'indigo',
                'content' => 'Platform ini luar biasa. Saya tidak perlu khawatir tentang diskriminasi lagi. KerjaSetara memilah lowongan berdasarkan kebutuhan aksesibilitas saya secara otomatis. Dalam 3 minggu, saya sudah mendapat panggilan interview dari 5 perusahaan!',
            ],
            [
                'name' => 'Tri Hartawan',
                'position' => 'HR Manager',
                'company' => 'BCA',
                'role_type' => 'hrd',
                'avatar_initials' => 'TH',
                'avatar_color' => 'green',
                'content' => 'Sebagai HR, saya sangat terbantu dengan fitur matching dari KerjaSetara. Kami kini berhasil membangun tim yang beragam dan produktif. Kualitas kandidat yang masuk sangat tinggi dan sudah terverifikasi kebutuhan aksesibilitasnya.',
            ],
            [
                'name' => 'Andi Firmansyah',
                'position' => 'Frontend Developer',
                'company' => 'Microsoft Indonesia',
                'role_type' => 'kandidat',
                'avatar_initials' => 'AF',
                'avatar_color' => 'purple',
                'content' => 'Sebagai tunanetra, saya sering kesulitan melamar kerja karena website yang tidak ramah screen reader. KerjaSetara adalah pengecualian — platform-nya sangat aksesibel dan saya bisa navigasi dengan keyboard tanpa masalah.',
            ],
            [
                'name' => 'Nita Permata',
                'position' => 'Talent Acquisition Lead',
                'company' => 'Shopee Indonesia',
                'role_type' => 'hrd',
                'avatar_initials' => 'NP',
                'avatar_color' => 'orange',
                'content' => 'KerjaSetara mengubah cara kami merekrut. Sekarang proses kami jauh lebih inklusif dan kandidat yang datang sudah "pre-matched" dengan fasilitas yang kami sediakan. Waktu rekrutmen kami turun 40%!',
            ],
            [
                'name' => 'Rizki Maulana',
                'position' => 'Graphic Designer',
                'company' => 'Telkomsel',
                'role_type' => 'kandidat',
                'avatar_initials' => 'RM',
                'avatar_color' => 'teal',
                'content' => 'Saya pengguna kursi roda dan selalu was-was apakah kantor yang melamar punya fasilitas yang memadai. Di KerjaSetara, saya bisa filter langsung "ramp" dan "lift" — dan semua info itu transparan, bukan janji-janji kosong.',
            ],
        ];

        foreach ($testimonials as $data) {
            Testimonial::create(array_merge($data, ['is_active' => true]));
        }
    }
}
