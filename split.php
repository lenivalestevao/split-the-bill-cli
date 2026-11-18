<?php

if ($argc < 2) {
  fwrite(STDERR, "Usage: php split.php <expenses.csv>\n");
  exit(1);
}

$path = $argv[1];
if (!is_readable($path)) {
  fwrite(STDERR, "Cannot read file: {$path}\n");
  exit(1);
}

$paid = [];
$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
  $parts = str_getcsv($line);
  $name = trim($parts[0] ?? '');
  $amount = (float) ($parts[1] ?? 0);

if ($name === '') {
  continue;
}

if (!isset($paid[$name])) {
  $paid[$name] = 0.0;
}
  $paid[$name] += $amount;
}

$people = array_keys($paid);
$count = count($people);

if ($count === 0) {
  fwrite(STDERR, "No participants found in {$path}\n");
  exit(1);
}

$total = array_sum($paid);
$fairShare = $total / $count;

$balances = [];
foreach ($paid as $name => $amount) {
  $balances[$name] = round($amount - $fairShare, 2);
}

echo "Total spent: " . number_format($total, 2) . "\n";
echo "Fair share per person ({$count} people): " . number_format($fairShare, 2) . "\n\n";

echo "Balances:\n";
foreach ($balances as $name => $balance) {
  $label = $balance >= 0 ? '(is owed)' : '(owes)';
  $sign = $balance >= 0 ? '+' : '';
  printf("%-10s %s%-8.2f %s\n", $name, $sign, $balance, $label);
}

$creditors = [];
$debtors = [];
foreach ($balances as $name => $balance) {
  if ($balance > 0.01) {
    $creditors[] = ['name' => $name, 'amount' => $balance];
  } elseif ($balance < -0.01) {
    $debtors[] = ['name' => $name, 'amount' => -$balance];
  }
}

echo "\nSettle up:\n";
if (empty($creditors)) {
  echo "Everyone is already even.\n";
} else {
  $i = 0;
  $j = 0;
  while ($i < count($debtors) && $j < count($creditors)) {
    $payment = min($debtors[$i]['amount'], $creditors[$j]['amount']);
    printf("%s pays %s %.2f\n", $debtors[$i]['name'], $creditors[$j]['name'], $payment);

                $debtors[$i]['amount'] -= $payment;
        $creditors[$j]['amount'] -= $payment;
  if ($debtors[$i]['amount'] < 0.01) {
    $i++;
  }
    if ($creditors[$j]['amount'] < 0.01) {
      $j++;
    }
  }
}
