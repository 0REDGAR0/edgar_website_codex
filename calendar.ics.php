<?php
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename="calendar.ics"');

$icsUrls = [
  'https://calendar.google.com/calendar/ical/edgarbeduneyrinck%40gmail.com/public/basic.ics',
  'https://calendar.google.com/calendar/ical/a0c99bb4307bef20fe7d9333e1d759351c76a41235105231a5c420777a955458%40group.calendar.google.com/public/basic.ics',
  'https://calendar.google.com/calendar/ical/ca71460206105df3d75140d2c0d512c732b7f0c4e6753fd2aa8a87503498e063%40group.calendar.google.com/public/basic.ics',
  'https://calendar.google.com/calendar/ical/ukvj5o47thjhubs9b2g93mhde9em6d1p%40import.calendar.google.com/public/basic.ics'
];

// Output a minimal calendar header once
echo "BEGIN:VCALENDAR\r\n";
echo "VERSION:2.0\r\n";
echo "PRODID:-//Calendrier Edgar//EN\r\n";

foreach ($icsUrls as $url) {
  $data = @file_get_contents($url);
  if ($data === false) continue;
  $lines = explode("\n", $data);
  $insideEvent = false;
  foreach ($lines as $line) {
    $line = rtrim($line);
    if (stripos($line, 'BEGIN:VEVENT') === 0) {
      $insideEvent = true;
      echo $line . "\r\n";
      continue;
    }
    if (stripos($line, 'END:VEVENT') === 0) {
      $insideEvent = false;
      echo $line . "\r\n";
      continue;
    }
    if ($insideEvent) {
      echo $line . "\r\n";
    }
  }
}

echo "END:VCALENDAR\r\n";
?>
