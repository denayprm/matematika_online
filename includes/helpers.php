function time_elapsed_string($datetime, $full = false) {
$now = new DateTime();
$ago = new DateTime($datetime);
$diff = $now->diff($ago);

$diff->w = floor($diff->d / 7);
$diff->d -= $diff->w * 7;

$string = [
'y' => 'tahun',
'm' => 'bulan',
'w' => 'minggu',
'd' => 'hari',
'h' => 'jam',
'i' => 'menit',
's' => 'detik',
];
foreach ($string as $k => &$v) {
if ($diff->$k) {
$v = $diff->$k . ' ' . $v;
} else {
unset($string[$k]);
}
}

if (!$full) {
$string = array_slice($string, 0, 1);
}

return $string ? implode(', ', $string) . ' yang lalu' : 'baru saja';
}
<small class="text-muted">
    <?= time_elapsed_string($topik['created_at']) ?>
</small>