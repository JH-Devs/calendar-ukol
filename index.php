<?php
function getTitle($date) {
    $dateTime = new DateTime($date);
    $months = [
        'Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'
    ];
    $monthIndex = (int)$dateTime->format('n') - 1;
    $monthName = $months[$monthIndex];
    $year = $dateTime->format('Y');
    return $monthName . ' ' . $year;
}

$today = new DateTime();
$ym = isset($_GET['ym']) ? $_GET['ym'] : $today->format('Y-m');

$prev = new DateTime($ym . '-01');
$prev->modify('-1 month');
$prev = $prev->format('Y-m');

$next = new DateTime($ym . '-01');
$next->modify('+1 month');
$next = $next->format('Y-m');

$firstDay = new DateTime($ym . '-01');
$lastDay = (int)$firstDay->format('t');

$calendar = [];
$day = 1;

for ($i = 0; $i < 6; $i++) {
    for ($j = 0; $j < 7; $j++) {
        $date = new DateTime($ym . '-01');
        $date->modify('+' . ($day - 1) . ' days');
        if ($day <= $lastDay && ($i > 0 || $j + 1 >= $firstDay->format('N'))) {
            $calendar[$i][$j] = ['day' => $day, 'date' => $date, 'today' => $date->format('Y-m-d') === $today->format('Y-m-d')];
            $day++;
        } else {
            $calendar[$i][$j] = '';
        }
    }
}

$events = [
    '2023-10-05' => [
        '10:00' => 'Testovací událost 1',
        '12:00' => 'Testovací událost 2'
    ],
];
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Kalendář</title>
</head>
<body>
<div class="calendar">
    <div class="header">
        <a href="?ym=<?php echo $prev; ?>">&lt;</a>
        <span class="title"><?php echo getTitle($ym); ?></span>
        <a href="?ym=<?php echo $next; ?>">&gt;</a>
    </div>

    <table>
        <thead>
        <tr>
            <?php foreach (['Po', 'Út', 'St', 'Čt', 'Pá', 'So', 'Ne'] as $dayName): ?>
                <th><?php echo $dayName; ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($calendar as $week): ?>
            <tr>
                <?php foreach ($week as $dayInfo): ?>
                    <?php if ($dayInfo): ?>
                        <td <?php echo $dayInfo['today'] ? 'class="today"' : ''; ?>>
                            <div class="day-container" data-date="<?php echo $dayInfo['date']->format('Y-m-d'); ?>">
                                <?php echo $dayInfo['day']; ?>
                                <?php if (isset($events[$dayInfo['date']->format('Y-m-d')])): ?>
                                    <div class="event-date"></div>
                                <?php endif; ?>
                            </div>
                        </td>
                    <?php else: ?>
                        <td></td>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="events">
    <h2>Události</h2>
    <ul>
        <?php
        foreach ($events as $eventDate => $eventDetails) {
            $eventDateTime = new DateTime($eventDate);
            $formattedDate = $eventDateTime->format('j. n. Y');
            foreach ($eventDetails as $eventTime => $eventName) {
                $formattedTime = date('H:i', strtotime($eventTime));
                echo "<li>{$formattedDate} {$formattedTime} - {$eventName}</li>";
            }
        }
        ?>
    </ul>
</div>
 
<script>
    function showEvent() {
        var eventContainer = document.querySelector('.events');
        eventContainer.style.display = 'flex';
    }

    document.querySelector('.day-container[data-date="2023-10-05"]').addEventListener('click', function() {
        showEvent();
    });
</script>
</body>
</html>
