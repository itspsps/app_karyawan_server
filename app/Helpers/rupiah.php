<?php
function rupiah($list_masuktotal)
{
    $hasil_rupiah = "Rp. " . number_format($list_masuktotal, 0, ',', '.');
    return $hasil_rupiah;
}
/**
 * Menghitung kecerahan warna dan mengembalikan class CSS untuk teks yang kontras.
 * * @param string $hexColor Kode warna Hex (misal: #FF5733 atau FF5733)
 * @return string Class CSS ('text-white' atau 'text-dark')
 */
function getContrastTextColor($hexColor)
{
    // 1. Bersihkan kode Hex dari '#'
    $hex = str_replace('#', '', $hexColor);

    // Jika kode Hex adalah shorthand (misal: fff), perluas ke 6 digit
    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } elseif (strlen($hex) == 6) {
        // 2. Konversi Hex ke nilai desimal RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    } else {
        // Jika format tidak valid, kembalikan default
        return 'text-dark';
    }

    // 3. Hitung Kecerahan Relatif (Perceived Brightness) menggunakan formula:
    // Brightness = (Red * 0.299) + (Green * 0.587) + (Blue * 0.114)
    // Nilai bobot ini disesuaikan dengan sensitivitas mata manusia terhadap warna.
    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

    // 4. Tentukan Threshold: Jika kecerahan > 128 (dari skala 0-255), warnanya terang.
    // Teks harus gelap untuk kontras. Sebaliknya, gunakan teks putih.
    if ($brightness > 128) {
        // Warna terang, gunakan teks gelap
        return 'text-dark';
    } else {
        // Warna gelap, gunakan teks putih
        return 'text-white';
    }
}
