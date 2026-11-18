# 🧾 Split the Bill CLI

A tiny PHP command-line tool for splitting shared expenses — a trip, a shared apartment, a group dinner — fairly among a group, and telling you the smallest number of payments needed to settle up. No app, no account, no framework: just PHP and a text file.

<br/>

## ✨ Why

Splitting bills "evenly" by hand gets confusing fast once different people pay for different things. This script does the math and tells each person exactly who to pay (and how much) to settle up in the fewest transactions possible.

<br/>

## 🚀 Usage

Create a text file listing what each person paid, one expense per line, as `Name,Amount,Description`:

```
Ana,120.00,Hotel
Bruno,45.50,Dinner
Ana,30.00,Taxi
Carla,0,
```

Then run:

```bash
php split.php expenses.txt
```

Every name that appears is treated as a participant who shares the total cost equally, even if (like Carla above) they paid nothing.

<br/>

## 📋 Example output

```
Total spent: 195.50
Fair share per person (4 people): 48.88

Balances:
Ana      +101.12  (is owed)
Bruno     -3.38   (owes)
Carla    -48.88   (owes)

Settle up:
Carla pays Ana 48.88
Bruno pays Ana 3.38
```

<br/>

## 🛠️ Requirements

PHP 7.4+ (uses only the standard library, no Composer dependencies).

<br/>

## 🤝 Contributing

Ideas welcome — per-expense participant selection, multiple currencies, a `--json` output flag, etc.

<br/>

## 📄 License

MIT — see [LICENSE](LICENSE).
