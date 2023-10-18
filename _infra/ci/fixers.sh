#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

echo "################################################################################"
echo "# PHP CS Fixer                                                                 #"
echo "################################################################################"

./vendor/bin/php-cs-fixer \
  --config=.php-cs-fixer.php \
  --using-cache=no \
  --stop-on-violation \
  fix app/ routes/ config/ database/
