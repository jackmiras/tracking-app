#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

echo "################################################################################"
echo "# Running tests with coverage                                                  #"
echo "################################################################################"

./vendor/bin/pest --ci --coverage --min=87
