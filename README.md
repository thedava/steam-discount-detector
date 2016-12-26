# steam-discount-detector
Detects if steam has a discount for a specific app

[![Build Status](https://travis-ci.org/thedava/steam-discount-detector.svg?branch=master)](https://travis-ci.org/thedava/steam-discount-detector)

### Usage

```
php detect.php
php detect.php <app file 1> <app file 2> <app file ...>
```


### Sample Output
GTA Online Cash Cards

```
user@computer $ php detect.php apps/gta-cash-cards.php

App: gta-cash-cards.php
=======================

Name: GTA Online Cash Cards
Url: http://store.steampowered.com/app/376850/

Output:
--------
[No Discount] Red Shark: GTA$ 100,000 - 2,49€
[No Discount] Tiger Shark: GTA$ 200,000 - 3,99€
[No Discount] Bull Shark: GTA$ 500,000 - 7,49€
[No Discount] Great White Shark: GTA$ 1,250,000 - 14,99€
[DISCOUNT] Whale Shark: GTA$ 3,500,000 - 25,45€
[DISCOUNT] Megalodon Shark: GTA$ 8,000,000 - 44,99€
--------

```
