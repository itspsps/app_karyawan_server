<?php
function rupiah($list_masuktotal)
{
    $hasil_rupiah = "Rp. " . number_format($list_masuktotal, 0, ',', '.');
    return $hasil_rupiah;
}
