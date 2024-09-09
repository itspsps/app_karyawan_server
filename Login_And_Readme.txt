php artisan serv
php artisan storage:link
php artisan schedule:work
http://127.0.0.1:8000/

Login (Admin)
jagosoftware
jagosoftware

Login (user)
nasrul
password

Demo dan Tutorial https://youtu.be/rmqZbYHEFHI

Test webcame https://id.webcamtests.com/


Setting Reset cuti setiap hari dijalankan di cronjob setiap jam 05:34 WIB
app\Console\Kernel.php 
kode nya adalah 
$schedule->command('reset:cuti')->dailyAt('05:34')->timezone('Asia/Jakarta');

Command yang dijalankan reset:cuti ada di app\Console\Commands\resetCuti.php

Setting jumlah carakter yang ditampilkan Captcha misal 4 karakter
config\captcha.php

ubah 'default' => [
        'length' => 4, <-- jumlah karakter yang ditampilkan